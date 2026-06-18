@extends('layouts.admin')

@section('titulo', 'Editar Usuario - JM Dental')

@section('content')

<div class="flex h-screen overflow-hidden bg-slate-50">

    @include('layouts.partials.sidebar-admin')

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