@extends('layouts.admin')

@section('titulo', 'Editar Usuario - JM Dental')

@section('content')

<div class="flex h-screen overflow-hidden bg-slate-50">

    {{-- SIDEBAR --}}
    <aside class="w-64 flex flex-col bg-white border-r border-slate-200" style="min-height: 100vh">
        <div class="p-6 flex items-center gap-3">
            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center text-white">
                <span class="material-symbols-outlined text-sm">dentistry</span>
            </div>
            <h2 class="text-xl font-bold text-slate-900">DentalCare</h2>
        </div>
        <nav class="flex-1 px-4 space-y-1 mt-2">
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 transition-colors" href="{{ route('admin.dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span>Dashboard</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-50 text-blue-600 font-semibold" href="{{ route('admin.usuarios.index') }}">
                <span class="material-symbols-outlined">group</span>
                <span>Usuarios</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 transition-colors" href="#">
                <span class="material-symbols-outlined">calendar_month</span>
                <span>Citas</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 transition-colors" href="#">
                <span class="material-symbols-outlined">payments</span>
                <span>Finanzas</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 transition-colors" href="#">
                <span class="material-symbols-outlined">description</span>
                <span>Reportes</span>
            </a>
        </nav>
        <div class="p-4 border-t border-slate-200">
            <div class="flex items-center gap-3 p-2 bg-slate-50 rounded-xl">
                <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500">Administrador</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Cerrar sesión">
                        <span class="material-symbols-outlined text-slate-400">logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1 flex flex-col overflow-hidden">

        {{-- HEADER --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.usuarios.index') }}" class="text-slate-400 hover:text-slate-600">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <h1 class="text-xl font-bold text-slate-900">Editar Usuario</h1>
            </div>
        </header>

        {{-- FORMULARIO --}}
        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">

                    {{-- ERRORES --}}
                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.usuarios.update', $usuario->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- NOMBRE --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Nombre completo
                            </label>
                            <input type="text" name="name" value="{{ old('name', $usuario->name) }}"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                placeholder="Ingresa el nombre completo" required>
                        </div>

                        {{-- EMAIL --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Correo electrónico
                            </label>
                            <input type="email" name="email" value="{{ old('email', $usuario->email) }}"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                placeholder="correo@ejemplo.com" required>
                        </div>

                        {{-- TELÉFONO --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Teléfono
                            </label>
                            <input type="text" name="telefono" value="{{ old('telefono', $usuario->telefono) }}"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                placeholder="0991234567" required>
                        </div>

                        {{-- ROL --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Rol del usuario
                            </label>
                            <select name="rol"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500" required>
                                <option value="">Selecciona un rol</option>
                                @foreach($roles as $rol)
                                    <option value="{{ $rol->name }}"
                                        {{ old('rol', $usuario->roles->first()?->name) == $rol->name ? 'selected' : '' }}>
                                        {{ ucfirst($rol->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- CONTRASEÑA --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Nueva contraseña <span class="text-slate-400 font-normal">(dejar vacío para no cambiar)</span>
                            </label>
                            <input type="password" name="password"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                placeholder="Mínimo 8 caracteres">
                        </div>

                        {{-- CONFIRMAR CONTRASEÑA --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Confirmar nueva contraseña
                            </label>
                            <input type="password" name="password_confirmation"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                placeholder="Repite la nueva contraseña">
                        </div>

                        {{-- BOTONES --}}
                        <div class="flex gap-4 pt-4">
                            <button type="submit"
                                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-xl transition-colors">
                                Guardar Cambios
                            </button>
                            <a href="{{ route('admin.usuarios.index') }}"
                                class="flex-1 text-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition-colors">
                                Cancelar
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

@endsection