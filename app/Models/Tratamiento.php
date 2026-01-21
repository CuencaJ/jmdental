<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tratamiento extends Model
{
    use HasFactory;

    protected $table = 'tratamientos';

    protected $fillable = [
        'cita_id',
        'paciente_id',
        'odontologo_id',
        'procedimiento_id',
        'realizado_en',
        'notas',
        'precio',
    ];

    protected $casts = [
        'realizado_en' => 'datetime',
        'precio'       => 'decimal:2',
    ];

    public function cita()         { return $this->belongsTo(Cita::class, 'cita_id'); }
    public function paciente()     { return $this->belongsTo(User::class, 'paciente_id'); }
    public function odontologo()   { return $this->belongsTo(User::class, 'odontologo_id'); }
    public function procedimiento(){ return $this->belongsTo(Procedimiento::class, 'procedimiento_id'); }
}
