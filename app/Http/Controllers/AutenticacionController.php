<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AutenticacionController extends Controller
{
    // Mostrar formulario de login
    public function mostrarLogin()
    {
        return view('auth.login');
    }

    // Procesar login
    public function iniciarSesion(Request $request)
    {
        $credenciales = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credenciales)) {
            $request->session()->regenerate();
            $usuario = Auth::user();

            // Redirigir según el rol
            if ($usuario->hasRole('administrador')) {
                return redirect()->route('admin.dashboard');
            } elseif ($usuario->hasRole('odontologo')) {
                return redirect()->route('odontologo.dashboard');
            } elseif ($usuario->hasRole('recepcionista')) {
                return redirect()->route('recepcionista.dashboard');
            } elseif ($usuario->hasRole('paciente')) {
                return redirect()->route('paciente.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Las credenciales no son correctas.',
        ])->onlyInput('email');
    }

    // Cerrar sesión
    public function cerrarSesion(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // Mostrar formulario de registro
    public function mostrarRegistro()
    {
        return view('auth.register');
    }

    // Procesar registro
    public function registrar(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'telefono' => 'required|string|max:15',
            'password' => 'required|min:8|confirmed',
        ]);

        $usuario = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
        ]);

        $usuario->assignRole('paciente');

        Auth::login($usuario);

        return redirect()->route('paciente.dashboard');
    }
}