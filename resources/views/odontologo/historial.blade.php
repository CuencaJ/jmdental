@extends('layouts.admin')

@section('titulo', 'Historial Clínico - JM Dental')

@section('content')

<div class="flex h-screen overflow-hidden bg-slate-50">

    {{-- SIDEBAR --}}
    @include('layouts.partials.sidebar-odontologo')

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1 flex flex-col overflow-hidden">

        {{-- HEADER --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center px-8">
            <div class="relative w-full max-w-md">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input class="w-full bg-slate-100 rounded-lg pl-10 pr-4 py-2 text-sm border-none outline-none"
                    placeholder="Buscar paciente..." type="text" id="buscadorHistorial"/>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 space-y-4">

            {{-- MENSAJE --}}
            @if(session('mensaje'))
                <div class="bg-green-50 border border-green-200 text-green-700 text-sm font-medium px-4 py-3 rounded-xl">
                    {{ session('mensaje') }}
                </div>
            @endif

            <div class="flex items-center justify-between">
                <h1 class="text-xl font-bold text-slate-900">Historial Clínico</h1>
                <div class="bg-slate-100 text-slate-500 text-xs font-medium px-3 py-1.5 rounded-lg">
                    {{ $pacientes->count() }} pacientes
                </div>
            </div>

            {{-- LISTA DE PACIENTES --}}
            <div class="space-y-4" id="listaPacientes">
                @forelse($pacientes as $paciente)
                    @php
                        $citasConTratamiento = $paciente->citas->filter(fn($c) => $c->tratamiento);
                        $totalTratamientos = $citasConTratamiento->count();
                        $pendientes = $citasConTratamiento->filter(fn($c) => $c->tratamiento->estado === 'en_proceso')->count();
                    @endphp
                    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm paciente-card"
                         data-nombre="{{ strtolower($paciente->user->name) }}">

                        {{-- CABECERA PACIENTE --}}
                        <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between cursor-pointer"
                             onclick="togglePaciente({{ $paciente->id }})">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($paciente->user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $paciente->user->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $paciente->user->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($totalTratamientos > 0)
                                    @if($pendientes > 0)
                                        <span class="text-xs bg-amber-100 text-amber-700 font-bold px-2 py-1 rounded-full">
                                            {{ $pendientes }} pendiente{{ $pendientes > 1 ? 's' : '' }} de completar
                                        </span>
                                    @endif
                                    <span class="text-xs bg-blue-50 text-blue-500 font-bold px-2 py-1 rounded-full">
                                        {{ $totalTratamientos }} tratamiento{{ $totalTratamientos > 1 ? 's' : '' }}
                                    </span>
                                @else
                                    <span class="text-xs bg-slate-100 text-slate-400 font-bold px-2 py-1 rounded-full">
                                        Sin tratamientos
                                    </span>
                                @endif
                                <span class="material-symbols-outlined text-slate-400 text-base" id="icon-{{ $paciente->id }}">expand_more</span>
                            </div>
                        </div>

                        {{-- TRATAMIENTOS --}}
                        <div id="tratamientos-{{ $paciente->id }}" class="hidden">
                            @if($citasConTratamiento->count() > 0)
                                @foreach($citasConTratamiento as $cita)
                                    @php $t = $cita->tratamiento; @endphp
                                    <div class="p-4 border-b border-slate-100 flex items-start justify-between gap-4">
                                        <div class="flex items-start gap-3">
                                            <div class="w-2 h-2 rounded-full mt-2 flex-shrink-0
                                                {{ $t->estado === 'completado' ? 'bg-green-500' : 'bg-amber-400' }}"></div>
                                            <div>
                                                <p class="text-sm font-semibold text-slate-900">{{ $t->nombre }}</p>
                                                @if($t->descripcion)
                                                    <p class="text-xs text-slate-500 mt-0.5">{{ $t->descripcion }}</p>
                                                @else
                                                    <p class="text-xs text-slate-400 mt-0.5 italic">Sin descripción aún</p>
                                                @endif
                                                <p class="text-xs text-slate-400 mt-1">{{ $t->fecha_tratamiento->format('d/m/Y') }}</p>
                                                @if($t->observaciones)
                                                    <p class="text-xs text-slate-500 mt-1 bg-slate-50 px-2 py-1 rounded-lg">{{ $t->observaciones }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-end gap-2 flex-shrink-0">
                                            @if($t->costo > 0)
                                                <p class="text-sm font-bold text-slate-900">${{ number_format($t->costo, 2) }}</p>
                                            @endif
                                            <span class="text-xs font-bold px-2 py-1 rounded-full
                                                {{ $t->estado === 'completado' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                                {{ $t->estado === 'completado' ? 'Completado' : 'En proceso' }}
                                            </span>
                                            @if($t->estado !== 'completado')
                                                <a href="{{ route('odontologo.historial.editar', $t->id) }}"
                                                    class="text-xs bg-blue-500 hover:bg-blue-600 text-white font-bold px-3 py-1.5 rounded-lg transition-colors">
                                                    Completar detalles
                                                </a>
                                            @else
                                                <a href="{{ route('odontologo.historial.ver', $t->id) }}"
                                                    class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-3 py-1.5 rounded-lg transition-colors">
                                                    Ver
                                                </a>
                                                <a href="{{ route('odontologo.historial.editar', $t->id) }}"
                                                    class="text-xs text-blue-500 hover:underline font-medium">
                                                    Editar
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="p-6 text-center text-slate-400 text-sm">
                                    No hay tratamientos registrados para este paciente.
                                </div>
                            @endif
                        </div>

                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-slate-200 p-10 text-center">
                        <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="material-symbols-outlined text-blue-300 text-2xl">medical_information</span>
                        </div>
                        <p class="text-sm font-semibold text-slate-900">No hay historial clínico disponible</p>
                        <p class="text-xs text-slate-400 mt-1">Los tratamientos aparecerán aquí cuando completes citas.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </main>
</div>

@endsection

@section('scripts')
<script>
    function togglePaciente(id) {
        const contenido = document.getElementById('tratamientos-' + id);
        const icon = document.getElementById('icon-' + id);
        contenido.classList.toggle('hidden');
        icon.textContent = contenido.classList.contains('hidden') ? 'expand_more' : 'expand_less';
    }

    document.getElementById('buscadorHistorial').addEventListener('input', function() {
        const term = this.value.toLowerCase();
        document.querySelectorAll('.paciente-card').forEach(card => {
            card.style.display = card.dataset.nombre.includes(term) ? 'block' : 'none';
        });
    });
</script>
@endsection