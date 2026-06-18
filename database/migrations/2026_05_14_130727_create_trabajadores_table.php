<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla de trabajadores de la mina.
     * Sirve para registrar a quién se entrega material en cada salida.
     */
    public function up(): void
    {
        Schema::create('trabajadores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('ci', 20)->unique();           // Carnet de Identidad
            $table->string('cargo', 100)->nullable();     // Ej: Minero, Perforista, Capataz
            $table->string('telefono', 20)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trabajadores');
    }
};