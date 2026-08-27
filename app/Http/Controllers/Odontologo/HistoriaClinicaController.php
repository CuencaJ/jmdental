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

    private function filtrarHos(array $datos): array
    {
        return array_filter($datos, fn($v) => $v !== '' && $v !== null);
    }

    public function store(Request $request, $pacienteId)
    {
        $usuario    = User::role('paciente')->with('paciente')->findOrFail($pacienteId);
        $paciente   = $usuario->paciente;
        $odontologo = Odontologo::where('user_id', Auth::id())->first();

        $hosPlaca      = $this->filtrarHos($request->hos_placa ?? []);
        $hosCalculo    = $this->filtrarHos($request->hos_calculo ?? []);
        $hosGingivitis = $this->filtrarHos($request->hos_gingivitis ?? []);
        $hosExaminada  = $request->hos_examinada ?? [];

        $validated = $request->validate([
            'motivo_consulta'            => 'required|string|max:500',
            'enfermedad_actual'          => 'nullable|string',
            'antecedentes_personales'    => 'nullable|string',
            'antecedentes_familiares'    => 'nullable|string',
            'temperatura'                => 'nullable|string|max:10',
            'pulso'                      => 'nullable|string|max:10',
            'frecuencia_respiratoria'    => 'nullable|string|max:10',
            'presion_arterial'           => 'nullable|string|max:20',
            'examen_extraoral'           => 'nullable|string',
            'examen_intraoral'           => 'nullable|string',
            'diagnostico_inicial'        => 'nullable|string',
            'segundo_nombre'             => 'nullable|string|max:100',
            'segundo_apellido'           => 'nullable|string|max:100',
            'embarazada'                 => 'nullable|boolean',
            'condicion_edad'             => 'nullable|string|max:10',
            'tipo_oclusion'              => 'nullable|string|max:20',
            'nivel_fluorosis'            => 'nullable|string|max:20',
            'cpo_c'                      => 'nullable|integer|min:0',
            'cpo_p'                      => 'nullable|integer|min:0',
            'cpo_o'                      => 'nullable|integer|min:0',
            'ceo_c'                      => 'nullable|integer|min:0',
            'ceo_e'                      => 'nullable|integer|min:0',
            'ceo_o'                      => 'nullable|integer|min:0',
            'diagnosticos'               => 'nullable|array',
            'diagnosticos.*.descripcion' => 'nullable|string',
            'diagnosticos.*.cie'         => 'nullable|string|max:20',
            'diagnosticos.*.tipo'        => 'nullable|in:pre,def,ambos',
            'examenes_pedido'            => 'nullable|string',
            'examenes_biometria'         => 'nullable|boolean',
            'examenes_quimica'           => 'nullable|boolean',
            'examenes_rayos_x'           => 'nullable|boolean',
            'examenes_otros'             => 'nullable|string|max:255',
            'examenes_informe'           => 'nullable|string',
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
            'hos_placa'               => !empty($hosPlaca) ? $hosPlaca : null,
            'hos_calculo'             => !empty($hosCalculo) ? $hosCalculo : null,
            'hos_gingivitis'          => !empty($hosGingivitis) ? $hosGingivitis : null,
            'hos_examinada'           => !empty($hosExaminada) ? $hosExaminada : null,
            'tipo_oclusion'           => $validated['tipo_oclusion'] ?? null,
            'nivel_fluorosis'         => $validated['nivel_fluorosis'] ?? null,
            'cpo_c'                   => $validated['cpo_c'] ?? 0,
            'cpo_p'                   => $validated['cpo_p'] ?? 0,
            'cpo_o'                   => $validated['cpo_o'] ?? 0,
            'ceo_c'                   => $validated['ceo_c'] ?? 0,
            'ceo_e'                   => $validated['ceo_e'] ?? 0,
            'ceo_o'                   => $validated['ceo_o'] ?? 0,
            'diagnosticos'            => $validated['diagnosticos'] ?? null,
            'examenes_pedido'         => $validated['examenes_pedido'] ?? null,
            'examenes_biometria'      => $request->has('examenes_biometria'),
            'examenes_quimica'        => $request->has('examenes_quimica'),
            'examenes_rayos_x'        => $request->has('examenes_rayos_x'),
            'examenes_otros'          => $validated['examenes_otros'] ?? null,
            'examenes_informe'        => $validated['examenes_informe'] ?? null,
            'completado'              => true,
        ]);

        return redirect()->route('odontologo.pacientes.show', $pacienteId)
            ->with('mensaje', 'Historia clínica inicial registrada correctamente.');
    }

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

    public function update(Request $request, $pacienteId)
    {
        $usuario  = User::role('paciente')->with('paciente.historiaClinica')->findOrFail($pacienteId);
        $historia = $usuario->paciente?->historiaClinica;

        if (!$historia) {
            return redirect()->route('odontologo.historia.create', $pacienteId);
        }

        $hosPlaca      = $this->filtrarHos($request->hos_placa ?? []);
        $hosCalculo    = $this->filtrarHos($request->hos_calculo ?? []);
        $hosGingivitis = $this->filtrarHos($request->hos_gingivitis ?? []);
        $hosExaminada  = $request->hos_examinada ?? [];

        $validated = $request->validate([
            'motivo_consulta'            => 'required|string|max:500',
            'enfermedad_actual'          => 'nullable|string',
            'antecedentes_personales'    => 'nullable|string',
            'antecedentes_familiares'    => 'nullable|string',
            'temperatura'                => 'nullable|string|max:10',
            'pulso'                      => 'nullable|string|max:10',
            'frecuencia_respiratoria'    => 'nullable|string|max:10',
            'presion_arterial'           => 'nullable|string|max:20',
            'examen_extraoral'           => 'nullable|string',
            'examen_intraoral'           => 'nullable|string',
            'diagnostico_inicial'        => 'nullable|string',
            'segundo_nombre'             => 'nullable|string|max:100',
            'segundo_apellido'           => 'nullable|string|max:100',
            'embarazada'                 => 'nullable|boolean',
            'condicion_edad'             => 'nullable|string|max:10',
            'tipo_oclusion'              => 'nullable|string|max:20',
            'nivel_fluorosis'            => 'nullable|string|max:20',
            'cpo_c'                      => 'nullable|integer|min:0',
            'cpo_p'                      => 'nullable|integer|min:0',
            'cpo_o'                      => 'nullable|integer|min:0',
            'ceo_c'                      => 'nullable|integer|min:0',
            'ceo_e'                      => 'nullable|integer|min:0',
            'ceo_o'                      => 'nullable|integer|min:0',
            'diagnosticos'               => 'nullable|array',
            'diagnosticos.*.descripcion' => 'nullable|string',
            'diagnosticos.*.cie'         => 'nullable|string|max:20',
            'diagnosticos.*.tipo'        => 'nullable|in:pre,def,ambos',
            'examenes_pedido'            => 'nullable|string',
            'examenes_biometria'         => 'nullable|boolean',
            'examenes_quimica'           => 'nullable|boolean',
            'examenes_rayos_x'           => 'nullable|boolean',
            'examenes_otros'             => 'nullable|string|max:255',
            'examenes_informe'           => 'nullable|string',
        ]);

        $historia->update([
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
            'hos_placa'               => !empty($hosPlaca) ? $hosPlaca : $historia->hos_placa,
            'hos_calculo'             => !empty($hosCalculo) ? $hosCalculo : $historia->hos_calculo,
            'hos_gingivitis'          => !empty($hosGingivitis) ? $hosGingivitis : $historia->hos_gingivitis,
            'hos_examinada'           => !empty($hosExaminada) ? $hosExaminada : $historia->hos_examinada,
            'tipo_oclusion'           => $validated['tipo_oclusion'] ?? null,
            'nivel_fluorosis'         => $validated['nivel_fluorosis'] ?? null,
            'cpo_c'                   => $validated['cpo_c'] ?? 0,
            'cpo_p'                   => $validated['cpo_p'] ?? 0,
            'cpo_o'                   => $validated['cpo_o'] ?? 0,
            'ceo_c'                   => $validated['ceo_c'] ?? 0,
            'ceo_e'                   => $validated['ceo_e'] ?? 0,
            'ceo_o'                   => $validated['ceo_o'] ?? 0,
            'diagnosticos'            => $validated['diagnosticos'] ?? null,
            'examenes_pedido'         => $validated['examenes_pedido'] ?? null,
            'examenes_biometria'      => $request->has('examenes_biometria'),
            'examenes_quimica'        => $request->has('examenes_quimica'),
            'examenes_rayos_x'        => $request->has('examenes_rayos_x'),
            'examenes_otros'          => $validated['examenes_otros'] ?? null,
            'examenes_informe'        => $validated['examenes_informe'] ?? null,
        ]);

        return redirect()->route('odontologo.historia.edit', $pacienteId)
            ->with('mensaje', 'Historia clínica actualizada correctamente.');
    }

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