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
            'primer_nombre'    => 'Johan',
            'segundo_nombre'   => 'Francisco',
            'primer_apellido'  => 'Sanchez',
            'segundo_apellido' => 'Mero',
            'cedula'          => '1315207769',
            'email'           => 'admin@gmail.com',
            'password'        => Hash::make('admin123'),
            'telefono'        => '0979341684',
        ]);
        $admin->assignRole('administrador');

        // ── Odontólogo ──
        $doctor = User::create([
            'primer_nombre'    => 'Jennifer',
            'segundo_nombre'   => 'Andreina',
            'primer_apellido'  => 'Camacho',
            'segundo_apellido' => 'Mero',
            'cedula'           => '1313681486',
            'email'            => 'odontologa@gmail.com',
            'password'         => Hash::make('doctor123'),
            'telefono'         => '0967101552',
        ]);
        $doctor->assignRole('odontologo');

        // Sin este registro el odontólogo no aparece al agendar citas
        // y la sección O del PDF sale vacía.
        $doctor->odontologo()->create([
            'cedula'            => '1313681486',
            'especialidad'      => 'Odontología General',
            'numero_licencia'   => 'LIC-101',
            'telefono'          => '0967101552',
            'universidad'       => 'Universidad Central del Ecuador',
            'titulo'            => 'Odontólogo',
            'anios_experiencia' => 8,
        ]);

        // ── Recepcionista ──
        // No requiere tabla propia: no existe `recepcionistas` en el esquema.
        $recepcionista = User::create([
            'primer_nombre'    => 'Nayeska',
            'segundo_nombre'   => 'Antonella',
            'primer_apellido'  => 'Cuenca',
            'segundo_apellido' => 'Holguín',
            'cedula'           => '1315967917',
            'email'            => 'recepcionista@gmail.com',
            'password'         => Hash::make('recep123'),
            'telefono'         => '0939807139',
        ]);
        $recepcionista->assignRole('recepcionista');

        // ── Paciente ──
        $paciente = User::create([
            'primer_nombre'    => 'Jerson',
            'segundo_nombre'   => 'Steven',
            'primer_apellido'  => 'Cuenca',
            'segundo_apellido' => 'Holguín',
            'cedula'           => '1315967909',
            'email'            => 'paciente@gmail.com',
            'password'         => Hash::make('paciente123'),
            'telefono'         => '0993281458',
        ]);
        $paciente->assignRole('paciente');

        // Sin este registro las rutas /paciente abortan con 403.
        $paciente->paciente()->create([
            'cedula'              => '1315967909',
            'fecha_nacimiento'    => '2002-08-24',
            'genero'              => 'Masculino',
            'direccion'           => 'Cuba, Manta, Manabí, Ecuador',
            'telefono'            => '0993281458',
            'tipo_sangre'         => 'O+',
            'alergias'            => 'Ninguna',
            'contacto_emergencia' => 'Angel Cortez',
            'telefono_emergencia' => '0969145756',
        ]);
    }
}