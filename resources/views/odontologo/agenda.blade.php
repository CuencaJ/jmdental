@extends('layouts.admin')

@section('titulo', 'Agenda - JM Dental')

@section('content')

<div class="flex h-screen overflow-hidden bg-slate-50">

    @include('layouts.partials.sidebar-odontologo')

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1 flex flex-col overflow-hidden">

        {{-- HEADER --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center px-8">
            <div class="relative w-full max-w-md">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input id="buscadorAgenda" class="w-full bg-slate-100 rounded-lg pl-10 pr-4 py-2 text-sm border-none outline-none"
                    placeholder="Buscar por paciente o procedimiento..." type="text"/>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 space-y-4">

            {{-- MENSAJE --}}
            @if(session('mensaje'))
                <div class="bg-green-50 border border-green-200 text-green-700 text-sm font-medium px-4 py-3 rounded-xl">
                    {{ session('mensaje') }}
                </div>
            @endif

            {{-- DOCTORA + FILTRO FECHA --}}
            @if($odontologo)
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($odontologo->user->name, 0, 2)) }}
                        </div>
                        <div>
                            <h1 class="text-sm font-semibold text-slate-900">{{ $odontologo->user->name }}</h1>
                            <p class="text-xs text-slate-400">{{ $odontologo->especialidad ?? 'Odontólogo general' }}</p>
                        </div>
                    </div>
                    <form method="GET" action="{{ route('odontologo.agenda') }}" class="flex items-center gap-2">
                        <label class="text-sm text-slate-500 font-medium">Filtrar por fecha:</label>
                        <input type="date" name="fecha" value="{{ $fechaFiltro }}"
                            onchange="this.form.submit()"
                            class="bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-sm text-slate-700 outline-none focus:border-blue-400 cursor-pointer">
                        @if($fechaFiltro)
                            <a href="{{ route('odontologo.agenda') }}"
                                class="text-xs text-slate-400 hover:text-red-500 font-medium transition-colors">
                                Limpiar
                            </a>
                        @endif
                    </form>
                </div>
            @endif

            {{-- FILTROS --}}
            <div class="flex flex-wrap items-center justify-between gap-2">
                <div class="flex flex-wrap items-center gap-2">
                    <select id="filtroEstado" onchange="filtrarPorEstado()"
                        class="bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-sm text-slate-600 outline-none cursor-pointer">
                        <option value="todos">Todos los estados</option>
                        <option value="pendiente">Pendientes</option>
                        <option value="confirmada">Confirmadas</option>
                        <option value="completada">Completadas</option>
                        <option value="cancelada">Canceladas</option>
                    </select>
                    <span class="flex items-center gap-1.5 bg-green-100 text-green-700 text-xs font-bold px-3 py-1.5 rounded-lg">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-700"></span>Confirmadas
                    </span>
                    <span class="flex items-center gap-1.5 bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-lg">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-700"></span>Completadas
                    </span>
                    <span class="flex items-center gap-1.5 bg-amber-100 text-amber-700 text-xs font-bold px-3 py-1.5 rounded-lg">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-700"></span>Pendientes
                    </span>
                    <span class="flex items-center gap-1.5 bg-red-100 text-red-700 text-xs font-bold px-3 py-1.5 rounded-lg">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-700"></span>Canceladas
                    </span>
                </div>
                <a href="{{ route('odontologo.citas.create') }}"
                    class="flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-colors">
                    <span class="material-symbols-outlined text-base">add</span>
                    Agendar cita
                </a>
            </div>

            {{-- TABLA --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <table class="w-full text-left" id="tablaCitas">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400">Fecha</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400">Hora</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400">Paciente</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400">Procedimiento</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($citas as $cita)
                            <tr class="hover:bg-blue-50/30 transition-colors" data-estado="{{ $cita->estado }}">
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ $cita->fecha_hora->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-900">
                                    {{ $cita->fecha_hora->format('H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-900">
                                    {{ $cita->paciente->user->name ?? 'Paciente eliminado' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ $cita->motivo }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $estiloEstado = match($cita->estado) {
                                            'confirmada' => 'bg-green-100 text-green-700',
                                            'pendiente'  => 'bg-amber-100 text-amber-700',
                                            'completada' => 'bg-blue-100 text-blue-700',
                                            'cancelada'  => 'bg-red-100 text-red-700',
                                            default      => 'bg-slate-100 text-slate-600',
                                        };
                                    @endphp
                                    <form action="{{ route('odontologo.citas.estado', $cita->id) }}" method="POST"
                                        onchange="this.submit()" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <select name="estado"
                                            class="px-3 py-1 rounded-full text-xs font-bold border-none outline-none cursor-pointer {{ $estiloEstado }}">
                                            <option value="pendiente" {{ $cita->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="confirmada" {{ $cita->estado == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                            <option value="completada" {{ $cita->estado == 'completada' ? 'selected' : '' }}>Completada</option>
                                            <option value="cancelada" {{ $cita->estado == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16">
                                    <div class="flex flex-col items-center text-center">
                                        <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center mb-3">
                                            <span class="material-symbols-outlined text-blue-300 text-2xl">calendar_today</span>
                                        </div>
                                        <p class="text-sm font-semibold text-slate-900">No hay citas programadas</p>
                                        <p class="text-xs text-slate-400 mt-1">Las citas que agendes aparecerán aquí.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- TARJETAS RESUMEN --}}
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="bg-white border border-slate-200 rounded-xl p-4 flex items-center gap-3">
                    <span class="material-symbols-outlined text-blue-500">calendar_month</span>
                    <div>
                        <p class="text-lg font-bold text-slate-900">{{ $totalCitas }}</p>
                        <p class="text-xs text-slate-400">Total</p>
                    </div>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl p-4 flex items-center gap-3">
                    <span class="material-symbols-outlined text-green-600">check_circle</span>
                    <div>
                        <p class="text-lg font-bold text-slate-900">{{ $confirmadas }}</p>
                        <p class="text-xs text-slate-400">Confirmadas</p>
                    </div>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl p-4 flex items-center gap-3">
                    <span class="material-symbols-outlined text-blue-600">task_alt</span>
                    <div>
                        <p class="text-lg font-bold text-slate-900">{{ $completadas }}</p>
                        <p class="text-xs text-slate-400">Completadas</p>
                    </div>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl p-4 flex items-center gap-3">
                    <span class="material-symbols-outlined text-amber-600">schedule</span>
                    <div>
                        <p class="text-lg font-bold text-slate-900">{{ $pendientes }}</p>
                        <p class="text-xs text-slate-400">Pendientes</p>
                    </div>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl p-4 flex items-center gap-3">
                    <span class="material-symbols-outlined text-red-600">cancel</span>
                    <div>
                        <p class="text-lg font-bold text-slate-900">{{ $canceladas }}</p>
                        <p class="text-xs text-slate-400">Canceladas</p>
                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

@endsection

@section('scripts')
<script>
    function filtrarPorEstado() {
        const estado = document.getElementById('filtroEstado').value;
        document.querySelectorAll('#tablaCitas tbody tr').forEach(row => {
            if (!row.dataset.estado) return;
            row.style.display = (estado === 'todos' || row.dataset.estado === estado)
                ? 'table-row' : 'none';
        });
    }
    document.getElementById('buscadorAgenda').addEventListener('input', function() {
    const term = this.value.toLowerCase();
    document.querySelectorAll('#tablaCitas tbody tr').forEach(row => {
        if (!row.dataset.estado) return;
        const paciente = row.cells[2]?.innerText.toLowerCase() ?? '';
        const procedimiento = row.cells[3]?.innerText.toLowerCase() ?? '';
        row.style.display = (paciente.includes(term) || procedimiento.includes(term))
            ? 'table-row' : 'none';
    });
});
</script>
@endsection