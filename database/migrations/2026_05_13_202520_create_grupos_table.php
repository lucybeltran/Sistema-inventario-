<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla de grupos / categorías del inventario.
     * Ejemplos: G-1 = MATERIAL EXPLOSIVO, G-2 = ACCESORIOS, etc.
     */
    public function up(): void
    {
        Schema::create('grupos', function (Blueprint $table) {
            $table->string('id', 10)->primary();   // G-1, G-2, ..., G-9
            $table->string('nombre', 100);          // MATERIAL EXPLOSIVO, ACCESORIOS, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};