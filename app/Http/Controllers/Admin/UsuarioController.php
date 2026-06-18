<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
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
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'telefono' => 'required|string|max:15',
            'password' => 'required|min:8|confirmed',
            'rol'      => 'required|exists:roles,name',
        ]);

        $usuario = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
        ]);

        $usuario->assignRole($request->rol);

        return redirect()->route('admin.usuarios.index')
            ->with('mensaje', 'Usuario creado correctamente.')
            ->with('icono', 'success');
    }

    // Ver detalle de un usuario
    public function show($id)
    {
        $usuario = User::with('roles')->findOrFail($id);
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
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'telefono' => 'required|string|max:15',
            'rol'      => 'required|exists:roles,name',
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