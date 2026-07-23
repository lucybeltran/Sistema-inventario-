<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('movimientos', function (Blueprint $table) {
            $table->string('entregado_por')->nullable();
            $table->decimal('cantidad_restante', 12, 3)->default(0);
            $table->foreignId('lote_id')->nullable()->constrained('movimientos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimientos', function (Blueprint $table) {
            $table->dropForeign(['lote_id']);
            $table->dropColumn(['entregado_por', 'cantidad_restante', 'lote_id']);
        });
    }
};
