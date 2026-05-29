@extends('layouts.admin')

@section('titulo', 'Dashboard Odontólogo - JM Dental')

@section('content')

<div class="flex h-screen overflow-hidden bg-slate-50">

    {{-- SIDEBAR --}}
    <aside class="w-64 flex flex-col bg-white border-r border-slate-200">
        <div class="p-6 flex flex-col h-full">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center text-white">
                    <span class="material-symbols-outlined">dentistry</span>
                </div>
                <h2 class="text-xl font-bold text-slate-900">DentalAdmin</h2>
            </div>
            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl mb-6">
                <div class="w-10 h-10 rounded-full bg-slate-300 flex items-center justify-center">
                    <span class="material-symbols-outlined text-slate-600">person</span>
                </div>
                <div class="flex flex-col overflow-hidden">
                    <h1 class="text-sm font-semibold truncate">{{ Auth::user()->name }}</h1>
                    <p class="text-xs text-slate-500">Odontólogo</p>
                </div>
            </div>
            <nav class="flex-1 space-y-1">
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-blue-50 text-blue-500 font-semibold" href="#">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="text-sm">Dashboard</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors" href="#">
                    <span class="material-symbols-outlined text-slate-400">group</span>
                    <span class="text-sm">Pacientes</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors" href="#">
                    <span class="material-symbols-outlined text-slate-400">calendar_today</span>
                    <span class="text-sm">Agenda</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors" href="#">
                    <span class="material-symbols-outlined text-slate-400">payments</span>
                    <span class="text-sm">Ingresos</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors" href="#">
                    <span class="material-symbols-outlined text-slate-400">settings</span>
                    <span class="text-sm">Configuración</span>
                </a>
            </nav>
            <form action="{{ route('logout') }}" method="POST" class="mt-auto">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-blue-500 text-white py-3 rounded-lg font-bold text-sm hover:bg-blue-600 transition-all">
                    <span class="material-symbols-outlined">logout</span>
                    <span>Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1 flex flex-col overflow-y-auto">

        {{-- HEADER --}}
        <header class="h-16 flex items-center justify-between px-8 bg-white border-b border-slate-200 sticky top-0 z-10">
            <div class="relative w-96">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input class="w-full bg-slate-100 border-none rounded-lg pl-10 pr-4 py-2 text-sm outline-none" placeholder="Buscar pacientes, citas o archivos..." type="text"/>
            </div>
            <div class="flex items-center gap-4">
                <button class="p-2 text-slate-500 hover:bg-slate-100 rounded-full relative">
                    <span class="material-symbols-outlined">notifications</span>
                    <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                </button>
                <button class="p-2 text-slate-500 hover:bg-slate-100 rounded-full">
                    <span class="material-symbols-outlined">chat_bubble</span>
                </button>
                <div class="h-8 w-px bg-slate-200 mx-2"></div>
                <span class="text-sm font-medium">Estado: <span class="text-green-500">En línea</span></span>
            </div>
        </header>

        {{-- DASHBOARD --}}
        <div class="p-8 max-w-7xl mx-auto w-full space-y-8">

            {{-- BIENVENIDA --}}
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Resumen de Consulta</h1>
                <p class="text-slate-500 mt-1">Bienvenido, {{ Auth::user()->name }}. Tienes 0 citas programadas para hoy.</p>
            </div>

            {{-- MÉTRICAS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-slate-500 text-sm font-medium">Pacientes esta semana</span>
                        <span class="bg-green-100 text-green-600 text-xs font-bold px-2 py-1 rounded-lg">+0%</span>
                    </div>
                    <div class="text-3xl font-bold text-slate-900">0</div>
                    <div class="mt-2 text-xs text-slate-400">Total consultas y revisiones</div>
                </div>
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-slate-500 text-sm font-medium">Ingresos Semanales</span>
                        <span class="bg-blue-100 text-blue-600 text-xs font-bold px-2 py-1 rounded-lg">+0%</span>
                    </div>
                    <div class="text-3xl font-bold text-slate-900">$0</div>
                    <div class="mt-2 text-xs text-slate-400">Facturación bruta de servicios</div>
                </div>
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-slate-500 text-sm font-medium">Notas Pendientes</span>
                        <span class="bg-blue-50 text-blue-500 text-xs font-bold px-2 py-1 rounded-lg">Acción requerida</span>
                    </div>
                    <div class="text-3xl font-bold text-slate-900">0</div>
                    <div class="mt-2 text-xs text-slate-400">Documentación clínica sin firmar</div>
                </div>
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-slate-500 text-sm font-medium">Tasa de Satisfacción</span>
                        <span class="material-symbols-outlined text-yellow-400">star</span>
                    </div>
                    <div class="text-3xl font-bold text-slate-900">0%</div>
                    <div class="mt-2 text-xs text-slate-400">Basado en encuestas post-visita</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- AGENDA Y TAREAS --}}
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-slate-900">Agenda de Hoy</h2>
                        <button class="text-sm font-semibold text-blue-500">Ver Calendario Completo</button>
                    </div>
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Paciente</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Hora</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Procedimiento</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-400 text-sm">
                                        No hay citas programadas para hoy.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- TAREAS PENDIENTES --}}
                    <div class="pt-4">
                        <h2 class="text-xl font-bold text-slate-900 mb-4">Tareas Pendientes</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl flex items-start gap-4">
                                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600">
                                    <span class="material-symbols-outlined">edit_note</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-bold text-amber-900">Sin tareas pendientes</h4>
                                    <p class="text-xs text-amber-800 mt-1">Todo al día</p>
                                </div>
                            </div>
                            <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl flex items-start gap-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-500">
                                    <span class="material-symbols-outlined">description</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-bold text-blue-500">Sin notas clínicas</h4>
                                    <p class="text-xs text-slate-500 mt-1">Todo al día</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ACTIVIDAD RECIENTE --}}
                <div class="space-y-6">
                    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-slate-900 mb-6">Actividad Reciente</h2>
                        <p class="text-slate-400 text-sm text-center">No hay actividad reciente.</p>
                        <button class="w-full text-center mt-6 text-sm font-semibold text-slate-500 hover:text-blue-500">Ver toda la actividad</button>
                    </div>
                    <div class="bg-blue-500 p-6 rounded-xl text-white shadow-lg relative overflow-hidden">
                        <div class="relative z-10">
                            <h3 class="text-lg font-bold mb-2">Capacidad de Consulta</h3>
                            <div class="flex items-end justify-between mb-4">
                                <span class="text-3xl font-black">0%</span>
                                <span class="text-sm opacity-80 font-medium">Agenda Disponible</span>
                            </div>
                            <div class="w-full bg-white/20 h-2 rounded-full mb-6">
                                <div class="bg-white h-2 rounded-full" style="width: 0%"></div>
                            </div>
                            <p class="text-xs opacity-90 leading-relaxed mb-4">Tienes espacio disponible para nuevas citas esta semana.</p>
                            <button class="w-full bg-white text-blue-500 font-bold py-2 rounded-lg text-sm">Optimizar Agenda</button>
                        </div>
                        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>

@endsection