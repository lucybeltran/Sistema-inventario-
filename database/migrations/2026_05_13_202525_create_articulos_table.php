<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla de artículos del inventario.
     * Cada artículo pertenece a un grupo (G-1, G-2, etc.)
     */
    public function up(): void
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique();     // G-1/0001, G-1/0002, etc.
            $table->string('nombre', 200);              // NITRATO, DINAMITA, etc.
            $table->string('unidad', 30);               // KILOS, UNIDAD, METROS, LITROS
            $table->string('grupo_id', 10);             // FK a grupos
            $table->decimal('cantidad', 10, 2)->default(0);
            $table->string('imagen', 255)->nullable();
            $table->timestamps();

            $table->foreign('grupo_id')
                  ->references('id')->on('grupos')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articulos');
    }
};