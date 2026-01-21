<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilPaciente extends Model
{
    use HasFactory;

    protected $table = 'perfiles_pacientes';

    protected $fillable = [
        'user_id',
        'fecha_nacimiento',
        'telefono',
        'alergias',
        'notas',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
