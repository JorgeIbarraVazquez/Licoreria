@extends('layouts.plantillabase')

@section('contenido')
<h2>Crear Orden  de compra</h2>
<form class="row g-3" id="FormAgregarOrdenCompra">
    @csrf
    <div class="col-md-4">
    <label for="inputEmail4" class="form-label">Folio</label>
    <input type="text" name="folio" class="form-control" >
  </div>
  <div class="col-md-4">
    <label for="inputEmail4" class="form-label">Fecha en que se genero la orden</label>
    <input type="date" name="fecha" class="form-control" id="inputDate">
  </div>
  <div class="col-md-4">
    <label for="inputState" class="form-label">Proveedor</label>
    <select id="proveedor" name="proveedor" class="form-select" required onChange="buscarProductos(this)" >
      <option selected>Elige un Proveedor</option>
        @foreach ($proveedores as $provedor)
            <option value={{$provedor->id}} >{{$provedor->nombre_empresa}}</option>
        @endforeach
    </select>
  </div>
  <div class="col-12">
    <label for="inputAddress" class="form-label">Comentarios</label>
    <textarea class="form-control" id="inputAddress" name="comentarios"></textarea>
  </div>
  

  <table class="table table-bordered table-striped" id="tablaProductosByProvedor">
    <thead>
    <tr>
        <th colspan="4" style="text-align:center;">Productos Disponibles </th>
       
      </tr>
      <tr>
        <th>-</th>
        <th>Descripcion</th>
        <th>Cantidad</th>
        <th>Precio</th>
      </tr>
    </thead>
    <tbody id="TbodyTablaProductosByProvedor">
    </tbody>
  </table>

  <div class="col-12">
    <button type="submit"  class="btn btn-primary" onCick="agregarOrdenCompra();">Crear Orden</button>
  </div>
</form>

<script>
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

    document.querySelector('#FormAgregarOrdenCompra').addEventListener('submit', (e) => {
  e.preventDefault();
      
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
      
      let data = new FormData(document.getElementById("FormAgregarOrdenCompra"));
      data.append('productos',dataProductos);

      fetch("{{ route('ordenCompra.store') }}", {
            method: 'POST',
            body: data,

        })
            .then(res => res.ok ? res.json() : Promise.reject(res))
            .then(data => {
              window.location.href = "{{ route('ordenCompra.index') }}";
              
            })
            .catch(error => {
                alert(error);
                

            });
    

    });

</script>
@endsection


