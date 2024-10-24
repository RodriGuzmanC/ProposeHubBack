<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PropuestaController;
use App\Http\Controllers\OrganizacionController;
use App\Http\Controllers\ContactosController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PlantillaController;
// Ruta para mostrar todas las plantillas
Route::resource('plantillas', PlantillaController::class);
// Ruta para descargar PDF
Route::get('plantillas/{plantilla}/download', [PlantillaController::class, 'download'])->name('plantillas.download');



// Rutas para Clientes
//Route::resource('clientes', ClienteController::class);


Route::resource('propuestas', PropuestaController::class);
Route::get('/propuestas/create', [PropuestaController::class, 'create'])->name('propuestas.create');
// Agregar las siguientes rutas en tu archivo de rutas
Route::get('/propuestas/plantillas', [PropuestaController::class, 'showPlantillas'])->name('propuestas.plantillas');
Route::get('/propuestas/contacto', [PropuestaController::class, 'showContacto'])->name('propuestas.contacto');
Route::get('/propuestas/servicio', [PropuestaController::class, 'showServicio'])->name('propuestas.servicio');
Route::post('/propuestas/informacion', [PropuestaController::class, 'showInformacion'])->name('propuestas.informacion');

//usuarios
//login y register de usuarios
Route::get('/register', [UsuarioController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [UsuarioController::class, 'register']);

Route::get('/login', [UsuarioController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UsuarioController::class, 'login']);

Route::post('/logout', [UsuarioController::class, 'logout'])->name('logout');

Route::get('/dashboard', [UsuarioController::class, 'dashboard'])->name('dashboard');


// Ruta para Contactos
Route::get('/contactos', [ContactosController::class, 'index'])->name('contactos.index');
//ruta para clientes
Route::resource('clientes', ClienteController::class);

// Rutas para Organizaciones
Route::resource('organizaciones', OrganizacionController::class);
Route::get('/organizaciones', [OrganizacionController::class, 'index'])->name('organizaciones.index');
Route::get('/organizaciones/create', [OrganizacionController::class, 'create'])->name('organizaciones.create');
Route::post('/organizaciones', [OrganizacionController::class, 'store'])->name('organizaciones.store');
Route::get('/organizaciones/{id}/edit', [OrganizacionController::class, 'edit'])->name('organizaciones.edit');
Route::put('/organizaciones/{id}', [OrganizacionController::class, 'update'])->name('organizaciones.update');
Route::delete('/organizaciones/{id}', [OrganizacionController::class, 'destroy'])->name('organizaciones.destroy');


Route::get('/', function () {
    return view('welcome');
});
