@extends('layouts.admin')
@section('titulo', 'Historia Clínica - JM Dental')
@section('content')
<div class="flex h-screen overflow-hidden bg-slate-50">
    @include('layouts.partials.sidebar-odontologo')
    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center px-8">
            <h1 class="text-lg font-bold text-slate-900">Historia Clínica — Formulario 033 MSP</h1>
        </header>
        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-3xl mx-auto">

                <div class="flex items-center gap-3 mb-6">
                    <a href="{{ route('odontologo.pacientes.show', $usuario->id) }}"
                        class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-500">
                        <span class="material-symbols-outlined">arrow_back</span>
                    </a>
                    <h1 class="text-xl font-bold text-slate-900">Historia clínica — {{ $usuario->name }}</h1>
                    <a href="{{ route('odontologo.historia.pdf', $usuario->id) }}" target="_blank"
                        class="ml-auto flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-semibold">
                        <span class="material-symbols-outlined text-base">download</span>
                        Descargar Formulario 033
                    </a>
                </div>

                @if(session('mensaje'))
                    <div class="bg-green-50 border border-green-200 text-green-700 text-sm font-medium px-4 py-3 rounded-xl mb-6">
                        {{ session('mensaje') }}
                    </div>
                @endif

                {{-- INFO PACIENTE --}}
                <div class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 mb-6 flex items-center gap-3">
                    <span class="material-symbols-outlined text-blue-500">person</span>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ $usuario->name }}</p>
                        <p class="text-xs text-slate-500">
                            {{ $usuario->email }}
                            @if($paciente?->edad) · {{ $paciente->edad }} años @endif
                            @if($paciente?->tipo_denticion)
                                · <span class="font-medium {{ $paciente->color_denticion }} px-1.5 rounded">{{ $paciente->tipo_denticion }}</span>
                            @endif
                        </p>
                    </div>
                    <span class="ml-auto text-xs text-slate-400">
                        Apertura: {{ $historia?->fecha_apertura?->format('d/m/Y') ?? '—' }}
                    </span>
                </div>

                {{-- DATOS INICIALES EDITABLES --}}
                <form action="{{ route('odontologo.historia.update', $usuario->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
                        <h3 class="font-bold text-slate-900">B. Motivo de consulta y enfermedad actual</h3>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1.5">Motivo de consulta</label>
                            <input type="text" name="motivo_consulta" required
                                value="{{ old('motivo_consulta', $historia?->motivo_consulta) }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1.5">Enfermedad actual</label>
                            <textarea name="enfermedad_actual" rows="3"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">{{ old('enfermedad_actual', $historia?->enfermedad_actual) }}</textarea>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
                        <h3 class="font-bold text-slate-900">D/E. Antecedentes patológicos</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Antecedentes personales</label>
                                <textarea name="antecedentes_personales" rows="4"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">{{ old('antecedentes_personales', $historia?->antecedentes_personales) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Antecedentes familiares</label>
                                <textarea name="antecedentes_familiares" rows="4"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">{{ old('antecedentes_familiares', $historia?->antecedentes_familiares) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
                        <h3 class="font-bold text-slate-900">F. Constantes vitales</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Temperatura °C</label>
                                <input type="text" name="temperatura"
                                    value="{{ old('temperatura', $historia?->temperatura) }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Pulso / min</label>
                                <input type="text" name="pulso"
                                    value="{{ old('pulso', $historia?->pulso) }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Frec. respiratoria / min</label>
                                <input type="text" name="frecuencia_respiratoria"
                                    value="{{ old('frecuencia_respiratoria', $historia?->frecuencia_respiratoria) }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Presión arterial (mmHg)</label>
                                <input type="text" name="presion_arterial"
                                    value="{{ old('presion_arterial', $historia?->presion_arterial) }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
                        <h3 class="font-bold text-slate-900">G. Examen del sistema estomatognático</h3>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1.5">Examen extraoral</label>
                            <textarea name="examen_extraoral" rows="3"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">{{ old('examen_extraoral', $historia?->examen_extraoral) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1.5">Examen intraoral</label>
                            <textarea name="examen_intraoral" rows="3"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">{{ old('examen_intraoral', $historia?->examen_intraoral) }}</textarea>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-2xl p-6">
                        <h3 class="font-bold text-slate-900 mb-4">N. Diagnóstico inicial</h3>
                        <textarea name="diagnostico_inicial" rows="3"
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">{{ old('diagnostico_inicial', $historia?->diagnostico_inicial) }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2.5 rounded-lg text-sm font-semibold">
                            Actualizar historia clínica
                        </button>
                    </div>
                </form>

                {{-- SESIONES / TRATAMIENTOS --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6 mt-6">
                    <h3 class="font-bold text-slate-900 mb-4">P. Sesiones de tratamiento</h3>
                    @forelse($tratamientos as $i => $t)
                        <div class="border border-slate-100 rounded-xl p-4 mb-3">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-bold text-slate-900">
                                    Sesión {{ $i + 1 }} — {{ $t->nombre }}
                                </span>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($t->fecha_tratamiento)->format('d/m/Y') }}</span>
                                    <span class="text-xs font-bold px-2 py-0.5 rounded-full
                                        {{ $t->estado === 'completado' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $t->estado === 'completado' ? 'Completado' : 'En proceso' }}
                                    </span>
                                </div>
                            </div>
                            @if($t->descripcion)
                                <p class="text-xs text-slate-600 mb-1">{{ $t->descripcion }}</p>
                            @endif
                            @if($t->piezas->count() > 0)
                                <div class="flex flex-wrap gap-1 mt-2">
                                    @foreach($t->piezas as $p)
                                        <span class="text-xs bg-blue-50 text-blue-600 border border-blue-100 px-2 py-0.5 rounded-full">
                                            Pieza {{ $p->pieza_numero }} · {{ ucfirst($p->cara) }}
                                            @if($p->procedimiento) · {{ $p->procedimiento }} @endif
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs text-slate-400">
                                    Odontólogo: {{ $t->cita?->odontologo?->user?->name ?? '—' }}
                                </span>
                                @if($t->costo > 0)
                                    <span class="text-xs font-bold text-slate-700">${{ number_format($t->costo, 2) }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 text-center py-4">Sin sesiones registradas aún.</p>
                    @endforelse
                </div>

            </div>
        </div>
    </main>
</div>
@endsection