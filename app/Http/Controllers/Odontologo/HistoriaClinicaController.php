<?php

namespace App\Http\Controllers\Odontologo;

use App\Http\Controllers\Controller;
use App\Models\HistoriaClinica;
use App\Models\Odontologo;
use App\Models\Paciente;
use App\Models\Tratamiento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoriaClinicaController extends Controller
{
    // Mostrar formulario para llenar la historia clínica inicial
    public function create($pacienteId)
    {
        $usuario    = User::role('paciente')->with('paciente')->findOrFail($pacienteId);
        $paciente   = $usuario->paciente;
        $odontologo = Odontologo::where('user_id', Auth::id())->first();

        if ($paciente && $paciente->historiaClinica) {
            return redirect()->route('odontologo.historia.edit', $pacienteId)
                ->with('info', 'Este paciente ya tiene una historia clínica. Puedes editarla aquí.');
        }

        return view('odontologo.historia-clinica.crear', compact('usuario', 'paciente', 'odontologo'));
    }

    // Guardar historia clínica inicial
    public function store(Request $request, $pacienteId)
    {
        $usuario    = User::role('paciente')->with('paciente')->findOrFail($pacienteId);
        $paciente   = $usuario->paciente;
        $odontologo = Odontologo::where('user_id', Auth::id())->first();

        $validated = $request->validate([
            'motivo_consulta'         => 'required|string|max:500',
            'enfermedad_actual'       => 'nullable|string',
            'antecedentes_personales' => 'nullable|string',
            'antecedentes_familiares' => 'nullable|string',
            'temperatura'             => 'nullable|string|max:10',
            'pulso'                   => 'nullable|string|max:10',
            'frecuencia_respiratoria' => 'nullable|string|max:10',
            'presion_arterial'        => 'nullable|string|max:20',
            'examen_extraoral'        => 'nullable|string',
            'examen_intraoral'        => 'nullable|string',
            'diagnostico_inicial'     => 'nullable|string',
            'segundo_nombre'          => 'nullable|string|max:100',
            'segundo_apellido'        => 'nullable|string|max:100',
            'embarazada'              => 'nullable|boolean',
            'condicion_edad'          => 'nullable|string|max:10',
        ]);

        HistoriaClinica::create([
            'paciente_id'             => $paciente->id,
            'odontologo_id'           => $odontologo->id,
            'fecha_apertura'          => now()->toDateString(),
            'motivo_consulta'         => $validated['motivo_consulta'],
            'enfermedad_actual'       => $validated['enfermedad_actual'] ?? null,
            'antecedentes_personales' => $validated['antecedentes_personales'] ?? null,
            'antecedentes_familiares' => $validated['antecedentes_familiares'] ?? null,
            'temperatura'             => $validated['temperatura'] ?? null,
            'pulso'                   => $validated['pulso'] ?? null,
            'frecuencia_respiratoria' => $validated['frecuencia_respiratoria'] ?? null,
            'presion_arterial'        => $validated['presion_arterial'] ?? null,
            'examen_extraoral'        => $validated['examen_extraoral'] ?? null,
            'examen_intraoral'        => $validated['examen_intraoral'] ?? null,
            'diagnostico_inicial'     => $validated['diagnostico_inicial'] ?? null,
            'segundo_nombre'          => $validated['segundo_nombre'] ?? null,
            'segundo_apellido'        => $validated['segundo_apellido'] ?? null,
            'embarazada'              => $request->embarazada ?? false,
            'condicion_edad'          => $validated['condicion_edad'] ?? 'anios',
            'completado'              => true,
        ]);

        return redirect()->route('odontologo.pacientes.show', $pacienteId)
            ->with('mensaje', 'Historia clínica inicial registrada correctamente.');
    }

    // Ver/editar historia clínica existente
    public function edit($pacienteId)
    {
        $usuario    = User::role('paciente')->with('paciente.historiaClinica')->findOrFail($pacienteId);
        $paciente   = $usuario->paciente;
        $historia   = $paciente?->historiaClinica;
        $odontologo = Odontologo::where('user_id', Auth::id())->first();

        $tratamientos = Tratamiento::whereHas('cita', fn($q) =>
            $q->where('paciente_id', $paciente?->id)
        )->with(['cita.odontologo.user', 'piezas'])
        ->orderBy('fecha_tratamiento')
        ->get();

        return view('odontologo.historia-clinica.ver', compact(
            'usuario', 'paciente', 'historia', 'odontologo', 'tratamientos'
        ));
    }

    // Actualizar historia clínica
    public function update(Request $request, $pacienteId)
    {
        $usuario  = User::role('paciente')->with('paciente.historiaClinica')->findOrFail($pacienteId);
        $historia = $usuario->paciente?->historiaClinica;

        if (!$historia) {
            return redirect()->route('odontologo.historia.create', $pacienteId);
        }

        $validated = $request->validate([
            'motivo_consulta'         => 'required|string|max:500',
            'enfermedad_actual'       => 'nullable|string',
            'antecedentes_personales' => 'nullable|string',
            'antecedentes_familiares' => 'nullable|string',
            'temperatura'             => 'nullable|string|max:10',
            'pulso'                   => 'nullable|string|max:10',
            'frecuencia_respiratoria' => 'nullable|string|max:10',
            'presion_arterial'        => 'nullable|string|max:20',
            'examen_extraoral'        => 'nullable|string',
            'examen_intraoral'        => 'nullable|string',
            'diagnostico_inicial'     => 'nullable|string',
            'segundo_nombre'          => 'nullable|string|max:100',
            'segundo_apellido'        => 'nullable|string|max:100',
            'embarazada'              => 'nullable|boolean',
            'condicion_edad'          => 'nullable|string|max:10',
        ]);

        $historia->update(array_merge($validated, [
            'embarazada'     => $request->embarazada ?? false,
            'condicion_edad' => $validated['condicion_edad'] ?? 'anios',
        ]));

        return redirect()->route('odontologo.historia.edit', $pacienteId)
            ->with('mensaje', 'Historia clínica actualizada correctamente.');
    }

    // Descargar formulario 033 PDF completo
    public function pdf($pacienteId)
    {
        $usuario  = User::role('paciente')->with('paciente.historiaClinica')->findOrFail($pacienteId);
        $historia = $usuario->paciente?->historiaClinica;

        $tratamientos = \App\Models\Tratamiento::whereHas('cita', fn($q) =>
            $q->where('paciente_id', $usuario->paciente?->id)
        )->with(['cita.odontologo.user', 'piezas'])
        ->orderBy('fecha_tratamiento')
        ->get();

        $service = new \App\Services\Formulario033Service();
        $pdf = $service->generar($usuario, $historia, $tratamientos);

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="formulario-033-' . \Illuminate\Support\Str::slug($usuario->name) . '.pdf"');
    }
}