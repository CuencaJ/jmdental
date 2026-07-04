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
        $usuario   = User::role('paciente')->with('paciente')->findOrFail($pacienteId);
        $paciente  = $usuario->paciente;
        $odontologo = Odontologo::where('user_id', Auth::id())->first();

        // Si ya existe historia clínica redirigir a ver/editar
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
        ]);

        HistoriaClinica::create([
            'paciente_id'             => $paciente->id,
            'odontologo_id'           => $odontologo->id,
            'fecha_apertura'          => now()->toDateString(),
            'motivo_consulta'         => $validated['motivo_consulta'],
            'enfermedad_actual'       => $validated['enfermedad_actual'],
            'antecedentes_personales' => $validated['antecedentes_personales'],
            'antecedentes_familiares' => $validated['antecedentes_familiares'],
            'temperatura'             => $validated['temperatura'],
            'pulso'                   => $validated['pulso'],
            'frecuencia_respiratoria' => $validated['frecuencia_respiratoria'],
            'presion_arterial'        => $validated['presion_arterial'],
            'examen_extraoral'        => $validated['examen_extraoral'],
            'examen_intraoral'        => $validated['examen_intraoral'],
            'diagnostico_inicial'     => $validated['diagnostico_inicial'],
            'completado'              => true,
        ]);

        return redirect()->route('odontologo.pacientes.show', $pacienteId)
            ->with('mensaje', 'Historia clínica inicial registrada correctamente.');
    }

    // Ver/editar historia clínica existente
    public function edit($pacienteId)
    {
        $usuario        = User::role('paciente')->with('paciente.historiaClinica')->findOrFail($pacienteId);
        $paciente       = $usuario->paciente;
        $historia       = $paciente?->historiaClinica;
        $odontologo     = Odontologo::where('user_id', Auth::id())->first();

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
        ]);

        $historia->update($validated);

        return redirect()->route('odontologo.historia.edit', $pacienteId)
            ->with('mensaje', 'Historia clínica actualizada correctamente.');
    }

    // Descargar formulario 033 PDF completo
    public function pdf($pacienteId)
    {
        $usuario      = User::role('paciente')->with('paciente.historiaClinica')->findOrFail($pacienteId);
        $paciente     = $usuario;
        $historia     = $usuario->paciente?->historiaClinica;

        $tratamientos = Tratamiento::whereHas('cita', fn($q) =>
            $q->where('paciente_id', $usuario->paciente?->id)
        )->with(['cita.odontologo.user', 'piezas'])
        ->orderBy('fecha_tratamiento')
        ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'odontologo.historia-clinica.formulario-033',
            compact('paciente', 'historia', 'tratamientos')
        );
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('formulario-033-' . \Illuminate\Support\Str::slug($usuario->name) . '.pdf');
    }
}