<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionHorario;
use App\Models\HorarioBloqueado;
use App\Models\Odontologo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SemanaController extends Controller
{
    public function adminIndex(Request $request)
    {
        $odontologos  = Odontologo::with('user')->get();
        $odontologoId = $request->get('odontologo_id', $odontologos->first()?->id);
        $odontologo   = Odontologo::find($odontologoId);
        $semana       = $this->getSemana($request->get('semana'));
        $config       = ConfiguracionHorario::obtener();
        $slots        = $this->generarSlots($config);
        $bloqueados   = $this->getBloqueados($semana, $odontologoId);

        // Generar slots por día — filtrando solo los pasados en el día de hoy
        $slotsPorDia = [];
        $ahora = Carbon::now();
        foreach ($semana as $dia) {
            $esHoy = $dia->toDateString() === Carbon::today()->toDateString();
            if ($esHoy) {
                $slotsPorDia[$dia->toDateString()] = array_values(array_filter($slots, function($slot) use ($ahora, $config, $dia) {
                    $slotTime = Carbon::parse($dia->toDateString() . ' ' . $slot);
                    $slotFin  = $slotTime->copy()->addMinutes($config->duracion_slot);
                    return $slotFin->gt($ahora);
                }));
            } else {
                $slotsPorDia[$dia->toDateString()] = $slots;
            }
        }

        return view('semana.preparar-semana-admin', compact(
            'odontologos', 'odontologo', 'semana', 'slots', 'slotsPorDia', 'bloqueados', 'config'
        ));
    }

    public function odontologoIndex(Request $request)
    {
        $odontologo = Odontologo::where('user_id', auth()->id())->first();
        $semana     = $this->getSemana($request->get('semana'));
        $config     = ConfiguracionHorario::obtener();
        $slots      = $this->generarSlots($config);
        $bloqueados = $this->getBloqueados($semana, $odontologo?->id);

        // Generar slots por día — filtrando solo los pasados en el día de hoy
        $slotsPorDia = [];
        $ahora = Carbon::now();
        foreach ($semana as $dia) {
            $esHoy = $dia->toDateString() === Carbon::today()->toDateString();
            if ($esHoy) {
                $slotsPorDia[$dia->toDateString()] = array_values(array_filter($slots, function($slot) use ($ahora, $config, $dia) {
                    $slotTime = Carbon::parse($dia->toDateString() . ' ' . $slot);
                    $slotFin  = $slotTime->copy()->addMinutes($config->duracion_slot);
                    return $slotFin->gt($ahora);
                }));
            } else {
                $slotsPorDia[$dia->toDateString()] = $slots;
            }
        }

        return view('semana.preparar-semana', compact(
            'odontologo', 'semana', 'slots', 'slotsPorDia', 'bloqueados', 'config'
        ));
    }

    public function semana(Request $request)
    {
        $odontologo = Odontologo::where('user_id', auth()->id())->first();
        $semana     = $this->getSemana($request->get('semana'));
        $config     = ConfiguracionHorario::obtener();
        $slots      = $this->generarSlots($config);
        $bloqueados = $this->getBloqueados($semana, $odontologo?->id);

        // Generar slots por día — filtrando solo los pasados en el día de hoy
        $slotsPorDia = [];
        $ahora = Carbon::now();
        foreach ($semana as $dia) {
            $esHoy = $dia->toDateString() === Carbon::today()->toDateString();
            if ($esHoy) {
                $slotsPorDia[$dia->toDateString()] = array_values(array_filter($slots, function($slot) use ($ahora, $config, $dia) {
                    $slotTime = Carbon::parse($dia->toDateString() . ' ' . $slot);
                    $slotFin  = $slotTime->copy()->addMinutes($config->duracion_slot);
                    return $slotFin->gt($ahora);
                }));
            } else {
                $slotsPorDia[$dia->toDateString()] = $slots;
            }
        }

        return view('semana.preparar-semana', compact(
            'odontologo', 'semana', 'slots', 'slotsPorDia', 'bloqueados', 'config'
        ));
    }

    public function bloquear(Request $request)
    {
        $validado = $request->validate([
            'odontologo_id' => 'required|exists:odontologos,id',
            'fecha'         => 'required|date',
            'hora_inicio'   => 'required',
            // hora_fin es opcional: la vista solo envía la hora de inicio,
            // así que se calcula con la duración de slot configurada.
            'hora_fin'      => 'nullable',
            'motivo'        => 'nullable|string|max:255',
        ]);

        $config  = ConfiguracionHorario::obtener();
        $horaFin = $validado['hora_fin'] ?? Carbon::parse($validado['hora_inicio'])
            ->addMinutes($config->duracion_slot)
            ->format('H:i');

        // Se busca solo por hora_inicio: comparar también hora_fin hacía que
        // no encontrara el registro al desbloquear.
        $existe = HorarioBloqueado::where('odontologo_id', $validado['odontologo_id'])
            ->where('fecha', $validado['fecha'])
            ->where('hora_inicio', $validado['hora_inicio'])
            ->first();

        if ($existe) {
            $existe->delete();
            $bloqueado = false;
        } else {
            HorarioBloqueado::create([
                'odontologo_id' => $validado['odontologo_id'],
                'fecha'         => $validado['fecha'],
                'hora_inicio'   => $validado['hora_inicio'],
                'hora_fin'      => $horaFin,
                'motivo'        => $validado['motivo'] ?? null,
                // La columna created_by es NOT NULL: registra quién bloqueó.
                'created_by'    => auth()->id(),
            ]);
            $bloqueado = true;
        }

        // La vista usa fetch(), así que se responde en JSON.
        if ($request->expectsJson()) {
            return response()->json([
                'ok'        => true,
                'bloqueado' => $bloqueado,
                'motivo'    => $bloqueado ? ($validado['motivo'] ?? null) : null,
                'mensaje'   => $bloqueado ? 'Horario bloqueado.' : 'Horario liberado.',
            ]);
        }

        return back()->with('mensaje', 'Horario actualizado correctamente.');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function getSemana(?string $semanaParam): array
    {
        $lunes = $semanaParam
            ? Carbon::parse($semanaParam)->startOfWeek()
            : Carbon::now()->startOfWeek();

        return array_map(
            fn($i) => $lunes->copy()->addDays($i),
            range(0, 6)
        );
    }

    private function generarSlots(ConfiguracionHorario $config): array
    {
        $inicio   = Carbon::parse('2000-01-01 ' . $config->hora_inicio);
        $fin      = Carbon::parse('2000-01-01 ' . $config->hora_fin);
        $duracion = $config->duracion_slot;
        $slots    = [];
        $cursor   = $inicio->copy();

        while ($cursor->copy()->addMinutes($duracion)->lte($fin)) {
            $slots[] = $cursor->format('H:i');
            $cursor->addMinutes($duracion);
        }
        return $slots;
    }

    /**
     * Devuelve los bloqueos de la semana indexados por fecha y hora, con su
     * motivo: ['2026-09-01' => ['09:00' => 'Reunión', ...], ...]
     */
    private function getBloqueados(array $semana, ?int $odontologoId): array
    {
        if (!$odontologoId) return [];

        $fechas = array_map(fn($d) => $d->toDateString(), $semana);

        return HorarioBloqueado::where('odontologo_id', $odontologoId)
            ->whereIn('fecha', $fechas)
            ->get()
            ->groupBy(fn($b) => Carbon::parse($b->fecha)->toDateString())
            ->map(fn($grupo) => $grupo->mapWithKeys(fn($b) => [
                Carbon::parse($b->hora_inicio)->format('H:i') => $b->motivo,
            ])->toArray())
            ->toArray();
    }
}