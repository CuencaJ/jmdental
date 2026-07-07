<?php

namespace App\Http\Controllers\Recepcionista;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PacienteRecepcionistaController extends Controller
{
    // Ver lista de pacientes
    public function index()
    {
        $pacientes = User::role('paciente')->get();
        $totalPacientes = $pacientes->count();

        return view('recepcionista.pacientes.recepcionista-pacientes-lista', compact(
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

    // Mostrar formulario de registro
    public function create()
    {
        return view('recepcionista.pacientes.recepcionista-pacientes-crear');
    }

    // Guardar nuevo paciente
    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'telefono'         => 'required|string|max:15',
            'password'         => 'required|min:8|confirmed',
            'cedula'           => 'nullable|string|max:10',
            'fecha_nacimiento' => 'nullable|date',
            'direccion'        => 'nullable|string|max:255',
            'tipo_sangre'      => 'nullable|string|max:5',
            'alergias'         => 'nullable|string',
            'contacto_emergencia'  => 'nullable|string|max:255',
            'telefono_emergencia'  => 'nullable|string|max:15',
        ]);

        $usuario = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
            'activo'   => true,
        ]);

        $usuario->assignRole('paciente');

        $usuario->paciente()->create([
            'cedula'              => $request->cedula,
            'fecha_nacimiento'    => $request->fecha_nacimiento,
            'direccion'           => $request->direccion,
            'telefono'            => $request->telefono,
            'tipo_sangre'         => $request->tipo_sangre,
            'alergias'            => $request->alergias,
            'contacto_emergencia' => $request->contacto_emergencia,
            'telefono_emergencia' => $request->telefono_emergencia,
        ]);

        return redirect()->route('recepcionista.pacientes')
            ->with('mensaje', 'Paciente registrado correctamente.');
    }
}