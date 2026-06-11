<?php

namespace App\Http\Controllers\Odontologo;

use App\Http\Controllers\Controller;
use App\Models\User;

class PacienteController extends Controller
{
    // Ver lista de todos los pacientes
    public function index()
    {
        $pacientes = User::role('paciente')->get();
        $totalPacientes = $pacientes->count();

        return view('odontologo.pacientes.odontologo-pacientes-lista', compact(
            'pacientes',
            'totalPacientes'
        ));
    }

    // Ver detalle de un paciente
    public function show($id)
    {
        $paciente = User::role('paciente')->findOrFail($id);
        return view('odontologo.pacientes.odontologo-pacientes-detalle', compact('paciente'));
    }
}