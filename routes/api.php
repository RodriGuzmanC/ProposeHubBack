<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\OrganizacionController;
use App\Http\Controllers\Api\ServicioController;
use App\Http\Controllers\Api\PropuestaController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\PlantillaController;
use App\Http\Controllers\Api\VersionPropuestaController;
use App\Http\Controllers\Api\MailController;



//rutas clientes
Route::get('clientes', [ClienteController::class, 'obtenerTodos']);
Route::get('clientes/{id}', [ClienteController::class, 'obtenerUno']);
Route::post('clientes', [ClienteController::class, 'crear']);
Route::put('clientes/{id}', [ClienteController::class, 'editar']);
Route::delete('clientes/{id}', [ClienteController::class, 'eliminar']);

//rutas organizaciones
Route::get('organizaciones', [OrganizacionController::class, 'obtenerTodos']);
Route::get('organizaciones/{id}', [OrganizacionController::class, 'obtenerUno']);
Route::post('organizaciones', [OrganizacionController::class, 'crear']);
Route::put('organizaciones/{id}', [OrganizacionController::class, 'editar']);
Route::delete('organizaciones/{id}', [OrganizacionController::class, 'eliminar']);

//rutas propuestas
Route::get('propuestas', [PropuestaController::class, 'obtenerTodos']);
Route::get('propuestas/{id}', [PropuestaController::class, 'obtenerUno']);
Route::post('propuestas', [PropuestaController::class, 'crear']);
Route::put('propuestas/{id}', [PropuestaController::class, 'editar']);
Route::delete('propuestas/{id}', [PropuestaController::class, 'eliminar']);

//rutas usuarios
route::post('register', [UsuarioController::class, 'register']);
Route::post('login', [UsuarioController::class, 'login']);
Route::post('logout', [UsuarioController::class, 'logout']);

Route::get('usuarios', [UsuarioController::class, 'index']); // Obtener todos los usuarios
Route::get('usuarios/{id}', [UsuarioController::class, 'show']); // Obtener un usuario
Route::put('usuarios/{id}', [UsuarioController::class, 'update']); // Actualizar un usuario
Route::delete('usuarios/{id}', [UsuarioController::class, 'destroy']); // Eliminar un usuario

//rutas plantillas
Route::get('plantillas/', [PlantillaController::class, 'obtenerTodos']);
Route::get('plantillas/{id}', [PlantillaController::class, 'obtenerUno']);
Route::get('plantillas/contenido/{id}', [PlantillaController::class, 'obtenerContenido']);
Route::put('plantillas/contenido/{id}', [PlantillaController::class, 'editarContenido']);

Route::post('plantillas/', [PlantillaController::class, 'crear']);
Route::put('plantillas/{id}', [PlantillaController::class, 'editar']);
Route::delete('plantillas/{id}', [PlantillaController::class, 'eliminar']);


//rutas usuarios


//rutas servicios
Route::get('servicios', [ServicioController::class, 'obtenerTodos']);
Route::get('servicios/{id}', [ServicioController::class, 'obtenerUno']);
Route::post('servicios', [ServicioController::class, 'crear']);
Route::put('servicios/{id}', [ServicioController::class, 'editar']);
Route::delete('servicios/{id}', [ServicioController::class, 'eliminar']);

//rutas versiones propuestas
Route::get('versiones-propuesta/{id}', [VersionPropuestaController::class, 'obtenerTodos']); // Obtiene las versiones de una propuesta, id es de la propuesta
Route::get('version-propuesta/{id}', [VersionPropuestaController::class, 'obtenerUno']);
Route::post('version-propuesta', [VersionPropuestaController::class, 'crear']);
Route::put('version-propuesta/{id}', [VersionPropuestaController::class, 'editar']);

// Ruta para envio de correos
Route::post('/enviar-correo', [MailController::class, 'sendEmail']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
