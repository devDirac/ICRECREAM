<?php

use App\Http\Controllers\API\ContratosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;

use App\Http\Controllers\API\TiendasController;
use App\Http\Controllers\API\CategoriasProductosController;
use App\Http\Controllers\API\ProductosController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**RUTAS PARA EL MANEJO DEL ESTADO DE SESION Y COSAS RELEVANTES AL USUARIO */
Route::post('login', [AuthController::class, 'signin']);
Route::post('logOut', [AuthController::class, 'logOut'])->middleware('auth:sanctum');
Route::post('register', [AuthController::class, 'signup'])->middleware('auth:sanctum');
Route::get('getUserRefrsh/{id}', [AuthController::class, 'getUserRefrsh'])->middleware('auth:sanctum');
Route::post('recuperaContrasena', [AuthController::class,'passwordRecoverSendLink']);
Route::post('recuperaContrasenaTokenValidacion', [AuthController::class,'passwordRecoverTokenValidation']);
Route::post('actualizacionContrasena', [AuthController::class,'passwordReset']);
Route::put('editUser', [AuthController::class, 'editUser'])->middleware('auth:sanctum');
Route::delete('setActiveUser', [AuthController::class, 'setActiveUser'])->middleware('auth:sanctum');

/* Tiendas */
Route::get('get-tiendas', [TiendasController::class, 'getAll'])->middleware('auth:sanctum');
Route::post('creaTienda', [TiendasController::class,'creaTienda'])->middleware('auth:sanctum');
Route::Put('editaTienda', [TiendasController::class,'editaTienda'])->middleware('auth:sanctum');

/* Categorias */
Route::get('get-categorias', [CategoriasProductosController::class, 'getAll'])->middleware('auth:sanctum');
Route::post('creaCategoria', [CategoriasProductosController::class,'creaCategoria'])->middleware('auth:sanctum');
Route::Put('editaCategoria', [CategoriasProductosController::class,'editaCategoria'])->middleware('auth:sanctum');



/* productos */
Route::get('get-all-productos', [ProductosController::class, 'getProductos'])->middleware('auth:sanctum');
Route::post('get-all-productos-fecha', [ProductosController::class,'getProductosPorFecha'])->middleware('auth:sanctum');

Route::post('creaProducto', [ProductosController::class,'creaProducto'])->middleware('auth:sanctum');
Route::Put('editaProducto', [ProductosController::class,'editaProducto'])->middleware('auth:sanctum');
Route::Put('vendeProducto', [ProductosController::class,'vendeProducto'])->middleware('auth:sanctum');
