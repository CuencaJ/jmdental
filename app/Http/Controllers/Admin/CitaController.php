<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Cita;
use App\Models\Odontologo;
use App\Models\Paciente;
use App\Models\Tratamiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitaController extends Controller
{
    public function index(Request $request)
    {
        $odontologo = Odontologo::with('user')->first();

        $fechaFiltro = $request->get('fecha');

        $citas = Cita::with('paciente.user')
            ->when($odontologo, fn ($q) => $q->where('odontologo_id', $odontologo->id))
            ->when($fechaFiltro, fn ($q) => $q->whereDate('fecha_hora', $fechaFiltro))
            ->orderBy('fecha_hora')
            ->get();

        $totalCitas = $citas->count();
        $confirmadas = $citas->where('estado', 'confirmada')->count();
        $completadas = $citas->where('estado', 'completada')->count();
        $pendientes = $citas->where('estado', 'pendiente')->count();
        $canceladas = $citas->where('estado', 'cancelada')->count();

        return view('citas.listacitas', compact(
            'citas',
            'odontologo',
            'fechaFiltro',
            'totalCitas',
            'confirmadas',
            'completadas',
            'pendientes',
            'canceladas'
        ));
    }

    public function create()
    {
        $pacientes = Paciente::with('user')->get();

        return view('citas.crearcita', compact('pacientes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'fecha_hora' => 'required|date',
            'motivo' => 'required|string|max:255',
            'estado' => 'required|in:pendiente,confirmada,completada,cancelada',
            'notas' => 'nullable|string',
        ]);

        $odontologo = Odontologo::first();

        Cita::create([
            'paciente_id' => $validated['paciente_id'],
            'odontologo_id' => $odontologo?->id,
            'user_id' => Auth::id(),
            'fecha_hora' => $validated['fecha_hora'],
            'estado' => $validated['estado'],
            'motivo' => $validated['motivo'],
            'notas' => $validated['notas'] ?? null,
        ]);

        return redirect()->route('admin.citas.index')->with('mensaje', 'Cita agregada correctamente.');
    }

    public function updateEstado(Request $request, $id)
    {
        $validated = $request->validate([
            'estado' => 'required|in:pendiente,confirmada,completada,cancelada',
        ]);

        $cita = Cita::findOrFail($id);
        $estadoAnterior = $cita->estado;
        $cita->update(['estado' => $validated['estado']]);

        // Si cambia a completada y no tiene tratamiento aún, se crea automáticamente
        if ($validated['estado'] === 'completada' && $estadoAnterior !== 'completada') {
            $yaExiste = Tratamiento::where('cita_id', $cita->id)->exists();
            if (!$yaExiste) {
                Tratamiento::create([
                    'cita_id' => $cita->id,
                    'nombre' => $cita->motivo,
                    'descripcion' => null,
                    'costo' => 0,
                    'fecha_tratamiento' => $cita->fecha_hora->toDateString(),
                    'estado' => 'en_proceso',
                    'observaciones' => null,
                ]);
            }
        }

        return redirect()->route('admin.citas.index')->with('mensaje', 'Estado de la cita actualizado.');
    }
}