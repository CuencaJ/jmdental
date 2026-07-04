@extends('layouts.admin')
@section('titulo', 'Preparar Semana - JM Dental')
@section('content')
<div class="flex h-screen overflow-hidden bg-slate-50">
    @include('layouts.partials.sidebar-admin')
    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center px-8 gap-4">
            <h1 class="text-lg font-bold text-slate-900">Preparar semana</h1>
            <div class="ml-auto flex items-center gap-2">
                <a href="{{ route('admin.semana', ['semana' => $semana[0]->copy()->subWeek()->toDateString(), 'odontologo_id' => $odontologo?->id]) }}"
                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-500">
                    <span class="material-symbols-outlined">chevron_left</span>
                </a>
                <span class="text-sm font-semibold text-slate-700">
                    {{ $semana[0]->format('d M') }} — {{ $semana[6]->format('d M Y') }}
                </span>
                <a href="{{ route('admin.semana', ['semana' => $semana[0]->copy()->addWeek()->toDateString(), 'odontologo_id' => $odontologo?->id]) }}"
                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-500">
                    <span class="material-symbols-outlined">chevron_right</span>
                </a>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-6">

            {{-- SELECTOR ODONTÓLOGO --}}
            <div class="bg-white border border-slate-200 rounded-xl p-4 mb-5 flex items-center gap-4">
                <span class="material-symbols-outlined text-slate-400">stethoscope</span>
                <form method="GET" action="{{ route('admin.semana') }}" class="flex items-center gap-3 flex-1">
                    <input type="hidden" name="semana" value="{{ $semana[0]->toDateString() }}">
                    <select name="odontologo_id" onchange="this.form.submit()"
                        class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-blue-400">
                        @foreach($odontologos as $od)
                            <option value="{{ $od->id }}" {{ $odontologo?->id == $od->id ? 'selected' : '' }}>
                                {{ $od->user->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-sm text-slate-500">Gestionando horario de:</span>
                    <span class="text-sm font-semibold text-blue-500">{{ $odontologo?->user?->name ?? '—' }}</span>
                </form>
            </div>

            <div class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 mb-5 flex items-center gap-3">
                <span class="material-symbols-outlined text-blue-500">info</span>
                <p class="text-sm text-blue-700">
                    Haz clic en cualquier hora para <strong>bloquearla</strong> o <strong>desbloquearla</strong>.
                    Los horarios bloqueados no estarán disponibles para los pacientes.
                </p>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="grid grid-cols-8 border-b border-slate-200">
                    <div class="py-3 px-3 text-xs font-bold text-slate-400 uppercase border-r border-slate-200">Hora</div>
                    @foreach($semana as $dia)
                        @php
                            $esDiaLaboral = in_array((string)$dia->isoWeekday(), $config->dias_laborables);
                            $esHoy = $dia->isToday();
                        @endphp
                        <div class="py-3 px-2 text-center {{ !$esDiaLaboral ? 'bg-slate-50' : '' }}">
                            <p class="text-xs font-bold uppercase {{ $esHoy ? 'text-blue-500' : 'text-slate-500' }}">
                                {{ $dia->locale('es')->isoFormat('ddd') }}
                            </p>
                            <p class="text-sm font-bold {{ $esHoy ? 'text-blue-500' : 'text-slate-900' }} mt-0.5">
                                {{ $dia->format('d') }}
                            </p>
                            @if(!$esDiaLaboral)
                                <p class="text-xs text-slate-400 mt-0.5">No laboral</p>
                            @endif
                        </div>
                    @endforeach
                </div>

                @foreach($slots as $slot)
                    <div class="grid grid-cols-8 border-b border-slate-100 last:border-none">
                        <div class="py-2 px-3 text-xs font-medium text-slate-400 border-r border-slate-200 flex items-center">
                            {{ $slot }}
                        </div>
                        @foreach($semana as $dia)
                            @php
                                $esDiaLaboral = in_array((string)$dia->isoWeekday(), $config->dias_laborables);
                                $fechaStr = $dia->toDateString();
                                $esBloqueado = isset($bloqueados[$fechaStr]) && in_array($slot, $bloqueados[$fechaStr]);
                                $esPasado = $dia->isPast() && !$dia->isToday();
                            @endphp
                            <div class="py-1 px-1 border-r border-slate-100 last:border-none
                                {{ !$esDiaLaboral ? 'bg-slate-50' : '' }}">
                                @if($esDiaLaboral && !$esPasado)
                                    <button type="button"
                                        onclick="toggleBloqueo('{{ $fechaStr }}', '{{ $slot }}', {{ $odontologo?->id }}, this)"
                                        class="w-full py-1.5 px-1 rounded-lg text-xs font-medium transition-all
                                            {{ $esBloqueado
                                                ? 'bg-red-100 text-red-700 hover:bg-red-200'
                                                : 'bg-slate-50 text-slate-400 hover:bg-blue-50 hover:text-blue-500 border border-dashed border-slate-200 hover:border-blue-300' }}">
                                        {{ $esBloqueado ? '🔒 Bloqueado' : '+ Libre' }}
                                    </button>
                                @else
                                    <div class="w-full py-1.5 px-1 text-center text-xs text-slate-300">—</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>

            <div class="flex items-center gap-4 mt-4 text-xs text-slate-500">
                <div class="flex items-center gap-1.5">
                    <div class="w-4 h-4 bg-slate-50 border border-dashed border-slate-200 rounded"></div>
                    <span>Libre</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="w-4 h-4 bg-red-100 rounded"></div>
                    <span>Bloqueado</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="w-4 h-4 bg-slate-50 rounded"></div>
                    <span>No laboral / Pasado</span>
                </div>
            </div>
        </div>
    </main>
</div>

{{-- MODAL MOTIVO --}}
<div id="modal-motivo" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 p-6">
        <h2 class="text-base font-bold text-slate-900 mb-4">Bloquear horario</h2>
        <div id="info-bloqueo" class="bg-slate-50 rounded-lg px-3 py-2 mb-4 text-sm text-slate-700"></div>
        <div class="mb-4">
            <label class="block text-xs font-medium text-slate-500 mb-1.5">Motivo del bloqueo</label>
            <input type="text" id="input-motivo" placeholder="Ej. Reunión, Urgencia personal, Vacaciones..."
                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
        </div>
        <div class="flex gap-3">
            <button onclick="cerrarModal()" class="flex-1 px-4 py-2.5 rounded-lg text-sm font-semibold text-slate-500 hover:bg-slate-100">
                Cancelar
            </button>
            <button onclick="confirmarBloqueo()" class="flex-1 bg-red-500 hover:bg-red-600 text-white px-4 py-2.5 rounded-lg text-sm font-semibold">
                Bloquear
            </button>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>
let _fecha = null, _hora = null, _odontologoId = null, _btn = null;

function toggleBloqueo(fecha, hora, odontologoId, btn) {
    const esBloqueado = btn.classList.contains('bg-red-100');
    if (esBloqueado) {
        fetch('{{ route("semana.bloquear") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ fecha, hora_inicio: hora, odontologo_id: odontologoId, motivo: '' })
        })
        .then(r => r.json())
        .then(() => {
            btn.className = 'w-full py-1.5 px-1 rounded-lg text-xs font-medium transition-all bg-slate-50 text-slate-400 hover:bg-blue-50 hover:text-blue-500 border border-dashed border-slate-200 hover:border-blue-300';
            btn.textContent = '+ Libre';
        });
        return;
    }
    _fecha = fecha; _hora = hora; _odontologoId = odontologoId; _btn = btn;
    document.getElementById('info-bloqueo').textContent = fecha + ' — ' + hora;
    document.getElementById('input-motivo').value = '';
    const modal = document.getElementById('modal-motivo');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function confirmarBloqueo() {
    const motivo = document.getElementById('input-motivo').value || 'Bloqueado';
    fetch('{{ route("semana.bloquear") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ fecha: _fecha, hora_inicio: _hora, odontologo_id: _odontologoId, motivo })
    })
    .then(r => r.json())
    .then(() => {
        _btn.className = 'w-full py-1.5 px-1 rounded-lg text-xs font-medium transition-all bg-red-100 text-red-700 hover:bg-red-200';
        _btn.textContent = '🔒 Bloqueado';
        cerrarModal();
    });
}

function cerrarModal() {
    const modal = document.getElementById('modal-motivo');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.getElementById('modal-motivo').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});
</script>
@endsection