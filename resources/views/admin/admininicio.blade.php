@extends('layouts.admin')

@section('titulo', 'Dashboard - JM Dental')

@section('content')

<div class="flex h-screen overflow-hidden bg-slate-50">

    @include('layouts.partials.sidebar-admin')

    <main class="flex-1 flex flex-col overflow-hidden">

        {{-- HEADER --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8">
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
                <div class="flex gap-3">
                    <a href="{{ route('admin.usuarios.create') }}"
                        class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl font-medium hover:bg-slate-50 shadow-sm">
                        <span class="material-symbols-outlined text-blue-500">person_add</span>
                        <span>Añadir Usuario</span>
                    </a>
                    <a href="{{ route('admin.citas.create') }}"
                        class="flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-xl font-medium hover:bg-blue-600">
                        <span class="material-symbols-outlined">calendar_today</span>
                        <span>Agendar Cita</span>
                    </a>
                </div>
            </div>

            {{-- MÉTRICAS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                {{-- Usuarios --}}
                <a href="{{ route('admin.usuarios.index') }}"
                    class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col gap-3 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined">group</span>
                        </div>
                        <span class="text-xs bg-blue-50 text-blue-500 font-bold px-2 py-1 rounded-lg">Usuarios</span>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $totalUsuarios }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">usuarios registrados</p>
                    </div>
                </a>

                {{-- Citas hoy --}}
                <a href="{{ route('admin.citas.index') }}"
                    class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col gap-3 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined">calendar_today</span>
                        </div>
                        <span class="text-xs bg-amber-50 text-amber-600 font-bold px-2 py-1 rounded-lg">Citas</span>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $citasHoy->count() }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">citas programadas hoy</p>
                    </div>
                    <div class="flex gap-2">
                        <span class="text-xs bg-green-100 text-green-700 font-bold px-2 py-0.5 rounded-full">{{ $citasHoyConfirmadas }} confirmadas</span>
                        <span class="text-xs bg-amber-100 text-amber-700 font-bold px-2 py-0.5 rounded-full">{{ $citasHoyPendientes }} pendientes</span>
                    </div>
                </a>

                {{-- Pacientes --}}
                <a href="{{ route('admin.usuarios.index') }}"
                    class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col gap-3 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined">personal_injury</span>
                        </div>
                        <span class="text-xs bg-emerald-50 text-emerald-600 font-bold px-2 py-1 rounded-lg">Pacientes</span>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $totalPacientes }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">pacientes registrados</p>
                    </div>
                    <div class="flex gap-2">
                        <span class="text-xs bg-green-100 text-green-700 font-bold px-2 py-0.5 rounded-full">{{ $pacientesActivos }} activos</span>
                        <span class="text-xs bg-slate-100 text-slate-500 font-bold px-2 py-0.5 rounded-full">{{ $pacientesInactivos }} inactivos</span>
                    </div>
                </a>

                {{-- Reportes --}}
                <a href="{{ route('admin.reportes.tratamientos') }}"
                    class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col gap-3 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 bg-violet-100 text-violet-600 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined">bar_chart</span>
                        </div>
                        <span class="text-xs bg-violet-50 text-violet-600 font-bold px-2 py-1 rounded-lg">Reportes</span>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $totalTratamientosMes }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">tratamientos este mes</p>
                    </div>
                </a>

            </div>

            {{-- AGENDA Y TRATAMIENTOS --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- AGENDA DE HOY --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="text-xl font-bold">Agenda de Hoy</h2>
                        <a class="text-blue-500 text-sm font-semibold hover:underline"
                            href="{{ route('admin.citas.index') }}">Ver agenda completa</a>
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
                                {{ ucfirst(\Carbon\Carbon::now()->locale('es')->isoFormat('MMMM YYYY')) }}
                            </span>
                        </div>
                        <h3 class="text-4xl font-black mb-2">{{ $totalTratamientosMes }}</h3>
                        <p class="text-sm opacity-80 mb-6">tratamientos completados este mes</p>

                        @if($tratamientosMes->count() > 0)
                            <div class="space-y-2 flex-1">
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

                        <a href="{{ route('admin.reportes.tratamientos') }}"
                            class="mt-6 w-full bg-white text-blue-500 font-bold py-2 rounded-xl text-sm text-center block hover:bg-slate-50 transition-colors">
                            Ver reporte completo
                        </a>
                    </div>
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                </div>

            </div>
        </div>
    </main>
</div>

@endsection