<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilOdontologo extends Model
{
    use HasFactory;

    protected $table = 'perfiles_odontologos';

    protected $fillable = [
        'user_id',
        'licencia',
        'especialidad',
        'telefono',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
