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
    // Tratamientos del mes
    $inicio = \Carbon\Carbon::now()->startOfMonth();
    $fin = \Carbon\Carbon::now()->endOfMonth();
    $citasMes = \App\Models\Cita::where('estado', 'completada')
        ->whereBetween('fecha_hora', [$inicio, $fin])
        ->get();
    $totalTratamientosMes = $citasMes->count();
    $tratamientosMes = $citasMes->groupBy('motivo')
        ->map(fn ($g) => $g->count())
        ->sortDesc();

    // Citas de hoy
    $citasHoy = \App\Models\Cita::with('paciente.user')
        ->whereDate('fecha_hora', today())
        ->orderBy('fecha_hora')
        ->get();

    // Métricas
    $totalUsuarios = \App\Models\User::count();
    $totalPacientes = \App\Models\Paciente::count();
    $pacientesActivos = \App\Models\Paciente::whereHas('user', fn($q) => $q->where('activo', true))->count();
    $pacientesInactivos = $totalPacientes - $pacientesActivos;
    $citasHoyConfirmadas = $citasHoy->where('estado', 'confirmada')->count();
    $citasHoyPendientes = $citasHoy->where('estado', 'pendiente')->count();

    return view('admin.admininicio', compact(
        'totalTratamientosMes',
        'tratamientosMes',
        'citasHoy',
        'totalUsuarios',
        'totalPacientes',
        'pacientesActivos',
        'pacientesInactivos',
        'citasHoyConfirmadas',
        'citasHoyPendientes'
    ));
})->name('admin.dashboard')->middleware(['auth', 'role:administrador']);

// Dashboard odontólogo
Route::get('/odontologo/dashboard', function () {
    $odontologo = \App\Models\Odontologo::where('user_id', Auth::id())->first();

    $citasHoy = \App\Models\Cita::with('paciente.user')
        ->when($odontologo, fn ($q) => $q->where('odontologo_id', $odontologo->id))
        ->whereDate('fecha_hora', today())
        ->orderBy('fecha_hora')
        ->get();

    $inicio = \Carbon\Carbon::now()->startOfMonth();
    $fin = \Carbon\Carbon::now()->endOfMonth();
    $citasMes = \App\Models\Cita::where('estado', 'completada')
        ->when($odontologo, fn ($q) => $q->where('odontologo_id', $odontologo->id))
        ->whereBetween('fecha_hora', [$inicio, $fin])
        ->get();

    $totalTratamientosMes = $citasMes->count();
    $tratamientosMes = $citasMes->groupBy('motivo')
        ->map(fn ($g) => $g->count())
        ->sortDesc();

    $totalPacientes = \App\Models\Paciente::count();
    $totalCitas = \App\Models\Cita::when($odontologo, fn ($q) => $q->where('odontologo_id', $odontologo->id))->count();
    $citasHoyConfirmadas = $citasHoy->where('estado', 'confirmada')->count();
    $citasHoyPendientes = $citasHoy->where('estado', 'pendiente')->count();

    return view('odontologo.odontologoinicio', compact(
        'citasHoy',
        'totalTratamientosMes',
        'tratamientosMes',
        'totalPacientes',
        'totalCitas',
        'citasHoyConfirmadas',
        'citasHoyPendientes'
    ));
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

    Route::resource('citas', \App\Http\Controllers\Admin\CitaController::class)
        ->names('admin.citas')
        ->only(['index', 'create', 'store']);
    Route::patch('citas/{id}/estado', [\App\Http\Controllers\Admin\CitaController::class, 'updateEstado'])
        ->name('admin.citas.estado');

    Route::get('/reportes/tratamientos', [\App\Http\Controllers\Admin\ReporteController::class, 'tratamientos'])
        ->name('admin.reportes.tratamientos');
});

// Rutas odontólogo
Route::prefix('odontologo')->middleware(['auth', 'role:odontologo'])->group(function () {
    Route::get('/pacientes', [\App\Http\Controllers\Odontologo\PacienteController::class, 'index'])
        ->name('odontologo.pacientes.index');
    Route::get('/pacientes/{id}', [\App\Http\Controllers\Odontologo\PacienteController::class, 'show'])
        ->name('odontologo.pacientes.show');
    Route::get('/agenda', [\App\Http\Controllers\Odontologo\AgendaController::class, 'index'])
        ->name('odontologo.agenda');
});