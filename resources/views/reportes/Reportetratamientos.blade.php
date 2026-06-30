@extends('layouts.admin')

@section('titulo', 'Reporte de Tratamientos - JM Dental')

@section('content')

<div class="flex h-screen overflow-hidden bg-slate-50">

    @include('layouts.partials.sidebar-admin')

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1 flex flex-col overflow-hidden">

        {{-- HEADER --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8">
        </header>

        <div class="flex-1 overflow-y-auto p-8 space-y-5">

            <h1 class="text-xl font-bold text-slate-900">Reporte de tratamientos</h1>

            {{-- SELECTOR DE PERIODO --}}
            <form id="filtroForm" action="{{ route('admin.reportes.tratamientos') }}" method="GET"
                class="flex flex-wrap items-center justify-between gap-4">

                <input type="hidden" name="periodo" id="periodoHidden" value="{{ $periodo }}">

                <div class="flex bg-slate-100 p-1 rounded-xl">
                    @foreach(['dia' => 'Día', 'mes' => 'Mes', 'anio' => 'Año'] as $valor => $etiqueta)
                        <button type="button"
                            onclick="document.getElementById('periodoHidden').value='{{ $valor }}'; document.getElementById('filtroForm').submit();"
                            class="px-4 py-2 rounded-lg font-bold text-sm transition-colors
                                {{ $periodo == $valor ? 'bg-white shadow-sm text-blue-500' : 'text-slate-400 hover:text-blue-500' }}">
                            {{ $etiqueta }}
                        </button>
                    @endforeach
                </div>

                <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-lg px-3 py-2">
                    <span class="material-symbols-outlined text-base text-slate-400">calendar_today</span>
                    <input type="date" name="fecha" value="{{ $fecha }}"
                        onchange="this.form.submit()"
                        class="text-sm text-slate-700 outline-none border-none">
                </div>
            </form>

            {{-- TOTAL --}}
            <div class="bg-blue-500 rounded-xl px-6 py-5 text-white shadow-lg flex items-center justify-between">
                <div>
                    <p class="text-xs opacity-85">Total de tratamientos completados — {{ $etiquetaPeriodo }}</p>
                    <p class="text-3xl font-black mt-1">{{ $totalTratamientos }}</p>
                </div>
                <span class="material-symbols-outlined text-4xl opacity-90">task_alt</span>
            </div>

            {{-- TABLA --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400">Tratamiento</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400">Frecuencia</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400 text-right">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($porTratamiento as $tratamiento => $cantidad)
                            <tr>
                                <td class="px-6 py-4 text-sm text-slate-900">{{ $tratamiento }}</td>
                                <td class="px-6 py-4">
                                    <div class="bg-blue-50 rounded-full h-1.5 w-full max-w-xs">
                                        <div class="bg-blue-500 rounded-full h-1.5"
                                            style="width: {{ round($cantidad / $maxCantidad * 100) }}%"></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-slate-900 text-right">{{ $cantidad }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-slate-400 text-sm">
                                    No hay tratamientos completados en este periodo.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </main>
</div>

@endsection