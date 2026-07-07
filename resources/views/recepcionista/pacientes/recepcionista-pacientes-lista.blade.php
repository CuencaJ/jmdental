@extends('layouts.admin')

@section('titulo', 'Pacientes - JM Dental')

@section('content')

<div class="flex min-h-screen bg-slate-50">

    @include('layouts.partials.sidebar-recepcionista')

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1 flex flex-col overflow-hidden">

        {{-- HEADER --}}
        <header class="h-16 flex items-center justify-between px-8 bg-white border-b border-slate-200 sticky top-0 z-10">
            <div class="relative w-96">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input type="text" id="buscador"
                    class="w-full bg-slate-100 border-none rounded-lg pl-10 pr-4 py-2 text-sm outline-none"
                    placeholder="Buscar pacientes por nombre, correo o ID...">
            </div>
            <a href="{{ route('recepcionista.pacientes.create') }}"
                class="flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                <span class="material-symbols-outlined text-base">person_add</span>
                Nuevo Paciente
            </a>
        </header>

        {{-- CONTENIDO --}}
        <div class="flex-1 overflow-y-auto p-8 space-y-8">

            {{-- TARJETAS RESUMEN --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border-t-4 border-blue-500 flex flex-col gap-4">
                    <div class="flex justify-between items-start">
                        <div class="p-3 bg-blue-50 rounded-lg text-blue-500">
                            <span class="material-symbols-outlined">groups</span>
                        </div>
                        <span class="text-blue-500 font-bold text-3xl">{{ $totalPacientes }}</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase text-slate-400">Total Pacientes</p>
                        <div class="flex items-center gap-1 text-green-600 text-sm font-bold mt-1">
                            <span class="material-symbols-outlined text-base">trending_up</span>
                            <span>Registrados en el sistema</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border-t-4 border-amber-500 flex flex-col gap-4">
                    <div class="flex justify-between items-start">
                        <div class="p-3 bg-amber-50 rounded-lg text-amber-500">
                            <span class="material-symbols-outlined">event_available</span>
                        </div>
                        <span class="text-amber-500 font-bold text-3xl">0</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase text-slate-400">Visitas de Hoy</p>
                        <p class="text-sm text-slate-400 mt-1">0 pendientes de completar</p>
                    </div>
                </div>
            </div>

            {{-- FILTROS --}}
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex bg-slate-100 p-1 rounded-xl">
                    <button onclick="filtrar('todos')" id="btn-todos"
                        class="px-4 py-2 rounded-lg bg-white shadow-sm text-blue-500 font-bold text-sm">
                        Todos
                    </button>
                    <button onclick="filtrar('activo')" id="btn-activo"
                        class="px-4 py-2 rounded-lg text-slate-400 font-bold text-sm hover:text-blue-500 transition-colors">
                        Activos
                    </button>
                    <button onclick="filtrar('inactivo')" id="btn-inactivo"
                        class="px-4 py-2 rounded-lg text-slate-400 font-bold text-sm hover:text-blue-500 transition-colors">
                        Inactivos
                    </button>
                </div>
            </div>

            {{-- TABLA --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left" id="tablaPacientes">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-8 py-5 text-xs font-bold uppercase text-slate-400">#</th>
                                <th class="px-8 py-5 text-xs font-bold uppercase text-slate-400">Usuario</th>
                                <th class="px-8 py-5 text-xs font-bold uppercase text-slate-400">Correo</th>
                                <th class="px-8 py-5 text-xs font-bold uppercase text-slate-400">Teléfono</th>
                                <th class="px-8 py-5 text-xs font-bold uppercase text-slate-400">Estado</th>
                                <th class="px-8 py-5 text-xs font-bold uppercase text-slate-400 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($pacientes as $paciente)
                                <tr class="hover:bg-blue-50/30 transition-colors group"
                                    data-estado="{{ $paciente->activo ? 'activo' : 'inactivo' }}">
                                    <td class="px-8 py-4 text-sm text-blue-500 font-bold">
                                        #BS-{{ str_pad($paciente->id, 4, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-8 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                                {{ strtoupper(substr($paciente->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-slate-900">{{ $paciente->name }}</p>
                                                <p class="text-xs text-slate-400">
                                                    Registrado {{ $paciente->created_at->format('d M Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-4 text-sm text-slate-500">{{ $paciente->email }}</td>
                                    <td class="px-8 py-4 text-sm text-slate-500">{{ $paciente->telefono ?? '-' }}</td>
                                    <td class="px-8 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold
                                            {{ $paciente->activo ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $paciente->activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-4">
                                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('recepcionista.pacientes.show', $paciente->id) }}"
                                                class="p-2 text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-all">
                                                <span class="material-symbols-outlined text-xl">visibility</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-10 text-center text-slate-400 text-sm">
                                        No hay pacientes registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINACIÓN --}}
                <div class="px-8 py-5 border-t border-slate-200 flex justify-between items-center bg-slate-50">
                    <p class="text-sm text-slate-400">
                        Mostrando <span class="font-bold text-slate-900" id="contadorPacientes">{{ $totalPacientes }}</span> pacientes
                    </p>
                </div>
            </div>

        </div>
    </main>
</div>

@endsection

@section('scripts')
<script>
    document.getElementById('buscador').addEventListener('input', function() {
        const term = this.value.toLowerCase();
        let contador = 0;
        document.querySelectorAll('#tablaPacientes tbody tr').forEach(row => {
            const name = row.cells[1]?.innerText.toLowerCase() ?? '';
            const email = row.cells[2]?.innerText.toLowerCase() ?? '';
            const id = row.cells[0]?.innerText.toLowerCase() ?? '';
            if (name.includes(term) || email.includes(term) || id.includes(term)) {
                row.style.display = 'table-row';
                contador++;
            } else {
                row.style.display = 'none';
            }
        });
        document.getElementById('contadorPacientes').innerText = contador;
    });

    function filtrar(estado) {
        const btns = {
            'todos': document.getElementById('btn-todos'),
            'activo': document.getElementById('btn-activo'),
            'inactivo': document.getElementById('btn-inactivo')
        };

        Object.values(btns).forEach(btn => {
            btn.classList.remove('bg-white', 'shadow-sm', 'text-blue-500');
            btn.classList.add('text-slate-400');
        });

        btns[estado].classList.add('bg-white', 'shadow-sm', 'text-blue-500');
        btns[estado].classList.remove('text-slate-400');

        let contador = 0;
        document.querySelectorAll('#tablaPacientes tbody tr').forEach(row => {
            if (estado === 'todos') {
                row.style.display = 'table-row';
                contador++;
            } else {
                if (row.dataset.estado === estado) {
                    row.style.display = 'table-row';
                    contador++;
                } else {
                    row.style.display = 'none';
                }
            }
        });

        document.getElementById('contadorPacientes').innerText = contador;
    }
</script>
@endsection