<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// 👇 Agregar esto
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles; 

    /**
     * Atributos asignables en masa.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Atributos ocultos.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atributos con casteo.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * ──────────────────────────────
     * RELACIONES PERSONALIZADAS
     * ──────────────────────────────
     */

    // PERFIL
    public function Odontologo()
    {
        return $this->hasOne(PerfilOdontologo::class, 'user_id');
    }

    public function Paciente()
    {
        return $this->hasOne(PerfilPaciente::class, 'user_id');
    }

    // CITAS
    public function citasOdontologo()
    {
        return $this->hasMany(Cita::class, 'odontologo_id');
    }

    public function citasPaciente()
    {
        return $this->hasMany(Cita::class, 'paciente_id');
    }

    // TRATAMIENTOS
    public function tratamientosOdontologo()
    {
        return $this->hasMany(Tratamiento::class, 'odontologo_id');
    }

    public function tratamientosPaciente()
    {
        return $this->hasMany(Tratamiento::class, 'paciente_id');
    }
}
