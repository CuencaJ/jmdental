@extends('layouts.admin')
@section('titulo', 'Detalle del Tratamiento - JM Dental')
@section('content')
<div class="flex h-screen overflow-hidden bg-slate-50">
    @include('layouts.partials.sidebar-odontologo')

    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center px-8">
            <div class="relative w-full max-w-md">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input class="w-full bg-slate-100 rounded-lg pl-10 pr-4 py-2 text-sm border-none outline-none" placeholder="Buscar..." type="text"/>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-3xl mx-auto space-y-6">

                <div class="flex items-center gap-3">
                    <a href="{{ route('odontologo.historial') }}" class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-500">
                        <span class="material-symbols-outlined">arrow_back</span>
                    </a>
                    <h1 class="text-xl font-bold text-slate-900">Detalle del tratamiento</h1>
                    <a href="{{ route('odontologo.historial.editar', $tratamiento->id) }}"
                        class="ml-auto flex items-center gap-2 bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-600">
                        <span class="material-symbols-outlined text-base">edit</span>
                        Editar
                    </a>
                </div>

                {{-- INFO PACIENTE --}}
                <div class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 flex items-center gap-3">
                    <span class="material-symbols-outlined text-blue-500">person</span>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ $tratamiento->cita->paciente->user->name ?? 'Paciente' }}</p>
                        <p class="text-xs text-slate-500">Cita del {{ $tratamiento->cita->fecha_hora->format('d/m/Y') }} — {{ $tratamiento->cita->motivo }}</p>
                    </div>
                </div>

                {{-- DATOS DEL TRATAMIENTO --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-slate-900">{{ $tratamiento->nombre }}</h2>
                        <span class="text-xs font-bold px-3 py-1 rounded-full {{ $tratamiento->estado === 'completado' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $tratamiento->estado === 'completado' ? 'Completado' : 'En proceso' }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-xs text-slate-400 mb-1">Fecha</p>
                            <p class="text-slate-900 font-medium">{{ $tratamiento->fecha_tratamiento->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 mb-1">Costo (referencia)</p>
                            <p class="text-slate-900 font-medium">${{ number_format($tratamiento->costo, 2) }}</p>
                        </div>
                    </div>
                    @if($tratamiento->descripcion)
                        <div>
                            <p class="text-xs text-slate-400 mb-1">Descripción</p>
                            <p class="text-sm text-slate-700">{{ $tratamiento->descripcion }}</p>
                        </div>
                    @endif
                    @if($tratamiento->observaciones)
                        <div>
                            <p class="text-xs text-slate-400 mb-1">Observaciones</p>
                            <p class="text-sm text-slate-700">{{ $tratamiento->observaciones }}</p>
                        </div>
                    @endif
                </div>

                {{-- PIEZAS DENTALES --}}
                @if($tratamiento->piezas->count() > 0)
                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <h3 class="text-base font-bold text-slate-900 mb-4">Piezas trabajadas</h3>
                    <div class="space-y-2">
                        @foreach($tratamiento->piezas as $pieza)
                            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                                <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center font-bold text-sm flex-shrink-0">
                                    {{ $pieza->pieza_numero }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="text-xs bg-blue-50 text-blue-600 font-medium px-2 py-0.5 rounded-full">
                                            {{ $pieza->tipo_denticion === 'permanente' ? 'Adulto' : 'Infantil' }}
                                        </span>
                                        @if($pieza->cara)
                                            <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">Cara: {{ $pieza->cara }}</span>
                                        @endif
                                        @if($pieza->ausente)
                                            <span class="text-xs bg-red-100 text-red-600 font-medium px-2 py-0.5 rounded-full">Ausente</span>
                                        @endif
                                    </div>
                                    @if($pieza->procedimiento)
                                        <p class="text-xs text-slate-600 mt-1">{{ $pieza->procedimiento }}</p>
                                    @endif
                                    @if($pieza->diagnostico)
                                        <p class="text-xs text-slate-400 mt-0.5">Dx: {{ $pieza->diagnostico }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- ARCHIVOS --}}
                @if($tratamiento->archivos->count() > 0)
                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <h3 class="text-base font-bold text-slate-900 mb-4">Archivos adjuntos</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($tratamiento->archivos as $archivo)
                            <a href="{{ Storage::url($archivo->ruta_archivo) }}" target="_blank"
                                class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl hover:bg-blue-50 transition-colors group">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0
                                    {{ str_contains($archivo->tipo_archivo, 'pdf') ? 'bg-red-100 text-red-500' : 'bg-blue-100 text-blue-500' }}">
                                    <span class="material-symbols-outlined text-xl">
                                        {{ str_contains($archivo->tipo_archivo, 'pdf') ? 'picture_as_pdf' : 'image' }}
                                    </span>
                                </div>
                                <div class="flex-1 overflow-hidden">
                                    <p class="text-sm font-medium text-slate-900 truncate group-hover:text-blue-500">{{ $archivo->nombre_archivo }}</p>
                                    <p class="text-xs text-slate-400">{{ round($archivo->tamanio_archivo / 1024) }} KB</p>
                                </div>
                                <span class="material-symbols-outlined text-slate-300 group-hover:text-blue-400">open_in_new</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </main>
</div>
@endsection