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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('permiso_almacen')->default(false)->after('rol');
            $table->boolean('permiso_reportes')->default(false)->after('permiso_almacen');
        });

        // Inicializar datos existentes
        \Illuminate\Support\Facades\DB::table('users')->where('rol', 'admin')->update([
            'permiso_almacen' => true,
            'permiso_reportes' => true,
        ]);
        \Illuminate\Support\Facades\DB::table('users')->where('rol', 'almacenero')->update([
            'permiso_almacen' => true,
            'permiso_reportes' => false,
        ]);
        \Illuminate\Support\Facades\DB::table('users')->where('rol', 'reportes')->update([
            'permiso_almacen' => false,
            'permiso_reportes' => true,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['permiso_almacen', 'permiso_reportes']);
        });
    }
};
