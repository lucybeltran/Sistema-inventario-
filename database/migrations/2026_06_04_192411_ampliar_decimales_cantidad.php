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
        Schema::table('articulos', function (Blueprint $table) {
            $table->decimal('cantidad', 12, 3)->default(0)->change();
        });

        Schema::table('movimientos', function (Blueprint $table) {
            $table->decimal('cantidad', 12, 3)->change();
        });
    }

    public function down(): void
    {
        Schema::table('articulos', function (Blueprint $table) {
            $table->decimal('cantidad', 10, 2)->default(0)->change();
        });

        Schema::table('movimientos', function (Blueprint $table) {
            $table->decimal('cantidad', 10, 2)->change();
        });
    }
};
