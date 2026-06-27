<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen - {{ $usuario->name }}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size:12px; color:#1e293b; background:#fff; }
        .header { background:#3b82f6; color:#fff; padding:24px 32px; margin-bottom:24px; }
        .header h1 { font-size:20px; font-weight:700; margin-bottom:4px; }
        .header p { font-size:12px; opacity:0.85; }
        .content { padding:0 32px 32px; }
        .paciente-card { background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:16px; margin-bottom:20px; }
        .paciente-card h2 { font-size:16px; font-weight:700; color:#0f172a; margin-bottom:4px; }
        .paciente-card p { font-size:11px; color:#64748b; margin-bottom:2px; }
        .badge { display:inline-block; padding:2px 8px; border-radius:20px; font-size:10px; font-weight:600; background:#ede9fe; color:#5b21b6; margin-top:4px; }
        .section-title { font-size:13px; font-weight:700; color:#0f172a; margin:20px 0 10px; padding-bottom:6px; border-bottom:2px solid #e2e8f0; }
        .tratamiento { border:1px solid #e2e8f0; border-radius:8px; padding:14px; margin-bottom:12px; }
        .tratamiento-header { display:flex; justify-content:space-between; margin-bottom:8px; }
        .tratamiento-nombre { font-weight:700; font-size:13px; color:#0f172a; }
        .tratamiento-fecha { font-size:11px; color:#64748b; }
        .tratamiento-costo { font-size:12px; font-weight:700; color:#0f172a; }
        .estado-completado { background:#dcfce7; color:#15803d; padding:2px 8px; border-radius:20px; font-size:10px; font-weight:600; }
        .estado-proceso { background:#fef3c7; color:#92400e; padding:2px 8px; border-radius:20px; font-size:10px; font-weight:600; }
        .label { font-size:10px; font-weight:700; text-transform:uppercase; color:#94a3b8; margin-bottom:3px; }
        .value { font-size:12px; color:#334155; margin-bottom:8px; }
        .piezas { margin-top:8px; }
        .pieza-item { display:inline-block; background:#eff6ff; border:1px solid #bfdbfe; border-radius:6px; padding:3px 8px; font-size:10px; color:#1d4ed8; margin:2px; }
        .footer { margin-top:32px; padding-top:16px; border-top:1px solid #e2e8f0; text-align:center; font-size:10px; color:#94a3b8; }
        .no-tratamientos { text-align:center; color:#94a3b8; padding:20px; font-size:12px; }
        table { width:100%; border-collapse:collapse; }
        td { padding:4px 0; vertical-align:top; }
        td:first-child { width:140px; }
    </style>
</head>
<body>

<div class="header">
    <h1>Resumen Clínico</h1>
    <p>JM Dental · {{ now()->format('d/m/Y H:i') }}</p>
</div>

<div class="content">

    <div class="paciente-card">
        <h2>{{ $usuario->name }}</h2>
        <p>{{ $usuario->email }} · {{ $usuario->telefono ?? 'Sin teléfono' }}</p>
        @if($paciente)
            @if($paciente->edad)
                <p>{{ $paciente->edad }} años</p>
            @endif
            @if($paciente->tipo_sangre)
                <span class="badge">Sangre: {{ $paciente->tipo_sangre }}</span>
            @endif
            <span class="badge">{{ $paciente->tipo_denticion }}</span>
            @if($paciente->alergias)
                <p style="margin-top:6px;"><strong>Alergias:</strong> {{ $paciente->alergias }}</p>
            @endif
        @endif
        <p style="margin-top:6px;font-size:10px;color:#94a3b8;">Registrado el {{ $usuario->created_at->format('d/m/Y') }}</p>
    </div>

    <div class="section-title">Historial de Tratamientos</div>

    @forelse($citas as $cita)
        @php $t = $cita->tratamiento; @endphp
        <div class="tratamiento">
            <div class="tratamiento-header">
                <span class="tratamiento-nombre">{{ $t->nombre }}</span>
                <span class="tratamiento-fecha">{{ $t->fecha_tratamiento->format('d/m/Y') }}</span>
            </div>

            <table>
                @if($t->costo > 0)
                <tr>
                    <td class="label">Costo</td>
                    <td class="value">${{ number_format($t->costo, 2) }}</td>
                </tr>
                @endif
                <tr>
                    <td class="label">Estado</td>
                    <td>
                        @if($t->estado === 'completado')
                            <span class="estado-completado">Completado</span>
                        @else
                            <span class="estado-proceso">En proceso</span>
                        @endif
                    </td>
                </tr>
                @if($t->descripcion)
                <tr>
                    <td class="label">Descripción</td>
                    <td class="value">{{ $t->descripcion }}</td>
                </tr>
                @endif
                @if($t->observaciones)
                <tr>
                    <td class="label">Observaciones</td>
                    <td class="value">{{ $t->observaciones }}</td>
                </tr>
                @endif
            </table>

            @if($t->piezas->count() > 0)
                <div class="piezas">
                    <div class="label" style="margin-bottom:4px;">Piezas trabajadas</div>
                    @foreach($t->piezas as $pieza)
                        <span class="pieza-item">
                            {{ $pieza->pieza_numero }} · {{ ucfirst($pieza->cara) }}
                            @if($pieza->procedimiento) · {{ $pieza->procedimiento }} @endif
                            @if($pieza->ausente) · Ausente @endif
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
    @empty
        <div class="no-tratamientos">No hay tratamientos registrados para este paciente.</div>
    @endforelse

    <div class="footer">
        Documento generado automáticamente por JM Dental · {{ now()->format('d/m/Y H:i') }}
    </div>

</div>
</body>
</html>