<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionHorario extends Model
{
    protected $table = 'configuracion_horarios';

    protected $fillable = [
        'hora_inicio',
        'hora_fin',
        'duracion_slot',
        'dias_laborables',
    ];

    protected $casts = [
        'dias_laborables' => 'array',
    ];

    // Obtener la configuración única (singleton)
    public static function obtener(): self
    {
        return self::firstOrCreate([], [
            'hora_inicio'     => '08:00:00',
            'hora_fin'        => '20:00:00',
            'duracion_slot'   => 60,
            'dias_laborables' => ['1','2','3','4','5'],
        ]);
    }
}