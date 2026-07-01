@extends('layouts.admin')

@section('titulo', 'Mis Citas - JM Dental')

@section('content')

<div class="flex h-screen overflow-hidden bg-slate-50">

    @include('layouts.partials.sidebar-paciente')

    <main class="flex-1 flex flex-col overflow-hidden">

        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8">
            <h1 class="text-xl font-bold text-slate-900">Mis Citas</h1>
            <a href="{{ route('paciente.citas.create') }}"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2">
                <span class="material-symbols-outlined">add</span>
                Nueva Cita
            </a>
        </header>

        <div class="flex-1 overflow-y-auto p-8 space-y-6">

            @if(session('mensaje'))
                <div class="bg-green-50 border border-green-200 text-green-700 text-sm font-medium px-4 py-3 rounded-xl">
                    {{ session('mensaje') }}
                </div>
            @endif

            <div class="flex bg-slate-100 p-1 rounded-xl w-fit">
                <button onclick="filtrar('todas')" id="btn-todas"
                    class="px-4 py-2 rounded-lg bg-white shadow-sm text-blue-500 font-bold text-sm">
                    Todas
                </button>
                <button onclick="filtrar('pendiente')" id="btn-pendiente"
                    class="px-4 py-2 rounded-lg text-slate-400 font-bold text-sm hover:text-blue-500 transition-colors">
                    Pendientes
                </button>
                <button onclick="filtrar('confirmada')" id="btn-confirmada"
                    class="px-4 py-2 rounded-lg text-slate-400 font-bold text-sm hover:text-blue-500 transition-colors">
                    Confirmadas
                </button>
                <button onclick="filtrar('completada')" id="btn-completada"
                    class="px-4 py-2 rounded-lg text-slate-400 font-bold text-sm hover:text-blue-500 transition-colors">
                    Completadas
                </button>
                <button onclick="filtrar('cancelada')" id="btn-cancelada"
                    class="px-4 py-2 rounded-lg text-slate-400 font-bold text-sm hover:text-blue-500 transition-colors">
                    Canceladas
                </button>
            </div>

            <div class="space-y-4" id="listaCitas">
                @forelse($citas as $cita)
                    @php
                        $estilo = match($cita->estado) {
                            'confirmada' => 'bg-green-100 text-green-700',
                            'pendiente'  => 'bg-amber-100 text-amber-700',
                            'completada' => 'bg-blue-100 text-blue-700',
                            'cancelada'  => 'bg-red-100 text-red-700',
                            default      => 'bg-slate-100 text-slate-600',
                        };
                    @endphp
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex items-center justify-between gap-4"
                        data-estado="{{ $cita->estado }}">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-blue-50 rounded-xl flex flex-col items-center justify-center text-blue-500">
                                <span class="text-xs font-bold uppercase">{{ $cita->fecha_hora->format('M') }}</span>
                                <span class="text-lg font-black leading-none">{{ $cita->fecha_hora->format('d') }}</span>
                            </div>
                            <div>
                                <p class="font-bold text-slate-900">{{ $cita->motivo }}</p>
                                <p class="text-sm text-slate-500">
                                    {{ $cita->fecha_hora->format('H:i') }} —
                                    {{ $cita->odontologo->user->name ?? 'Odontólogo no asignado' }}
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $estilo }}">
                            {{ ucfirst($cita->estado) }}
                        </span>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-10 text-center">
                        <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="material-symbols-outlined text-blue-300 text-4xl">calendar_today</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-700 mb-2">No tienes citas agendadas</h3>
                        <p class="text-slate-400 text-sm mb-6">Agenda tu próxima visita con nosotros</p>
                        <a href="{{ route('paciente.citas.create') }}"
                            class="inline-flex items-center gap-2 bg-blue-500 text-white px-6 py-3 rounded-xl text-sm font-semibold hover:bg-blue-600 transition-colors">
                            <span class="material-symbols-outlined">add_circle</span>
                            Agendar ahora
                        </a>
                    </div>
                @endforelse
            </div>

        </div>
    </main>
</div>

@endsection

@section('scripts')
<script>
    function filtrar(estado) {
        const btns = {
            'todas': document.getElementById('btn-todas'),
            'pendiente': document.getElementById('btn-pendiente'),
            'confirmada': document.getElementById('btn-confirmada'),
            'completada': document.getElementById('btn-completada'),
            'cancelada': document.getElementById('btn-cancelada')
        };

        Object.values(btns).forEach(btn => {
            btn.classList.remove('bg-white', 'shadow-sm', 'text-blue-500');
            btn.classList.add('text-slate-400');
        });

        btns[estado].classList.add('bg-white', 'shadow-sm', 'text-blue-500');
        btns[estado].classList.remove('text-slate-400');

        document.querySelectorAll('#listaCitas > div[data-estado]').forEach(card => {
            if (estado === 'todas') {
                card.style.display = 'flex';
            } else {
                card.style.display = card.dataset.estado === estado ? 'flex' : 'none';
            }
        });
    }
</script>
@endsection