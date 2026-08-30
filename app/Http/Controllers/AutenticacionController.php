<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Rules\CedulaEcuatoriana;

class AutenticacionController extends Controller
{
    public function mostrarLogin()
    {
        return view('auth.login');
    }

    // Login por CÉDULA (no por email): el correo puede repetirse porque
    // los menores de edad usan el correo del representante.
    public function iniciarSesion(Request $request)
    {
        // Aquí la cédula es solo una credencial de búsqueda: no se valida
        // el algoritmo ni la unicidad. Si algo está mal, el mensaje debe
        // ser genérico ("credenciales incorrectas"), no revelar detalles.
        $credenciales = $request->validate([
            'cedula'   => 'required|digits:10',
            'password' => 'required',
        ]);

        if (Auth::attempt($credenciales, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $usuario = Auth::user();

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
            'cedula' => 'La cédula o la contraseña no son correctas.',
        ])->onlyInput('cedula');
    }

    public function cerrarSesion(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function mostrarRegistro()
    {
        return view('auth.register');
    }

    public function registrar(Request $request)
    {
        // Respaldo: si el JS del formulario no llenó los 4 campos ocultos,
        // se reparte el nombre completo aquí en el servidor.
        if (!$request->filled('primer_nombre') && $request->filled('nombre_completo')) {
            $t = preg_split('/\s+/', trim($request->nombre_completo), -1, PREG_SPLIT_NO_EMPTY);
            $n = count($t);

            $request->merge([
                'primer_nombre'    => $t[0] ?? '',
                'segundo_nombre'   => $n >= 4 ? implode(' ', array_slice($t, 1, $n - 3)) : '',
                'primer_apellido'  => $n >= 3 ? $t[$n - 2] : ($t[1] ?? ''),
                'segundo_apellido' => $n >= 3 ? $t[$n - 1] : '',
            ]);
        }

        $request->validate([
            'primer_nombre'    => 'required|string|max:100',
            'segundo_nombre'   => 'nullable|string|max:100',
            'primer_apellido'  => 'required|string|max:100',
            'segundo_apellido' => 'nullable|string|max:100',
            // La cédula es la credencial de login: única, y validada con el
            // algoritmo del Registro Civil ecuatoriano.
            'cedula'           => ['required', 'digits:10', 'unique:users,cedula', new CedulaEcuatoriana],
            // El email NO es unique: un menor puede usar el correo de su representante.
            'email'            => 'required|string|email:rfc|max:255',
            'telefono'         => 'required|string|max:15',
            'password'         => 'required|min:8|confirmed',
            'fecha_nacimiento' => 'required|date|before:today|after:1900-01-01',
            'genero'           => 'nullable|in:Masculino,Femenino,Otro',
        ], [
            'cedula.unique' => 'Esta cédula ya está registrada en el sistema.',
        ]);

        // `name` lo arma solo el hook booted() del modelo User.
        $usuario = User::create([
            'primer_nombre'    => $request->primer_nombre,
            'segundo_nombre'   => $request->segundo_nombre,
            'primer_apellido'  => $request->primer_apellido,
            'segundo_apellido' => $request->segundo_apellido,
            'cedula'           => $request->cedula,
            'email'            => $request->email,
            'telefono'         => $request->telefono,
            'password'         => Hash::make($request->password),
        ]);

        $usuario->assignRole('paciente');

        // Sin este registro las rutas /paciente abortan con 403.
        $usuario->paciente()->create([
            'cedula'           => $request->cedula,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'genero'           => $request->genero,
            'telefono'         => $request->telefono,
        ]);

        Auth::login($usuario);

        return redirect()->route('paciente.dashboard');
    }
}