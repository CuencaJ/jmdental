<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionHorario;
use App\Services\HorarioDisponibilidadService;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    // Vista de configuración del horario (admin)
    public function index()
    {
        $config = ConfiguracionHorario::obtener();
        return view('admin.configuracion-horario', compact('config'));
    }

    // Guardar configuración del horario
    public function update(Request $request)
    {
        $request->validate([
            'hora_inicio'     => 'required|date_format:H:i',
            'hora_fin'        => 'required|date_format:H:i|after:hora_inicio',
            'duracion_slot'   => 'required|integer|in:15,30,45,60,90,120',
            'dias_laborables' => 'required|array|min:1',
            'dias_laborables.*' => 'in:1,2,3,4,5,6,7',
        ], [
            'hora_fin.after'        => 'La hora de cierre debe ser posterior a la de apertura.',
            'dias_laborables.min'   => 'Selecciona al menos un día laborable.',
        ]);

        $config = ConfiguracionHorario::obtener();
        $config->update([
            'hora_inicio'     => $request->hora_inicio . ':00',
            'hora_fin'        => $request->hora_fin . ':00',
            'duracion_slot'   => $request->duracion_slot,
            'dias_laborables' => $request->dias_laborables,
        ]);

        return redirect()->route('admin.horario.index')
            ->with('mensaje', 'Horario de atención actualizado correctamente.');
    }

    // Endpoint AJAX — devuelve slots disponibles para una fecha y odontólogo
    public function slotsDisponibles(Request $request)
    {
        $request->validate([
            'fecha'          => 'required|date',
            'odontologo_id'  => 'nullable|exists:odontologos,id',
            'excluir_cita'   => 'nullable|integer',
        ]);

        $service = new HorarioDisponibilidadService();
        $slots = $service->getSlotsDisponibles(
            $request->fecha,
            $request->odontologo_id,
            $request->excluir_cita
        );

        return response()->json(['slots' => $slots]);
    }
}