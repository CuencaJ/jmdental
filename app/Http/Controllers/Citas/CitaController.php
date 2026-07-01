<?php

namespace App\Http\Controllers\Citas;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Odontologo;
use App\Models\Paciente;
use App\Models\Tratamiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitaController extends Controller
{
    // ============================
    // ADMINISTRADOR
    // ============================

    public function indexAdmin(Request $request)
    {
        $fechaFiltro = $request->get('fecha');

        $citas = Cita::with('paciente.user', 'odontologo.user')
            ->when($fechaFiltro, fn ($q) => $q->whereDate('fecha_hora', $fechaFiltro))
            ->orderBy('fecha_hora')
            ->get();

        return view('citas.listacitas', array_merge(
            ['citas' => $citas, 'fechaFiltro' => $fechaFiltro],
            $this->contadores($citas)
        ));
    }

    public function createAdmin()
    {
        $pacientes = Paciente::with('user')->get();
        $odontologos = Odontologo::with('user')->get();

        return view('citas.crearcita', compact('pacientes', 'odontologos'));
    }

    public function storeAdmin(Request $request)
    {
        $validated = $this->validarCita($request, true);

        $this->crearCita($validated, $validated['odontologo_id'], Auth::id());

        return redirect()->route('admin.citas.index')
            ->with('mensaje', 'Cita agregada correctamente.');
    }

    // ============================
    // ODONTÓLOGO
    // ============================

    public function indexOdontologo(Request $request)
    {
        $odontologo = Odontologo::where('user_id', Auth::id())->first();
        $fechaFiltro = $request->get('fecha');

        $citas = Cita::with('paciente.user')
            ->when($odontologo, fn ($q) => $q->where('odontologo_id', $odontologo->id))
            ->when($fechaFiltro, fn ($q) => $q->whereDate('fecha_hora', $fechaFiltro))
            ->orderBy('fecha_hora')
            ->get();

        return view('odontologo.agenda', array_merge(
            ['citas' => $citas, 'odontologo' => $odontologo, 'fechaFiltro' => $fechaFiltro],
            $this->contadores($citas)
        ));
    }

    public function createOdontologo()
    {
        $pacientes = Paciente::with('user')->get();
        return view('odontologo.odontologo-agendar-cita', compact('pacientes'));
    }

    public function storeOdontologo(Request $request)
    {
        $validated = $this->validarCita($request);
        $odontologo = Odontologo::where('user_id', Auth::id())->first();

        $this->crearCita($validated, $odontologo?->id, Auth::id());

        return redirect()->route('odontologo.agenda')
            ->with('mensaje', 'Cita agendada correctamente.');
    }

    // ============================
    // PACIENTE
    // ============================

    public function indexPaciente()
    {
        $paciente = Paciente::where('user_id', Auth::id())->first();

        $citas = Cita::with('odontologo.user')
            ->when($paciente, fn ($q) => $q->where('paciente_id', $paciente->id))
            ->orderBy('fecha_hora')
            ->get();

        return view('paciente.paciente-citas', compact('citas'));
    }

    public function createPaciente()
    {
        $odontologos = Odontologo::with('user')->get();
        return view('paciente.paciente-crearcita', compact('odontologos'));
    }

    public function storePaciente(Request $request)
    {
        $validated = $request->validate([
            'odontologo_id' => 'required|exists:odontologos,id',
            'fecha_hora'    => 'required|date|after:now',
            'motivo'        => 'required|string|max:255',
        ]);

        $paciente = Paciente::where('user_id', Auth::id())->first();

        if (!$paciente) {
            return back()->withErrors(['error' => 'No se encontró tu perfil de paciente. Contacta al administrador.']);
        }

        if (!$this->horarioDisponible($validated['odontologo_id'], $validated['fecha_hora'])) {
            return back()->withErrors(['fecha_hora' => 'El odontólogo ya tiene una cita agendada en ese horario.'])
                ->withInput();
        }

        Cita::create([
            'paciente_id'   => $paciente->id,
            'odontologo_id' => $validated['odontologo_id'],
            'user_id'       => Auth::id(),
            'fecha_hora'    => $validated['fecha_hora'],
            'estado'        => 'pendiente',
            'motivo'        => $validated['motivo'],
        ]);

        return redirect()->route('paciente.citas')
            ->with('mensaje', 'Cita solicitada correctamente. Espera la confirmación.');
    }

    // ============================
    // COMPARTIDO (todos los roles)
    // ============================

    public function updateEstado(Request $request, $id)
    {
        $validated = $request->validate([
            'estado' => 'required|in:pendiente,confirmada,completada,cancelada',
        ]);

        $cita = Cita::findOrFail($id);
        $estadoAnterior = $cita->estado;
        $cita->update(['estado' => $validated['estado']]);

        if ($validated['estado'] === 'completada' && $estadoAnterior !== 'completada') {
            $yaExiste = Tratamiento::where('cita_id', $cita->id)->exists();
            if (!$yaExiste) {
                Tratamiento::create([
                    'cita_id'           => $cita->id,
                    'nombre'            => $cita->motivo,
                    'descripcion'       => null,
                    'costo'             => 0,
                    'fecha_tratamiento' => $cita->fecha_hora->toDateString(),
                    'estado'            => 'en_proceso',
                    'observaciones'     => null,
                ]);
            }
        }

        $usuario = Auth::user();
        $ruta = $usuario->hasRole('administrador') ? 'admin.citas.index' : 'odontologo.agenda';

        return redirect()->route($ruta)->with('mensaje', 'Estado de la cita actualizado.');
    }

    // ============================
    // MÉTODOS PRIVADOS DE APOYO
    // ============================

    private function validarCita(Request $request, bool $conOdontologo = false): array
    {
        $reglas = [
            'paciente_id' => 'required|exists:pacientes,id',
            'fecha_hora'  => 'required|date',
            'motivo'      => 'required|string|max:255',
            'estado'      => 'required|in:pendiente,confirmada,completada,cancelada',
            'notas'       => 'nullable|string',
        ];

        if ($conOdontologo) {
            $reglas['odontologo_id'] = 'required|exists:odontologos,id';
        }

        return $request->validate($reglas);
    }

    private function crearCita(array $datos, $odontologoId, $userId): Cita
    {
        return Cita::create([
            'paciente_id'   => $datos['paciente_id'],
            'odontologo_id' => $odontologoId,
            'user_id'       => $userId,
            'fecha_hora'    => $datos['fecha_hora'],
            'estado'        => $datos['estado'],
            'motivo'        => $datos['motivo'],
            'notas'         => $datos['notas'] ?? null,
        ]);
    }

    private function contadores($citas): array
    {
        return [
            'totalCitas'  => $citas->count(),
            'confirmadas' => $citas->where('estado', 'confirmada')->count(),
            'completadas' => $citas->where('estado', 'completada')->count(),
            'pendientes'  => $citas->where('estado', 'pendiente')->count(),
            'canceladas'  => $citas->where('estado', 'cancelada')->count(),
        ];
    }

    private function horarioDisponible($odontologoId, $fechaHora): bool
    {
        return !Cita::where('odontologo_id', $odontologoId)
            ->where('fecha_hora', $fechaHora)
            ->where('estado', '!=', 'cancelada')
            ->exists();
    }
}