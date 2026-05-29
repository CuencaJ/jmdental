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
        // Crear roles
        Role::create(['name' => 'administrador']);
        Role::create(['name' => 'odontologo']);
        Role::create(['name' => 'recepcionista']);
        Role::create(['name' => 'paciente']);

        // Administrador 1 (creador)
        $admin = User::create([
            'name'     => 'Jerson Admin',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'telefono' => '0991234567',
        ]);
        $admin->assignRole('administrador');

        // Odontólogo
        $doctor = User::create([
            'name'     => 'Dr. Smith',
            'email'    => 'ddoctor@gmail.com',
            'password' => Hash::make('doctor123'),
            'telefono' => '0992345678',
        ]);
        $doctor->assignRole('odontologo');

        // Recepcionista
        $recepcionista = User::create([
            'name'     => 'Garcia Recepcionista',
            'email'    => 'rrecepcionista@gmail.com',
            'password' => Hash::make('recepcionista123'),
            'telefono' => '0993456789',
        ]);
        $recepcionista->assignRole('recepcionista');

        // Paciente
        $paciente = User::create([
            'name'     => 'Lopez Paciente',
            'email'    => 'paciente@gmail.com',
            'password' => Hash::make('paciente123'),
            'telefono' => '0994567890',
        ]);
        $paciente->assignRole('paciente');
    }
}