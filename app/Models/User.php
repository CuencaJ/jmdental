<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'cedula',
        'email',
        'password',
        'telefono',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'activo'            => 'boolean',
    ];

    /**
     * Mantiene `name` sincronizado con las 4 partes del nombre.
     * Se ejecuta en cada save (create y update), así todas las vistas
     * que usan {{ $usuario->name }} siguen funcionando sin cambios.
     */
    protected static function booted(): void
    {
        static::saving(function (self $user) {
            foreach (['primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido'] as $campo) {
                if ($user->$campo) {
                    $user->$campo = mb_convert_case(trim($user->$campo), MB_CASE_TITLE, 'UTF-8');
                }
            }
            if ($user->primer_nombre || $user->primer_apellido) {
                $user->name = collect([
                    $user->primer_nombre,
                    $user->segundo_nombre,
                    $user->primer_apellido,
                    $user->segundo_apellido,
                ])->filter()->implode(' ');
            }
        });
    }

    /**
     * Nombre corto para listados y encabezados: primer nombre + primer apellido.
     */
    public function getNombreCortoAttribute(): string
    {
        return trim(($this->primer_nombre ?? '') . ' ' . ($this->primer_apellido ?? ''));
    }

    public function paciente()
    {
        return $this->hasOne(Paciente::class);
    }

    public function odontologo()
    {
        return $this->hasOne(Odontologo::class);
    }
}