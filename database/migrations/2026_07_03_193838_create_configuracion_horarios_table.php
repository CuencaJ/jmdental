<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracion_horarios', function (Blueprint $table) {
            $table->id();
            $table->time('hora_inicio')->default('08:00:00');
            $table->time('hora_fin')->default('20:00:00');
            $table->integer('duracion_slot')->default(60); // minutos por cita
            $table->json('dias_laborables')->default('["1","2","3","4","5"]'); // 1=lunes...7=domingo
            $table->timestamps();
        });

        // Insertar fila única de configuración por defecto
        DB::table('configuracion_horarios')->insert([
            'hora_inicio'     => '08:00:00',
            'hora_fin'        => '20:00:00',
            'duracion_slot'   => 60,
            'dias_laborables' => json_encode(['1','2','3','4','5']),
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // Agregar columna duracion_minutos a citas
        Schema::table('citas', function (Blueprint $table) {
            $table->integer('duracion_minutos')->default(60)->after('notas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion_horarios');
        Schema::table('citas', function (Blueprint $table) {
            $table->dropColumn('duracion_minutos');
        });
    }
};