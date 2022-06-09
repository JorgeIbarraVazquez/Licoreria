@extends('layouts.plantillabase')

@section('contenido')
<h2>Editar Orden  de compra</h2>
<div class="alert alert-info" role="alert">
  Para esta seccion primero se podra editar la información general de la orden, lo que no se podra modificar sera el provedor de la orden, si se desea cambiar el proveedor se recomienda eliminar la orden y crear una nueva.
</div>
<form id="FormEditarOrdenCompra">
<input type="hidden" name="folio" value="{{$data['orden_compra'][0]->folio}}" id="folioEditar">
</form>
<form class="row g-3" action="/ordenCompra/{{$data['orden_compra'][0]->folio}}" method="POST">
    @csrf
    @method('PUT')
  <div class="col-md-4">
    <label for="inputEmail4" class="form-label">Fecha en que se genero la orden</label>
    <input type="date" name="fecha" class="form-control" id="inputDate" value="{{$data['orden_compra'][0]->fecha_orden}}">
  </div>
  <div class="col-md-4">
    <label for="inputState" class="form-label">Proveedor</label>
    <select id="proveedor" name="proveedor" class="form-select" required onChange="buscarProductos(this)" readonly>
    
    @foreach ($data['prov'] as $provedor)
              @if($provedor->id == $data['orden_compra'][0]->id_proveedor)
                <option value={{$provedor->id}} selected>{{$provedor->nombre_empresa}}</option>
              @else
                <option value={{$provedor->id}} disabled>{{$provedor->nombre_empresa}}</option>
              @endif
        @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <label for="inputState" class="form-label">Estatus</label>
    <select id="estatus" name="estatus" class="form-select" required >
    @switch($data['orden_compra'][0]->estatus)
    @case('Pendiente')
        @php($opPen = "selected")
        @php($opAcep = "")
        @php($opCan = "")
        @break
    @case('Aceptada')
    @php($opPen = "")
        @php($opAcep = "selected")
        @php($opCan = "")
        @break
    @case('Cancelada')
    @php($opPen = "")
        @php($opAcep = "")
        @php($opCan = "selected")
        @break
    @default
   
        @break
@endswitch
    
        <option value="Pendiente" {{$opPen}}>Pendiente</option>
        <option value="Aceptada" {{$opAcep}}>Aceptada</option>
        <option value="Cancelada" {{$opCan}}>Cancelada</option>
    </select>
  </div>
  <div class="col-12">
    <label for="inputAddress" class="form-label">Comentarios</label>
    <textarea class="form-control" id="inputAddress" name="comentarios">{{$data['orden_compra'][0]->comentarios}}</textarea>
  </div>

  <div class="col-12">
    <button type="submit" class="btn btn-primary">Editar Orden</button>
  </div>
</form>
<hr>
<div class="alert alert-success" role="alert">
  En esta parte se mostraran todos los productos del proveedor de la orden, los que esten seleccionados son los productos que ya estan en la orden, aqui se puede modificar la cantidad del producto (ojo es importante que este seleccionado el producto), si desea eliminar todos los productos de la orden, solo basta con deseleccionar los productos y listo.
</div>
<table class="table table-bordered table-striped" id="tablaProductosByProvedor">
    <thead>
    <tr>
        <th colspan="4" style="text-align:center;">Productos de la orden {{$data['orden_compra'][0]->folio}} </th>
       
      </tr>
      <tr>
        <th>Editar</th>
        <th>Descripcion</th>
        <th>Cantidad</th>
        <th>Precio</th>
        
      </tr>
    </thead>
    <tbody id="TbodyTablaProductosByProvedor">
    {!!$data['productos_orden']!!}
    </tbody>
  </table>

  <button class="btn btn-info mb-3" onClick="EditarProductosOrden()">Editar Productos</button>
  <script>
      window.CSRF_TOKEN = '{{ csrf_token() }}';
  function buscarProductos(e){
      let id= e.value;
      

    fetch("{{ route('productos.busquedaByProveedor') }}?id="+id, )
    
            .then(res => res.ok ? res.json() : Promise.reject(res))
            .then(data => {
              if(data!=''){
                document.getElementById('TbodyTablaProductosByProvedor').innerHTML = data;
              }else{
                document.getElementById('TbodyTablaProductosByProvedor').innerHTML = "<td colspan='4'>No hay productos disponibles";
              }
              
            })
            .catch(error => {
                console.log(error);
                

            });
    
        
    }

    function EditarProductosOrden(){
      var checkboxes =document.getElementsByClassName("checkProd");
      var productos = ''; 
      let arraySeleccionados=[];
      
      for (var x=0; x < checkboxes.length; x++) {
        if (checkboxes[x].checked) {
          arraySeleccionados.push({
            SKU:checkboxes[x].value,
            cantidad:  document.getElementById("cantidadProd"+checkboxes[x].value).value,
            precio:  document.getElementById("precioProd"+checkboxes[x].value).value,
            
          });
        }
      }
      var dataProductos = JSON.stringify(arraySeleccionados);
      
      let data = new FormData(document.getElementById("FormEditarOrdenCompra"));
      data.append('productos',dataProductos);
      fetch("{{ route('ordenCompra.editarProd') }}", {
            method: 'POST',
            body: data,
            headers: {
        'X-CSRF-TOKEN': window.CSRF_TOKEN,// <--- aquí el token
        
    },
        })
            .then(res => res.ok ? res.json() : Promise.reject(res))
            .then(data => {
              window.location.href = window.location.href;
              
            })
            .catch(error => {
                alert(error);
                

            });
    

    
    }

    
    </script>

@endsection


