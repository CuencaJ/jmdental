<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tratamiento - {{ $tratamiento->nombre }}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size:12px; color:#1e293b; background:#fff; }
        .header { background:#3b82f6; color:#fff; padding:24px 32px; margin-bottom:24px; }
        .header h1 { font-size:18px; font-weight:700; margin-bottom:4px; }
        .header p { font-size:11px; opacity:0.85; }
        .content { padding:0 32px 32px; }
        .paciente-card { background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:16px; margin-bottom:20px; }
        .paciente-card h2 { font-size:15px; font-weight:700; color:#0f172a; margin-bottom:4px; }
        .paciente-card p { font-size:11px; color:#64748b; margin-bottom:2px; }
        .section-title { font-size:13px; font-weight:700; color:#0f172a; margin:20px 0 10px; padding-bottom:6px; border-bottom:2px solid #e2e8f0; }
        .tratamiento-card { border:1px solid #e2e8f0; border-radius:8px; padding:16px; }
        .badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:10px; font-weight:600; }
        .badge-completado { background:#dcfce7; color:#15803d; }
        .badge-proceso { background:#fef3c7; color:#92400e; }
        .badge-cancelado { background:#fee2e2; color:#991b1b; }
        .label { font-size:10px; font-weight:700; text-transform:uppercase; color:#94a3b8; margin-bottom:3px; }
        .value { font-size:12px; color:#334155; margin-bottom:12px; }
        table { width:100%; border-collapse:collapse; }
        td { padding:6px 0; vertical-align:top; }
        td:first-child { width:160px; }
        .piezas-title { font-size:10px; font-weight:700; text-transform:uppercase; color:#94a3b8; margin:12px 0 6px; }
        .pieza-item { display:inline-block; background:#eff6ff; border:1px solid #bfdbfe; border-radius:6px; padding:3px 8px; font-size:10px; color:#1d4ed8; margin:2px; }
        .footer { margin-top:32px; padding-top:16px; border-top:1px solid #e2e8f0; text-align:center; font-size:10px; color:#94a3b8; }
    </style>
</head>
<body>

<div class="header">
    <h1>Detalle de Tratamiento</h1>
    <p>JM Dental · Generado el {{ now()->format('d/m/Y H:i') }}</p>
</div>

<div class="content">

    {{-- DATOS DEL PACIENTE --}}
    <div class="paciente-card">
        <h2>{{ $tratamiento->cita->paciente->user->name ?? 'Paciente' }}</h2>
        <p>{{ $tratamiento->cita->paciente->user->email ?? '' }}</p>
        @if($tratamiento->cita->paciente->user->telefono)
            <p>Tel: {{ $tratamiento->cita->paciente->user->telefono }}</p>
        @endif
        @if($tratamiento->cita->odontologo)
            <p style="margin-top:6px;font-weight:700;">Odontólogo: {{ $tratamiento->cita->odontologo->user->name ?? 'No asignado' }}</p>
        @endif
    </div>

    {{-- DETALLE DEL TRATAMIENTO --}}
    <div class="section-title">Tratamiento</div>

    <div class="tratamiento-card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
            <span style="font-size:15px;font-weight:700;color:#0f172a;">{{ $tratamiento->nombre }}</span>
            @php
                $badgeClass = match($tratamiento->estado) {
                    'completado' => 'badge-completado',
                    'en_proceso' => 'badge-proceso',
                    'cancelado'  => 'badge-cancelado',
                    default      => '',
                };
                $badgeLabel = match($tratamiento->estado) {
                    'completado' => 'Completado',
                    'en_proceso' => 'En proceso',
                    'cancelado'  => 'Cancelado',
                    default      => ucfirst($tratamiento->estado),
                };
            @endphp
            <span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
        </div>

        <table>
            <tr>
                <td class="label">Fecha</td>
                <td class="value">{{ \Carbon\Carbon::parse($tratamiento->fecha_tratamiento)->format('d/m/Y') }}</td>
            </tr>
            @if($tratamiento->costo > 0)
            <tr>
                <td class="label">Costo</td>
                <td class="value">${{ number_format($tratamiento->costo, 2) }}</td>
            </tr>
            @endif
            @if($tratamiento->descripcion)
            <tr>
                <td class="label">Descripción</td>
                <td class="value">{{ $tratamiento->descripcion }}</td>
            </tr>
            @endif
            @if($tratamiento->observaciones)
            <tr>
                <td class="label">Observaciones</td>
                <td class="value">{{ $tratamiento->observaciones }}</td>
            </tr>
            @endif
        </table>

        {{-- PIEZAS DENTALES --}}
        @if($tratamiento->piezas && $tratamiento->piezas->count() > 0)
            <div class="piezas-title">Piezas trabajadas</div>
            @foreach($tratamiento->piezas as $pieza)
                <span class="pieza-item">
                    Pieza {{ $pieza->pieza_numero }} · {{ ucfirst($pieza->cara) }}
                    @if($pieza->procedimiento) · {{ $pieza->procedimiento }} @endif
                    @if($pieza->ausente) · Ausente @endif
                </span>
            @endforeach
        @endif
    </div>

    <div class="footer">
        Documento generado automáticamente por JM Dental · {{ now()->format('d/m/Y H:i') }}
    </div>

</div>
</body>
</html>