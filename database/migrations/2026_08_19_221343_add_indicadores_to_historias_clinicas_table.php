<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('historias_clinicas', function (Blueprint $table) {
            // Higiene Oral Simplificada — placa, cálculo, gingivitis por pieza
            $table->json('hos_placa')->nullable()->after('diagnostico_inicial');
            $table->json('hos_calculo')->nullable()->after('hos_placa');
            $table->json('hos_gingivitis')->nullable()->after('hos_calculo');

            // Enfermedad periodontal
            $table->string('tipo_oclusion')->nullable()->after('hos_gingivitis');
            $table->string('nivel_fluorosis')->nullable()->after('tipo_oclusion');

            // Índices CPO — dentición permanente
            $table->integer('cpo_c')->nullable()->default(0)->after('nivel_fluorosis');
            $table->integer('cpo_p')->nullable()->default(0)->after('cpo_c');
            $table->integer('cpo_o')->nullable()->default(0)->after('cpo_p');

            // Índices ceo — dentición temporal
            $table->integer('ceo_c')->nullable()->default(0)->after('cpo_o');
            $table->integer('ceo_e')->nullable()->default(0)->after('ceo_c');
            $table->integer('ceo_o')->nullable()->default(0)->after('ceo_e');
        });
    }

    public function down(): void
    {
        Schema::table('historias_clinicas', function (Blueprint $table) {
            $table->dropColumn([
                'hos_placa', 'hos_calculo', 'hos_gingivitis',
                'tipo_oclusion', 'nivel_fluorosis',
                'cpo_c', 'cpo_p', 'cpo_o',
                'ceo_c', 'ceo_e', 'ceo_o',
            ]);
        });
    }
};