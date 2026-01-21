<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procedimiento extends Model
{
    use HasFactory;

    protected $table = 'catalogo_procedimientos';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'precio_base',
    ];
}
