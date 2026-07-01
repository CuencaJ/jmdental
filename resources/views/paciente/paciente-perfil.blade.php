@extends('layouts.admin')

@section('titulo', 'Mi Perfil - JM Dental')

@section('content')

<div class="flex h-screen overflow-hidden bg-slate-50">

    @include('layouts.partials.sidebar-paciente')

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1 flex flex-col overflow-hidden">

        {{-- HEADER --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8">
            <h1 class="text-xl font-bold text-slate-900">Mi Perfil</h1>
        </header>

        {{-- CONTENIDO --}}
        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-3xl mx-auto space-y-6">

                {{-- MENSAJE ÉXITO --}}
                @if(session('mensaje'))
                    <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-medium">
                        {{ session('mensaje') }}
                    </div>
                @endif

                {{-- ERRORES --}}
                @if($errors->any())
                    <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('paciente.perfil.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- TARJETA PERFIL --}}
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-full bg-blue-500 flex items-center justify-center text-white text-2xl font-bold">
                                {{ strtoupper(substr($usuario->name, 0, 2)) }}
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-900">{{ $usuario->name }}</h2>
                                <p class="text-sm text-slate-500">{{ $usuario->email }}</p>
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700 mt-1 inline-block">
                                    Paciente
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- INFORMACIÓN PERSONAL --}}
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">
                        <h3 class="text-lg font-bold text-slate-900 mb-6">Información personal</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-slate-500 mb-1">Nombre completo</label>
                                <input type="text" name="name" value="{{ old('name', $usuario->name) }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm text-slate-500 mb-1">Correo electrónico</label>
                                <input type="email" name="email" value="{{ old('email', $usuario->email) }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm text-slate-500 mb-1">Teléfono</label>
                                <input type="text" name="telefono" value="{{ old('telefono', $usuario->telefono) }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm text-slate-500 mb-1">Cédula</label>
                                <input type="text" name="cedula" value="{{ old('cedula', $usuario->paciente->cedula ?? '') }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    maxlength="10">
                            </div>
                            <div>
                                <label class="block text-sm text-slate-500 mb-1">Fecha de nacimiento</label>
                                <input type="date" name="fecha_nacimiento"
                                    value="{{ old('fecha_nacimiento', $usuario->paciente->fecha_nacimiento ? $usuario->paciente->fecha_nacimiento->format('Y-m-d') : '') }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm text-slate-500 mb-1">Tipo de sangre</label>
                                <select name="tipo_sangre"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500">
                                    <option value="">Selecciona el tipo</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $tipo)
                                        <option value="{{ $tipo }}"
                                            {{ old('tipo_sangre', $usuario->paciente->tipo_sangre ?? '') == $tipo ? 'selected' : '' }}>
                                            {{ $tipo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm text-slate-500 mb-1">Dirección</label>
                                <input type="text" name="direccion"
                                    value="{{ old('direccion', $usuario->paciente->direccion ?? '') }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Av. Ejemplo N23-45, Ciudad">
                            </div>
                        </div>
                    </div>

                    {{-- INFORMACIÓN MÉDICA --}}
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">
                        <h3 class="text-lg font-bold text-slate-900 mb-6">Información médica</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-slate-500 mb-1">Alergias</label>
                                <textarea name="alergias" rows="2"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Ej: Penicilina, Ibuprofeno">{{ old('alergias', $usuario->paciente->alergias ?? '') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm text-slate-500 mb-1">Contacto de emergencia</label>
                                <input type="text" name="contacto_emergencia"
                                    value="{{ old('contacto_emergencia', $usuario->paciente->contacto_emergencia ?? '') }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Ej: Carlos Pérez">
                            </div>
                            <div>
                                <label class="block text-sm text-slate-500 mb-1">Teléfono de emergencia</label>
                                <input type="text" name="telefono_emergencia"
                                    value="{{ old('telefono_emergencia', $usuario->paciente->telefono_emergencia ?? '') }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="0987654321">
                            </div>
                        </div>
                    </div>

                    {{-- CAMBIAR CONTRASEÑA --}}
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">
                        <h3 class="text-lg font-bold text-slate-900 mb-6">Cambiar contraseña</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm text-slate-500 mb-1">Contraseña actual</label>
                                <input type="password" name="password_actual"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="••••••••">
                            </div>
                            <div>
                                <label class="block text-sm text-slate-500 mb-1">Nueva contraseña</label>
                                <input type="password" name="password"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="••••••••">
                            </div>
                            <div>
                                <label class="block text-sm text-slate-500 mb-1">Confirmar contraseña</label>
                                <input type="password" name="password_confirmation"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="••••••••">
                            </div>
                        </div>
                    </div>

                    {{-- BOTÓN GUARDAR --}}
                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold px-8 py-3 rounded-xl transition-colors">
                            Guardar cambios
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </main>
</div>

@endsection