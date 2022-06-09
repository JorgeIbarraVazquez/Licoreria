@extends('layouts.plantillabase')

@section('contenido')
<div class="alert alert-primary" role="alert">
  En esta seccion se mostraran todas las ordendenes de compra en donde se visualiza su informacion general, para ver que productos 
  pertenecen a esa orden de compra es necesario hacer click en el boton de etidar
</div>
<a href="ordenCompra/create" class="btn btn-primary">Crear Orden de Compra</a>

<table class="table table-bordered mt-3">
    <thead>
        <th>Folio</th>
        <th>Fecha</th>
        <th>Proveedor</th>
        <th>Comentarios</th>
        <th>Estatus</th>
        <th>Productos en la orden</th>
        <th>Cantidad Total</th>
        <th>Precio</th>
        <th>Opciones</th>
</thead>
<tbody>
    @foreach ($ordenes as $orden)
        <tr>
            <td>{{$orden->folio}}</td>
            <td>{{$orden->fecha_orden}}</td>
            <td>{{$orden->nombre_empresa}}</td>
            <td>{{$orden->comentarios}}</td>
            <td>{{$orden->estatus}}</td>
            <td>{{$orden->canitdad_productos}}</td>
            <td>{{$orden->cantidad_orden}}</td>
            <td>{{$orden->precio_total_orden}}</td>
            <td>
                <form action="{{route('ordenCompra.destroy',$orden->folio)}}" method="POST">
                @csrf
                @method('DELETE')
                <a href="/ordenCompra/{{$orden->folio}}/edit" class="btn btn-info">Editar</a>
                <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
        </td>
        </tr>  

    @endforeach
</tbody>
</table>
@endsection