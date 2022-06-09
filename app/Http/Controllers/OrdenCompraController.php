<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdenCompra;
use App\Models\Proveedor;
use App\Models\Producto;
use App\Models\OrdenCompraProductos;

use Illuminate\Support\Facades\DB;

class OrdenCompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         
        $ordenes_compra = DB::table('orden_compras')
        ->join('proveedors', 'orden_compras.id_proveedor', '=', 'proveedors.id')
        ->select('orden_compras.*','proveedors.nombre_empresa',
        DB::raw("(SELECT COUNT(orden_compra_productos.id) FROM orden_compra_productos
                                WHERE orden_compra_productos.folio_orden_compra = orden_compras.folio
                                ) as canitdad_productos"),
                    DB::raw("(SELECT SUM(orden_compra_productos.cantidad) FROM orden_compra_productos
                                WHERE orden_compra_productos.folio_orden_compra = orden_compras.folio
                                ) as cantidad_orden"),
                                DB::raw("(SELECT SUM(orden_compra_productos.precio_total) FROM orden_compra_productos
                                WHERE orden_compra_productos.folio_orden_compra = orden_compras.folio
                                ) as precio_total_orden")
                    )
        ->get();
        
        return view('orden_compras.index')->with('ordenes',$ordenes_compra);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $prov = Proveedor::all();
        return view('orden_compras.create')->with('proveedores',$prov);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ordenes = new OrdenCompra();
        $ordenes->folio = $request->get('folio');
        $ordenes->fecha_orden = $request->get('fecha');
        $ordenes->comentarios = $request->get('comentarios');
        $ordenes->id_proveedor = $request->get('proveedor');

        $ordenes->save();

        $productos=$request->get('productos');
        $info=json_decode(trim($productos));
        $ordenes_compra_productos= new OrdenCompraProductos();
        foreach ($info as $pos => $campo){
            $precio_total= $campo->cantidad * $campo->precio;
            DB::table('orden_compra_productos')->insert([
                'folio_orden_compra' =>  $request->get('folio'),
                'id_producto' => $campo->SKU,
                'cantidad' => $campo->cantidad,
                'precio_total' => $precio_total
            ]);
        }

        return json_encode('Ok');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $prov = Proveedor::all();
        $ordenes_compraEdit = DB::table('orden_compras')
        ->join('proveedors', 'orden_compras.id_proveedor', '=', 'proveedors.id')
        ->select('orden_compras.*','proveedors.nombre_empresa')
        ->where("orden_compras.folio", "=", $id)
        ->get();
        
   
        $results = Producto::where('id_proveedor',$ordenes_compraEdit[0]->id_proveedor)->get();
       
        $ordenes_compra_productos= OrdenCompraProductos::where('folio_orden_compra', $ordenes_compraEdit[0]->folio)->pluck('id_producto');

        $ordenes_compra_productos_cant= OrdenCompraProductos::where('folio_orden_compra', $ordenes_compraEdit[0]->folio)->pluck('cantidad');
        $array=$ordenes_compra_productos->toArray();
        $array_cant= $ordenes_compra_productos_cant->toArray();
        $options='';
        foreach($results as $pos => $prod){
           
            if(($prod->cantidad)>0){
                if (in_array($prod->SKU,$array)) {
                    $checked='checked';
                    $cantidad_orden=$array_cant[$pos];
                }else{
                    $checked='';
                    $cantidad_orden=$prod->cantidad;
                }
                $options.="<tr>";
                $options.="<td><input type='checkbox' class='checkProd'value='{$prod->SKU}' {$checked}></td>";
                $options.="<td>{$prod->descripcion}</td>";
                $options.="<td><input class='form-control' id='cantidadProd{$prod->SKU}' type='number' max='{$prod->cantidad}' value='{$cantidad_orden}' ></td>";
                $options.="<td><input class='form-control' id='precioProd{$prod->SKU}' type='number'  value='{$prod->precio}' readonly></td>";
                
                $options.="</tr>";
            }
            
        }
        
        return view('orden_compras.edit')->with('data',['orden_compra' => $ordenes_compraEdit, 
        'prov' => $prov,'productos_orden'=>$options] 
       );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $orden = OrdenCompra::where('folio', $id)->first();
        
        $orden->fecha_orden = $request->get('fecha');
        $orden->comentarios = $request->get('comentarios');
        $orden->id_proveedor = $request->get('proveedor');
        $orden->estatus = $request->get('estatus');
        $orden->save();

        return redirect('/ordenCompra');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ordenes_compra_productos= OrdenCompraProductos::where('folio_orden_compra', $id)->pluck('id');
        foreach($ordenes_compra_productos as $prod){

            $ordenDelete=OrdenCompraProductos::find($prod);
            $ordenDelete->delete();
        }
        $orden = OrdenCompra::where('folio', $id)->first();
        $orden->delete();

        return redirect('/ordenCompra');
    }

    public function buscarProductos()
{
    $id       = request('id');
    
    $options='';
    $results = Producto::where('id_proveedor',$id)->get();
    
        foreach($results as $prod){
           
            if(($prod->cantidad)>0){
                $options.="<tr>";
                $options.="<td><input type='checkbox' class='checkProd'value='{$prod->SKU}'></td>";
                $options.="<td>{$prod->descripcion}</td>";
                $options.="<td><input class='form-control' id='cantidadProd{$prod->SKU}' type='number' max='{$prod->cantidad}' value='{$prod->cantidad}'></td>";
                $options.="<td><input class='form-control' id='precioProd{$prod->SKU}' type='number'  value='{$prod->precio}' readonly></td>";
                
                $options.="</tr>";
            }
            
        }
   
    

    return response()->json($options);
}


public function editarProd(Request $request)
    {
        $id       =$request->get('folio');
        $ordenes_compra_productos= OrdenCompraProductos::where('folio_orden_compra', $id)->pluck('id');
        foreach($ordenes_compra_productos as $prod){

            $ordenDelete=OrdenCompraProductos::find($prod);
            $ordenDelete->delete();
        }

        $productos=$request->get('productos');
        $info=json_decode(trim($productos));
        $ordenes_compra_productos= new OrdenCompraProductos();
        foreach ($info as $pos => $campo){
            $precio_total= $campo->cantidad * $campo->precio;
            DB::table('orden_compra_productos')->insert([
                'folio_orden_compra' =>  $request->get('folio'),
                'id_producto' => $campo->SKU,
                'cantidad' => $campo->cantidad,
                'precio_total' => $precio_total
            ]);
        }
        
        return response()->json($id);
    }
}



