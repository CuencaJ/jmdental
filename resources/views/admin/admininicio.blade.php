@extends('layouts.admin')

@section('titulo', 'Dashboard - JM Dental')

@section('content')

<div class="flex h-screen overflow-hidden bg-slate-50">

    @include('layouts.partials.sidebar-admin')

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1 flex flex-col overflow-hidden">

        {{-- HEADER --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8">
            <div class="relative w-full max-w-md">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input class="w-full bg-slate-100 rounded-lg pl-10 pr-4 py-2 text-sm border-none outline-none"
                    placeholder="Buscar paciente, cita o historial..." type="text"/>
            </div>
            <div class="flex items-center gap-4">
                <button class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-600">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
                <a href="#"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2">
                    <span class="material-symbols-outlined">add</span>
                    Nueva Cita
                </a>
            </div>
        </header>

        {{-- DASHBOARD --}}
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
                    <a href="#"
                        class="flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-xl font-medium hover:bg-blue-600">
                        <span class="material-symbols-outlined">calendar_today</span>
                        <span>Agendar Cita</span>
                    </a>
                </div>
            </div>

            {{-- MÉTRICAS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined">event_available</span>
                    </div>
                    <div>
                        <p class="text-slate-500 text-sm">Citas Hoy</p>
                        <p class="text-2xl font-bold">0</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined">person_add</span>
                    </div>
                    <div>
                        <p class="text-slate-500 text-sm">Nuevos Pacientes</p>
                        <p class="text-2xl font-bold">0</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined">assignment_late</span>
                    </div>
                    <div>
                        <p class="text-slate-500 text-sm">Tareas Pendientes</p>
                        <p class="text-2xl font-bold">0</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined">trending_up</span>
                    </div>
                    <div>
                        <p class="text-slate-500 text-sm">Ingresos Semana</p>
                        <p class="text-2xl font-bold">$0</p>
                    </div>
                </div>
            </div>

            {{-- AGENDA Y TAREAS --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- AGENDA --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="text-xl font-bold">Agenda de Hoy</h2>
                        <a class="text-blue-500 text-sm font-semibold hover:underline" href="#">Ver agenda completa</a>
                    </div>
                    <div class="p-6 text-slate-400 text-sm text-center">
                        No hay citas programadas para hoy.
                    </div>
                </div>

                {{-- INGRESOS Y TAREAS --}}
                <div class="space-y-6">
                    <div class="bg-blue-500 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
                        <div class="relative z-10">
                            <p class="opacity-80 text-sm">Ingresos Mensuales</p>
                            <h3 class="text-3xl font-bold mt-1">$0.00</h3>
                            <div class="mt-4 flex items-center gap-2 text-xs font-bold text-emerald-300">
                                <span class="material-symbols-outlined text-sm">trending_up</span>
                                <span>+0% vs mes anterior</span>
                            </div>
                        </div>
                        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                    </div>
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                        <h2 class="text-lg font-bold mb-4">Tareas Prioritarias</h2>
                        <p class="text-slate-400 text-sm text-center">No hay tareas pendientes.</p>
                        <button class="w-full mt-4 py-2 text-slate-500 text-sm font-semibold hover:text-blue-500 transition-colors">
                            + Añadir nueva tarea
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>

@endsection