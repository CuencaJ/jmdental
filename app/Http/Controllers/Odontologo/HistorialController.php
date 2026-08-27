<?php

namespace App\Http\Controllers\Odontologo;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Odontologo;
use App\Models\Paciente;
use App\Models\Tratamiento;
use App\Models\TratamientoPieza;
use App\Models\ArchivoTratamiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HistorialController extends Controller
{
    public function index()
    {
        $odontologo = Odontologo::where('user_id', Auth::id())->first();

        $pacientes = Paciente::with(['user', 'citas' => function ($q) use ($odontologo) {
            $q->when($odontologo, fn($q) => $q->where('odontologo_id', $odontologo->id))
              ->with(['tratamiento.archivos', 'tratamiento.piezas']);
        }])
        ->whereHas('citas', fn($q) => $q->when($odontologo, fn($q) => $q->where('odontologo_id', $odontologo->id)))
        ->get();

        return view('odontologo.historial', compact('pacientes'));
    }

    public function ver($id)
    {
        $tratamiento = Tratamiento::with([
            'cita.paciente.user',
            'archivos',
            'piezas'
        ])->findOrFail($id);

        return view('odontologo.vertratamiento', compact('tratamiento'));
    }

    public function editar($id)
    {
        $tratamiento = Tratamiento::with([
            'cita.paciente.user',
            'archivos',
            'piezas'
        ])->findOrFail($id);

        return view('odontologo.editartratamiento', compact('tratamiento'));
    }

    public function actualizar(Request $request, $id)
    {
        $tratamiento = Tratamiento::findOrFail($id);

        $validated = $request->validate([
            'nombre'       => 'required|string|max:255',
            'descripcion'  => 'nullable|string',
            'costo'        => 'nullable|numeric|min:0',
            'observaciones'=> 'nullable|string',
            'archivos.*'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $tratamiento->update([
            'nombre'        => $validated['nombre'],
            'descripcion'   => $validated['descripcion'] ?? null,
            'costo'         => $validated['costo'] ?? 0,
            'observaciones' => $validated['observaciones'] ?? null,
            'estado'        => 'completado',
        ]);

        // Guardar piezas
        if ($request->has('piezas')) {
            $tratamiento->piezas()->delete();
            foreach ($request->piezas as $pieza) {
                if (empty($pieza['pieza_numero'])) continue;
                TratamientoPieza::create([
                    'tratamiento_id'  => $tratamiento->id,
                    'pieza_numero'    => $pieza['pieza_numero'],
                    'tipo_denticion'  => $pieza['tipo_denticion'] ?? 'permanente',
                    'cara'            => $pieza['cara'] ?? null,
                    'procedimiento'   => $pieza['procedimiento'] ?? null,
                    'diagnostico'     => $pieza['diagnostico'] ?? null,
                    'ausente'         => ($pieza['ausente'] ?? '0') === '1',
                    'movilidad'       => $pieza['movilidad'] ?? null,
                    'recesion'        => $pieza['recesion'] ?? null,
                ]);
            }
        }

        // Guardar archivos
        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $archivo) {
                $ruta = $archivo->store('tratamientos', 'public');
                ArchivoTratamiento::create([
                    'tratamiento_id'  => $tratamiento->id,
                    'nombre_archivo'  => $archivo->getClientOriginalName(),
                    'ruta_archivo'    => $ruta,
                    'tipo_archivo'    => $archivo->getMimeType(),
                    'tamanio_archivo' => $archivo->getSize(),
                ]);
            }
        }

        return redirect()->route('odontologo.historial')
            ->with('mensaje', 'Tratamiento actualizado correctamente.');
    }

    public function eliminarArchivo($id)
    {
        $archivo = ArchivoTratamiento::findOrFail($id);
        Storage::disk('public')->delete($archivo->ruta_archivo);
        $archivo->delete();

        return back()->with('mensaje', 'Archivo eliminado correctamente.');
    }
}