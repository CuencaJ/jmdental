@extends('layouts.admin')

@section('titulo', 'Agregar Paciente - JM Dental')

@section('content')

<div class="flex h-screen overflow-hidden bg-slate-50">

    @include('layouts.partials.sidebar-odontologo')

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1 flex flex-col overflow-hidden">

        {{-- CONTENIDO SCROLLABLE --}}
        <div class="flex-1 overflow-y-auto p-8 lg:p-12">
            <div class="max-w-2xl mx-auto space-y-6">

                {{-- HEADER / VOLVER --}}
                <div class="flex items-center gap-3">
                    <button type="button" onclick="window.history.back();"
                        class="text-slate-400 hover:text-slate-600 bg-transparent border-0 p-0 flex items-center cursor-pointer">
                        <span class="material-symbols-outlined text-2xl">arrow_back</span>
                    </button>
                    <h1 class="text-2xl font-bold text-slate-900">Agregar paciente</h1>
                </div>

                {{-- ALERTAS DE ERROR --}}
                @if($errors->any())
                    <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm shadow-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- FORMULARIO --}}
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-8 lg:p-10">
                    <form action="{{ route('odontologo.pacientes.store') }}" method="POST" class="space-y-6">
                        @csrf

                        {{-- CÉDULA --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Número de Cédula</label>
                            <input type="text" name="cedula" value="{{ old('cedula') }}" maxlength="10"
                                class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white transition-colors"
                                placeholder="Ej. 1312345678" required>
                        </div>

                        {{-- NOMBRE COMPLETO --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nombre completo</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white transition-colors"
                                placeholder="Ej. Juan Carlos Pérez Gómez" required>
                        </div>

                        {{-- CORREO Y TELÉFONO --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Correo electrónico</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white transition-colors"
                                    placeholder="ejemplo@correo.com" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Teléfono</label>
                                <input type="text" name="telefono" value="{{ old('telefono') }}"
                                    class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white transition-colors"
                                    placeholder="099856321">
                            </div>
                        </div>

                        {{-- CONTRASEÑAS --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Contraseña</label>
                                <input type="password" name="password"
                                    class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white transition-colors"
                                    placeholder="••••••••" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Confirmar contraseña</label>
                                <input type="password" name="password_confirmation"
                                    class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white transition-colors"
                                    placeholder="••••••••" required>
                            </div>
                        </div>

                        {{-- AVISO INFORMATIVO ROL --}}
                        <div class="bg-blue-50/60 border border-blue-100 rounded-2xl p-4 flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-500 text-xl flex-shrink-0">info</span>
                            <p class="text-xs text-slate-600">
                                El rol de <strong class="text-slate-800 font-semibold">Paciente</strong> se asigna automáticamente.
                            </p>
                        </div>

                        {{-- BOTONES DE ACCIÓN --}}
                        <div class="flex items-center justify-end gap-3 pt-4">
                            <button type="button" onclick="window.history.back();"
                                class="px-6 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-800 hover:bg-slate-100 transition-colors bg-transparent border-0 cursor-pointer">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-blue-500/20 transition-all">
                                Guardar paciente
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>

    </main>
</div>

@endsection