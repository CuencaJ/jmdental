@extends('layouts.admin')

@section('titulo', 'Dashboard Odontólogo - JM Dental')

@section('content')

<div class="flex h-screen overflow-hidden bg-slate-50">

    @include('layouts.partials.sidebar-odontologo')

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1 flex flex-col overflow-hidden">

        {{-- HEADER --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center px-8">
            <div class="relative w-full max-w-md">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input id="buscadorDashboard"
                    class="w-full bg-slate-100 rounded-lg pl-10 pr-4 py-2 text-sm border-none outline-none cursor-not-allowed opacity-60"
                    placeholder="Busca desde Pacientes, Agenda o Historial..."
                    type="text" disabled/>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 space-y-8">

            {{-- BIENVENIDA --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900">
                        Bienvenido, {{ Auth::user()->name }}
                    </h1>
                    <p class="text-slate-500 mt-1">
                        Hoy es {{ ucfirst(\Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY')) }}
                    </p>
                </div>
            </div>

            {{-- MÉTRICAS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <a href="{{ route('odontologo.pacientes.index') }}"
                    class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col gap-3 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined">group</span>
                        </div>
                        <span class="text-xs bg-emerald-50 text-emerald-600 font-bold px-2 py-1 rounded-lg">Pacientes</span>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $totalPacientes }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">pacientes registrados</p>
                    </div>
                </a>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined">calendar_today</span>
                        </div>
                        <span class="text-xs bg-amber-50 text-amber-600 font-bold px-2 py-1 rounded-lg">Agenda</span>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $citasHoy->count() }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">citas programadas hoy</p>
                    </div>
                    <div class="flex gap-2 flex-wrap">
                        <span class="text-xs bg-green-100 text-green-700 font-bold px-2 py-0.5 rounded-full">{{ $citasHoyConfirmadas }} confirmadas</span>
                        <span class="text-xs bg-amber-100 text-amber-700 font-bold px-2 py-0.5 rounded-full">{{ $citasHoyPendientes }} pendientes</span>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined">task_alt</span>
                        </div>
                        <span class="text-xs bg-blue-50 text-blue-500 font-bold px-2 py-1 rounded-lg">Este mes</span>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $totalTratamientosMes }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">tratamientos completados</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 bg-violet-100 text-violet-600 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined">event_note</span>
                        </div>
                        <span class="text-xs bg-violet-50 text-violet-600 font-bold px-2 py-1 rounded-lg">Total</span>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $totalCitas }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">citas en el sistema</p>
                    </div>
                </div>

            </div>

            {{-- AGENDA Y TRATAMIENTOS --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- AGENDA DE HOY --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="text-xl font-bold">Agenda de Hoy</h2>
                        <a class="text-blue-500 text-sm font-semibold hover:underline" href="{{ route('odontologo.agenda') }}">Ver agenda completa</a>
                    </div>
                    @if($citasHoy->count() > 0)
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-bold uppercase text-slate-400">Hora</th>
                                    <th class="px-6 py-3 text-xs font-bold uppercase text-slate-400">Paciente</th>
                                    <th class="px-6 py-3 text-xs font-bold uppercase text-slate-400">Procedimiento</th>
                                    <th class="px-6 py-3 text-xs font-bold uppercase text-slate-400">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($citasHoy as $cita)
                                    <tr class="hover:bg-blue-50/30 transition-colors">
                                        <td class="px-6 py-3 text-sm font-semibold text-slate-900">
                                            {{ $cita->fecha_hora->format('H:i') }}
                                        </td>
                                        <td class="px-6 py-3 text-sm text-slate-900">
                                            {{ $cita->paciente->user->name ?? 'Paciente eliminado' }}
                                        </td>
                                        <td class="px-6 py-3 text-sm text-slate-500">
                                            {{ $cita->motivo }}
                                        </td>
                                        <td class="px-6 py-3">
                                            @php
                                                $estilo = match($cita->estado) {
                                                    'confirmada' => 'bg-green-100 text-green-700',
                                                    'pendiente'  => 'bg-amber-100 text-amber-700',
                                                    'completada' => 'bg-blue-100 text-blue-700',
                                                    'cancelada'  => 'bg-red-100 text-red-700',
                                                    default      => 'bg-slate-100 text-slate-500',
                                                };
                                            @endphp
                                            <span class="px-2 py-1 rounded-full text-xs font-bold {{ $estilo }}">
                                                {{ ucfirst($cita->estado) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-6 text-slate-400 text-sm text-center">
                            No hay citas programadas para hoy.
                        </div>
                    @endif
                </div>

                {{-- TRATAMIENTOS DEL MES --}}
                <div class="bg-blue-500 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
                    <div class="relative z-10 flex flex-col h-full">
                        <div class="flex items-center justify-between mb-4">
                            <p class="opacity-80 text-sm font-medium">Tratamientos del mes</p>
                            <span class="text-xs font-bold uppercase tracking-wide opacity-70">
                                {{ strtoupper(\Carbon\Carbon::now()->locale('es')->isoFormat('MMMM YYYY')) }}
                            </span>
                        </div>
                        <h3 class="text-4xl font-black mb-2">{{ $totalTratamientosMes }}</h3>
                        <p class="text-sm opacity-80 mb-6">tratamientos completados este mes</p>
                        @if($tratamientosMes->count() > 0)
                            <div class="space-y-2">
                                @foreach($tratamientosMes->take(4) as $tratamiento => $cantidad)
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs opacity-90 truncate mr-2">{{ $tratamiento }}</span>
                                        <span class="text-xs font-bold bg-white/20 px-2 py-0.5 rounded-full">{{ $cantidad }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm opacity-70">No hay tratamientos registrados este mes.</p>
                        @endif
                    </div>
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                </div>

            </div>
        </div>
    </main>
</div>

@endsection