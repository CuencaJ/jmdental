<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Roles ──
        Role::create(['name' => 'administrador']);
        Role::create(['name' => 'odontologo']);
        Role::create(['name' => 'recepcionista']);
        Role::create(['name' => 'paciente']);

        // ── Administrador ──
        // `name` no se envía: lo arma solo el hook booted() del modelo User.
        $admin = User::create([
            'primer_nombre'   => 'Jerson',
            'primer_apellido' => 'Admin',
            'cedula'          => '1111111111',
            'email'           => 'admin@gmail.com',
            'password'        => Hash::make('admin123'),
            'telefono'        => '0991234567',
        ]);
        $admin->assignRole('administrador');

        // ── Odontólogo ──
        $doctor = User::create([
            'primer_nombre'    => 'Carlos',
            'primer_apellido'  => 'Smith',
            'segundo_apellido' => 'Vera',
            'cedula'           => '2222222222',
            'email'            => 'ddoctor@gmail.com',
            'password'         => Hash::make('doctor123'),
            'telefono'         => '0992345678',
        ]);
        $doctor->assignRole('odontologo');

        // Sin este registro el odontólogo no aparece al agendar citas
        // y la sección O del PDF sale vacía.
        $doctor->odontologo()->create([
            'cedula'            => '2222222222',
            'especialidad'      => 'Odontología General',
            'numero_licencia'   => 'LIC-101',
            'telefono'          => '0992345678',
            'universidad'       => 'Universidad Central del Ecuador',
            'titulo'            => 'Odontólogo',
            'anios_experiencia' => 8,
        ]);

        // ── Recepcionista ──
        // No requiere tabla propia: no existe `recepcionistas` en el esquema.
        $recepcionista = User::create([
            'primer_nombre'    => 'Maria',
            'primer_apellido'  => 'Garcia',
            'segundo_apellido' => 'Ruiz',
            'cedula'           => '3333333333',
            'email'            => 'rrecepcionista@gmail.com',
            'password'         => Hash::make('recep123'),
            'telefono'         => '0993456789',
        ]);
        $recepcionista->assignRole('recepcionista');

        // ── Paciente ──
        $paciente = User::create([
            'primer_nombre'    => 'Ana',
            'segundo_nombre'   => 'Lucia',
            'primer_apellido'  => 'Lopez',
            'segundo_apellido' => 'Mora',
            'cedula'           => '4444444444',
            'email'            => 'paciente@gmail.com',
            'password'         => Hash::make('paciente123'),
            'telefono'         => '0994567890',
        ]);
        $paciente->assignRole('paciente');

        // Sin este registro las rutas /paciente abortan con 403.
        $paciente->paciente()->create([
            'cedula'              => '4444444444',
            'fecha_nacimiento'    => '1995-04-18',
            'genero'              => 'Femenino',
            'direccion'           => 'Av. Amazonas y Naciones Unidas, Quito',
            'telefono'            => '0994567890',
            'tipo_sangre'         => 'O+',
            'alergias'            => 'Penicilina',
            'contacto_emergencia' => 'Luis Lopez',
            'telefono_emergencia' => '0987654321',
        ]);
    }
}