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
