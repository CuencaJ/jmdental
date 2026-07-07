@extends('layouts.admin')

@section('titulo', 'Dashboard Recepcionista - JM Dental')

@section('content')

<div class="flex h-screen overflow-hidden bg-slate-50">

    @include('layouts.partials.sidebar-recepcionista')

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1 flex flex-col overflow-hidden">

        {{-- HEADER --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8">
            <div class="relative w-full max-w-md">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input class="w-full bg-slate-100 rounded-lg pl-10 pr-4 py-2 text-sm border-none outline-none"
                    placeholder="Buscar paciente o cita..." type="text"/>
            </div>
            <div class="flex items-center gap-4">
                <button class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-600">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 space-y-8">

            {{-- BIENVENIDA --}}
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">
                    ¡Buenos días, {{ Auth::user()->name }}!
                </h1>
                <p class="text-slate-500 mt-1">
                    Hoy es {{ ucfirst(\Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY')) }}
                </p>
            </div>

            {{-- MÉTRICAS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Citas hoy --}}
                <a href="{{ route('recepcionista.citas') }}"
                    class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col gap-3 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined">calendar_today</span>
                        </div>
                        <span class="text-xs bg-blue-50 text-blue-500 font-bold px-2 py-1 rounded-lg">Hoy</span>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $citasHoy->count() }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">citas programadas hoy</p>
                    </div>
                    <div class="flex gap-2 flex-wrap">
                        <span class="text-xs bg-green-100 text-green-700 font-bold px-2 py-0.5 rounded-full">{{ $citasHoyConfirmadas }} confirmadas</span>
                        <span class="text-xs bg-amber-100 text-amber-700 font-bold px-2 py-0.5 rounded-full">{{ $citasHoyPendientes }} pendientes</span>
                    </div>
                </a>

                {{-- Pacientes registrados --}}
                <a href="{{ route('recepcionista.pacientes') }}"
                    class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col gap-3 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined">group</span>
                        </div>
                        <span class="text-xs bg-green-50 text-green-500 font-bold px-2 py-1 rounded-lg">Total</span>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $totalPacientes }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">pacientes registrados</p>
                    </div>
                </a>

                {{-- Citas pendientes --}}
                <a href="{{ route('recepcionista.citas') }}"
                    class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col gap-3 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined">pending_actions</span>
                        </div>
                        <span class="text-xs bg-amber-50 text-amber-600 font-bold px-2 py-1 rounded-lg">Pendientes</span>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $citasPendientes }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">citas por confirmar</p>
                    </div>
                </a>

            </div>

            {{-- AGENDA Y ACCIONES RÁPIDAS --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- AGENDA DE HOY --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="text-xl font-bold">Agenda de Hoy</h2>
                        <a class="text-blue-500 text-sm font-semibold hover:underline" href="{{ route('recepcionista.citas') }}">Ver todas</a>
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

                {{-- ACCIONES RÁPIDAS --}}
                <div class="space-y-4">
                    <h2 class="text-lg font-bold text-slate-900">Acciones rápidas</h2>
                    <a href="{{ route('recepcionista.citas.create') }}"
                        class="flex items-center gap-3 bg-white border border-slate-200 rounded-2xl p-4 hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined">calendar_add_on</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">Nueva cita</p>
                            <p class="text-xs text-slate-400">Agendar una cita</p>
                        </div>
                    </a>
                    <a href="{{ route('recepcionista.pacientes.create') }}"
                        class="flex items-center gap-3 bg-white border border-slate-200 rounded-2xl p-4 hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined">person_add</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">Nuevo paciente</p>
                            <p class="text-xs text-slate-400">Registrar un paciente</p>
                        </div>
                    </a>
                    <a href="{{ route('recepcionista.pacientes') }}"
                        class="flex items-center gap-3 bg-white border border-slate-200 rounded-2xl p-4 hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined">group</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">Ver pacientes</p>
                            <p class="text-xs text-slate-400">Lista de pacientes</p>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </main>
</div>

@endsection