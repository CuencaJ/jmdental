<?php

namespace App\Http\Controllers\Odontologo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
    // Ver perfil del odontólogo
    public function index()
    {
        $usuario = Auth::user()->load('odontologo');
        return view('odontologo.odontologo-perfil', compact('usuario'));
    }

    // Actualizar perfil del odontólogo
    public function update(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email,' . $usuario->id,
            'telefono'       => 'required|string|max:15',
            'cedula'         => 'nullable|string|max:10',
            'especialidad'   => 'nullable|string|max:255',
            'numero_licencia' => 'nullable|string|max:255',
            'descripcion'    => 'nullable|string',
            'anios_experiencia' => 'nullable|integer|min:0',
            'universidad'    => 'nullable|string|max:255',
            'titulo'         => 'nullable|string|max:255',
        ]);

        // Actualizar datos básicos
        $usuario->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'telefono' => $request->telefono,
        ]);

        // Actualizar o crear datos del odontólogo
        $usuario->odontologo()->updateOrCreate(
            ['user_id' => $usuario->id],
            [
                'cedula'            => $request->cedula,
                'especialidad'      => $request->especialidad,
                'numero_licencia'   => $request->numero_licencia,
                'telefono'          => $request->telefono,
                'descripcion'       => $request->descripcion,
                'anios_experiencia' => $request->anios_experiencia,
                'universidad'       => $request->universidad,
                'titulo'            => $request->titulo,
            ]
        );

        // Cambiar contraseña si se proporcionó
        if ($request->filled('password')) {
            $request->validate([
                'password_actual'  => 'required',
                'password'         => 'required|min:8|confirmed',
            ]);

            if (!Hash::check($request->password_actual, $usuario->password)) {
                return back()->withErrors(['password_actual' => 'La contraseña actual no es correcta.']);
            }

            $usuario->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('odontologo.perfil')
            ->with('mensaje', 'Perfil actualizado correctamente.');
    }
}