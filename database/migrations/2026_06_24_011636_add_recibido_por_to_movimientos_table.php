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
            $table->string('recibido_por', 100)->nullable()->after('entregado_por');
        });

        // Inicializar los registros existentes de tipo entrada con el nombre del usuario que los registró
        \DB::statement("UPDATE movimientos JOIN users ON movimientos.user_id = users.id SET movimientos.recibido_por = users.name WHERE movimientos.tipo = 'entrada'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimientos', function (Blueprint $table) {
            $table->dropColumn('recibido_por');
        });
    }
};
