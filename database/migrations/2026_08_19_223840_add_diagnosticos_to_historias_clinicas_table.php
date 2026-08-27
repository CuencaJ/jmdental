<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('historias_clinicas', function (Blueprint $table) {
            // Reemplazar diagnostico_inicial por 6 diagnósticos con CIE, PRE y DEF
            $table->json('diagnosticos')->nullable()->after('diagnostico_inicial');
        });
    }

    public function down(): void
    {
        Schema::table('historias_clinicas', function (Blueprint $table) {
            $table->dropColumn('diagnosticos');
        });
    }
};