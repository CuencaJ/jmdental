<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionHorario;
use App\Models\HorarioBloqueado;
use App\Models\Odontologo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SemanaController extends Controller
{
    // Vista semanal del odontólogo
    public function odontologoIndex(Request $request)
    {
        $odontologo = Odontologo::where('user_id', Auth::id())->first();
        $semana     = $this->getSemana($request->get('semana'));
        $config     = ConfiguracionHorario::obtener();
        $slots      = $this->generarSlots($config);
        $bloqueados = $this->getBloqueados($semana, $odontologo?->id);

        return view('semana.preparar-semana', compact(
            'odontologo', 'semana', 'slots', 'bloqueados', 'config'
        ));
    }

    // Vista semanal del admin
    public function adminIndex(Request $request)
    {
        $odontologos  = Odontologo::with('user')->get();
        $odontologoId = $request->get('odontologo_id', $odontologos->first()?->id);
        $odontologo   = Odontologo::find($odontologoId);
        $semana       = $this->getSemana($request->get('semana'));
        $config       = ConfiguracionHorario::obtener();
        $slots        = $this->generarSlots($config);
        $bloqueados   = $this->getBloqueados($semana, $odontologoId);

        return view('semana.preparar-semana-admin', compact(
            'odontologos', 'odontologo', 'semana', 'slots', 'bloqueados', 'config'
        ));
    }

    // Bloquear un slot
    public function bloquear(Request $request)
    {
        $validated = $request->validate([
            'odontologo_id' => 'required|exists:odontologos,id',
            'fecha'         => 'required|date',
            'hora_inicio'   => 'required|date_format:H:i',
            'motivo'        => 'nullable|string|max:255',
        ]);

        $config      = ConfiguracionHorario::obtener();
        $horaFin     = Carbon::parse($validated['fecha'] . ' ' . $validated['hora_inicio'])
            ->addMinutes($config->duracion_slot)
            ->format('H:i');

        // Si ya existe ese bloqueo, desbloquear (toggle)
        $existente = HorarioBloqueado::where('odontologo_id', $validated['odontologo_id'])
            ->whereDate('fecha', $validated['fecha'])
            ->where('hora_inicio', $validated['hora_inicio'] . ':00')
            ->first();

        if ($existente) {
            $existente->delete();
            return response()->json(['accion' => 'desbloqueado', 'mensaje' => 'Horario desbloqueado.']);
        }

        HorarioBloqueado::create([
            'odontologo_id' => $validated['odontologo_id'],
            'fecha'         => $validated['fecha'],
            'hora_inicio'   => $validated['hora_inicio'] . ':00',
            'hora_fin'      => $horaFin . ':00',
            'motivo'        => $validated['motivo'] ?? 'Bloqueado',
            'created_by'    => Auth::id(),
        ]);

        return response()->json(['accion' => 'bloqueado', 'mensaje' => 'Horario bloqueado correctamente.']);
    }

    // ============================
    // MÉTODOS PRIVADOS
    // ============================

    private function getSemana(?string $fechaBase): array
    {
        $inicio = $fechaBase
            ? Carbon::parse($fechaBase)->startOfWeek()
            : Carbon::now()->startOfWeek();

        $dias = [];
        for ($i = 0; $i < 7; $i++) {
            $dias[] = $inicio->copy()->addDays($i);
        }
        return $dias;
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

        $inicio = $semana[0]->toDateString();
        $fin    = $semana[6]->toDateString();

        return HorarioBloqueado::where('odontologo_id', $odontologoId)
            ->whereBetween('fecha', [$inicio, $fin])
            ->get()
            ->groupBy(fn($b) => $b->fecha->toDateString())
            ->map(fn($grupo) => $grupo->pluck('hora_inicio')->map(fn($h) => substr($h, 0, 5))->toArray())
            ->toArray();
    }
}