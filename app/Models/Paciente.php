<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'pacientes';

    protected $fillable = [
        'user_id',
        'cedula',
        'fecha_nacimiento',
        'genero',
        'direccion',
        'telefono',
        'tipo_sangre',
        'alergias',
        'observaciones',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'paciente_id');
    }
}