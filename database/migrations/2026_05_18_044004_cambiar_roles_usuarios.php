<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Crear columna temporal con los nuevos roles
        DB::statement("ALTER TABLE users MODIFY COLUMN rol ENUM('admin','almacenero','reportes','operador') DEFAULT 'almacenero'");

        // 2. Migrar usuarios existentes: operador → almacenero
        DB::table('users')->where('rol', 'operador')->update(['rol' => 'almacenero']);

        // 3. Quitar 'operador' del enum (queda solo admin, almacenero, reportes)
        DB::statement("ALTER TABLE users MODIFY COLUMN rol ENUM('admin','almacenero','reportes') DEFAULT 'almacenero'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN rol ENUM('admin','operador') DEFAULT 'operador'");
        DB::table('users')->whereIn('rol', ['almacenero', 'reportes'])->update(['rol' => 'operador']);
    }
};