<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';

    protected $fillable = [
        'paciente_id',
        'odontologo_id',
        'user_id',
        'fecha_hora',
        'estado',
        'motivo',
        'notas',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function odontologo()
    {
        return $this->belongsTo(Odontologo::class, 'odontologo_id');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tratamiento()
    {
        return $this->hasOne(Tratamiento::class);
    }
}