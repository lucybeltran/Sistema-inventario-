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
        // 1. Columnas en users
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('permitir_cambio_password')->default(false);
            $table->integer('intentos_fallidos')->default(0);
            $table->timestamp('bloqueado_hasta')->nullable();
        });

        // 2. Tabla notificaciones
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('titulo');
            $table->text('mensaje');
            $table->boolean('leido')->default(false);
            $table->timestamps();
        });

        // 3. Tabla inicios_sesion
        Schema::create('inicios_sesion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('ip_address');
            $table->text('user_agent');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inicios_sesion');
        Schema::dropIfExists('notificaciones');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['permitir_cambio_password', 'intentos_fallidos', 'bloqueado_hasta']);
        });
    }
};
