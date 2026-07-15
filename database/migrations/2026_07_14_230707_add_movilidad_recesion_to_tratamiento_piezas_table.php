<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tratamiento_piezas', function (Blueprint $table) {
            $table->string('movilidad', 5)->nullable()->after('ausente');
            $table->string('recesion', 5)->nullable()->after('movilidad');
        });
    }

    public function down(): void
    {
        Schema::table('tratamiento_piezas', function (Blueprint $table) {
            $table->dropColumn(['movilidad', 'recesion']);
        });
    }
};