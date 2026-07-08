<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tratamiento_piezas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tratamiento_id')->constrained('tratamientos')->onDelete('cascade');
            $table->integer('pieza_numero');
            $table->string('tipo_denticion', 20)->default('permanente');
            $table->string('cara', 50)->nullable();
            $table->string('procedimiento', 255)->nullable();
            $table->string('diagnostico', 255)->nullable();
            $table->boolean('ausente')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tratamiento_piezas');
    }
};