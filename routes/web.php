<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutenticacionController;

// Ruta principal → pantalla informativa
Route::get('/', function () {
    return view('welcome');
})->name('inicio');

// Rutas de autenticación
Route::get('/login', [AutenticacionController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AutenticacionController::class, 'iniciarSesion'])->name('login.iniciar');
Route::post('/logout', [AutenticacionController::class, 'cerrarSesion'])->name('logout');

// Rutas de registro
Route::get('/registro', [AutenticacionController::class, 'mostrarRegistro'])->name('registro');
Route::post('/registro', [AutenticacionController::class, 'registrar'])->name('registro.guardar');

// Dashboard administrador
Route::get('/admin/dashboard', function () {
    return view('admin.admininicio');
})->name('admin.dashboard')->middleware(['auth', 'role:administrador']);

// Dashboard odontólogo
Route::get('/odontologo/dashboard', function () {
    return view('odontologo.odontologoinicio');
})->name('odontologo.dashboard')->middleware(['auth', 'role:odontologo']);

// Dashboard recepcionista
Route::get('/recepcionista/dashboard', function () {
    return view('recepcionista.recepcionistainicio');
})->name('recepcionista.dashboard')->middleware(['auth', 'role:recepcionista']);

// Dashboard paciente
Route::get('/paciente/dashboard', function () {
    return view('paciente.pacienteinicio');
})->name('paciente.dashboard')->middleware(['auth', 'role:paciente']);

// Rutas administrador
Route::prefix('admin')->middleware(['auth', 'role:administrador'])->group(function () {
    Route::resource('usuarios', \App\Http\Controllers\Admin\UsuarioController::class)
        ->names('admin.usuarios');
    Route::patch('usuarios/{id}/toggle-estado', [\App\Http\Controllers\Admin\UsuarioController::class, 'toggleEstado'])
        ->name('admin.usuarios.toggle');
});

// Rutas odontólogo
Route::prefix('odontologo')->middleware(['auth', 'role:odontologo'])->group(function () {
    Route::get('/pacientes', [\App\Http\Controllers\Odontologo\PacienteController::class, 'index'])
        ->name('odontologo.pacientes.index');
    Route::get('/pacientes/{id}', [\App\Http\Controllers\Odontologo\PacienteController::class, 'show'])
        ->name('odontologo.pacientes.show');
});