<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrdenCompraController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



// Usando la sintaxis PHP callable...
Route::resource('/ordenCompra', OrdenCompraController::class, [
    'names' => [
        'index'=>'ordenCompra.index',
        'store' => 'ordenCompra.store',
    ],
]);
Route::get('producto/busquedaByProveedor', [OrdenCompraController::class,'buscarProductos'])->name('productos.busquedaByProveedor');

Route::get('producto/EliminarProductoOrden', [OrdenCompraController::class,'eliminarProductoOrden'])->name('productos.eliminarProductoOrden');

Route::post('producto/editarProd', [OrdenCompraController::class,'editarProd'])->name('ordenCompra.editarProd');

