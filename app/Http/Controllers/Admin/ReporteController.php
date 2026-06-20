<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function tratamientos(Request $request)
    {
        $periodo = $request->get('periodo', 'mes'); // dia | mes | anio
        $fecha = $request->get('fecha', now()->format('Y-m-d'));

        $fechaCarbon = Carbon::parse($fecha);

        [$inicio, $fin] = match ($periodo) {
            'dia' => [$fechaCarbon->copy()->startOfDay(), $fechaCarbon->copy()->endOfDay()],
            'anio' => [$fechaCarbon->copy()->startOfYear(), $fechaCarbon->copy()->endOfYear()],
            default => [$fechaCarbon->copy()->startOfMonth(), $fechaCarbon->copy()->endOfMonth()],
        };

        $citas = Cita::where('estado', 'completada')
            ->whereBetween('fecha_hora', [$inicio, $fin])
            ->get();

        $totalTratamientos = $citas->count();

        $porTratamiento = $citas->groupBy('motivo')
            ->map(fn ($grupo) => $grupo->count())
            ->sortDesc();

        $maxCantidad = $porTratamiento->max() ?: 1;

        $etiquetaPeriodo = match ($periodo) {
            'dia' => $fechaCarbon->locale('es')->isoFormat('D [de] MMMM [de] YYYY'),
            'anio' => $fechaCarbon->format('Y'),
            default => ucfirst($fechaCarbon->locale('es')->isoFormat('MMMM [de] YYYY')),
        };

        return view('reportes.reportetratamientos', compact(
            'periodo',
            'fecha',
            'totalTratamientos',
            'porTratamiento',
            'maxCantidad',
            'etiquetaPeriodo'
        ));
    }
}