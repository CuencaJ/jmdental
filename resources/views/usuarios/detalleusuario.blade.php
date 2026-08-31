@extends('layouts.admin')
@section('titulo', 'Detalle de Usuario - JM Dental')
@section('content')
<div class="flex min-h-screen bg-slate-50">
    @if(Auth::user()->hasRole('odontologo'))
        @include('layouts.partials.sidebar-odontologo')
    @elseif(Auth::user()->hasRole('recepcionista'))
        @include('layouts.partials.sidebar-recepcionista')
    @endif

    <main class="flex-1 overflow-y-auto p-8">
        {{-- BOTÓN VOLVER --}}
        <div class="mb-6">
            <a href="{{ Auth::user()->hasRole('administrador') ? route('admin.usuarios.index') : (Auth::user()->hasRole('recepcionista') ? route('recepcionista.pacientes') : route('odontologo.pacientes.index')) }}"
                class="inline-flex items-center gap-2 text-slate-400 hover:text-blue-500 transition-colors text-sm font-semibold">
                <span class="material-symbols-outlined text-xl">arrow_back</span>
                <span>Volver</span>
            </a>
        </div>

        {{-- CABECERA DEL USUARIO Y ACCIONES --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 mb-6 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-sm">
            <div class="flex items-center gap-5">
                <div class="relative">
                    <div class="w-16 h-16 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-2xl flex-shrink-0">
                        {{ strtoupper(substr($usuario->name, 0, 2)) }}
                    </div>
                    <span class="w-4 h-4 rounded-full border-2 border-white absolute bottom-0 right-0 {{ $usuario->activo ? 'bg-green-500' : 'bg-red-500' }}"></span>
                </div>
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl font-bold text-slate-900">{{ $usuario->name }}</h1>
                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $usuario->activo ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                            {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                    <p class="text-sm text-slate-400 font-mono mt-0.5">#{{ str_pad($usuario->id, 4, '0', STR_PAD_LEFT) }}</p>
                    <div class="mt-2">
                        @foreach($usuario->roles as $rol)
                            <span class="text-xs bg-purple-50 text-purple-600 px-2.5 py-0.5 rounded-full font-medium capitalize">{{ $rol->name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- BOTONES DE ACCIÓN DINÁMICOS POR ROL --}}
            <div class="flex flex-wrap items-center gap-3">
                {{-- Historia Clínica --}}
                <a href="{{ Auth::user()->hasRole('administrador') ? route('admin.historia.edit', $usuario->id) : route('odontologo.historia.edit', $usuario->id) }}"
                    class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-700 font-semibold text-sm px-4 py-2.5 rounded-xl hover:bg-slate-50 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-base">folder_open</span>
                    <span>Historia Clínica</span>
                </a>

                {{-- Descargar Resumen --}}
                <a href="{{ Auth::user()->hasRole('administrador') ? route('admin.pacientes.resumen', $usuario->id) : (Auth::user()->hasRole('recepcionista') ? route('recepcionista.pacientes.resumen', $usuario->id) : route('odontologo.pacientes.resumen', $usuario->id)) }}"
                    class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-700 font-semibold text-sm px-4 py-2.5 rounded-xl hover:bg-slate-50 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-base">download</span>
                    <span>Descargar Resumen</span>
                </a>

                {{-- Agendar Cita --}}
                @php
                    $pacienteId = $usuario->paciente->id ?? $usuario->id;
                    $rutaCita = match(true) {
                        Auth::user()->hasRole('administrador') => route('admin.citas.create', ['paciente_id' => $pacienteId]),
                        Auth::user()->hasRole('recepcionista') => route('recepcionista.citas.create', ['paciente_id' => $pacienteId]),
                        default                                => route('odontologo.citas.create', ['paciente_id' => $pacienteId]),
                    };
                @endphp
                <a href="{{ $rutaCita }}"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-4 py-2.5 rounded-xl shadow-sm transition-colors">
                    <span class="material-symbols-outlined text-base">calendar_add_on</span>
                    <span>Agendar Cita</span>
                </a>

                {{-- Editar Usuario (Admin) --}}
                @if(Auth::user()->hasRole('administrador'))
                    <a href="{{ route('admin.usuarios.edit', $usuario->id) }}"
                        class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-700 font-semibold text-sm px-4 py-2.5 rounded-xl hover:bg-slate-50 transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-base">edit</span>
                        <span>Editar Usuario</span>
                    </a>
                @endif
            </div>
        </div>

        {{-- INFORMACIÓN DETALLADA --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <h2 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-500">person</span>
                    Información de Contacto
                </h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <span class="text-slate-400 font-medium">Correo Electrónico:</span>
                        <span class="text-slate-800 font-semibold">{{ $usuario->email }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <span class="text-slate-400 font-medium">Teléfono:</span>
                        <span class="text-slate-800 font-semibold">{{ $usuario->telefono ?? 'No registrado' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <span class="text-slate-400 font-medium">Registrado el:</span>
                        <span class="text-slate-800 font-semibold">{{ $usuario->created_at ? $usuario->created_at->format('d/m/Y') : '-' }}</span>
                    </div>
                </div>
            </div>

            @if($usuario->paciente)
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <h2 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-500">dentistry</span>
                        Ficha Médica Rápida
                    </h2>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between border-b border-slate-100 pb-2">
                            <span class="text-slate-400 font-medium">Edad:</span>
                            <span class="text-slate-800 font-semibold">{{ $usuario->paciente->edad ? $usuario->paciente->edad . ' años' : 'No registrada' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-100 pb-2">
                            <span class="text-slate-400 font-medium">Tipo de Sangre:</span>
                            <span class="text-slate-800 font-semibold">{{ $usuario->paciente->tipo_sangre ?? 'No registrado' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-100 pb-2">
                            <span class="text-slate-400 font-medium">Dentición:</span>
                            <span class="text-slate-800 font-semibold">{{ $usuario->paciente->tipo_denticion ?? 'Permanente' }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>
</div>
@endsection