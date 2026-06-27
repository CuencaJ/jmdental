<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'pacientes';

    protected $fillable = [
        'user_id',
        'cedula',
        'fecha_nacimiento',
        'direccion',
        'telefono',
        'tipo_sangre',
        'alergias',
        'observaciones',
        'contacto_emergencia',
        'telefono_emergencia',
        'enfermedades_cronicas',
        'medicamentos_actuales',
        'medico_cabecera',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Calcular edad automáticamente
    public function getEdadAttribute()
    {
        if (!$this->fecha_nacimiento) return null;
        return Carbon::parse($this->fecha_nacimiento)->age;
    }

    // Calcular tipo de dentición automáticamente
    public function getTipoDenticionAttribute()
    {
        if (!$this->fecha_nacimiento) return 'No registrado';
        $edad = $this->edad;
        if ($edad < 6) return 'Temporal';
        if ($edad < 12) return 'Mixta';
        return 'Permanente';
    }

    // Color del badge según dentición
    public function getColorDenticionAttribute()
    {
        return match($this->tipo_denticion) {
            'Temporal'   => 'bg-yellow-100 text-yellow-700',
            'Mixta'      => 'bg-orange-100 text-orange-700',
            'Permanente' => 'bg-green-100 text-green-700',
            default      => 'bg-slate-100 text-slate-500',
        };
    }
    public function citas()
    {
        return $this->hasMany(Cita::class, 'paciente_id');
    }
}