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
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->string('key')->unique()->primary();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insertar valores por defecto para backup automático
        \Illuminate\Support\Facades\DB::table('configuraciones')->insert([
            ['key' => 'backup_auto_habilitado', 'value' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'backup_auto_hora', 'value' => '23:00', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'backup_auto_ultimo_dia_mes', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuraciones');
    }
};
