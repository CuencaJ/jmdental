<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Formulario 033 MSP</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: DejaVu Sans, sans-serif; font-size:7px; color:#000; }
.page { padding:6px 8px; }
.page-break { page-break-after:always; }

/* Tablas generales */
table { width:100%; border-collapse:collapse; }
td, th { vertical-align:top; }

/* Encabezado */
.enc td { border:1px solid #000; padding:2px 3px; }
.enc-logo { width:50px; text-align:center; }
.enc-titulo { text-align:center; }
.enc-titulo h1 { font-size:8px; font-weight:bold; text-transform:uppercase; }
.enc-titulo p { font-size:6.5px; }
.enc-right { width:190px; font-size:6.5px; }

/* Secciones */
.sec { border:1px solid #000; margin-bottom:2px; }
.sec-tit { background:#b8b8b8; padding:1.5px 3px; font-weight:bold; font-size:7px; text-transform:uppercase; border-bottom:1px solid #000; }
.row-table td { border:1px solid #ccc; padding:1.5px 3px; }
.row-table th { border:1px solid #000; padding:1.5px 3px; background:#ddd; font-size:6.5px; }
.lbl { font-size:5.5px; color:#444; font-weight:bold; text-transform:uppercase; display:block; margin-bottom:1px; }
.val { font-size:7px; font-weight:bold; display:block; border-bottom:1px solid #555; min-height:9px; padding-bottom:1px; }
.linea { border-bottom:1px solid #555; min-height:9px; display:block; font-size:7px; font-weight:bold; }
.content-area { padding:2px 3px; min-height:12px; font-size:7px; }

/* Checkboxes antecedentes */
.check-tbl td { border:1px solid #ccc; padding:1px 2px; text-align:center; font-size:5.5px; width:10%; }
.check-box { width:7px; height:7px; border:1px solid #000; display:inline-block; }

/* Odontograma */
.pieza { display:inline-block; width:13px; height:13px; border:1px solid #000; font-size:5.5px; font-weight:bold; text-align:center; line-height:13px; margin:0 0.3px; }
.pieza-ausente { background:#000; color:#fff; }
.pieza-tratado { background:#aaaaaa; }

/* Tratamiento */
.trat-tbl td { border:1px solid #000; padding:2px 3px; font-size:6.5px; }
.trat-tbl th { border:1px solid #000; padding:2px 3px; font-size:6.5px; background:#b8b8b8; font-weight:bold; text-align:center; }
.sesion-hdr { background:#dddddd; font-weight:bold; font-size:6.5px; }

.pie { font-size:6px; color:#555; text-align:center; border-top:1px solid #ccc; padding-top:2px; margin-top:3px; }
</style>
</head>
<body>
@php
    $pac      = $paciente->paciente ?? null;
    $hist     = $historia ?? null;
    $sesiones = $tratamientos;
    $od       = $sesiones->first()?->cita?->odontologo ?? null;

    $todasPiezas = collect();
    $sn = 1;
    foreach($sesiones as $t) {
        foreach($t->piezas as $p) {
            $todasPiezas->push(['pieza'=>$p,'sesion'=>$sn,'trat'=>$t]);
        }
        $sn++;
    }
    $numsPerm = $todasPiezas->filter(fn($i)=>$i['pieza']->tipo_denticion==='permanente')->pluck('pieza.pieza_numero')->unique()->toArray();
    $numsTemp = $todasPiezas->filter(fn($i)=>$i['pieza']->tipo_denticion==='temporal')->pluck('pieza.pieza_numero')->unique()->toArray();
    $ausentes = $todasPiezas->filter(fn($i)=>$i['pieza']->ausente)->pluck('pieza.pieza_numero')->unique()->toArray();

    $supPerm = [18,17,16,15,14,13,12,11,21,22,23,24,25,26,27,28];
    $infPerm = [48,47,46,45,44,43,42,41,31,32,33,34,35,36,37,38];
    $supTemp = [55,54,53,52,51,61,62,63,64,65];
    $infTemp = [85,84,83,82,81,71,72,73,74,75];

    $nombre = $paciente->user->name ?? '—';
    $partes = explode(' ', $nombre);
@endphp

{{-- ===== PÁGINA 1 ===== --}}
<div class="page">

{{-- ENCABEZADO --}}
<table class="enc" style="margin-bottom:2px;">
    <tr>
        <td class="enc-logo" rowspan="2" style="width:50px;border:1.5px solid #000;">
            <div style="font-size:10px;font-weight:bold;border:1.5px solid #000;padding:2px 5px;display:inline-block;">MSP</div>
            <div style="font-size:5.5px;margin-top:2px;">MINISTERIO DE<br>SALUD PÚBLICA</div>
        </td>
        <td class="enc-titulo" rowspan="2" style="border:1.5px solid #000;padding:4px;">
            <h1>Ministerio de Salud Pública</h1>
            <p style="margin:2px 0;">Historia Clínica Única — Odontología</p>
            <p style="font-weight:bold;font-size:8.5px;margin-top:2px;">SNS-MSP / HCU-FORM.033 / 2021</p>
        </td>
        <td style="border:1.5px solid #000;padding:1.5px 3px;width:190px;font-size:6.5px;">
            <span class="lbl">Institución del sistema</span>
            <span class="linea">IESS-SSC</span>
        </td>
        <td style="border:1.5px solid #000;padding:1.5px 3px;width:60px;font-size:6.5px;">
            <span class="lbl">Unicódigo</span>
            <span class="linea">—</span>
        </td>
        <td style="border:1.5px solid #000;padding:1.5px 3px;font-size:6.5px;">
            <span class="lbl">Establecimiento de salud</span>
            <span class="linea">JM Dental</span>
        </td>
    </tr>
    <tr>
        <td style="border:1.5px solid #000;padding:1.5px 3px;font-size:6.5px;">
            <span class="lbl">N° Historia Clínica Única</span>
            <span class="linea">#BS-{{ str_pad($paciente->id, 4, '0', STR_PAD_LEFT) }}</span>
        </td>
        <td style="border:1.5px solid #000;padding:1.5px 3px;font-size:6.5px;">
            <span class="lbl">N° Archivo</span>
            <span class="linea">—</span>
        </td>
        <td style="border:1.5px solid #000;padding:1.5px 3px;font-size:6.5px;">
            <span class="lbl">No. Hoja</span>
            <span class="linea">1</span>
        </td>
    </tr>
</table>

{{-- A. DATOS PACIENTE --}}
<div class="sec">
    <div class="sec-tit">A. Datos de establecimiento y usuario / paciente</div>
    <table class="row-table">
        <tr>
            <td style="width:20%;"><span class="lbl">Primer apellido</span><span class="linea">{{ $partes[1] ?? '—' }}</span></td>
            <td style="width:20%;"><span class="lbl">Segundo apellido</span><span class="linea">{{ $partes[2] ?? '—' }}</span></td>
            <td style="width:20%;"><span class="lbl">Primer nombre</span><span class="linea">{{ $partes[0] ?? '—' }}</span></td>
            <td style="width:20%;"><span class="lbl">Segundo nombre</span><span class="linea">—</span></td>
            <td style="width:8%;"><span class="lbl">Sexo</span><span class="linea">—</span></td>
            <td style="width:12%;"><span class="lbl">Edad</span><span class="linea">{{ $pac?->edad ?? '—' }} a</span></td>
        </tr>
        <tr>
            <td colspan="6" style="padding:1px 3px;font-size:6.5px;">
                <b>Condición edad (marcar):</b>&nbsp; H <span class="check-box"></span>&nbsp; D <span class="check-box"></span>&nbsp; M <span class="check-box"></span>&nbsp; A <span class="check-box"></span>
            </td>
        </tr>
        <tr>
            <td colspan="2"><span class="lbl">Cédula de identidad</span><span class="linea">{{ $pac?->cedula ?? '—' }}</span></td>
            <td><span class="lbl">Fecha de nacimiento</span><span class="linea">{{ $pac?->fecha_nacimiento?->format('d/m/Y') ?? '—' }}</span></td>
            <td><span class="lbl">Teléfono</span><span class="linea">{{ $paciente->user->telefono ?? '—' }}</span></td>
            <td colspan="2"><span class="lbl">Correo electrónico</span><span class="linea">{{ $paciente->user->email ?? '—' }}</span></td>
        </tr>
        <tr>
            <td colspan="3"><span class="lbl">Dirección domiciliaria</span><span class="linea">{{ $pac?->direccion ?? '—' }}</span></td>
            <td><span class="lbl">Contacto emergencia</span><span class="linea">{{ $pac?->contacto_emergencia ?? '—' }}</span></td>
            <td colspan="2"><span class="lbl">Teléfono emergencia</span><span class="linea">{{ $pac?->telefono_emergencia ?? '—' }}</span></td>
        </tr>
    </table>
</div>

{{-- B. MOTIVO --}}
<div class="sec">
    <table style="width:100%;border-collapse:collapse;">
        <tr>
            <td style="background:#b8b8b8;padding:1.5px 3px;font-weight:bold;font-size:7px;text-transform:uppercase;border-bottom:1px solid #000;">B. Motivo de consulta</td>
            <td style="background:#b8b8b8;padding:1.5px 3px;font-size:6.5px;text-align:right;border-bottom:1px solid #000;width:180px;">Embarazada:&nbsp; SI <span class="check-box"></span>&nbsp; NO <span class="check-box"></span></td>
        </tr>
    </table>
    <div class="content-area">{{ $hist?->motivo_consulta ?? '—' }}</div>
</div>

{{-- C. ENFERMEDAD --}}
<div class="sec">
    <div class="sec-tit">C. Enfermedad actual</div>
    <div class="content-area" style="min-height:20px;">{{ $hist?->enfermedad_actual ?? '—' }}</div>
</div>

{{-- D. ANTECEDENTES PERSONALES --}}
<div class="sec">
    <div class="sec-tit">D. Antecedentes patológicos personales</div>
    <table class="check-tbl">
        <tr>
            <td><span class="check-box"></span><br>1. Alergia antibiótico</td>
            <td><span class="check-box"></span><br>2. Alergia anestesia</td>
            <td><span class="check-box"></span><br>3. Hemorragias</td>
            <td><span class="check-box"></span><br>4. VIH/SIDA</td>
            <td><span class="check-box"></span><br>5. Tuberculosis</td>
            <td><span class="check-box"></span><br>6. Asma</td>
            <td><span class="check-box"></span><br>7. Diabetes</td>
            <td><span class="check-box"></span><br>8. Hipertensión arterial</td>
            <td><span class="check-box"></span><br>9. Enf. cardíaca</td>
            <td><span class="check-box"></span><br>10. Otro</td>
        </tr>
    </table>
    <div class="content-area" style="border-top:1px solid #ccc;">{{ $hist?->antecedentes_personales ?? $pac?->enfermedades_cronicas ?? '—' }}</div>
    <div class="content-area" style="border-top:1px solid #ccc;"><b>Alergias:</b> {{ $pac?->alergias ?? 'Ninguna referida' }}</div>
    <div class="content-area" style="border-top:1px solid #ccc;"><b>Medicamentos actuales:</b> {{ $pac?->medicamentos_actuales ?? 'Ninguno' }}</div>
</div>

{{-- E. ANTECEDENTES FAMILIARES --}}
<div class="sec">
    <div class="sec-tit">E. Antecedentes patológicos familiares</div>
    <table class="check-tbl">
        <tr>
            <td><span class="check-box"></span><br>1. Cardiopatía</td>
            <td><span class="check-box"></span><br>2. Hipertensión arterial</td>
            <td><span class="check-box"></span><br>3. Enf. C. vascular</td>
            <td><span class="check-box"></span><br>4. Endocrino metabólico</td>
            <td><span class="check-box"></span><br>5. Cáncer</td>
            <td><span class="check-box"></span><br>6. Tuberculosis</td>
            <td><span class="check-box"></span><br>7. Enf. mental</td>
            <td><span class="check-box"></span><br>8. Enf. infecciosa</td>
            <td><span class="check-box"></span><br>9. Mal formación</td>
            <td><span class="check-box"></span><br>10. Otro</td>
        </tr>
    </table>
    <div class="content-area" style="border-top:1px solid #ccc;min-height:16px;">{{ $hist?->antecedentes_familiares ?? '—' }}</div>
</div>

{{-- F. CONSTANTES VITALES --}}
<div class="sec">
    <div class="sec-tit">F. Constantes vitales</div>
    <table class="row-table">
        <tr>
            <td style="width:25%;"><span class="lbl">Temperatura °C</span><span class="linea">{{ $hist?->temperatura ?? '—' }}</span></td>
            <td style="width:25%;"><span class="lbl">Pulso / min</span><span class="linea">{{ $hist?->pulso ?? '—' }}</span></td>
            <td style="width:25%;"><span class="lbl">Frecuencia respiratoria / min</span><span class="linea">{{ $hist?->frecuencia_respiratoria ?? '—' }}</span></td>
            <td style="width:25%;"><span class="lbl">Presión arterial (mmHg)</span><span class="linea">{{ $hist?->presion_arterial ?? '—' }}</span></td>
        </tr>
    </table>
</div>

{{-- G. EXAMEN ESTOMATOGNÁTICO --}}
<div class="sec">
    <div class="sec-tit">G. Examen del sistema estomatognático — Describir la patología de la región afectada registrando el número</div>
    <table class="row-table">
        <tr>
            <td style="width:14%;"><span class="lbl">1. Labios</span><span class="linea" style="min-height:8px;"></span></td>
            <td style="width:14%;"><span class="lbl">3. Maxilar superior</span><span class="linea" style="min-height:8px;"></span></td>
            <td style="width:14%;"><span class="lbl">5. Lengua</span><span class="linea" style="min-height:8px;"></span></td>
            <td style="width:14%;"><span class="lbl">7. Piso de la boca</span><span class="linea" style="min-height:8px;"></span></td>
            <td style="width:14%;"><span class="lbl">9. Glándulas salivales</span><span class="linea" style="min-height:8px;"></span></td>
            <td style="width:14%;"><span class="lbl">11. A.T.M.</span><span class="linea" style="min-height:8px;"></span></td>
            <td style="width:16%;"><span class="lbl">13. Otros</span><span class="linea" style="min-height:8px;"></span></td>
        </tr>
        <tr>
            <td><span class="lbl">2. Mejillas</span><span class="linea" style="min-height:8px;"></span></td>
            <td><span class="lbl">4. Maxilar inferior</span><span class="linea" style="min-height:8px;"></span></td>
            <td><span class="lbl">6. Paladar</span><span class="linea" style="min-height:8px;"></span></td>
            <td><span class="lbl">8. Carrillos</span><span class="linea" style="min-height:8px;"></span></td>
            <td><span class="lbl">10. Oro faringe</span><span class="linea" style="min-height:8px;"></span></td>
            <td><span class="lbl">12. Ganglios</span><span class="linea" style="min-height:8px;"></span></td>
            <td></td>
        </tr>
    </table>
    <div class="content-area" style="border-top:1px solid #ccc;">{{ ($hist?->examen_extraoral ?? '') . ' ' . ($hist?->examen_intraoral ?? '') }}</div>
</div>

{{-- H. ODONTOGRAMA --}}
<div class="sec">
    <div class="sec-tit">H. Odontograma — Pintar con AZUL para tratamiento realizado y ROJO para patología actual. Movilidad y recesión: marcar (1, 2, 3 o 4). Si aplica</div>
    <div style="padding:3px 4px;text-align:center;">
        {{-- Movilidad/Recesión superior permanente --}}
        <div style="font-size:5.5px;text-align:left;margin-bottom:1px;">Recesión &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Recesión</div>
        <div style="font-size:5.5px;text-align:left;margin-bottom:2px;">Movilidad &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Movilidad</div>

        {{-- Superior permanente --}}
        <div style="margin-bottom:2px;">
            @foreach($supPerm as $n)
                @php $cls = in_array($n,$ausentes)?'pieza-ausente':(in_array($n,$numsPerm)?'pieza-tratado':''); @endphp
                <span class="pieza {{ $cls }}">{{ $n }}</span>
            @endforeach
        </div>
        <div style="border-top:1.5px solid #000;border-bottom:1.5px solid #000;height:3px;margin:1px 0;"></div>
        {{-- Inferior permanente --}}
        <div style="margin-top:2px;margin-bottom:4px;">
            @foreach($infPerm as $n)
                @php $cls = in_array($n,$ausentes)?'pieza-ausente':(in_array($n,$numsPerm)?'pieza-tratado':''); @endphp
                <span class="pieza {{ $cls }}">{{ $n }}</span>
            @endforeach
        </div>

        {{-- Superior temporal --}}
        <div style="margin-bottom:2px;">
            @foreach($supTemp as $n)
                @php $cls = in_array($n,$ausentes)?'pieza-ausente':(in_array($n,$numsTemp)?'pieza-tratado':''); @endphp
                <span class="pieza {{ $cls }}">{{ $n }}</span>
            @endforeach
        </div>
        <div style="border-top:1.5px solid #000;border-bottom:1.5px solid #000;height:3px;margin:1px 0;"></div>
        {{-- Inferior temporal --}}
        <div style="margin-top:2px;">
            @foreach($infTemp as $n)
                @php $cls = in_array($n,$ausentes)?'pieza-ausente':(in_array($n,$numsTemp)?'pieza-tratado':''); @endphp
                <span class="pieza {{ $cls }}">{{ $n }}</span>
            @endforeach
        </div>
        <div style="font-size:5.5px;text-align:left;margin-top:1px;">Movilidad</div>
        <div style="font-size:5.5px;text-align:left;">Recesión</div>
    </div>
</div>

{{-- I + J --}}
<table style="width:100%;border-collapse:collapse;border:1px solid #000;margin-bottom:2px;">
    <tr>
        <td style="border-right:1px solid #000;vertical-align:top;width:65%;">
            <div class="sec-tit">I. Indicadores de salud bucal</div>
            <table style="width:100%;border-collapse:collapse;">
                <tr>
                    <td style="border-right:1px solid #ccc;vertical-align:top;width:55%;padding:2px;">
                        <div style="font-size:6px;font-weight:bold;text-align:center;margin-bottom:1px;">Higiene oral simplificada</div>
                        <table class="row-table" style="font-size:6px;">
                            <tr><th>Piezas examinadas</th><th>Placa (0-1-2-3-A)</th><th>Cálculo (0-1-2-3)</th><th>Gingivitis (0-1)</th></tr>
                            @foreach([16,11,26,36,31,46] as $p)
                                <tr><td style="text-align:center;">{{ $p }}</td><td></td><td></td><td></td></tr>
                            @endforeach
                            <tr><td style="background:#ddd;font-weight:bold;text-align:center;">Totales</td><td></td><td></td><td></td></tr>
                        </table>
                    </td>
                    <td style="vertical-align:top;padding:2px;">
                        <div style="font-size:6px;font-weight:bold;text-align:center;margin-bottom:1px;">Enfermedad periodontal</div>
                        <table class="row-table" style="font-size:6px;">
                            <tr><th colspan="2">Tipos de oclusión</th><th colspan="2">Nivel de fluorosis</th></tr>
                            <tr><td>Leve</td><td>Angle I</td><td>Leve</td><td></td></tr>
                            <tr><td>Moderada</td><td>Angle II</td><td>Moderada</td><td></td></tr>
                            <tr><td>Severa</td><td>Angle III</td><td>Severa</td><td></td></tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
        <td style="vertical-align:top;">
            <div class="sec-tit">J. Índices CPO-ceo</div>
            <table class="row-table" style="margin:2px 3px;font-size:6.5px;width:95%;">
                <tr><th></th><th>C</th><th>P</th><th>O</th><th>Total</th></tr>
                <tr><td style="font-weight:bold;background:#ddd;">D</td><td></td><td></td><td></td><td style="font-weight:bold;">0</td></tr>
                <tr><th></th><th>c</th><th>e</th><th>o</th><th>Total</th></tr>
                <tr><td style="font-weight:bold;background:#ddd;">d</td><td></td><td></td><td></td><td style="font-weight:bold;">0</td></tr>
            </table>
        </td>
    </tr>
</table>

{{-- K. SIMBOLOGÍA --}}
<div class="sec">
    <div class="sec-tit">K. Simbología del odontograma</div>
    <table style="width:100%;border-collapse:collapse;font-size:6px;padding:2px 3px;">
        <tr>
            <td style="padding:1px 3px;width:50%;">
                <span style="color:red;">rojo</span> SELLANTE NECESARIO &nbsp;|&nbsp;
                <span style="color:blue;">azul</span> SELLANTE REALIZADO &nbsp;|&nbsp;
                X rojo EXTRACCIÓN INDICADA &nbsp;|&nbsp;
                X azul PÉRDIDA POR CARIES
            </td>
            <td style="padding:1px 3px;">
                □ PÉRDIDA (OTRA CAUSA) &nbsp;|&nbsp;
                △ ENDODONCIA POR REALIZAR &nbsp;|&nbsp;
                ● CORONA INDICADA &nbsp;|&nbsp;
                □-□ PRÓTESIS FIJA INDICADA &nbsp;|&nbsp;
                A AUSENTE
            </td>
        </tr>
    </table>
</div>

<div class="pie">SNS-MSP / HCU-form.033/ 2021 &nbsp;&nbsp;&nbsp; ODONTOLOGÍA (1)</div>
</div>

{{-- ===== PÁGINA 2 ===== --}}
<div class="page-break"></div>
<div class="page">

{{-- ENCABEZADO P2 --}}
<table class="enc" style="margin-bottom:2px;">
    <tr>
        <td style="width:50px;text-align:center;border:1.5px solid #000;padding:3px;">
            <div style="font-size:10px;font-weight:bold;border:1.5px solid #000;padding:2px 5px;display:inline-block;">MSP</div>
            <div style="font-size:5.5px;margin-top:2px;">MINISTERIO DE<br>SALUD PÚBLICA</div>
        </td>
        <td style="text-align:center;border:1.5px solid #000;padding:4px;">
            <h1 style="font-size:8px;font-weight:bold;text-transform:uppercase;">Ministerio de Salud Pública</h1>
            <p style="font-size:6.5px;margin:2px 0;">Historia Clínica Única — Odontología</p>
            <p style="font-weight:bold;font-size:8.5px;margin-top:2px;">SNS-MSP / HCU-FORM.033 / 2021</p>
        </td>
        <td style="width:190px;border:1.5px solid #000;padding:2px 3px;font-size:6.5px;vertical-align:top;">
            <span class="lbl">Paciente</span>
            <span class="linea">{{ $paciente->user->name ?? '—' }}</span>
            <span class="lbl" style="margin-top:3px;">N° Historia Clínica</span>
            <span class="linea">#BS-{{ str_pad($paciente->id, 4, '0', STR_PAD_LEFT) }}</span>
            <span class="lbl" style="margin-top:3px;">No. Hoja</span>
            <span class="linea">2</span>
        </td>
    </tr>
</table>

{{-- L. EXÁMENES COMPLEMENTARIOS --}}
<div class="sec">
    <div class="sec-tit">L. Pedido de exámenes complementarios</div>
    <div class="content-area" style="min-height:20px;"></div>
</div>

{{-- M. INFORME EXÁMENES --}}
<div class="sec">
    <div class="sec-tit">M. Informe de exámenes</div>
    <table class="row-table">
        <tr>
            <td style="width:15%;font-weight:bold;">Biometría</td>
            <td style="width:20%;font-weight:bold;">Química sanguínea</td>
            <td style="width:15%;font-weight:bold;">Rayos-X</td>
            <td style="font-weight:bold;">Otros</td>
            <td style="width:8%;font-weight:bold;text-align:center;"><span class="check-box"></span></td>
        </tr>
    </table>
    <div class="content-area" style="min-height:24px;"></div>
</div>

{{-- N. DIAGNÓSTICO --}}
<div class="sec">
    <div class="sec-tit">N. Diagnóstico &nbsp;&nbsp; PRE= Presuntivo &nbsp;&nbsp; DEF= Definitivo</div>
    <table class="row-table" style="font-size:6.5px;">
        <tr>
            <th style="width:35%;">Diagnóstico</th><th style="width:8%;">CIE</th><th style="width:6%;">PRE</th><th style="width:6%;">DEF</th>
            <th style="width:35%;">Diagnóstico</th><th style="width:8%;">CIE</th><th style="width:6%;">PRE</th><th style="width:6%;">DEF</th>
        </tr>
        <tr>
            <td>1. {{ $hist?->diagnostico_inicial ?? '—' }}</td><td></td><td></td><td></td>
            <td>4.</td><td></td><td></td><td></td>
        </tr>
        <tr>
            <td>2.</td><td></td><td></td><td></td>
            <td>5.</td><td></td><td></td><td></td>
        </tr>
        <tr>
            <td>3.</td><td></td><td></td><td></td>
            <td>6.</td><td></td><td></td><td></td>
        </tr>
    </table>
</div>

{{-- O. DATOS PROFESIONAL --}}
<div class="sec">
    <div class="sec-tit">O. Datos del profesional responsable</div>
    <table class="row-table">
        <tr>
            <td style="width:20%;"><span class="lbl">Fecha de apertura (aaaa-mm-dd)</span><span class="linea">{{ $hist?->fecha_apertura?->format('Y-m-d') ?? now()->format('Y-m-d') }}</span></td>
            <td style="width:10%;"><span class="lbl">Hora (hh:mm)</span><span class="linea">—</span></td>
            <td style="width:35%;"><span class="lbl">Primer nombre del profesional</span><span class="linea">{{ $od?->user?->name ?? '—' }}</span></td>
            <td style="width:20%;"><span class="lbl">Primer apellido</span><span class="linea">—</span></td>
            <td style="width:15%;"><span class="lbl">Segundo apellido</span><span class="linea">—</span></td>
        </tr>
        <tr>
            <td colspan="2"><span class="lbl">N° documento identificación / licencia MSP</span><span class="linea">{{ $od?->numero_licencia ?? '—' }}</span></td>
            <td colspan="2" style="min-height:35px;"><span class="lbl">Firma</span><div style="border-bottom:1px solid #000;margin-top:25px;"></div></td>
            <td style="min-height:35px;"><span class="lbl">Sello</span><div style="border:1px dashed #aaa;min-height:28px;margin-top:3px;"></div></td>
        </tr>
    </table>
</div>

{{-- P. TRATAMIENTO --}}
<div class="sec">
    <div class="sec-tit">P. Tratamiento</div>
    <table class="trat-tbl">
        <thead>
            <tr>
                <th style="width:18%;">No. de sesión y fecha</th>
                <th style="width:28%;">Diagnósticos y complicaciones</th>
                <th style="width:28%;">Procedimientos</th>
                <th style="width:18%;">Prescripciones</th>
                <th style="width:8%;">Firma y sello</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sesiones as $i => $t)
                <tr>
                    <td class="sesion-hdr" style="width:18%;">
                        No. SESIÓN: {{ $i + 1 }}<br>
                        FECHA: {{ \Carbon\Carbon::parse($t->fecha_tratamiento)->format('d/m/Y') }}
                    </td>
                    <td>
                        {{ $t->descripcion ?? '—' }}
                        @if($t->piezas->count() > 0)
                            <br><small>Piezas: {{ $t->piezas->pluck('pieza_numero')->unique()->join(', ') }}</small>
                            @if($t->piezas->pluck('diagnostico')->filter()->count() > 0)
                                <br><small>Dx: {{ $t->piezas->pluck('diagnostico')->filter()->unique()->join(', ') }}</small>
                            @endif
                        @endif
                    </td>
                    <td>
                        {{ $t->nombre }}
                        @if($t->piezas->pluck('procedimiento')->filter()->count() > 0)
                            <br><small>{{ $t->piezas->pluck('procedimiento')->filter()->unique()->join(', ') }}</small>
                        @endif
                    </td>
                    <td>{{ $t->observaciones ?? '—' }}</td>
                    <td style="font-size:6px;background:#f5f5f5;text-align:center;">
                        FIRMA<br><br>
                        {{ $t->cita?->odontologo?->user?->name ?? '—' }}<br><br>
                        SELLO
                    </td>
                </tr>
            @empty
                @for($i = 0; $i < 3; $i++)
                    <tr>
                        <td class="sesion-hdr">No. SESIÓN:<br><br>FECHA:</td>
                        <td style="min-height:30px;"></td>
                        <td></td><td></td>
                        <td style="background:#f5f5f5;font-size:6px;text-align:center;">FIRMA<br><br><br>SELLO</td>
                    </tr>
                @endfor
            @endforelse
            {{-- Filas vacías adicionales --}}
            @for($i = 0; $i < max(0, 2 - $sesiones->count()); $i++)
                <tr>
                    <td class="sesion-hdr">No. SESIÓN:<br><br>FECHA:</td>
                    <td style="min-height:30px;"></td>
                    <td></td><td></td>
                    <td style="background:#f5f5f5;font-size:6px;text-align:center;">FIRMA<br><br><br>SELLO</td>
                </tr>
            @endfor
        </tbody>
    </table>
    <div style="text-align:right;padding:2px 4px;font-size:7px;font-weight:bold;border-top:1px solid #000;">
        Total acumulado: ${{ number_format($sesiones->sum('costo'), 2) }}
    </div>
</div>

<div class="pie">
    SNS-MSP / HCU-form.033/ 2021 &nbsp;&nbsp;&nbsp; ODONTOLOGÍA (2) &nbsp;&nbsp;&nbsp;
    Generado: {{ now()->format('d/m/Y H:i') }} | Sistema JM Dental
</div>

</div>
</body>
</html>