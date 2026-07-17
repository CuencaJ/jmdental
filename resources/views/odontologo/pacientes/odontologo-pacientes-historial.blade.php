@extends('layouts.admin')
@section('titulo', 'Historial del Paciente - JM Dental')
@section('content')
<div class="flex min-h-screen bg-slate-50">
    @if(Auth::user()->hasRole('odontologo'))
        @include('layouts.partials.sidebar-odontologo')
    @elseif(Auth::user()->hasRole('recepcionista'))
        @include('layouts.partials.sidebar-recepcionista')
    @endif
    <main class="flex-1 overflow-y-auto p-8">

        <div class="mb-6 flex items-center justify-between">
            <a href="{{ url()->previous() }}"
                class="flex items-center gap-2 text-slate-400 hover:text-blue-500 transition-colors text-sm font-semibold">
                <span class="material-symbols-outlined text-xl">arrow_back</span>
                <span>Volver</span>
            </a>
            <a href="{{ Auth::user()->hasRole('odontologo') 
                ? route('odontologo.historia.pdf', $usuario->id) 
                : route('recepcionista.historia.pdf', $usuario->id) }}" 
                target="_blank"
                class="flex items-center gap-2 bg-white border border-slate-200 text-slate-700 font-semibold text-sm px-4 py-2 rounded-xl hover:bg-slate-50 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-base">download</span>
                Descargar Historial Clinico
            </a>
        </div>

        {{-- CABECERA PACIENTE --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 mb-6 flex items-center gap-5">
            <div class="w-14 h-14 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-xl flex-shrink-0">
                {{ strtoupper(substr($usuario->name, 0, 2)) }}
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-900">{{ $usuario->name }}</h1>
                <p class="text-sm text-slate-500">{{ $usuario->email }}</p>
                @if($paciente)
                    <div class="flex gap-3 mt-1">
                        @if($paciente->edad)
                            <span class="text-xs text-slate-400">{{ $paciente->edad }} años</span>
                        @endif
                        @if($paciente->tipo_sangre)
                            <span class="text-xs bg-red-50 text-red-600 px-2 py-0.5 rounded-full font-medium">{{ $paciente->tipo_sangre }}</span>
                        @endif
                        <span class="text-xs {{ $paciente->color_denticion }} px-2 py-0.5 rounded-full font-medium">{{ $paciente->tipo_denticion }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- TRATAMIENTOS --}}
        <h2 class="text-lg font-bold text-slate-900 mb-4">Tratamientos realizados</h2>

        @forelse($citas as $cita)
            @php $t = $cita->tratamiento; @endphp
            <div class="bg-white border border-slate-200 rounded-2xl p-6 mb-4 space-y-4">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-slate-900">{{ $t->nombre }}</h3>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $t->fecha_tratamiento->format('d/m/Y') }}</p>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        @if($t->costo > 0)
                            <span class="text-sm font-bold text-slate-900">${{ number_format($t->costo, 2) }}</span>
                        @endif
                        <span class="text-xs font-bold px-2 py-1 rounded-full
                            {{ $t->estado === 'completado' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $t->estado === 'completado' ? 'Completado' : 'En proceso' }}
                        </span>
                    </div>
                </div>

                @if($t->descripcion)
                    <div>
                        <p class="text-xs font-bold uppercase text-slate-400 mb-1">Descripción</p>
                        <p class="text-sm text-slate-700">{{ $t->descripcion }}</p>
                    </div>
                @endif

                @if($t->observaciones)
                    <div>
                        <p class="text-xs font-bold uppercase text-slate-400 mb-1">Observaciones</p>
                        <p class="text-sm text-slate-700">{{ $t->observaciones }}</p>
                    </div>
                @endif

                @if($t->piezas->count() > 0)
                    <div>
                        <p class="text-xs font-bold uppercase text-slate-400 mb-2">Piezas trabajadas</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($t->piezas as $pieza)
                                <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5">
                                    <span class="w-6 h-6 rounded-full bg-blue-500 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">
                                        {{ $pieza->pieza_numero }}
                                    </span>
                                    <div class="text-xs text-slate-600">
                                        {{ ucfirst($pieza->cara) }}
                                        @if($pieza->procedimiento) · {{ $pieza->procedimiento }} @endif
                                        @if($pieza->ausente) <span class="text-red-500 font-medium">· Ausente</span> @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white border border-slate-200 rounded-2xl p-10 text-center">
                <span class="material-symbols-outlined text-4xl text-slate-300 block mb-2">medical_information</span>
                <p class="text-sm font-semibold text-slate-900">Sin tratamientos registrados</p>
                <p class="text-xs text-slate-400 mt-1">Los tratamientos completados aparecerán aquí.</p>
            </div>
        @endforelse

    </main>
</div>
@endsection