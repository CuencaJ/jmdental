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
        'nombre',
        'descripcion',
        'costo',
        'fecha_tratamiento',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_tratamiento' => 'date',
        'costo' => 'decimal:2',
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    public function archivos()
    {
        return $this->hasMany(ArchivoTratamiento::class);
    }

    public function piezas()
    {
        return $this->hasMany(TratamientoPieza::class);
    }
}