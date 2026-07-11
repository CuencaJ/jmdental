<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('historias_clinicas', function (Blueprint $table) {
            $table->string('segundo_nombre')->nullable()->after('motivo_consulta');
            $table->string('segundo_apellido')->nullable()->after('segundo_nombre');
            $table->boolean('embarazada')->nullable()->after('segundo_apellido');
            $table->string('condicion_edad', 10)->nullable()->default('anios')->after('embarazada');
        });
    }

    public function down(): void
    {
        Schema::table('historias_clinicas', function (Blueprint $table) {
            $table->dropColumn(['segundo_nombre', 'segundo_apellido', 'embarazada', 'condicion_edad']);
        });
    }
};