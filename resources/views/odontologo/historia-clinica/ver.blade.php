@extends('layouts.admin')
@section('titulo', 'Historia Clínica - JM Dental')
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
                    <h1 class="text-xl font-bold text-slate-900">Historia clínica — {{ $usuario->name }}</h1>
                    <a href="{{ route('odontologo.historia.pdf', $usuario->id) }}" target="_blank"
                        class="ml-auto flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-semibold">
                        <span class="material-symbols-outlined text-base">download</span>
                        Descargar Formulario 033
                    </a>
                </div>

                @if(session('mensaje'))
                    <div class="bg-green-50 border border-green-200 text-green-700 text-sm font-medium px-4 py-3 rounded-xl mb-6">
                        {{ session('mensaje') }}
                    </div>
                @endif

                {{-- INFO PACIENTE --}}
                <div class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 mb-6 flex items-center gap-3">
                    <span class="material-symbols-outlined text-blue-500">person</span>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ $usuario->name }}</p>
                        <p class="text-xs text-slate-500">
                            {{ $usuario->email }}
                            @if($paciente?->edad) · {{ $paciente->edad }} años @endif
                            @if($paciente?->tipo_denticion)
                                · <span class="font-medium {{ $paciente->color_denticion }} px-1.5 rounded">{{ $paciente->tipo_denticion }}</span>
                            @endif
                        </p>
                    </div>
                    <span class="ml-auto text-xs text-slate-400">
                        Apertura: {{ $historia?->fecha_apertura?->format('d/m/Y') ?? '—' }}
                    </span>
                </div>

                {{-- DATOS INICIALES EDITABLES --}}
                <form action="{{ route('odontologo.historia.update', $usuario->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
                        <h3 class="font-bold text-slate-900">B. Motivo de consulta y enfermedad actual</h3>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1.5">Motivo de consulta</label>
                            <input type="text" name="motivo_consulta" required
                                value="{{ old('motivo_consulta', $historia?->motivo_consulta) }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1.5">Enfermedad actual</label>
                            <textarea name="enfermedad_actual" rows="3"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">{{ old('enfermedad_actual', $historia?->enfermedad_actual) }}</textarea>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
                        <h3 class="font-bold text-slate-900">D/E. Antecedentes patológicos</h3>
                        @php
                            $itemsPersonales = [
                                1 => 'Alergia antibiótico', 2 => 'Alergia anestesia', 3 => 'Hemorragias',
                                4 => 'VIH/SIDA', 5 => 'Tuberculosis', 6 => 'Asma', 7 => 'Diabetes',
                                8 => 'Hipertensión arterial', 9 => 'Enf. cardiaca', 10 => 'Otro',
                            ];
                            $itemsFamiliares = [
                                1 => 'Cardiopatía', 2 => 'Hipertensión arterial', 3 => 'Enf. C. vascular',
                                4 => 'Endócrino metabólico', 5 => 'Cáncer', 6 => 'Tuberculosis',
                                7 => 'Enf. mental', 8 => 'Enf. infecciosa', 9 => 'Mal formación', 10 => 'Otro',
                            ];
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Antecedentes personales — marcar los que apliquen</label>
                                <div class="grid grid-cols-2 gap-1.5 mb-2">
                                    @foreach($itemsPersonales as $num => $label)
                                        <label class="flex items-center gap-1.5 text-xs text-slate-600">
                                            <input type="checkbox" name="antecedentes_personales_check[]" value="{{ $num }}"
                                                {{ in_array($num, old('antecedentes_personales_check', $historia?->antecedentes_personales_check ?? [])) ? 'checked' : '' }}
                                                class="rounded border-slate-300 text-blue-600 focus:ring-blue-400">
                                            {{ $num }}. {{ $label }}
                                        </label>
                                    @endforeach
                                </div>
                                <textarea name="antecedentes_personales" rows="4"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">{{ old('antecedentes_personales', $historia?->antecedentes_personales) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Antecedentes familiares — marcar los que apliquen</label>
                                <div class="grid grid-cols-2 gap-1.5 mb-2">
                                    @foreach($itemsFamiliares as $num => $label)
                                        <label class="flex items-center gap-1.5 text-xs text-slate-600">
                                            <input type="checkbox" name="antecedentes_familiares_check[]" value="{{ $num }}"
                                                {{ in_array($num, old('antecedentes_familiares_check', $historia?->antecedentes_familiares_check ?? [])) ? 'checked' : '' }}
                                                class="rounded border-slate-300 text-blue-600 focus:ring-blue-400">
                                            {{ $num }}. {{ $label }}
                                        </label>
                                    @endforeach
                                </div>
                                <textarea name="antecedentes_familiares" rows="4"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">{{ old('antecedentes_familiares', $historia?->antecedentes_familiares) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
                        <h3 class="font-bold text-slate-900">F. Constantes vitales</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Temperatura °C</label>
                                <input type="text" name="temperatura"
                                    value="{{ old('temperatura', $historia?->temperatura) }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Pulso / min</label>
                                <input type="text" name="pulso"
                                    value="{{ old('pulso', $historia?->pulso) }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Frec. respiratoria / min</label>
                                <input type="text" name="frecuencia_respiratoria"
                                    value="{{ old('frecuencia_respiratoria', $historia?->frecuencia_respiratoria) }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Presión arterial (mmHg)</label>
                                <input type="text" name="presion_arterial"
                                    value="{{ old('presion_arterial', $historia?->presion_arterial) }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
                        <h3 class="font-bold text-slate-900">G. Examen del sistema estomatognático</h3>
                        @php
                            $itemsExamen = [
                                1 => 'Labios', 2 => 'Mejillas', 3 => 'Maxilar superior', 4 => 'Maxilar inferior',
                                5 => 'Lengua', 6 => 'Paladar', 7 => 'Piso de la boca', 8 => 'Carrillos',
                                9 => 'Glándulas salivales', 10 => 'Oro faringe', 11 => 'A.T.M.',
                                12 => 'Ganglios', 13 => 'Otros',
                            ];
                        @endphp
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1.5">Marcar la región afectada</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-1.5 mb-2">
                                @foreach($itemsExamen as $num => $label)
                                    <label class="flex items-center gap-1.5 text-xs text-slate-600">
                                        <input type="checkbox" name="examen_estomatognatico_check[]" value="{{ $num }}"
                                            {{ in_array($num, old('examen_estomatognatico_check', $historia?->examen_estomatognatico_check ?? [])) ? 'checked' : '' }}
                                            class="rounded border-slate-300 text-blue-600 focus:ring-blue-400">
                                        {{ $num }}. {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1.5">Examen extraoral</label>
                            <textarea name="examen_extraoral" rows="3"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">{{ old('examen_extraoral', $historia?->examen_extraoral) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1.5">Examen intraoral</label>
                            <textarea name="examen_intraoral" rows="3"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">{{ old('examen_intraoral', $historia?->examen_intraoral) }}</textarea>
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
                                        $hosExaminada = $historia?->hos_examinada ?? [];
                                    @endphp
                                    @for($i = 0; $i < 6; $i++)
                                        @php
                                            $p1 = $col1[$i];
                                            $p2 = $col2[$i];
                                            $p3 = $col3[$i];
                                            $rowKey = $i;
                                            $vPlaca = old("hos_placa.$rowKey", ($historia?->hos_placa[(string)$rowKey] ?? $historia?->hos_placa[$rowKey] ?? ''));
                                            $vCalculo = old("hos_calculo.$rowKey", ($historia?->hos_calculo[(string)$rowKey] ?? $historia?->hos_calculo[$rowKey] ?? ''));
                                            $vGingivitis = old("hos_gingivitis.$rowKey", ($historia?->hos_gingivitis[(string)$rowKey] ?? $historia?->hos_gingivitis[$rowKey] ?? ''));
                                        @endphp
                                        <tr>
                                            <td class="px-2 py-2 font-bold text-blue-500 text-center border-r border-slate-200">{{ $p1 }}</td>
                                            <td class="px-2 py-1.5 text-center border-r border-slate-200">
                                                <input type="checkbox" name="hos_examinada[{{ $p1 }}]" value="1"
                                                    {{ old("hos_examinada.$p1", $hosExaminada[(string)$p1] ?? $hosExaminada[$p1] ?? false) ? 'checked' : '' }}
                                                    class="w-3.5 h-3.5 accent-blue-500 cursor-pointer">
                                            </td>
                                            <td class="px-2 py-2 font-bold text-purple-500 text-center border-r border-slate-200">{{ $p2 }}</td>
                                            <td class="px-2 py-1.5 text-center border-r border-slate-200">
                                                <input type="checkbox" name="hos_examinada[{{ $p2 }}]" value="1"
                                                    {{ old("hos_examinada.$p2", $hosExaminada[(string)$p2] ?? $hosExaminada[$p2] ?? false) ? 'checked' : '' }}
                                                    class="w-3.5 h-3.5 accent-purple-500 cursor-pointer">
                                            </td>
                                            <td class="px-2 py-2 font-bold text-green-600 text-center border-r border-slate-200">{{ $p3 }}</td>
                                            <td class="px-2 py-1.5 text-center border-r border-slate-200">
                                                <input type="checkbox" name="hos_examinada[{{ $p3 }}]" value="1"
                                                    {{ old("hos_examinada.$p3", $hosExaminada[(string)$p3] ?? $hosExaminada[$p3] ?? false) ? 'checked' : '' }}
                                                    class="w-3.5 h-3.5 accent-green-500 cursor-pointer">
                                            </td>
                                            <td class="px-2 py-1.5 text-center border-r border-slate-200">
                                                <select name="hos_placa[{{ $rowKey }}]"
                                                    class="bg-slate-50 border border-slate-200 rounded px-1 py-1 text-xs outline-none focus:border-blue-400 w-12">
                                                    <option value="">—</option>
                                                    @foreach(['0','1','2','3','9'] as $v)
                                                        <option value="{{ $v }}" {{ $vPlaca == $v ? 'selected' : '' }}>{{ $v }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-2 py-1.5 text-center border-r border-slate-200">
                                                <select name="hos_calculo[{{ $rowKey }}]"
                                                    class="bg-slate-50 border border-slate-200 rounded px-1 py-1 text-xs outline-none focus:border-blue-400 w-12">
                                                    <option value="">—</option>
                                                    @foreach(['0','1','2','3'] as $v)
                                                        <option value="{{ $v }}" {{ $vCalculo == $v ? 'selected' : '' }}>{{ $v }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-2 py-1.5 text-center">
                                                <select name="hos_gingivitis[{{ $rowKey }}]"
                                                    class="bg-slate-50 border border-slate-200 rounded px-1 py-1 text-xs outline-none focus:border-blue-400 w-12">
                                                    <option value="">—</option>
                                                    @foreach(['0','1'] as $v)
                                                        <option value="{{ $v }}" {{ $vGingivitis == $v ? 'selected' : '' }}>{{ $v }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Enfermedad periodontal</label>
                                <select name="enfermedad_periodontal"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                                    <option value="">Selecciona</option>
                                    @foreach(['Leve','Moderada','Severa'] as $v)
                                        <option value="{{ $v }}" {{ old('enfermedad_periodontal', $historia?->enfermedad_periodontal) == $v ? 'selected' : '' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Tipo de oclusión</label>
                                <select name="tipo_oclusion"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                                    <option value="">Selecciona</option>
                                    @foreach(['Angle I','Angle II','Angle III'] as $v)
                                        <option value="{{ $v }}" {{ old('tipo_oclusion', $historia?->tipo_oclusion) == $v ? 'selected' : '' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Nivel de fluorosis</label>
                                <select name="nivel_fluorosis"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                                    <option value="">Selecciona</option>
                                    @foreach(['Leve','Moderada','Severa'] as $v)
                                        <option value="{{ $v }}" {{ old('nivel_fluorosis', $historia?->nivel_fluorosis) == $v ? 'selected' : '' }}>{{ $v }}</option>
                                    @endforeach
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
                                    @foreach(['c' => 'C — Cariadas', 'p' => 'P — Perdidas', 'o' => 'O — Obturadas'] as $k => $label)
                                        <div>
                                            <label class="block text-xs font-medium text-slate-500 mb-1.5">{{ $label }}</label>
                                            <input type="number" name="cpo_{{ $k }}" min="0" max="32"
                                                value="{{ old('cpo_'.$k, $historia?->{'cpo_'.$k} ?? 0) }}"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-xs text-slate-400 mt-2">Total CPO: <span id="total-cpo" class="font-bold text-slate-700">{{ ($historia?->cpo_c ?? 0) + ($historia?->cpo_p ?? 0) + ($historia?->cpo_o ?? 0) }}</span></p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-600 mb-3">ceo — Dentición temporal</p>
                                <div class="grid grid-cols-3 gap-3">
                                    @foreach(['c' => 'c — Cariadas', 'e' => 'e — Extraídas', 'o' => 'o — Obturadas'] as $k => $label)
                                        <div>
                                            <label class="block text-xs font-medium text-slate-500 mb-1.5">{{ $label }}</label>
                                            <input type="number" name="ceo_{{ $k }}" min="0" max="20"
                                                value="{{ old('ceo_'.$k, $historia?->{'ceo_'.$k} ?? 0) }}"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-xs text-slate-400 mt-2">Total ceo: <span id="total-ceo" class="font-bold text-slate-700">{{ ($historia?->ceo_c ?? 0) + ($historia?->ceo_e ?? 0) + ($historia?->ceo_o ?? 0) }}</span></p>
                            </div>
                        </div>
                    </div>


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
                                        @php
                                            $diag = $historia?->diagnosticos[$i] ?? [];
                                        @endphp
                                        <tr>
                                            <td class="px-3 py-2 text-xs font-bold text-slate-400">{{ $i + 1 }}.</td>
                                            <td class="px-3 py-2">
                                                <input type="text" name="diagnosticos[{{ $i }}][descripcion]"
                                                    value="{{ old("diagnosticos.$i.descripcion", $diag['descripcion'] ?? '') }}"
                                                    placeholder="Descripción del diagnóstico..."
                                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2 py-1.5 text-sm outline-none focus:border-blue-400">
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                <input type="text" name="diagnosticos[{{ $i }}][cie]"
                                                    value="{{ old("diagnosticos.$i.cie", $diag['cie'] ?? '') }}"
                                                    placeholder="K02.1"
                                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2 py-1.5 text-sm outline-none focus:border-blue-400 text-center">
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                <input type="radio" name="diagnosticos[{{ $i }}][tipo]" value="pre"
                                                    {{ old("diagnosticos.$i.tipo", $diag['tipo'] ?? '') == 'pre' ? 'checked' : '' }}
                                                    class="w-4 h-4 accent-blue-500 cursor-pointer">
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                <input type="radio" name="diagnosticos[{{ $i }}][tipo]" value="def"
                                                    {{ old("diagnosticos.$i.tipo", $diag['tipo'] ?? '') == 'def' ? 'checked' : '' }}
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
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1.5">Descripción del pedido</label>
                            <textarea name="examenes_pedido" rows="3"
                                placeholder="Describa los exámenes complementarios solicitados..."
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">{{ old('examenes_pedido', $historia?->examenes_pedido) }}</textarea>
                        </div>
                    </div>

                    {{-- M. INFORME DE EXÁMENES --}}
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
                        <h3 class="font-bold text-slate-900">M. Informe de exámenes</h3>
                        <div class="flex flex-wrap gap-4 mb-3">
                            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                                <input type="checkbox" name="examenes_biometria" value="1"
                                    {{ old('examenes_biometria', $historia?->examenes_biometria) ? 'checked' : '' }}
                                    class="w-4 h-4 accent-blue-500">
                                Biometría
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                                <input type="checkbox" name="examenes_quimica" value="1"
                                    {{ old('examenes_quimica', $historia?->examenes_quimica) ? 'checked' : '' }}
                                    class="w-4 h-4 accent-blue-500">
                                Química sanguínea
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                                <input type="checkbox" name="examenes_rayos_x" value="1"
                                    {{ old('examenes_rayos_x', $historia?->examenes_rayos_x) ? 'checked' : '' }}
                                    class="w-4 h-4 accent-blue-500">
                                Rayos-X
                            </label>
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-slate-600">Otros:</span>
                                <input type="text" name="examenes_otros"
                                    value="{{ old('examenes_otros', $historia?->examenes_otros) }}"
                                    placeholder="Especifique..."
                                    class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-blue-400">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1.5">Informe / resultado de exámenes</label>
                            <textarea name="examenes_informe" rows="4"
                                placeholder="Registre los resultados o informe de los exámenes realizados..."
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">{{ old('examenes_informe', $historia?->examenes_informe) }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2.5 rounded-lg text-sm font-semibold">
                            Actualizar historia clínica
                        </button>
                    </div>
                </form>

                {{-- SESIONES / TRATAMIENTOS --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6 mt-6">
                    <h3 class="font-bold text-slate-900 mb-4">P. Sesiones de tratamiento</h3>
                    @forelse($tratamientos as $i => $t)
                        <div class="border border-slate-100 rounded-xl p-4 mb-3">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-bold text-slate-900">
                                    Sesión {{ $i + 1 }} — {{ $t->nombre }}
                                </span>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($t->fecha_tratamiento)->format('d/m/Y') }}</span>
                                    <span class="text-xs font-bold px-2 py-0.5 rounded-full
                                        {{ $t->estado === 'completado' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $t->estado === 'completado' ? 'Completado' : 'En proceso' }}
                                    </span>
                                </div>
                            </div>
                            @if($t->descripcion)
                                <p class="text-xs text-slate-600 mb-1">{{ $t->descripcion }}</p>
                            @endif
                            @if($t->piezas->count() > 0)
                                <div class="flex flex-wrap gap-1 mt-2">
                                    @foreach($t->piezas as $p)
                                        <span class="text-xs bg-blue-50 text-blue-600 border border-blue-100 px-2 py-0.5 rounded-full">
                                            Pieza {{ $p->pieza_numero }} · {{ ucfirst($p->cara) }}
                                            @if($p->procedimiento) · {{ $p->procedimiento }} @endif
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs text-slate-400">
                                    Odontólogo: {{ $t->cita?->odontologo?->user?->name ?? '—' }}
                                </span>
                                @if($t->costo > 0)
                                    <span class="text-xs font-bold text-slate-700">${{ number_format($t->costo, 2) }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 text-center py-4">Sin sesiones registradas aún.</p>
                    @endforelse
                </div>

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
</script>
@endsection