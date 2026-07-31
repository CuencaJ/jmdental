<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // `name` se mantiene por compatibilidad: lo llena automáticamente
            // el hook booted() del modelo User concatenando las 4 partes.
            // Así todas las vistas que usan {{ $usuario->name }} siguen igual.
            $table->string('name');

            $table->string('primer_nombre', 100);
            $table->string('segundo_nombre', 100)->nullable();
            $table->string('primer_apellido', 100);
            $table->string('segundo_apellido', 100)->nullable();

            // Credencial de login (reemplaza al email). Única e irrepetible.
            $table->string('cedula', 10)->unique()->nullable();

            // El email NO es unique: varios pacientes pueden compartir el
            // correo del representante (menores de edad).
            $table->string('email');

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('telefono', 15)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};