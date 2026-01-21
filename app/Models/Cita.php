<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    // Nombre real de la tabla
    protected $table = 'citas';

    // Campos que podrás crear/actualizar con create()/update()
    protected $fillable = [
        'paciente_id',
        'odontologo_id',
        'inicia_en',
        'termina_en',
        'estado',
        'motivo',
    ];

    // Cast de fechas
    protected $casts = [
        'inicia_en'  => 'datetime',
        'termina_en' => 'datetime',
    ];

    // Relaciones
    public function paciente()   { return $this->belongsTo(User::class, 'paciente_id'); }
    public function odontologo() { return $this->belongsTo(User::class, 'odontologo_id'); }
    public function tratamientos(){ return $this->hasMany(Tratamiento::class, 'cita_id'); }
}
