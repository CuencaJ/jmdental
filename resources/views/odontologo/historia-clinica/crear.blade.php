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
                                        <input type="radio" name="embarazada" value="0" {{ old('embarazada', '0') == '0' ? 'checked' : '' }}> No
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

                    {{-- I. INDICADORES DE SALUD BUCAL --}}
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
                        <h3 class="font-bold text-slate-900">I. Indicadores de salud bucal — Higiene Oral Simplificada</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs border border-slate-200 rounded-lg overflow-hidden">
                                <thead>
                                    <tr class="bg-slate-100">
                                        <th colspan="6" class="px-3 py-2 text-xs font-bold text-slate-600 text-center border-b border-r border-slate-200">
                                            PIEZAS DENTALES EXAMINADAS
                                        </th>
                                        <th class="px-2 py-2 text-xs font-bold text-slate-500 text-center border-b border-r border-slate-200">Placa<br><span class="font-normal text-slate-400">0-1-2-3-9</span></th>
                                        <th class="px-2 py-2 text-xs font-bold text-slate-500 text-center border-b border-r border-slate-200">Cálculo<br><span class="font-normal text-slate-400">0-1-2-3</span></th>
                                        <th class="px-2 py-2 text-xs font-bold text-slate-500 text-center border-b border-slate-200">Gingivitis<br><span class="font-normal text-slate-400">0-1</span></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @php
                                        $col1 = [16, 11, 26, 36, 31, 46];
                                        $col2 = [17, 21, 27, 37, 41, 47];
                                        $col3 = [55, 51, 65, 75, 71, 81];
                                    @endphp
                                    @for($i = 0; $i < 6; $i++)
                                        @php
                                            $p1 = $col1[$i];
                                            $p2 = $col2[$i];
                                            $p3 = $col3[$i];
                                            $rowKey = $i; // clave de fila para placa/calculo/gingivitis
                                        @endphp
                                        <tr>
                                            {{-- Pieza col1 + checkbox --}}
                                            <td class="px-2 py-2 font-bold text-blue-500 text-center border-r border-slate-200">{{ $p1 }}</td>
                                            <td class="px-2 py-1.5 text-center border-r border-slate-200">
                                                <input type="checkbox" name="hos_examinada[{{ $p1 }}]" value="1"
                                                    {{ old("hos_examinada.$p1") ? 'checked' : '' }}
                                                    class="w-3.5 h-3.5 accent-blue-500 cursor-pointer">
                                            </td>
                                            {{-- Pieza col2 + checkbox --}}
                                            <td class="px-2 py-2 font-bold text-purple-500 text-center border-r border-slate-200">{{ $p2 }}</td>
                                            <td class="px-2 py-1.5 text-center border-r border-slate-200">
                                                <input type="checkbox" name="hos_examinada[{{ $p2 }}]" value="1"
                                                    {{ old("hos_examinada.$p2") ? 'checked' : '' }}
                                                    class="w-3.5 h-3.5 accent-purple-500 cursor-pointer">
                                            </td>
                                            {{-- Pieza col3 + checkbox --}}
                                            <td class="px-2 py-2 font-bold text-green-600 text-center border-r border-slate-200">{{ $p3 }}</td>
                                            <td class="px-2 py-1.5 text-center border-r border-slate-200">
                                                <input type="checkbox" name="hos_examinada[{{ $p3 }}]" value="1"
                                                    {{ old("hos_examinada.$p3") ? 'checked' : '' }}
                                                    class="w-3.5 h-3.5 accent-green-500 cursor-pointer">
                                            </td>
                                            {{-- Placa fila --}}
                                            <td class="px-2 py-1.5 text-center border-r border-slate-200">
                                                <select name="hos_placa[{{ $rowKey }}]"
                                                    class="bg-slate-50 border border-slate-200 rounded px-1 py-1 text-xs outline-none focus:border-blue-400 w-12">
                                                    <option value="">—</option>
                                                    @foreach(['0','1','2','3','9'] as $v)
                                                        <option value="{{ $v }}" {{ old("hos_placa.$rowKey") == $v ? 'selected' : '' }}>{{ $v }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            {{-- Cálculo fila --}}
                                            <td class="px-2 py-1.5 text-center border-r border-slate-200">
                                                <select name="hos_calculo[{{ $rowKey }}]"
                                                    class="bg-slate-50 border border-slate-200 rounded px-1 py-1 text-xs outline-none focus:border-blue-400 w-12">
                                                    <option value="">—</option>
                                                    @foreach(['0','1','2','3'] as $v)
                                                        <option value="{{ $v }}" {{ old("hos_calculo.$rowKey") == $v ? 'selected' : '' }}>{{ $v }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            {{-- Gingivitis fila --}}
                                            <td class="px-2 py-1.5 text-center">
                                                <select name="hos_gingivitis[{{ $rowKey }}]"
                                                    class="bg-slate-50 border border-slate-200 rounded px-1 py-1 text-xs outline-none focus:border-blue-400 w-12">
                                                    <option value="">—</option>
                                                    @foreach(['0','1'] as $v)
                                                        <option value="{{ $v }}" {{ old("hos_gingivitis.$rowKey") == $v ? 'selected' : '' }}>{{ $v }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>

                        {{-- Enfermedad Periodontal --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Tipo de oclusión</label>
                                <select name="tipo_oclusion"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                                    <option value="">Selecciona</option>
                                    <option value="Angle I" {{ old('tipo_oclusion') == 'Angle I' ? 'selected' : '' }}>Angle I</option>
                                    <option value="Angle II" {{ old('tipo_oclusion') == 'Angle II' ? 'selected' : '' }}>Angle II</option>
                                    <option value="Angle III" {{ old('tipo_oclusion') == 'Angle III' ? 'selected' : '' }}>Angle III</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Nivel de fluorosis</label>
                                <select name="nivel_fluorosis"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                                    <option value="">Selecciona</option>
                                    <option value="Leve" {{ old('nivel_fluorosis') == 'Leve' ? 'selected' : '' }}>Leve</option>
                                    <option value="Moderada" {{ old('nivel_fluorosis') == 'Moderada' ? 'selected' : '' }}>Moderada</option>
                                    <option value="Severa" {{ old('nivel_fluorosis') == 'Severa' ? 'selected' : '' }}>Severa</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- J. ÍNDICES CPO-ceo --}}
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
                        <h3 class="font-bold text-slate-900">J. Índices CPO-ceo</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs font-bold text-slate-600 mb-3">CPO — Dentición permanente</p>
                                <div class="grid grid-cols-3 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-slate-500 mb-1.5">C — Cariadas</label>
                                        <input type="number" name="cpo_c" min="0" max="32" value="{{ old('cpo_c', 0) }}"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-500 mb-1.5">P — Perdidas</label>
                                        <input type="number" name="cpo_p" min="0" max="32" value="{{ old('cpo_p', 0) }}"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-500 mb-1.5">O — Obturadas</label>
                                        <input type="number" name="cpo_o" min="0" max="32" value="{{ old('cpo_o', 0) }}"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                                    </div>
                                </div>
                                <p class="text-xs text-slate-400 mt-2">Total CPO: <span id="total-cpo" class="font-bold text-slate-700">0</span></p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-600 mb-3">ceo — Dentición temporal</p>
                                <div class="grid grid-cols-3 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-slate-500 mb-1.5">c — Cariadas</label>
                                        <input type="number" name="ceo_c" min="0" max="20" value="{{ old('ceo_c', 0) }}"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-500 mb-1.5">e — Extraídas</label>
                                        <input type="number" name="ceo_e" min="0" max="20" value="{{ old('ceo_e', 0) }}"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-500 mb-1.5">o — Obturadas</label>
                                        <input type="number" name="ceo_o" min="0" max="20" value="{{ old('ceo_o', 0) }}"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                                    </div>
                                </div>
                                <p class="text-xs text-slate-400 mt-2">Total ceo: <span id="total-ceo" class="font-bold text-slate-700">0</span></p>
                            </div>
                        </div>
                    </div>

                    {{-- N. DIAGNÓSTICO --}}
                    <div class="bg-white border border-slate-200 rounded-2xl p-6">
                        <h3 class="font-bold text-slate-900 mb-1">N. Diagnóstico</h3>
                        <p class="text-xs text-slate-400 mb-4">PRE = Presuntivo &nbsp;·&nbsp; DEF = Definitivo</p>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm border border-slate-200 rounded-lg overflow-hidden">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-3 py-2 text-xs font-bold text-slate-500 text-left w-6">#</th>
                                        <th class="px-3 py-2 text-xs font-bold text-slate-500 text-left">Descripción del diagnóstico</th>
                                        <th class="px-3 py-2 text-xs font-bold text-slate-500 text-center w-24">CIE-10</th>
                                        <th class="px-3 py-2 text-xs font-bold text-slate-500 text-center w-16">PRE</th>
                                        <th class="px-3 py-2 text-xs font-bold text-slate-500 text-center w-16">DEF</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @for($i = 0; $i < 6; $i++)
                                        <tr>
                                            <td class="px-3 py-2 text-xs font-bold text-slate-400">{{ $i + 1 }}.</td>
                                            <td class="px-3 py-2">
                                                <input type="text" name="diagnosticos[{{ $i }}][descripcion]"
                                                    value="{{ old("diagnosticos.$i.descripcion") }}"
                                                    placeholder="Descripción del diagnóstico..."
                                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2 py-1.5 text-sm outline-none focus:border-blue-400">
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                <input type="text" name="diagnosticos[{{ $i }}][cie]"
                                                    value="{{ old("diagnosticos.$i.cie") }}"
                                                    placeholder="K02.1"
                                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2 py-1.5 text-sm outline-none focus:border-blue-400 text-center">
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                <input type="radio" name="diagnosticos[{{ $i }}][tipo]" value="pre"
                                                    {{ old("diagnosticos.$i.tipo") == 'pre' ? 'checked' : '' }}
                                                    class="w-4 h-4 accent-blue-500 cursor-pointer">
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                <input type="radio" name="diagnosticos[{{ $i }}][tipo]" value="def"
                                                    {{ old("diagnosticos.$i.tipo") == 'def' ? 'checked' : '' }}
                                                    class="w-4 h-4 accent-blue-500 cursor-pointer">
                                            </td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- L. PEDIDO DE EXÁMENES COMPLEMENTARIOS --}}
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
                        <h3 class="font-bold text-slate-900">L. Pedido de exámenes complementarios</h3>
                        <div class="form-group">
                            <label class="block text-xs font-medium text-slate-500 mb-1.5">Descripción del pedido</label>
                            <textarea name="examenes_pedido" rows="3"
                                placeholder="Describa los exámenes complementarios solicitados..."
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">{{ old('examenes_pedido') }}</textarea>
                        </div>
                    </div>

                    {{-- M. INFORME DE EXÁMENES --}}
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
                        <h3 class="font-bold text-slate-900">M. Informe de exámenes</h3>
                        <div class="flex flex-wrap gap-4 mb-3">
                            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                                <input type="checkbox" name="examenes_biometria" value="1"
                                    {{ old('examenes_biometria') ? 'checked' : '' }}
                                    class="w-4 h-4 accent-blue-500">
                                Biometría
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                                <input type="checkbox" name="examenes_quimica" value="1"
                                    {{ old('examenes_quimica') ? 'checked' : '' }}
                                    class="w-4 h-4 accent-blue-500">
                                Química sanguínea
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                                <input type="checkbox" name="examenes_rayos_x" value="1"
                                    {{ old('examenes_rayos_x') ? 'checked' : '' }}
                                    class="w-4 h-4 accent-blue-500">
                                Rayos-X
                            </label>
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-slate-600">Otros:</span>
                                <input type="text" name="examenes_otros"
                                    value="{{ old('examenes_otros') }}"
                                    placeholder="Especifique..."
                                    class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-blue-400">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1.5">Informe / resultado de exámenes</label>
                            <textarea name="examenes_informe" rows="4"
                                placeholder="Registre los resultados o informe de los exámenes realizados..."
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">{{ old('examenes_informe') }}</textarea>
                        </div>
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

@section('scripts')
<script>
function calcularTotales() {
    const cpo = ['cpo_c','cpo_p','cpo_o'].reduce((s, n) => s + (parseInt(document.querySelector(`[name="${n}"]`)?.value) || 0), 0);
    const ceo = ['ceo_c','ceo_e','ceo_o'].reduce((s, n) => s + (parseInt(document.querySelector(`[name="${n}"]`)?.value) || 0), 0);
    document.getElementById('total-cpo').textContent = cpo;
    document.getElementById('total-ceo').textContent = ceo;
}
['cpo_c','cpo_p','cpo_o','ceo_c','ceo_e','ceo_o'].forEach(n => {
    document.querySelector(`[name="${n}"]`)?.addEventListener('input', calcularTotales);
});
calcularTotales();
</script>
@endsection