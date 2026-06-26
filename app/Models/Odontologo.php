<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Odontologo extends Model
{
    use HasFactory;

    protected $table = 'odontologos';

    protected $fillable = [
        'user_id',
        'cedula',
        'especialidad',
        'numero_licencia',
        'telefono',
        'descripcion',
        'anios_experiencia',
        'universidad',
        'titulo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}