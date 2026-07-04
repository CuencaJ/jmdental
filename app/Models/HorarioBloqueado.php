<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioBloqueado extends Model
{
    use HasFactory;

    protected $table = 'horarios_bloqueados';

    protected $fillable = [
        'odontologo_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'motivo',
        'created_by',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function odontologo()
    {
        return $this->belongsTo(Odontologo::class);
    }

    public function creadoPor()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}