<?php

namespace App\Http\Controllers\Odontologo;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Odontologo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        $odontologo = Odontologo::where('user_id', Auth::id())->first();

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

        return view('odontologo.agenda', compact(
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
}