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
        'segundo_nombre',
        'segundo_apellido',
        'embarazada',
        'condicion_edad',
        'hos_placa',
        'hos_calculo',
        'hos_gingivitis',
        'tipo_oclusion',
        'nivel_fluorosis',
        'cpo_c', 'cpo_p', 'cpo_o',
        'ceo_c', 'ceo_e', 'ceo_o',
        'diagnosticos',
        'examenes_pedido',
        'examenes_biometria',
        'examenes_quimica',
        'examenes_rayos_x',
        'examenes_otros',
        'examenes_informe',
        'hos_examinada', 
    ];

    protected $casts = [
        'fecha_apertura' => 'date',
        'completado'     => 'boolean',
        'embarazada'     => 'boolean',
        'hos_placa'      => 'array',
        'hos_calculo'    => 'array',
        'hos_gingivitis' => 'array',
        'diagnosticos'   => 'array',
        'examenes_biometria' => 'boolean',
        'examenes_quimica'   => 'boolean',
        'examenes_rayos_x'   => 'boolean',
        'hos_examinada' => 'array',
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