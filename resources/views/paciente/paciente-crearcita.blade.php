@extends('layouts.admin')

@section('titulo', 'Agendar Cita - JM Dental')

@section('content')

<div class="flex h-screen overflow-hidden bg-slate-50">

    @include('layouts.partials.sidebar-paciente')

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1 flex flex-col overflow-hidden">

        {{-- HEADER --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center gap-3 px-8">
            <a href="{{ route('paciente.citas') }}" class="text-slate-400 hover:text-slate-600">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="text-xl font-bold text-slate-900">Agendar Nueva Cita</h1>
        </header>

        {{-- FORMULARIO --}}
        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-xl mx-auto">
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

                    <form action="{{ route('paciente.citas.store') }}" method="POST" class="space-y-6">
                        @csrf

                        {{-- ODONTÓLOGO --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Selecciona un odontólogo
                            </label>
                            <select name="odontologo_id"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500" required>
                                <option value="">Selecciona un odontólogo</option>
                                @foreach($odontologos as $odontologo)
                                    <option value="{{ $odontologo->id }}" {{ old('odontologo_id') == $odontologo->id ? 'selected' : '' }}>
                                        {{ $odontologo->user->name }} — {{ $odontologo->especialidad ?? 'Odontología general' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- FECHA Y HORA --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Fecha y hora
                            </label>
                            <input type="datetime-local" name="fecha_hora" value="{{ old('fecha_hora') }}"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                required>
                        </div>

                        {{-- MOTIVO --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Motivo de la cita
                            </label>
                            <textarea name="motivo" rows="3"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                placeholder="Ej: Revisión general, dolor de muela, limpieza dental..." required>{{ old('motivo') }}</textarea>
                        </div>

                        {{-- BOTONES --}}
                        <div class="flex gap-4 pt-4">
                            <button type="submit"
                                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-xl transition-colors">
                                Solicitar Cita
                            </button>
                            <a href="{{ route('paciente.citas') }}"
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