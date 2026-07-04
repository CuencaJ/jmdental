<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historias_clinicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
            $table->foreignId('odontologo_id')->constrained('odontologos')->onDelete('cascade');

            // Datos de la primera consulta
            $table->date('fecha_apertura');
            $table->string('motivo_consulta')->nullable();
            $table->text('enfermedad_actual')->nullable();

            // Antecedentes personales
            $table->text('antecedentes_personales')->nullable();
            $table->text('antecedentes_familiares')->nullable();

            // Constantes vitales
            $table->string('temperatura')->nullable();
            $table->string('pulso')->nullable();
            $table->string('frecuencia_respiratoria')->nullable();
            $table->string('presion_arterial')->nullable();

            // Examen estomatognático
            $table->text('examen_extraoral')->nullable();
            $table->text('examen_intraoral')->nullable();

            // Diagnóstico inicial
            $table->text('diagnostico_inicial')->nullable();

            // Estado del formulario
            $table->boolean('completado')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historias_clinicas');
    }
};