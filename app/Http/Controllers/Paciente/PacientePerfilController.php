<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PacientePerfilController extends Controller
{
    // Ver perfil del paciente
    public function index()
    {
        $usuario = Auth::user()->load('paciente');
        return view('paciente.paciente-perfil', compact('usuario'));
    }

    // Actualizar perfil del paciente
    public function update(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email,' . $usuario->id,
            'telefono'              => 'required|string|max:15',
            'cedula'                => 'nullable|string|max:10',
            'fecha_nacimiento'      => 'nullable|date',
            'direccion'             => 'nullable|string|max:255',
            'tipo_sangre'           => 'nullable|string|max:5',
            'alergias'              => 'nullable|string',
            'contacto_emergencia'   => 'nullable|string|max:255',
            'telefono_emergencia'   => 'nullable|string|max:15',
        ]);

        // Actualizar datos básicos
        $usuario->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'telefono' => $request->telefono,
        ]);

        // Actualizar o crear datos del paciente
        $usuario->paciente()->updateOrCreate(
            ['user_id' => $usuario->id],
            [
                'cedula'              => $request->cedula,
                'fecha_nacimiento'    => $request->fecha_nacimiento,
                'direccion'           => $request->direccion,
                'telefono'            => $request->telefono,
                'tipo_sangre'         => $request->tipo_sangre,
                'alergias'            => $request->alergias,
                'contacto_emergencia' => $request->contacto_emergencia,
                'telefono_emergencia' => $request->telefono_emergencia,
            ]
        );

        // Cambiar contraseña si se proporcionó
        if ($request->filled('password')) {
            $request->validate([
                'password_actual' => 'required',
                'password'        => 'required|min:8|confirmed',
            ]);

            if (!Hash::check($request->password_actual, $usuario->password)) {
                return back()->withErrors(['password_actual' => 'La contraseña actual no es correcta.']);
            }

            $usuario->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('paciente.perfil')
            ->with('mensaje', 'Perfil actualizado correctamente.');
    }
}