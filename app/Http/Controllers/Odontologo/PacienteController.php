<?php

namespace App\Http\Controllers\Odontologo;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PacienteController extends Controller
{
    // Ver lista de todos los pacientes
    public function index()
    {
        $pacientes = User::role('paciente')->get();
        $totalPacientes = $pacientes->count();

        return view('odontologo.pacientes.odontologo-pacientes-lista', compact(
            'pacientes',
            'totalPacientes'
        ));
    }

    // Ver detalle de un paciente
    public function show($id)
    {
        $usuario = User::role('paciente')->findOrFail($id);
        return view('usuarios.detalleusuario', compact('usuario'));
    }

    // Mostrar formulario para crear paciente
    public function create()
    {
        return view('odontologo.pacientes.odontologo-pacientes-crear');
    }

    // Guardar nuevo paciente
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'telefono' => 'nullable|string|max:15',
            'password' => 'required|min:8|confirmed',
        ], [
            'email.unique'       => 'Este correo ya está registrado.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'telefono' => $validated['telefono'],
            'password' => Hash::make($validated['password']),
            'activo'   => true,
        ]);

        $user->assignRole('paciente');

        Paciente::create([
            'user_id' => $user->id,
        ]);

        return redirect()->route('odontologo.pacientes.index')
            ->with('mensaje', 'Paciente registrado correctamente.');
    }

    // Ver historial clínico de un paciente específico
    public function historial($id)
    {
        $usuario = User::role('paciente')->with('paciente.citas.tratamiento')->findOrFail($id);
        $paciente = $usuario->paciente;

        $citas = $paciente ? $paciente->citas()
            ->with(['tratamiento.piezas', 'tratamiento.archivos'])
            ->whereHas('tratamiento')
            ->orderByDesc('fecha_hora')
            ->get() : collect();

        return view('odontologo.pacientes.odontologo-pacientes-historial', compact('usuario', 'paciente', 'citas'));
    }

    // Descargar resumen PDF del paciente
    public function resumen($id)
    {
        $usuario = User::role('paciente')->with('paciente')->findOrFail($id);
        $paciente = $usuario->paciente;

        $citas = $paciente ? $paciente->citas()
            ->with(['tratamiento.piezas'])
            ->whereHas('tratamiento')
            ->orderByDesc('fecha_hora')
            ->get() : collect();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'odontologo.pacientes.odontologo-pacientes-pdf',
            compact('usuario', 'paciente', 'citas')
        );

        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('resumen-' . \Illuminate\Support\Str::slug($usuario->name) . '.pdf');
    }
}