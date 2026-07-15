<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TratamientoPieza extends Model
{
    use HasFactory;

    protected $table = 'tratamiento_piezas';

    protected $fillable = [
        'tratamiento_id',
        'pieza_numero',
        'tipo_denticion',
        'cara',
        'procedimiento',
        'diagnostico',
        'ausente',
        'movilidad',
        'recesion',
    ];

    protected $casts = [
        'ausente' => 'boolean',
    ];

    public function tratamiento()
    {
        return $this->belongsTo(Tratamiento::class);
    }
}