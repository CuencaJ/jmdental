<?php

namespace App\Services;

use App\Models\Cita;
use App\Models\ConfiguracionHorario;
use App\Models\HorarioBloqueado;
use Carbon\Carbon;

class HorarioDisponibilidadService
{
    public function getSlotsDisponibles(string $fecha, ?int $odontologoId = null, ?int $citaExcluirId = null): array
    {
        $config = ConfiguracionHorario::obtener();

        // Verificar que el día sea laborable
        $diaSemana = Carbon::parse($fecha)->isoWeekday();
        if (!in_array((string)$diaSemana, $config->dias_laborables)) {
            return [];
        }

        // Generar todos los slots del día
        $inicio   = Carbon::parse($fecha . ' ' . $config->hora_inicio);
        $fin      = Carbon::parse($fecha . ' ' . $config->hora_fin);
        $duracion = $config->duracion_slot;
        $ahora    = Carbon::now();

        $slots  = [];
        $cursor = $inicio->copy();

        while ($cursor->copy()->addMinutes($duracion)->lte($fin)) {
            if ($cursor->copy()->addMinutes($duracion)->gt($ahora) || $cursor->toDateString() !== $ahora->toDateString()) {
                $slots[] = $cursor->format('H:i');
            }
            $cursor->addMinutes($duracion);
        }

        // Citas ya agendadas
        $citasOcupadas = Cita::whereDate('fecha_hora', $fecha)
            ->whereNotIn('estado', ['cancelada'])
            ->when($odontologoId, fn($q) => $q->where('odontologo_id', $odontologoId))
            ->when($citaExcluirId, fn($q) => $q->where('id', '!=', $citaExcluirId))
            ->get(['fecha_hora', 'duracion_minutos']);

        // Horarios bloqueados
        $bloqueados = HorarioBloqueado::whereDate('fecha', $fecha)
            ->when($odontologoId, fn($q) => $q->where('odontologo_id', $odontologoId))
            ->get(['hora_inicio', 'hora_fin']);

        // Filtrar slots ocupados o bloqueados
        $slotsLibres = array_filter($slots, function($slot) use ($fecha, $citasOcupadas, $bloqueados, $duracion) {
            $slotInicio = Carbon::parse($fecha . ' ' . $slot);
            $slotFin    = $slotInicio->copy()->addMinutes($duracion);

            // Verificar citas ocupadas
            foreach ($citasOcupadas as $cita) {
                $citaInicio = Carbon::parse($cita->fecha_hora);
                $citaFin    = $citaInicio->copy()->addMinutes($cita->duracion_minutos ?? $duracion);
                if ($slotInicio->lt($citaFin) && $slotFin->gt($citaInicio)) {
                    return false;
                }
            }

            // Verificar horarios bloqueados
            foreach ($bloqueados as $bloqueo) {
                $bloqueoInicio = Carbon::parse($fecha . ' ' . $bloqueo->hora_inicio);
                $bloqueoFin    = Carbon::parse($fecha . ' ' . $bloqueo->hora_fin);
                if ($slotInicio->lt($bloqueoFin) && $slotFin->gt($bloqueoInicio)) {
                    return false;
                }
            }

            return true;
        });

        return array_values($slotsLibres);
    }
}