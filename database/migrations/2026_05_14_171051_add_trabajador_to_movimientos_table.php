<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega 'trabajador_id' a movimientos.
     * Solo aplica para SALIDAS (entrada queda NULL).
     */
    public function up(): void
    {
        Schema::table('movimientos', function (Blueprint $table) {
            $table->foreignId('trabajador_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('trabajadores')
                  ->onDelete('restrict'); // No se puede borrar un trabajador si tiene movimientos
        });
    }

    public function down(): void
    {
        Schema::table('movimientos', function (Blueprint $table) {
            $table->dropForeign(['trabajador_id']);
            $table->dropColumn('trabajador_id');
        });
    }
};