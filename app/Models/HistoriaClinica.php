<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriaClinica extends Model
{
    use HasFactory;

    protected $table = 'historias_clinicas';

    protected $fillable = [
        'paciente_id',
        'odontologo_id',
        'fecha_apertura',
        'motivo_consulta',
        'enfermedad_actual',
        'antecedentes_personales',
        'antecedentes_familiares',
        'temperatura',
        'pulso',
        'frecuencia_respiratoria',
        'presion_arterial',
        'examen_extraoral',
        'examen_intraoral',
        'diagnostico_inicial',
        'completado',
    ];

    protected $casts = [
        'fecha_apertura' => 'date',
        'completado'     => 'boolean',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function odontologo()
    {
        return $this->belongsTo(Odontologo::class);
    }
}