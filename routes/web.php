<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutenticacionController;
use Illuminate\Support\Facades\Auth;

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
    $inicio = \Carbon\Carbon::now()->startOfMonth();
    $fin = \Carbon\Carbon::now()->endOfMonth();
    $citasMes = \App\Models\Cita::where('estado', 'completada')
        ->whereBetween('fecha_hora', [$inicio, $fin])
        ->get();
    $totalTratamientosMes = $citasMes->count();
    $tratamientosMes = $citasMes->groupBy('motivo')
        ->map(fn ($g) => $g->count())
        ->sortDesc();

    $citasHoy = \App\Models\Cita::with('paciente.user')
        ->whereDate('fecha_hora', today())
        ->orderBy('fecha_hora')
        ->get();

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

    $totalCitasPendientes = \App\Models\Cita::when($odontologo, fn ($q) => $q->where('odontologo_id', $odontologo->id))
        ->where('estado', 'pendiente')
        ->count();

    $citasHoyConfirmadas = $citasHoy->where('estado', 'confirmada')->count();
    $citasHoyPendientes = $citasHoy->where('estado', 'pendiente')->count();

    return view('odontologo.odontologoinicio', compact(
        'citasHoy',
        'totalTratamientosMes',
        'tratamientosMes',
        'totalPacientes',
        'totalCitasPendientes',
        'citasHoyConfirmadas',
        'citasHoyPendientes'
    ));
})->name('odontologo.dashboard')->middleware(['auth', 'role:odontologo']);

// Dashboard recepcionista
Route::get('/recepcionista/dashboard', function () {
    return view('recepcionista.recepcionistainicio');
})->name('recepcionista.dashboard')->middleware(['auth', 'role:recepcionista']);

// Rutas administrador
Route::prefix('admin')->middleware(['auth', 'role:administrador'])->group(function () {
    Route::resource('usuarios', \App\Http\Controllers\Admin\UsuarioController::class)
        ->names('admin.usuarios');
    Route::patch('usuarios/{id}/toggle-estado', [\App\Http\Controllers\Admin\UsuarioController::class, 'toggleEstado'])
        ->name('admin.usuarios.toggle');

    Route::get('/citas', [\App\Http\Controllers\Citas\CitaController::class, 'indexAdmin'])
        ->name('admin.citas.index');
    Route::get('/citas/crear', [\App\Http\Controllers\Citas\CitaController::class, 'createAdmin'])
        ->name('admin.citas.create');
    Route::post('/citas', [\App\Http\Controllers\Citas\CitaController::class, 'storeAdmin'])
        ->name('admin.citas.store');
    Route::patch('/citas/{id}/estado', [\App\Http\Controllers\Citas\CitaController::class, 'updateEstado'])
        ->name('admin.citas.estado');

    Route::get('/reportes/tratamientos', [\App\Http\Controllers\Admin\ReporteController::class, 'tratamientos'])
        ->name('admin.reportes.tratamientos');

    Route::get('/horario', [\App\Http\Controllers\Admin\HorarioController::class, 'index'])
        ->name('admin.horario.index');
    Route::patch('/horario', [\App\Http\Controllers\Admin\HorarioController::class, 'update'])
        ->name('admin.horario.update');
    Route::get('/semana', [\App\Http\Controllers\SemanaController::class, 'adminIndex'])
        ->name('admin.semana');    
});

// Rutas odontólogo
Route::prefix('odontologo')->middleware(['auth', 'role:odontologo'])->group(function () {
    Route::get('/pacientes', [\App\Http\Controllers\Odontologo\PacienteController::class, 'index'])
        ->name('odontologo.pacientes.index');
    Route::get('/pacientes/crear', [\App\Http\Controllers\Odontologo\PacienteController::class, 'create'])
        ->name('odontologo.pacientes.create');
    Route::post('/pacientes', [\App\Http\Controllers\Odontologo\PacienteController::class, 'store'])
        ->name('odontologo.pacientes.store');
    Route::get('/pacientes/{id}', [\App\Http\Controllers\Odontologo\PacienteController::class, 'show'])
        ->name('odontologo.pacientes.show');
    Route::get('/pacientes/{id}/historial', [\App\Http\Controllers\Odontologo\PacienteController::class, 'historial'])
        ->name('odontologo.pacientes.historial');
    Route::get('/pacientes/{id}/resumen', [\App\Http\Controllers\Odontologo\PacienteController::class, 'resumen'])
        ->name('odontologo.pacientes.resumen');

    Route::get('/agenda', [\App\Http\Controllers\Citas\CitaController::class, 'indexOdontologo'])
        ->name('odontologo.agenda');
    Route::get('/citas/crear', [\App\Http\Controllers\Citas\CitaController::class, 'createOdontologo'])
        ->name('odontologo.citas.create');
    Route::post('/citas', [\App\Http\Controllers\Citas\CitaController::class, 'storeOdontologo'])
        ->name('odontologo.citas.store');
    Route::patch('/citas/{id}/estado', [\App\Http\Controllers\Citas\CitaController::class, 'updateEstado'])
        ->name('odontologo.citas.estado');

    Route::get('/historial', [\App\Http\Controllers\Odontologo\HistorialController::class, 'index'])
        ->name('odontologo.historial');
    Route::get('/historial/{id}', [\App\Http\Controllers\Odontologo\HistorialController::class, 'ver'])
        ->name('odontologo.historial.ver');
    Route::get('/historial/{id}/editar', [\App\Http\Controllers\Odontologo\HistorialController::class, 'editar'])
        ->name('odontologo.historial.editar');
    Route::patch('/historial/{id}', [\App\Http\Controllers\Odontologo\HistorialController::class, 'actualizar'])
        ->name('odontologo.historial.actualizar');
    Route::get('/historial/archivo/{id}/eliminar', [\App\Http\Controllers\Odontologo\HistorialController::class, 'eliminarArchivo'])
        ->name('odontologo.historial.archivo.eliminar');

    Route::get('/perfil', [\App\Http\Controllers\Odontologo\PerfilController::class, 'index'])
        ->name('odontologo.perfil');
    Route::put('/perfil', [\App\Http\Controllers\Odontologo\PerfilController::class, 'update'])
        ->name('odontologo.perfil.update');
    Route::get('/semana', [\App\Http\Controllers\SemanaController::class, 'odontologoIndex'])
        ->name('odontologo.semana');
});

// Rutas paciente
Route::prefix('paciente')->middleware(['auth', 'role:paciente'])->group(function () {
    Route::get('/dashboard', function () {
        $paciente = \App\Models\Paciente::where('user_id', Auth::id())->first();

        $proximaCita = \App\Models\Cita::with('odontologo.user')
            ->when($paciente, fn($q) => $q->where('paciente_id', $paciente->id))
            ->where('fecha_hora', '>=', now())
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->orderBy('fecha_hora')
            ->first();

        $totalTratamientos = \App\Models\Tratamiento::whereHas('cita', fn($q) =>
            $q->when($paciente, fn($q2) => $q2->where('paciente_id', $paciente->id))
        )->count();

        return view('paciente.pacienteinicio', compact(
            'proximaCita',
            'totalTratamientos'
        ));
    })->name('paciente.dashboard');

    Route::get('/citas', [\App\Http\Controllers\Citas\CitaController::class, 'indexPaciente'])
        ->name('paciente.citas');
    Route::get('/citas/crear', [\App\Http\Controllers\Citas\CitaController::class, 'createPaciente'])
        ->name('paciente.citas.create');
    Route::post('/citas', [\App\Http\Controllers\Citas\CitaController::class, 'storePaciente'])
        ->name('paciente.citas.store');

    Route::get('/tratamientos', function () {
        $paciente = \App\Models\Paciente::where('user_id', Auth::id())->first();

        $tratamientos = \App\Models\Tratamiento::whereHas('cita', fn($q) =>
            $q->when($paciente, fn($q2) => $q2->where('paciente_id', $paciente->id))
        )->with(['cita.odontologo.user', 'piezas'])->orderBy('fecha_tratamiento', 'desc')->get();

        $totalTratamientos = $tratamientos->count();
        $totalCosto = $tratamientos->sum('costo');

        $datosTratamientos = $tratamientos->map(function($t) {
            return [
                'id'            => $t->id,
                'nombre'        => $t->nombre,
                'descripcion'   => $t->descripcion ?? 'Sin descripción',
                'fecha'         => \Carbon\Carbon::parse($t->fecha_tratamiento)->format('d/m/Y'),
                'odontologo'    => $t->cita->odontologo->user->name ?? 'No asignado',
                'estado'        => $t->estado,
                'costo'         => number_format($t->costo, 2),
                'observaciones' => $t->observaciones ?? 'Sin observaciones',
            ];
        });

        return view('paciente.paciente-tratamientos', compact(
            'tratamientos',
            'totalTratamientos',
            'totalCosto',
            'datosTratamientos'
        ));
    })->name('paciente.tratamientos');

    // Descargar PDF de un tratamiento específico
    Route::get('/tratamientos/{id}/pdf', function ($id) {
        $paciente = \App\Models\Paciente::where('user_id', Auth::id())->first();

        $tratamiento = \App\Models\Tratamiento::with([
            'cita.paciente.user',
            'cita.odontologo.user',
            'piezas'
        ])->whereHas('cita', fn($q) =>
            $q->when($paciente, fn($q2) => $q2->where('paciente_id', $paciente->id))
        )->findOrFail($id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'paciente.paciente-tratamiento-pdf',
            compact('tratamiento')
        );
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('tratamiento-' . \Illuminate\Support\Str::slug($tratamiento->nombre) . '.pdf');
    })->name('paciente.tratamientos.pdf');

    Route::get('/perfil', [\App\Http\Controllers\Paciente\PacientePerfilController::class, 'index'])
        ->name('paciente.perfil');
    Route::put('/perfil', [\App\Http\Controllers\Paciente\PacientePerfilController::class, 'update'])
        ->name('paciente.perfil.update');
    
});
     //Ruta compartida — slots disponibles (accesible por todos los roles)
Route::get('/citas/horas-disponibles', [\App\Http\Controllers\Admin\HorarioController::class, 'slotsDisponibles'])
    ->middleware('auth')
    ->name('citas.horas-disponibles');

Route::post('/semana/bloquear', [\App\Http\Controllers\SemanaController::class, 'bloquear'])
    ->middleware('auth')
    ->name('semana.bloquear');

// Historia Clínica 033
Route::get('/pacientes/{id}/historia/crear', [\App\Http\Controllers\Odontologo\HistoriaClinicaController::class, 'create'])
    ->name('odontologo.historia.create');
Route::post('/pacientes/{id}/historia', [\App\Http\Controllers\Odontologo\HistoriaClinicaController::class, 'store'])
    ->name('odontologo.historia.store');
Route::get('/pacientes/{id}/historia', [\App\Http\Controllers\Odontologo\HistoriaClinicaController::class, 'edit'])
    ->name('odontologo.historia.edit');
Route::patch('/pacientes/{id}/historia', [\App\Http\Controllers\Odontologo\HistoriaClinicaController::class, 'update'])
    ->name('odontologo.historia.update');
Route::get('/pacientes/{id}/historia/pdf', [\App\Http\Controllers\Odontologo\HistoriaClinicaController::class, 'pdf'])
    ->name('odontologo.historia.pdf');