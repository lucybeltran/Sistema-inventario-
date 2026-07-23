<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\AuditoriaLog;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ── Limpieza automática diaria de la Bitácora (registros > 1 mes) ──────────
Schedule::call(function () {
    $eliminados = AuditoriaLog::where('created_at', '<', now()->subMonth())->delete();
    \Illuminate\Support\Facades\Log::info("Bitácora: {$eliminados} registros eliminados automáticamente (> 1 mes).");
})->daily()->name('bitacora:limpiar')->withoutOverlapping();

// ── Respaldos automáticos configurables (Base de datos + Storage) ──────────
Schedule::call(function () {
    // 1. Verificar si el backup automático está habilitado en la BD
    $habilitado = \App\Models\Configuracion::obtener('backup_auto_habilitado', '0');
    if ($habilitado !== '1') {
        return;
    }

    // 2. Verificar la hora configurada (ej: "23:00")
    $horaConfig = \App\Models\Configuracion::obtener('backup_auto_hora', '23:00');
    $horaActual = now()->format('H:i');

    // Solo se ejecuta en el minuto exacto configurado
    if ($horaActual !== $horaConfig) {
        return;
    }

    // 3. Verificar si se debe ejecutar solo el último día del mes
    $soloFinDeMes = \App\Models\Configuracion::obtener('backup_auto_ultimo_dia_mes', '1');
    if ($soloFinDeMes === '1' && !now()->isLastOfMonth()) {
        return;
    }

    // 4. Ejecutar el backup completo
    try {
        $backupController = new \App\Http\Controllers\BackupController();
        $resultado = $backupController->generarRespaldoInterno('Sistema (Programado)');
        \Illuminate\Support\Facades\Log::info("Backup automático ejecutado con éxito: " . $resultado['filename']);
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error("Error en backup automático: " . $e->getMessage());
    }
})->everyMinute()->name('backup:automatico')->withoutOverlapping();
