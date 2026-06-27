<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchivoTratamiento extends Model
{
    use HasFactory;

    protected $table = 'archivos_tratamiento';

    protected $fillable = [
        'tratamiento_id',
        'nombre_archivo',
        'ruta_archivo',
        'tipo_archivo',
        'tamanio_archivo',
        'descripcion',
    ];

    public function tratamiento()
    {
        return $this->belongsTo(Tratamiento::class);
    }
}