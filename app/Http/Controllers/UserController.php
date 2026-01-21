<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        $usuarios = User::all();
        return view('admin.users.index', compact('usuarios'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|max:250',
            'email'=>'required|max:250|unique:users',
            'password'=>'required|max:250|confirmed',
        ]);

        $usuario = new User();
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = Hash::make($request['password']);
        $usuario->save();
        //nos redirige a nuestra vista principal
        return redirect()->route('admin.users.index')
        //mostramos un mensaje
        ->with('message','Usuario registrado exitosamente')
        ->with('icon','success');
    }

    public function show($id)
    {
        $usuario = User::findorfail($id);
        return view('admin.users.show', compact('usuario'));
    }

    public function edit($id)
    {
        $usuario = User::findorfail($id);
        return view('admin.users.edit', compact('usuario'));
    }

    public function update(Request $request, string $id)
    {
        $usuario = User::find($id);
        $request->validate([
            'name'=>'required|max:250',
            'email'=>'required|max:250|unique:users,email,'.$usuario->id,
            'password'=>'nullable|max:250|confirmed',
        ]);
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        if($request->filled('password')){
            $usuario->password = Hash::make($request['password']);
        }
        $usuario->save();
        return redirect()->route('admin.users.index')
        ->with('message','Usuario registrado exitosamente')
        ->with('icon','success');
    }

    public function confirmDelete($id){
        $usuario = User::findorfail($id);
        return view('admin.users.delete', compact('usuario'));
    }
    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('admin.users.index')
        //mostramos un mensaje
        ->with('message','Usuario registrado exitosamente')
        ->with('icon','success');
    }
}
