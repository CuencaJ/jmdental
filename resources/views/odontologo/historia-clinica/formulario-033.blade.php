<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Formulario 033 MSP - {{ $paciente->user->name ?? '' }}</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: DejaVu Sans, sans-serif; font-size:8px; color:#000; background:#fff; }
.page { width:100%; padding:10px; }
.page-break { page-break-after:always; }
/* ENCABEZADO */
.enc { border:1px solid #000; display:table; width:100%; margin-bottom:5px; }
.enc-logo { display:table-cell; width:55px; border-right:1px solid #000; padding:4px; text-align:center; vertical-align:middle; }
.enc-titulo { display:table-cell; padding:4px; text-align:center; vertical-align:middle; }
.enc-titulo h1 { font-size:9px; font-weight:bold; text-transform:uppercase; }
.enc-titulo p { font-size:7px; }
.enc-datos { display:table-cell; width:170px; border-left:1px solid #000; padding:4px; font-size:7px; vertical-align:top; }
/* SECCIONES */
.sec { border:1px solid #000; margin-bottom:3px; }
.sec-tit { background:#c8c8c8; padding:2px 4px; font-weight:bold; font-size:7.5px; text-transform:uppercase; border-bottom:1px solid #000; }
.fila { display:table; width:100%; }
.celda { display:table-cell; padding:2px 4px; border-right:1px solid #ccc; vertical-align:top; }
.celda:last-child { border-right:none; }
.lbl { font-size:6px; color:#555; display:block; text-transform:uppercase; margin-bottom:1px; }
.val { font-size:8px; font-weight:bold; display:block; min-height:11px; }
.linea { border-bottom:1px solid #333; min-height:11px; font-size:7.5px; font-weight:bold; display:block; padding-bottom:1px; }
/* TABLA TRATAMIENTOS */
table { width:100%; border-collapse:collapse; }
th { background:#c8c8c8; border:1px solid #000; padding:2px; font-size:6.5px; text-align:center; }
td { border:1px solid #aaa; padding:2px 3px; font-size:7px; vertical-align:top; min-height:12px; }
.pie { margin-top:6px; font-size:6.5px; text-align:center; color:#666; border-top:1px solid #ccc; padding-top:3px; }
.w10 { width:10%; } .w15 { width:15%; } .w20 { width:20%; }
.w25 { width:25%; } .w33 { width:33.33%; } .w50 { width:50%; }
.w100 { width:100%; }
</style>
</head>
<body>
@php
    $pac      = $paciente->paciente ?? null;
    $od       = $tratamientos->first()?->cita?->odontologo ?? null;
    $historia = $historia ?? null;
    $sesiones = $tratamientos;
@endphp

{{-- ===== PÁGINA 1 ===== --}}
<div class="page">

    {{-- ENCABEZADO --}}
    <div class="enc">
        <div class="enc-logo">
            <div style="font-size:9px;font-weight:bold;border:1px solid #000;padding:2px;">MSP</div>
            <div style="font-size:6px;margin-top:2px;">ECUADOR</div>
        </div>
        <div class="enc-titulo">
            <h1>Ministerio de Salud Pública del Ecuador</h1>
            <p>Historia Clínica Única — Odontología</p>
            <p style="font-weight:bold;font-size:8px;">SNS-MSP / HCU-FORM.033/ 2021</p>
        </div>
        <div class="enc-datos">
            <span class="lbl">Establecimiento de salud</span>
            <span class="linea">JM Dental</span>
            <span class="lbl" style="margin-top:2px;">N° Historia clínica</span>
            <span class="linea">#BS-{{ str_pad($paciente->id, 4, '0', STR_PAD_LEFT) }}</span>
            <span class="lbl" style="margin-top:2px;">Fecha de apertura</span>
            <span class="linea">{{ $historia?->fecha_apertura?->format('d/m/Y') ?? now()->format('d/m/Y') }}</span>
            <span class="lbl" style="margin-top:2px;">No. hoja</span>
            <span class="linea">1</span>
        </div>
    </div>

    {{-- A. DATOS DEL PACIENTE --}}
    <div class="sec">
        <div class="sec-tit">A. Datos del establecimiento y paciente</div>
        <div class="fila">
            <div class="celda w50">
                <span class="lbl">Primer apellido / Segundo apellido / Primer nombre / Segundo nombre</span>
                <span class="val">{{ $paciente->user->name ?? '—' }}</span>
            </div>
            <div class="celda w25">
                <span class="lbl">N° cédula de identidad</span>
                <span class="val">{{ $pac?->cedula ?? '—' }}</span>
            </div>
            <div class="celda w15">
                <span class="lbl">Fecha de nacimiento</span>
                <span class="val">{{ $pac?->fecha_nacimiento?->format('d/m/Y') ?? '—' }}</span>
            </div>
            <div class="celda">
                <span class="lbl">Edad</span>
                <span class="val">{{ $pac?->edad ?? '—' }} a</span>
            </div>
        </div>
        <div class="fila">
            <div class="celda w50">
                <span class="lbl">Dirección domiciliaria</span>
                <span class="val">{{ $pac?->direccion ?? '—' }}</span>
            </div>
            <div class="celda w25">
                <span class="lbl">Teléfono</span>
                <span class="val">{{ $paciente->user->telefono ?? $pac?->telefono ?? '—' }}</span>
            </div>
            <div class="celda w25">
                <span class="lbl">Correo electrónico</span>
                <span class="val">{{ $paciente->user->email ?? '—' }}</span>
            </div>
        </div>
        <div class="fila">
            <div class="celda w33">
                <span class="lbl">Tipo de sangre</span>
                <span class="val">{{ $pac?->tipo_sangre ?? '—' }}</span>
            </div>
            <div class="celda w33">
                <span class="lbl">Contacto de emergencia</span>
                <span class="val">{{ $pac?->contacto_emergencia ?? '—' }}</span>
            </div>
            <div class="celda w33">
                <span class="lbl">Teléfono de emergencia</span>
                <span class="val">{{ $pac?->telefono_emergencia ?? '—' }}</span>
            </div>
        </div>
    </div>

    {{-- B. MOTIVO DE CONSULTA --}}
    <div class="sec">
        <div class="sec-tit">B. Motivo de consulta</div>
        <div style="padding:3px 4px;min-height:16px;">
            <span style="font-size:8px;">{{ $historia?->motivo_consulta ?? '—' }}</span>
        </div>
    </div>

    {{-- C. ENFERMEDAD ACTUAL --}}
    <div class="sec">
        <div class="sec-tit">C. Enfermedad actual</div>
        <div style="padding:3px 4px;min-height:20px;">
            <span style="font-size:8px;">{{ $historia?->enfermedad_actual ?? '—' }}</span>
        </div>
    </div>

    {{-- D. ANTECEDENTES PERSONALES --}}
    <div class="sec">
        <div class="sec-tit">D. Antecedentes patológicos personales</div>
        <div style="padding:3px 4px;min-height:20px;">
            <span style="font-size:8px;">{{ $historia?->antecedentes_personales ?? $pac?->enfermedades_cronicas ?? 'Ninguno referido' }}</span>
        </div>
        <div style="padding:2px 4px;border-top:1px solid #ccc;">
            <span class="lbl">Alergias:</span>
            <span style="font-size:8px;">{{ $pac?->alergias ?? 'Ninguna referida' }}</span>
        </div>
        <div style="padding:2px 4px;border-top:1px solid #ccc;">
            <span class="lbl">Medicamentos actuales:</span>
            <span style="font-size:8px;">{{ $pac?->medicamentos_actuales ?? 'Ninguno' }}</span>
        </div>
    </div>

    {{-- E. ANTECEDENTES FAMILIARES --}}
    <div class="sec">
        <div class="sec-tit">E. Antecedentes patológicos familiares</div>
        <div style="padding:3px 4px;min-height:16px;">
            <span style="font-size:8px;">{{ $historia?->antecedentes_familiares ?? 'Ninguno referido' }}</span>
        </div>
    </div>

    {{-- F. CONSTANTES VITALES --}}
    <div class="sec">
        <div class="sec-tit">F. Constantes vitales</div>
        <div class="fila">
            <div class="celda w25">
                <span class="lbl">Temperatura °C</span>
                <span class="val">{{ $historia?->temperatura ?? '—' }}</span>
            </div>
            <div class="celda w25">
                <span class="lbl">Pulso / min</span>
                <span class="val">{{ $historia?->pulso ?? '—' }}</span>
            </div>
            <div class="celda w25">
                <span class="lbl">Frec. respiratoria / min</span>
                <span class="val">{{ $historia?->frecuencia_respiratoria ?? '—' }}</span>
            </div>
            <div class="celda w25">
                <span class="lbl">Presión arterial (mmHg)</span>
                <span class="val">{{ $historia?->presion_arterial ?? '—' }}</span>
            </div>
        </div>
    </div>

    {{-- G. EXAMEN ESTOMATOGNÁTICO --}}
    <div class="sec">
        <div class="sec-tit">G. Examen del sistema estomatognático</div>
        <div class="fila">
            <div class="celda w50">
                <span class="lbl">Examen extraoral</span>
                <span style="font-size:7.5px;display:block;min-height:16px;">{{ $historia?->examen_extraoral ?? '—' }}</span>
            </div>
            <div class="celda w50">
                <span class="lbl">Examen intraoral</span>
                <span style="font-size:7.5px;display:block;min-height:16px;">{{ $historia?->examen_intraoral ?? '—' }}</span>
            </div>
        </div>
    </div>

    {{-- H. ODONTOGRAMA --}}
    <div class="sec">
        <div class="sec-tit">H. Odontograma — Piezas registradas</div>
        <div style="padding:3px 4px;">
            <table>
                <thead>
                    <tr>
                        <th>Pieza</th>
                        <th>Dentición</th>
                        <th>Cara</th>
                        <th>Diagnóstico CIE-10</th>
                        <th>Procedimiento</th>
                        <th>Estado</th>
                        <th>Sesión</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $todasPiezas = collect();
                        $sesionNum = 1;
                        foreach($sesiones as $t) {
                            foreach($t->piezas as $p) {
                                $todasPiezas->push(['pieza' => $p, 'sesion' => $sesionNum, 'trat' => $t]);
                            }
                            $sesionNum++;
                        }
                    @endphp
                    @forelse($todasPiezas as $item)
                        @php $p = $item['pieza']; @endphp
                        <tr>
                            <td style="text-align:center;font-weight:bold;">{{ $p->pieza_numero }}</td>
                            <td>{{ $p->tipo_denticion === 'permanente' ? 'Adulto' : 'Infantil' }}</td>
                            <td>{{ ucfirst($p->cara ?? '—') }}</td>
                            <td>{{ $p->diagnostico ?? '—' }}</td>
                            <td>{{ $p->procedimiento ?? '—' }}</td>
                            <td style="text-align:center;">{{ $p->ausente ? 'Ausente' : 'Presente' }}</td>
                            <td style="text-align:center;">{{ $item['sesion'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" style="text-align:center;color:#999;">Sin piezas registradas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="pie">SNS-MSP / HCU-form.033/ 2021 &nbsp;|&nbsp; ODONTOLOGÍA (1)</div>
</div>

{{-- ===== PÁGINA 2 ===== --}}
<div class="page-break"></div>
<div class="page">

    {{-- ENCABEZADO PÁGINA 2 --}}
    <div class="enc">
        <div class="enc-logo">
            <div style="font-size:9px;font-weight:bold;border:1px solid #000;padding:2px;">MSP</div>
            <div style="font-size:6px;margin-top:2px;">ECUADOR</div>
        </div>
        <div class="enc-titulo">
            <h1>Ministerio de Salud Pública del Ecuador</h1>
            <p>Historia Clínica Única — Odontología &nbsp;|&nbsp; Plan de Tratamiento y Evolución</p>
            <p style="font-weight:bold;font-size:8px;">SNS-MSP / HCU-FORM.033/ 2021</p>
        </div>
        <div class="enc-datos">
            <span class="lbl">Paciente</span>
            <span class="linea">{{ $paciente->user->name ?? '—' }}</span>
            <span class="lbl" style="margin-top:2px;">N° Historia</span>
            <span class="linea">#BS-{{ str_pad($paciente->id, 4, '0', STR_PAD_LEFT) }}</span>
            <span class="lbl" style="margin-top:2px;">No. hoja</span>
            <span class="linea">2</span>
        </div>
    </div>

    {{-- N. DIAGNÓSTICO --}}
    <div class="sec">
        <div class="sec-tit">N. Diagnóstico</div>
        <div style="padding:3px 4px;min-height:16px;">
            <span style="font-size:8px;">{{ $historia?->diagnostico_inicial ?? '—' }}</span>
        </div>
    </div>

    {{-- P. TRATAMIENTO / SESIONES --}}
    <div class="sec">
        <div class="sec-tit">P. Tratamiento — Evolución por sesiones</div>
        <table>
            <thead>
                <tr>
                    <th style="width:5%;">Ses.</th>
                    <th style="width:9%;">Fecha</th>
                    <th style="width:20%;">Diagnóstico y complicaciones</th>
                    <th style="width:25%;">Procedimientos realizados</th>
                    <th style="width:20%;">Prescripciones / Observaciones</th>
                    <th style="width:8%;">Costo</th>
                    <th style="width:13%;">Odontólogo / Firma</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sesiones as $i => $t)
                    <tr>
                        <td style="text-align:center;font-weight:bold;">{{ $i + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($t->fecha_tratamiento)->format('d/m/Y') }}</td>
                        <td>
                            {{ $t->descripcion ?? '—' }}
                            @if($t->piezas->count() > 0)
                                <br><small>Piezas: {{ $t->piezas->pluck('pieza_numero')->unique()->join(', ') }}</small>
                            @endif
                        </td>
                        <td>
                            {{ $t->nombre }}
                            @if($t->piezas->where('procedimiento', '!=', null)->count() > 0)
                                <br><small>{{ $t->piezas->pluck('procedimiento')->filter()->unique()->join(', ') }}</small>
                            @endif
                        </td>
                        <td>{{ $t->observaciones ?? '—' }}</td>
                        <td style="text-align:right;">${{ number_format($t->costo, 2) }}</td>
                        <td style="font-size:6.5px;">{{ $t->cita?->odontologo?->user?->name ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center;color:#999;">Sin sesiones registradas</td></tr>
                @endforelse
                {{-- Filas vacías --}}
                @for($i = 0; $i < max(0, 4 - $sesiones->count()); $i++)
                    <tr style="height:18px;"><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                @endfor
            </tbody>
        </table>
        <div style="text-align:right;padding:2px 4px;font-size:7.5px;font-weight:bold;border-top:1px solid #000;">
            Total acumulado: ${{ number_format($sesiones->sum('costo'), 2) }}
        </div>
    </div>

    {{-- O. DATOS DEL PROFESIONAL --}}
    <div class="sec">
        <div class="sec-tit">O. Datos del profesional responsable</div>
        <div class="fila">
            <div class="celda w33">
                <span class="lbl">Nombre del odontólogo</span>
                <span class="val">{{ $od?->user?->name ?? '—' }}</span>
            </div>
            <div class="celda w33">
                <span class="lbl">N° licencia / registro MSP</span>
                <span class="val">{{ $od?->numero_licencia ?? '—' }}</span>
            </div>
            <div class="celda w33">
                <span class="lbl">Especialidad</span>
                <span class="val">{{ $od?->especialidad ?? 'Odontología General' }}</span>
            </div>
        </div>
        <div class="fila">
            <div class="celda w50" style="min-height:45px;">
                <span class="lbl">Firma del profesional</span>
                <div style="border-bottom:1px solid #000;margin-top:30px;"></div>
            </div>
            <div class="celda w50" style="min-height:45px;">
                <span class="lbl">Sello del establecimiento</span>
                <div style="border:1px dashed #aaa;min-height:38px;margin-top:4px;"></div>
            </div>
        </div>
    </div>

    <div class="pie">
        SNS-MSP / HCU-form.033/ 2021 &nbsp;|&nbsp; ODONTOLOGÍA (2) &nbsp;|&nbsp;
        Generado: {{ now()->format('d/m/Y H:i') }} &nbsp;|&nbsp; Sistema JM Dental
    </div>
</div>
</body>
</html>