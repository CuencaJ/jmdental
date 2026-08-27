<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('historias_clinicas', function (Blueprint $table) {
            // L. Pedido de exámenes complementarios
            $table->text('examenes_pedido')->nullable()->after('diagnosticos');

            // M. Informe de exámenes
            $table->boolean('examenes_biometria')->default(false)->after('examenes_pedido');
            $table->boolean('examenes_quimica')->default(false)->after('examenes_biometria');
            $table->boolean('examenes_rayos_x')->default(false)->after('examenes_quimica');
            $table->text('examenes_otros')->nullable()->after('examenes_rayos_x');
            $table->text('examenes_informe')->nullable()->after('examenes_otros');
        });
    }

    public function down(): void
    {
        Schema::table('historias_clinicas', function (Blueprint $table) {
            $table->dropColumn([
                'examenes_pedido',
                'examenes_biometria',
                'examenes_quimica',
                'examenes_rayos_x',
                'examenes_otros',
                'examenes_informe',
            ]);
        });
    }
};