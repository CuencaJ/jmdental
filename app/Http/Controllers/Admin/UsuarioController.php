<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Paciente;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Rules\CedulaEcuatoriana;

class UsuarioController extends Controller
{
    // Ver lista de usuarios
    public function index()
    {
        $usuarios = User::with('roles')->get();
        $totalAdmins = User::role('administrador')->count();
        $totalDoctores = User::role('odontologo')->count();
        $totalRecepcionistas = User::role('recepcionista')->count();
        $totalPacientes = User::role('paciente')->count();

        return view('usuarios.listausuarios', compact(
            'usuarios',
            'totalAdmins',
            'totalDoctores',
            'totalRecepcionistas',
            'totalPacientes'
        ));
    }

    // Mostrar formulario de creación
    public function create()
    {
        $roles = Role::all();
        return view('usuarios.crearusuario', compact('roles'));
    }

    // Guardar nuevo usuario
    public function store(Request $request)
    {
        $request->validate([
            'primer_nombre'     => 'required|string|max:100',
            'segundo_nombre'    => 'nullable|string|max:100',
            'primer_apellido'   => 'required|string|max:100',
            'segundo_apellido'  => 'nullable|string|max:100',
            // La cédula es la credencial de login: obligatoria para todos los roles.
            'cedula' => ['required', 'digits:10', 'unique:users,cedula', new CedulaEcuatoriana],
            // El email NO es unique: un menor puede usar el correo de su representante.
            'email'             => 'required|string|email:rfc|max:255',
            'telefono'          => 'required|string|max:15',
            'password'          => 'required|min:8|confirmed',
            'rol'               => 'required|exists:roles,name',
            'fecha_nacimiento'  => 'required_if:rol,paciente|nullable|date|before:today|after:1900-01-01',
            'genero'            => 'nullable|in:Masculino,Femenino,Otro',
            'numero_licencia'   => 'nullable|string|max:255|unique:odontologos,numero_licencia',
            'anios_experiencia' => 'nullable|integer|min:0|max:70',
        ], [
            'cedula.unique'          => 'Esta cédula ya está registrada en el sistema.',
            'numero_licencia.unique' => 'Este número de licencia ya está registrado en otro odontólogo.',
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

        $usuario->assignRole($request->rol);

        // Si es paciente, guardar datos adicionales
        if ($request->rol === 'paciente') {
            $usuario->paciente()->create([
                'cedula'                => $request->cedula,
                'fecha_nacimiento'      => $request->fecha_nacimiento,
                'genero'                => $request->genero,
                'direccion'             => $request->direccion,
                'telefono'              => $request->telefono,
                'tipo_sangre'           => $request->tipo_sangre,
                'alergias'              => $request->alergias,
                'observaciones'         => $request->observaciones,
                'contacto_emergencia'   => $request->contacto_emergencia,
                'telefono_emergencia'   => $request->telefono_emergencia,
                'enfermedades_cronicas' => $request->enfermedades_cronicas,
                'medicamentos_actuales' => $request->medicamentos_actuales,
                'medico_cabecera'       => $request->medico_cabecera,
            ]);
        }

        // Sin este registro el odontólogo no aparece al agendar citas
        // y la sección O del formulario 033 sale vacía.
        if ($request->rol === 'odontologo') {
            $usuario->odontologo()->create([
                'cedula'            => $request->cedula,
                'especialidad'      => $request->especialidad,
                'numero_licencia'   => $request->numero_licencia ?: null,
                'telefono'          => $request->telefono,
                'universidad'       => $request->universidad,
                'titulo'            => $request->titulo,
                'anios_experiencia' => $request->anios_experiencia,
            ]);
        }

        return redirect()->route('admin.usuarios.index')
            ->with('mensaje', 'Usuario creado correctamente.')
            ->with('icono', 'success');
    }

    // Ver detalle de un usuario
    public function show($id)
    {
        $usuario = User::with('roles', 'paciente')->findOrFail($id);
        return view('usuarios.detalleusuario', compact('usuario'));
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $usuario = User::with('roles', 'paciente', 'odontologo')->findOrFail($id);
        $roles = Role::all();
        return view('usuarios.editarusuario', compact('usuario', 'roles'));
    }

    // Actualizar usuario
    public function update(Request $request, $id)
    {
        $usuario = User::with('odontologo')->findOrFail($id);

        $request->validate([
            'primer_nombre'    => 'required|string|max:100',
            'segundo_nombre'   => 'nullable|string|max:100',
            'primer_apellido'  => 'required|string|max:100',
            'segundo_apellido' => 'nullable|string|max:100',
            'cedula' => [
                'required', 'digits:10',
                Rule::unique('users', 'cedula')->ignore($usuario->id),
                new CedulaEcuatoriana,
            ],
            'email'            => 'required|string|email:rfc|max:255',
            'telefono'         => 'required|string|max:15',
            'password'         => 'nullable|min:8|confirmed',
            'rol'              => 'required|exists:roles,name',
            'fecha_nacimiento' => 'required_if:rol,paciente|nullable|date|before:today|after:1900-01-01',
            'genero'           => 'nullable|in:Masculino,Femenino,Otro',
            'numero_licencia'  => [
                'nullable', 'string', 'max:255',
                Rule::unique('odontologos', 'numero_licencia')->ignore($usuario->odontologo?->id),
            ],
            'anios_experiencia' => 'nullable|integer|min:0|max:70',
        ], [
            'cedula.unique'          => 'Esta cédula ya está registrada en otro usuario.',
            'numero_licencia.unique' => 'Este número de licencia ya está registrado en otro odontólogo.',
        ]);

        $usuario->update([
            'primer_nombre'    => $request->primer_nombre,
            'segundo_nombre'   => $request->segundo_nombre,
            'primer_apellido'  => $request->primer_apellido,
            'segundo_apellido' => $request->segundo_apellido,
            'cedula'           => $request->cedula,
            'email'            => $request->email,
            'telefono'         => $request->telefono,
        ]);

        if ($request->password) {
            $usuario->update(['password' => Hash::make($request->password)]);
        }

        $usuario->syncRoles($request->rol);

        // Si es paciente, actualizar datos adicionales
        if ($request->rol === 'paciente') {
            $usuario->paciente()->updateOrCreate(
                ['user_id' => $usuario->id],
                [
                    'cedula'                => $request->cedula,
                    'fecha_nacimiento'      => $request->fecha_nacimiento,
                    'genero'                => $request->genero,
                    'direccion'             => $request->direccion,
                    'telefono'              => $request->telefono,
                    'tipo_sangre'           => $request->tipo_sangre,
                    'alergias'              => $request->alergias,
                    'observaciones'         => $request->observaciones,
                    'contacto_emergencia'   => $request->contacto_emergencia,
                    'telefono_emergencia'   => $request->telefono_emergencia,
                    'enfermedades_cronicas' => $request->enfermedades_cronicas,
                    'medicamentos_actuales' => $request->medicamentos_actuales,
                    'medico_cabecera'       => $request->medico_cabecera,
                ]
            );
        }

        if ($request->rol === 'odontologo') {
            $usuario->odontologo()->updateOrCreate(
                ['user_id' => $usuario->id],
                [
                    'cedula'            => $request->cedula,
                    'especialidad'      => $request->especialidad,
                    'numero_licencia'   => $request->numero_licencia ?: null,
                    'telefono'          => $request->telefono,
                    'universidad'       => $request->universidad,
                    'titulo'            => $request->titulo,
                    'anios_experiencia' => $request->anios_experiencia,
                ]
            );
        }

        return redirect()->route('admin.usuarios.index')
            ->with('mensaje', 'Usuario actualizado correctamente.')
            ->with('icono', 'success');
    }

    // Eliminar usuario
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect()->route('admin.usuarios.index')
            ->with('mensaje', 'Usuario eliminado correctamente.')
            ->with('icono', 'success');
    }

    // Activar/desactivar usuario
    public function toggleEstado($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->update(['activo' => !$usuario->activo]);

        return redirect()->route('admin.usuarios.index')
            ->with('mensaje', 'Estado del usuario actualizado.')
            ->with('icono', 'success');
    }
}