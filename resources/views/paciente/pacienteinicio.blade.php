@extends('layouts.admin')

@section('titulo', 'Mi Panel - JM Dental')

@section('content')

<div class="flex h-screen overflow-hidden bg-slate-50">

    @include('layouts.partials.sidebar-paciente')

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1 flex flex-col overflow-hidden">

        {{-- HEADER --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8">
            <div class="relative w-full max-w-md">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input class="w-full bg-slate-100 rounded-lg pl-10 pr-4 py-2 text-sm border-none outline-none"
                    placeholder="Buscar..." type="text"/>
            </div>
            <div class="flex items-center gap-4">
                <button class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-600">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
            </div>
        </header>

        {{-- DASHBOARD --}}
        <div class="flex-1 overflow-y-auto p-8 space-y-8">

            {{-- BIENVENIDA --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900">
                        ¡Bienvenido, {{ Auth::user()->name }}!
                    </h1>
                    <p class="text-slate-500 mt-1">
                        Hoy es {{ ucfirst(\Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY')) }}
                    </p>
                </div>
                <a href="{{ route('paciente.citas.create') }}"
                    class="flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-xl font-medium hover:bg-blue-600 transition-colors">
                    <span class="material-symbols-outlined">add_circle</span>
                    <span>Agendar Cita</span>
                </a>
            </div>

            {{-- TARJETAS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- PRÓXIMA CITA --}}
                <a href="{{ route('paciente.citas') }}"
                    class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col gap-3 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined">calendar_today</span>
                        </div>
                        <span class="text-xs bg-blue-50 text-blue-500 font-bold px-2 py-1 rounded-lg">Próxima</span>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Próxima Cita</p>
                        @if($proximaCita)
                            <p class="text-xl font-bold text-slate-900">
                                {{ $proximaCita->fecha_hora->format('d/m/Y') }}
                            </p>
                            <p class="text-xs text-slate-400 mt-1">
                                {{ $proximaCita->fecha_hora->format('H:i') }} — {{ $proximaCita->odontologo->user->name ?? 'Sin odontólogo' }}
                            </p>
                        @else
                            <p class="text-2xl font-bold text-slate-900">No agendada</p>
                        @endif
                    </div>
                </a>

                {{-- TRATAMIENTOS --}}
                <a href="{{ route('paciente.tratamientos') }}"
                    class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col gap-3 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined">medical_information</span>
                        </div>
                        <span class="text-xs bg-green-50 text-green-500 font-bold px-2 py-1 rounded-lg">Historial</span>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Tratamientos</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $totalTratamientos }}</p>
                    </div>
                </a>

                {{-- ESTADO --}}
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined">verified_user</span>
                        </div>
                        <span class="text-xs bg-emerald-50 text-emerald-600 font-bold px-2 py-1 rounded-lg">Estado</span>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Mi Estado</p>
                        <p class="text-2xl font-bold text-slate-900">
                            {{ Auth::user()->activo ? 'Activo' : 'Inactivo' }}
                        </p>
                    </div>
                </div>

            </div>

            {{-- PRÓXIMA CITA DETALLE --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="text-xl font-bold">Mis Próximas Citas</h2>
                        <a href="{{ route('paciente.citas') }}" class="text-blue-500 text-sm font-semibold hover:underline">
                            Ver todas
                        </a>
                    </div>
                    @if($proximaCita)
                        <div class="p-6">
                            <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-xl">
                                <div class="w-14 h-14 bg-blue-500 rounded-xl flex flex-col items-center justify-center text-white">
                                    <span class="text-xs font-bold uppercase">{{ $proximaCita->fecha_hora->format('M') }}</span>
                                    <span class="text-lg font-black leading-none">{{ $proximaCita->fecha_hora->format('d') }}</span>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900">{{ $proximaCita->motivo }}</p>
                                    <p class="text-sm text-slate-500">
                                        {{ $proximaCita->fecha_hora->format('H:i') }} —
                                        {{ $proximaCita->odontologo->user->name ?? 'Sin odontólogo' }}
                                    </p>
                                    @php
                                        $estilo = match($proximaCita->estado) {
                                            'confirmada' => 'bg-green-100 text-green-700',
                                            'pendiente'  => 'bg-amber-100 text-amber-700',
                                            default      => 'bg-slate-100 text-slate-600',
                                        };
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-bold {{ $estilo }} mt-1 inline-block">
                                        {{ ucfirst($proximaCita->estado) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="p-6 text-slate-400 text-sm text-center">
                            <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="material-symbols-outlined text-blue-300 text-4xl">calendar_today</span>
                            </div>
                            <p class="font-semibold text-slate-500">No tienes citas agendadas</p>
                            <p class="mt-1">Agenda tu próxima visita con nosotros</p>
                            <a href="{{ route('paciente.citas.create') }}"
                                class="inline-flex items-center gap-2 mt-4 bg-blue-500 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-blue-600 transition-colors">
                                <span class="material-symbols-outlined text-lg">add_circle</span>
                                Agendar ahora
                            </a>
                        </div>
                    @endif
                </div>

                {{-- INFORMACIÓN RÁPIDA --}}
                <div class="space-y-6">
                    <div class="bg-blue-500 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
                        <div class="relative z-10">
                            <p class="opacity-80 text-sm">Mi Salud Dental</p>
                            <h3 class="text-2xl font-bold mt-1">{{ Auth::user()->paciente->tipo_denticion ?? 'No registrado' }}</h3>
                            <p class="text-xs opacity-70 mt-2">Tipo de dentición</p>
                            <div class="mt-4 flex items-center gap-2 text-xs font-bold text-emerald-300">
                                <span class="material-symbols-outlined text-sm">favorite</span>
                                <span>Tipo de sangre: {{ Auth::user()->paciente->tipo_sangre ?? 'No registrado' }}</span>
                            </div>
                        </div>
                        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                        <h2 class="text-lg font-bold mb-4">Mis Datos</h2>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-slate-400 text-lg">phone</span>
                                <span class="text-sm text-slate-600">{{ Auth::user()->telefono ?? 'No registrado' }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-slate-400 text-lg">mail</span>
                                <span class="text-sm text-slate-600">{{ Auth::user()->email }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-slate-400 text-lg">location_on</span>
                                <span class="text-sm text-slate-600">{{ Auth::user()->paciente->direccion ?? 'No registrada' }}</span>
                            </div>
                        </div>
                        <a href="{{ route('paciente.perfil') }}"
                            class="w-full mt-4 flex items-center justify-center gap-2 border border-slate-200 text-slate-600 py-2 rounded-xl text-sm font-semibold hover:bg-slate-50 transition-colors">
                            <span class="material-symbols-outlined text-lg">edit</span>
                            Editar mi perfil
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>

@endsection