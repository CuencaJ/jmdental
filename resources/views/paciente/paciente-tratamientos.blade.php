@extends('layouts.admin')

@section('titulo', 'Mis Tratamientos - JM Dental')

@section('content')

<div class="flex h-screen overflow-hidden bg-slate-50">

    @include('layouts.partials.sidebar-paciente')

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1 flex flex-col overflow-hidden">

        {{-- HEADER --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8">
            <h1 class="text-xl font-bold text-slate-900">Mis Tratamientos</h1>
        </header>

        <div class="flex-1 overflow-y-auto p-8 space-y-6">

            {{-- TARJETAS RESUMEN --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined">medical_information</span>
                        </div>
                        <span class="text-xs bg-blue-50 text-blue-500 font-bold px-2 py-1 rounded-lg">Total</span>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Tratamientos realizados</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $totalTratamientos }}</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined">payments</span>
                        </div>
                        <span class="text-xs bg-green-50 text-green-500 font-bold px-2 py-1 rounded-lg">Costo total</span>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Total acumulado</p>
                        <p class="text-2xl font-bold text-slate-900">${{ number_format($totalCosto, 2) }}</p>
                    </div>
                </div>
            </div>

            {{-- TABLA --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left" id="tablaTratamientos">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400">Tratamiento</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400">Fecha</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400">Odontólogo</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400">Estado</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400 text-right">Costo</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($tratamientos as $tratamiento)
                                @php
                                    $estilo = match($tratamiento->estado) {
                                        'completado' => 'bg-green-100 text-green-700',
                                        'en_proceso' => 'bg-amber-100 text-amber-700',
                                        'cancelado'  => 'bg-red-100 text-red-700',
                                        default      => 'bg-slate-100 text-slate-600',
                                    };
                                    $etiqueta = match($tratamiento->estado) {
                                        'completado' => 'Completado',
                                        'en_proceso' => 'En proceso',
                                        'cancelado'  => 'Cancelado',
                                        default      => ucfirst($tratamiento->estado),
                                    };
                                @endphp
                                <tr class="hover:bg-blue-50/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-slate-900">{{ $tratamiento->nombre }}</p>
                                        @if($tratamiento->observaciones)
                                            <p class="text-xs text-slate-400 mt-0.5 truncate max-w-xs">{{ $tratamiento->observaciones }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-500">
                                        {{ \Carbon\Carbon::parse($tratamiento->fecha_tratamiento)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-500">
                                        {{ $tratamiento->cita->odontologo->user->name ?? 'No asignado' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $estilo }}">
                                            {{ $etiqueta }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-slate-900 text-right">
                                        ${{ number_format($tratamiento->costo, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick="verDetalle({{ $tratamiento->id }})"
                                                class="p-2 text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-all"
                                                title="Ver detalle">
                                                <span class="material-symbols-outlined text-xl">visibility</span>
                                            </button>
                                            <a href="{{ route('paciente.tratamientos.pdf', $tratamiento->id) }}"
                                                target="_blank"
                                                class="p-2 text-slate-400 hover:text-green-500 hover:bg-green-50 rounded-lg transition-all"
                                                title="Descargar historial">
                                                <span class="material-symbols-outlined text-xl">download</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-14 h-14 bg-blue-50 rounded-full flex items-center justify-center mb-3">
                                                <span class="material-symbols-outlined text-blue-300 text-3xl">medical_information</span>
                                            </div>
                                            <p class="text-sm font-semibold text-slate-500">No tienes tratamientos registrados</p>
                                            <p class="text-xs text-slate-400 mt-1">Tus tratamientos aparecerán aquí después de tus citas</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tratamientos->count() > 0)
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex justify-between items-center">
                    <p class="text-sm text-slate-500">
                        Total: <span class="font-bold text-slate-900">{{ $totalTratamientos }}</span> tratamientos
                    </p>
                    <p class="text-sm font-bold text-slate-900">
                        Total: ${{ number_format($totalCosto, 2) }}
                    </p>
                </div>
                @endif
            </div>

        </div>
    </main>
</div>

{{-- MODAL DETALLE --}}
<div id="modalDetalle" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-slate-900">Detalle del Tratamiento</h2>
            <button onclick="cerrarModal()" class="p-2 hover:bg-slate-100 rounded-lg">
                <span class="material-symbols-outlined text-slate-400">close</span>
            </button>
        </div>
        <div id="contenidoModal" class="space-y-4">
        </div>
    </div>
</div>

@php
$piezasJson = collect($tratamientos)->mapWithKeys(function($t) {
    return [$t->id => $t->piezas->map(function($p) {
        return [
            'pieza_numero' => $p->pieza_numero,
            'cara'         => $p->cara,
            'procedimiento'=> $p->procedimiento,
            'ausente'      => $p->ausente,
        ];
    })->values()->toArray()];
});
@endphp

@endsection

@section('scripts')
<script>
const tratamientos = @json($datosTratamientos);
const piezasPorTratamiento = @json($piezasJson);

function verDetalle(id) {
    const t = tratamientos.find(t => t.id === id);
    if (!t) return;

    const piezasData = piezasPorTratamiento[id] ?? [];
    let piezasHtml = '';
    if (piezasData.length > 0) {
        const tags = piezasData.map(function(p) {
            let label = 'Pieza ' + p.pieza_numero;
            if (p.cara) label += ' · ' + p.cara;
            if (p.procedimiento) label += ' · ' + p.procedimiento;
            if (p.ausente) label += ' · Ausente';
            return '<span style="display:inline-block;background:#eff6ff;border:1px solid #bfdbfe;border-radius:20px;padding:3px 10px;font-size:11px;color:#1d4ed8;margin:3px;">' + label + '</span>';
        }).join('');
        piezasHtml = '<div style="border-top:1px solid #f1f5f9;padding-top:12px;margin-top:8px;">'
            + '<span style="font-size:10px;font-weight:700;text-transform:uppercase;color:#94a3b8;">Piezas trabajadas</span>'
            + '<div style="margin-top:6px;">' + tags + '</div></div>';
    }

    document.getElementById('contenidoModal').innerHTML =
        '<div class="space-y-3">'
        + '<div class="flex justify-between py-2 border-b border-slate-100"><span class="text-xs font-bold uppercase text-slate-400">Tratamiento</span><span class="text-sm font-bold text-slate-900">' + t.nombre + '</span></div>'
        + '<div class="flex justify-between py-2 border-b border-slate-100"><span class="text-xs font-bold uppercase text-slate-400">Descripción</span><span class="text-sm text-slate-900 text-right max-w-xs">' + t.descripcion + '</span></div>'
        + '<div class="flex justify-between py-2 border-b border-slate-100"><span class="text-xs font-bold uppercase text-slate-400">Fecha</span><span class="text-sm text-slate-900">' + t.fecha + '</span></div>'
        + '<div class="flex justify-between py-2 border-b border-slate-100"><span class="text-xs font-bold uppercase text-slate-400">Odontólogo</span><span class="text-sm text-slate-900">' + t.odontologo + '</span></div>'
        + '<div class="flex justify-between py-2 border-b border-slate-100"><span class="text-xs font-bold uppercase text-slate-400">Estado</span><span class="text-sm text-slate-900">' + t.estado + '</span></div>'
        + '<div class="flex justify-between py-2 border-b border-slate-100"><span class="text-xs font-bold uppercase text-slate-400">Costo</span><span class="text-sm font-bold text-slate-900">$' + t.costo + '</span></div>'
        + '<div class="flex justify-between py-2' + (piezasHtml ? ' border-b border-slate-100' : '') + '"><span class="text-xs font-bold uppercase text-slate-400">Observaciones</span><span class="text-sm text-slate-900 text-right max-w-xs">' + t.observaciones + '</span></div>'
        + piezasHtml
        + '</div>';

    const modal = document.getElementById('modalDetalle');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function cerrarModal() {
    const modal = document.getElementById('modalDetalle');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.getElementById('modalDetalle').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});
</script>
@endsection