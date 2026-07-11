@extends('layouts.admin')
@section('titulo', 'Historia Clínica Inicial - JM Dental')
@section('content')
<div class="flex h-screen overflow-hidden bg-slate-50">
    @include('layouts.partials.sidebar-odontologo')
    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center px-8">
            <h1 class="text-lg font-bold text-slate-900">Historia Clínica — Formulario 033 MSP</h1>
        </header>
        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-3xl mx-auto">

                <div class="flex items-center gap-3 mb-6">
                    <a href="{{ route('odontologo.pacientes.show', $usuario->id) }}"
                        class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-500">
                        <span class="material-symbols-outlined">arrow_back</span>
                    </a>
                    <h1 class="text-xl font-bold text-slate-900">Primera consulta — Historia clínica inicial</h1>
                </div>

                {{-- INFO PACIENTE --}}
                <div class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 mb-6 flex items-center gap-3">
                    <span class="material-symbols-outlined text-blue-500">person</span>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ $usuario->name }}</p>
                        <p class="text-xs text-slate-500">
                            {{ $usuario->email }}
                            @if($paciente?->edad) · {{ $paciente->edad }} años @endif
                            @if($paciente?->tipo_sangre) · Sangre: {{ $paciente->tipo_sangre }} @endif
                        </p>
                    </div>
                    <span class="ml-auto text-xs bg-amber-100 text-amber-700 font-bold px-3 py-1 rounded-full">
                        Paciente nuevo — Primera consulta
                    </span>
                </div>

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 mb-6">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('odontologo.historia.store', $usuario->id) }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- A. DATOS ADICIONALES DEL PACIENTE --}}
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
                        <h3 class="font-bold text-slate-900">A. Datos del paciente</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Segundo nombre</label>
                                <input type="text" name="segundo_nombre" value="{{ old('segundo_nombre') }}"
                                    placeholder="Segundo nombre del paciente"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Segundo apellido</label>
                                <input type="text" name="segundo_apellido" value="{{ old('segundo_apellido') }}"
                                    placeholder="Segundo apellido del paciente"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Condición edad</label>
                                <select name="condicion_edad"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                                    <option value="anios" {{ old('condicion_edad', 'anios') === 'anios' ? 'selected' : '' }}>Años (A)</option>
                                    <option value="meses" {{ old('condicion_edad') === 'meses' ? 'selected' : '' }}>Meses (M)</option>
                                    <option value="dias" {{ old('condicion_edad') === 'dias' ? 'selected' : '' }}>Días (D)</option>
                                    <option value="horas" {{ old('condicion_edad') === 'horas' ? 'selected' : '' }}>Horas (H)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Embarazada</label>
                                <div class="flex items-center gap-6 mt-2">
                                    <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                                        <input type="radio" name="embarazada" value="1" {{ old('embarazada') == '1' ? 'checked' : '' }}> Sí
                                    </label>
                                    <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                                        <input type="radio" name="embarazada" value="0" {{ old('embarazada') == '0' ? 'checked' : '' }} checked> No
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- B. MOTIVO Y ENFERMEDAD ACTUAL --}}
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
                        <h3 class="font-bold text-slate-900">B. Motivo de consulta y enfermedad actual</h3>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1.5">Motivo de consulta <span class="text-red-500">*</span></label>
                            <input type="text" name="motivo_consulta" required value="{{ old('motivo_consulta') }}"
                                placeholder="Ej. Dolor de muela, revisión general, limpieza..."
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1.5">Enfermedad actual / descripción</label>
                            <textarea name="enfermedad_actual" rows="3"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">{{ old('enfermedad_actual') }}</textarea>
                        </div>
                    </div>

                    {{-- D/E. ANTECEDENTES --}}
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
                        <h3 class="font-bold text-slate-900">D/E. Antecedentes patológicos</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Antecedentes personales</label>
                                <textarea name="antecedentes_personales" rows="4"
                                    placeholder="Alergias, enfermedades crónicas, medicamentos, cirugías previas..."
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">{{ old('antecedentes_personales', $paciente?->enfermedades_cronicas) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Antecedentes familiares</label>
                                <textarea name="antecedentes_familiares" rows="4"
                                    placeholder="Diabetes, hipertensión, cardiopatías, cáncer..."
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">{{ old('antecedentes_familiares') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- F. CONSTANTES VITALES --}}
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
                        <h3 class="font-bold text-slate-900">F. Constantes vitales</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Temperatura °C</label>
                                <input type="text" name="temperatura" value="{{ old('temperatura') }}"
                                    placeholder="36.5"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Pulso / min</label>
                                <input type="text" name="pulso" value="{{ old('pulso') }}"
                                    placeholder="72"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Frec. respiratoria / min</label>
                                <input type="text" name="frecuencia_respiratoria" value="{{ old('frecuencia_respiratoria') }}"
                                    placeholder="16"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Presión arterial (mmHg)</label>
                                <input type="text" name="presion_arterial" value="{{ old('presion_arterial') }}"
                                    placeholder="120/80"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                        </div>
                    </div>

                    {{-- G. EXAMEN CLÍNICO --}}
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
                        <h3 class="font-bold text-slate-900">G. Examen del sistema estomatognático</h3>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1.5">Examen extraoral</label>
                            <textarea name="examen_extraoral" rows="3"
                                placeholder="Labios, mejillas, maxilar superior/inferior, A.T.M, ganglios..."
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">{{ old('examen_extraoral') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1.5">Examen intraoral</label>
                            <textarea name="examen_intraoral" rows="3"
                                placeholder="Lengua, paladar, piso de la boca, carrillos, glándulas salivales, oro faringe..."
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">{{ old('examen_intraoral') }}</textarea>
                        </div>
                    </div>

                    {{-- N. DIAGNÓSTICO INICIAL --}}
                    <div class="bg-white border border-slate-200 rounded-2xl p-6">
                        <h3 class="font-bold text-slate-900 mb-4">N. Diagnóstico inicial</h3>
                        <textarea name="diagnostico_inicial" rows="3"
                            placeholder="Diagnóstico presuntivo o definitivo (CIE-10)..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">{{ old('diagnostico_inicial') }}</textarea>
                    </div>

                    <div class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-400 text-base">info</span>
                        <p class="text-xs text-blue-600">El odontograma y el plan de tratamiento se completarán desde <strong>"Completar tratamiento"</strong> después de cada sesión.</p>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('odontologo.pacientes.show', $usuario->id) }}"
                            class="px-4 py-2.5 rounded-lg text-sm font-semibold text-slate-500 hover:bg-slate-100">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2.5 rounded-lg text-sm font-semibold">
                            Guardar historia clínica
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection