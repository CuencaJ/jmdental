<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('historias_clinicas', function (Blueprint $table) {
            $table->json('antecedentes_personales_check')->nullable()->after('antecedentes_personales');
            $table->json('antecedentes_familiares_check')->nullable()->after('antecedentes_familiares');
            $table->json('examen_estomatognatico_check')->nullable()->after('examen_intraoral');
        });
    }

    public function down(): void
    {
        Schema::table('historias_clinicas', function (Blueprint $table) {
            $table->dropColumn([
                'antecedentes_personales_check',
                'antecedentes_familiares_check',
                'examen_estomatognatico_check',
            ]);
        });
    }
};