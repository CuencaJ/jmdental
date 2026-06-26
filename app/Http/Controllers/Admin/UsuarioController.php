<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Paciente;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

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
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'telefono'         => 'required|string|max:15',
            'password'         => 'required|min:8|confirmed',
            'rol'              => 'required|exists:roles,name',
            'cedula'           => 'required_if:rol,paciente|nullable|string|max:10',
            'fecha_nacimiento' => 'required_if:rol,paciente|nullable|date',
            'genero'           => 'required_if:rol,paciente|nullable|in:Masculino,Femenino,Otro',
        ]);

        $usuario = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
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
        $usuario = User::with('roles')->findOrFail($id);
        $roles = Role::all();
        return view('usuarios.editarusuario', compact('usuario', 'roles'));
    }

    // Actualizar usuario
    public function update(Request $request, $id)
{
    $usuario = User::findOrFail($id);

    $request->validate([
        'name'             => 'required|string|max:255',
        'email'            => 'required|email|unique:users,email,' . $id,
        'telefono'         => 'required|string|max:15',
        'rol'              => 'required|exists:roles,name',
        'cedula'           => 'required_if:rol,paciente|nullable|string|max:10',
        'fecha_nacimiento' => 'required_if:rol,paciente|nullable|date',
    ]);

    $usuario->update([
        'name'     => $request->name,
        'email'    => $request->email,
        'telefono' => $request->telefono,
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