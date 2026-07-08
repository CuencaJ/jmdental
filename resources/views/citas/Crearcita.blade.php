@extends('layouts.admin')

@section('titulo', 'Crear Cita - JM Dental')

@section('content')

<div class="flex h-screen overflow-hidden bg-slate-50">

    @if(Auth::user()->hasRole('administrador'))
        @include('layouts.partials.sidebar-admin')
    @elseif(Auth::user()->hasRole('recepcionista'))
        @include('layouts.partials.sidebar-recepcionista')
    @endif

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1 flex flex-col overflow-hidden">

        {{-- HEADER --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center gap-3 px-8">
            <a href="{{ Auth::user()->hasRole('administrador') ? route('admin.citas.index') : route('recepcionista.citas') }}"
                class="text-slate-400 hover:text-slate-600">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="text-xl font-bold text-slate-900">Nueva Cita</h1>
        </header>

        {{-- FORMULARIO --}}
        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">

                    {{-- ERRORES --}}
                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ Auth::user()->hasRole('administrador') ? route('admin.citas.store') : route('recepcionista.citas.store') }}"
                        method="POST" class="space-y-6">
                        @csrf

                        {{-- PACIENTE --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Paciente</label>
                            @if(request('paciente_id'))
                                @php
                                    $pacienteSeleccionado = $pacientes->firstWhere('id', request('paciente_id'));
                                @endphp
                                <div class="w-full bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr($pacienteSeleccionado->user->name ?? '', 0, 2)) }}
                                    </div>
                                    <span class="font-semibold text-slate-900">{{ $pacienteSeleccionado->user->name ?? 'Paciente' }}</span>
                                </div>
                                <input type="hidden" name="paciente_id" value="{{ request('paciente_id') }}">
                            @else
                                <select name="paciente_id" required
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500">
                                    <option value="">Selecciona un paciente</option>
                                    @foreach($pacientes as $paciente)
                                        <option value="{{ $paciente->id }}"
                                            {{ old('paciente_id') == $paciente->id ? 'selected' : '' }}>
                                            {{ $paciente->user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        {{-- ODONTÓLOGO --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Odontólogo</label>
                            <select name="odontologo_id" required
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500">
                                <option value="">Selecciona un odontólogo</option>
                                @foreach($odontologos as $odontologo)
                                    <option value="{{ $odontologo->id }}"
                                        {{ old('odontologo_id') == $odontologo->id ? 'selected' : '' }}>
                                        {{ $odontologo->user->name }} — {{ $odontologo->especialidad ?? 'Odontología general' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- FECHA Y HORA --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Fecha y hora</label>
                            <input type="datetime-local" name="fecha_hora" value="{{ old('fecha_hora') }}"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                required>
                        </div>

                        {{-- ESTADO --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Estado</label>
                            <select name="estado" required
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500">
                                <option value="pendiente" {{ old('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="confirmada" {{ old('estado') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                <option value="completada" {{ old('estado') == 'completada' ? 'selected' : '' }}>Completada</option>
                                <option value="cancelada" {{ old('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                        </div>

                        {{-- MOTIVO --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Motivo / Procedimiento</label>
                            <input type="text" name="motivo" value="{{ old('motivo') }}"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                placeholder="Ej. Limpieza dental, control, extracción..." required>
                        </div>

                        {{-- NOTAS --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Notas (opcional)</label>
                            <textarea name="notas" rows="3"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                placeholder="Observaciones adicionales...">{{ old('notas') }}</textarea>
                        </div>

                        {{-- BOTONES --}}
                        <div class="flex gap-4 pt-4">
                            <button type="submit"
                                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-xl transition-colors">
                                Guardar Cita
                            </button>
                            <a href="{{ Auth::user()->hasRole('administrador') ? route('admin.citas.index') : route('recepcionista.citas') }}"
                                class="flex-1 text-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition-colors">
                                Cancelar
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

@endsection