@extends('layouts.admin')

@section('titulo', 'Perfil de Usuario - JM Dental')

@section('content')

<div class="flex min-h-screen bg-slate-50">

    @if(Auth::user()->hasRole('administrador'))
        @include('layouts.partials.sidebar-admin')
    @elseif(Auth::user()->hasRole('odontologo'))
        @include('layouts.partials.sidebar-odontologo')
    @elseif(Auth::user()->hasRole('recepcionista'))
        @include('layouts.partials.sidebar-recepcionista')
    @endif

    <main class="flex-1 overflow-y-auto p-8 lg:p-10">

        <div class="mb-6">
            <a href="{{ url()->previous() }}"
                class="flex items-center gap-2 text-slate-400 hover:text-blue-500 transition-colors text-sm font-semibold">
                <span class="material-symbols-outlined text-xl">arrow_back</span>
                <span>Volver</span>
            </a>
        </div>

        {{-- MENSAJE --}}
        @if(session('mensaje'))
            <div class="bg-green-50 border border-green-200 text-green-700 text-sm font-medium px-4 py-3 rounded-xl mb-6">
                {{ session('mensaje') }}
            </div>
        @endif

        <header class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div class="flex items-center gap-6">
                <div class="relative">
                    <div class="w-24 h-24 rounded-full bg-blue-500 flex items-center justify-center text-white text-3xl font-bold border-4 border-slate-200 shadow-xl">
                        {{ strtoupper(substr($usuario->name, 0, 2)) }}
                    </div>
                    <div class="absolute bottom-1 right-1 w-6 h-6 bg-white border-4 border-white rounded-full flex items-center justify-center">
                        <div class="w-2 h-2 {{ $usuario->activo ? 'bg-green-500' : 'bg-slate-400' }} rounded-full"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-3xl font-extrabold text-slate-900">{{ $usuario->name }}</h2>
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                            {{ $usuario->activo ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                    <p class="text-lg text-slate-500 mt-1">#BS-{{ str_pad($usuario->id, 4, '0', STR_PAD_LEFT) }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        @foreach($usuario->roles as $rol)
                            @php
                                $colores = [
                                    'administrador' => 'bg-blue-100 text-blue-700',
                                    'odontologo'    => 'bg-green-100 text-green-700',
                                    'recepcionista' => 'bg-amber-100 text-amber-700',
                                    'paciente'      => 'bg-purple-100 text-purple-700',
                                ];
                                $color = $colores[$rol->name] ?? 'bg-slate-100 text-slate-700';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $color }}">
                                {{ ucfirst($rol->name) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ACCIONES RÁPIDAS --}}
            <div class="flex flex-wrap gap-3">
                @if($usuario->hasRole('paciente'))
                    {{-- Ver Historial Clínico --}}
                    <a href="{{ route('odontologo.pacientes.historial', $usuario->id) }}"
                        class="flex items-center gap-2 bg-white border border-slate-200 text-slate-900 font-semibold text-sm px-5 py-3 rounded-xl hover:bg-slate-50 transition-colors shadow-sm">
                        <span class="material-symbols-outlined">folder_shared</span>
                        Ver Historial Clínico
                    </a>

                    {{-- Descargar Resumen PDF --}}
                    <a href="{{ route('odontologo.pacientes.resumen', $usuario->id) }}" target="_blank"
                        class="flex items-center gap-2 bg-white border border-slate-200 text-slate-900 font-semibold text-sm px-5 py-3 rounded-xl hover:bg-slate-50 transition-colors shadow-sm">
                        <span class="material-symbols-outlined">download</span>
                        Descargar Resumen
                    </a>

                    {{-- Agendar Cita --}}
                    <a href="{{ route('odontologo.citas.create', ['paciente_id' => $usuario->paciente->id ?? '']) }}"
                        class="flex items-center gap-2 bg-blue-500 text-white font-semibold text-sm px-6 py-3 rounded-xl hover:bg-blue-600 transition-colors shadow-lg shadow-blue-500/20">
                        <span class="material-symbols-outlined">calendar_add_on</span>
                        Agendar Cita
                    </a>
                @endif

                @if(Auth::user()->hasRole('administrador'))
                    <a href="{{ route('admin.usuarios.edit', $usuario->id) }}"
                        class="flex items-center gap-2 bg-white border border-slate-200 text-slate-900 font-semibold text-sm px-5 py-3 rounded-xl hover:bg-slate-50 transition-colors shadow-sm">
                        <span class="material-symbols-outlined">edit</span>
                        Editar Usuario
                    </a>
                @endif
            </div>
        </header>

        {{-- INFORMACIÓN PERSONAL --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 justify-center">
            <div class="lg:col-span-8 lg:col-start-3 space-y-6">
                <section class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-500">
                            <span class="material-symbols-outlined">person</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">Información Personal</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-xs font-bold uppercase text-slate-400">Teléfono</span>
                            <span class="text-sm font-bold text-slate-900">{{ $usuario->telefono ?? 'No registrado' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-xs font-bold uppercase text-slate-400">Correo</span>
                            <span class="text-sm font-bold text-slate-900">{{ $usuario->email }}</span>
                        </div>
                        @if($usuario->paciente)
                            @if($usuario->paciente->fecha_nacimiento)
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-xs font-bold uppercase text-slate-400">Edad</span>
                                <span class="text-sm font-bold text-slate-900">{{ $usuario->paciente->edad }} años</span>
                            </div>
                            @endif
                            @if($usuario->paciente->tipo_sangre)
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-xs font-bold uppercase text-slate-400">Tipo de Sangre</span>
                                <span class="text-sm font-bold text-slate-900">{{ $usuario->paciente->tipo_sangre }}</span>
                            </div>
                            @endif
                            @if($usuario->paciente->alergias)
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-xs font-bold uppercase text-slate-400">Alergias</span>
                                <span class="text-sm font-bold text-slate-900">{{ $usuario->paciente->alergias }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-xs font-bold uppercase text-slate-400">Tipo de Dentición</span>
                                <span class="text-sm font-bold {{ $usuario->paciente->color_denticion }} px-2 py-0.5 rounded-full">
                                    {{ $usuario->paciente->tipo_denticion }}
                                </span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-xs font-bold uppercase text-slate-400">Fecha de Registro</span>
                            <span class="text-sm font-bold text-slate-900">{{ $usuario->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-xs font-bold uppercase text-slate-400">Última Actualización</span>
                            <span class="text-sm font-bold text-slate-900">{{ $usuario->updated_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </section>
            </div>
        </div>

    </main>
</div>

@endsection