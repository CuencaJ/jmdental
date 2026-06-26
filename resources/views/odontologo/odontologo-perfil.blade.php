@extends('layouts.admin')

@section('titulo', 'Mi Perfil - JM Dental')

@section('content')

<div class="flex h-screen overflow-hidden bg-slate-50">

    @include('layouts.partials.sidebar-odontologo')

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1 flex flex-col overflow-hidden">

        {{-- HEADER --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8">
            <div class="relative w-full max-w-md">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input class="w-full bg-slate-100 rounded-lg pl-10 pr-4 py-2 text-sm border-none outline-none"
                    placeholder="Buscar..." type="text"/>
            </div>
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

                <form action="{{ route('odontologo.perfil.update') }}" method="POST">
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
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 mt-1 inline-block">
                                    Odontólogo
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
                                    placeholder="Dr. Smith" required>
                            </div>
                            <div>
                                <label class="block text-sm text-slate-500 mb-1">Correo electrónico</label>
                                <input type="email" name="email" value="{{ old('email', $usuario->email) }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="correo@ejemplo.com" required>
                            </div>
                            <div>
                                <label class="block text-sm text-slate-500 mb-1">Teléfono</label>
                                <input type="text" name="telefono" value="{{ old('telefono', $usuario->telefono) }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="0991234567" required>
                            </div>
                            <div>
                                <label class="block text-sm text-slate-500 mb-1">Cédula</label>
                                <input type="text" name="cedula" value="{{ old('cedula', $usuario->odontologo->cedula ?? '') }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="1712345678" maxlength="10">
                            </div>
                        </div>
                    </div>

                    {{-- INFORMACIÓN PROFESIONAL --}}
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">
                        <h3 class="text-lg font-bold text-slate-900 mb-6">Información profesional</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-slate-500 mb-1">Especialidad</label>
                                <input type="text" name="especialidad" value="{{ old('especialidad', $usuario->odontologo->especialidad ?? '') }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Odontología general">
                            </div>
                            <div>
                                <label class="block text-sm text-slate-500 mb-1">Número de licencia</label>
                                <input type="text" name="numero_licencia" value="{{ old('numero_licencia', $usuario->odontologo->numero_licencia ?? '') }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="LIC-001">
                            </div>
                            <div>
                                <label class="block text-sm text-slate-500 mb-1">Años de experiencia</label>
                                <input type="number" name="anios_experiencia" value="{{ old('anios_experiencia', $usuario->odontologo->anios_experiencia ?? '') }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="5" min="0">
                            </div>
                            <div>
                                <label class="block text-sm text-slate-500 mb-1">Universidad</label>
                                <input type="text" name="universidad" value="{{ old('universidad', $usuario->odontologo->universidad ?? '') }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Universidad Central del Ecuador">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm text-slate-500 mb-1">Título</label>
                                <input type="text" name="titulo" value="{{ old('titulo', $usuario->odontologo->titulo ?? '') }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Doctor en Odontología">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm text-slate-500 mb-1">Descripción profesional</label>
                                <textarea name="descripcion" rows="3"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Odontólogo general con experiencia en tratamientos preventivos y restaurativos.">{{ old('descripcion', $usuario->odontologo->descripcion ?? '') }}</textarea>
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