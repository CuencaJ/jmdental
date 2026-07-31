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
        $request->validate([
            'odontologo_id' => 'required|exists:odontologos,id',
            'fecha'         => 'required|date',
            'hora_inicio'   => 'required',
            'hora_fin'      => 'required',
        ]);

        $existe = HorarioBloqueado::where('odontologo_id', $request->odontologo_id)
            ->where('fecha', $request->fecha)
            ->where('hora_inicio', $request->hora_inicio)
            ->where('hora_fin', $request->hora_fin)
            ->first();

        if ($existe) {
            $existe->delete();
        } else {
            HorarioBloqueado::create([
                'odontologo_id' => $request->odontologo_id,
                'fecha'         => $request->fecha,
                'hora_inicio'   => $request->hora_inicio,
                'hora_fin'      => $request->hora_fin,
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

    private function getBloqueados(array $semana, ?int $odontologoId): array
    {
        if (!$odontologoId) return [];

        $fechas = array_map(fn($d) => $d->toDateString(), $semana);

        return HorarioBloqueado::where('odontologo_id', $odontologoId)
            ->whereIn('fecha', $fechas)
            ->get()
            ->groupBy('fecha')
            ->map(fn($g) => $g->pluck('hora_inicio')->toArray())
            ->toArray();
    }
}