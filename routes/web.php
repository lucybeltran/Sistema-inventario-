<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GaleriaController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TrabajadorController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\NotificacionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // ===== TODOS LOS ROLES PUEDEN VER ESTO (solo lectura) =====
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
    Route::get('/movimientos', [MovimientoController::class, 'index'])->name('movimientos.index');
    Route::get('/galeria', [GaleriaController::class, 'index'])->name('galeria.index');
    Route::get('/trabajadores', [TrabajadorController::class, 'index'])->name('trabajadores.index');

    // Reporte individual por trabajador (accesible para admin, reportes y almacenero)
    Route::get('/reportes/trabajador/{trabajador}', [ReporteController::class, 'reporteTrabajador'])->name('reportes.trabajador');
    Route::get('/reportes/trabajador/{trabajador}/pdf', [ReporteController::class, 'reporteTrabajadorPdf'])->name('reportes.trabajador.pdf');
    Route::get('/reportes/trabajador/{trabajador}/excel', [ReporteController::class, 'reporteTrabajadorExcel'])->name('reportes.trabajador.excel');

    // Kardex por producto / material (accesible para admin, reportes y almacenero)
    Route::get('/reportes/kardex/preview', [ReporteController::class, 'kardexPreview'])->name('reportes.kardex.preview');
    Route::get('/reportes/kardex/{articuloId?}', [ReporteController::class, 'kardexProducto'])->name('reportes.kardex');
    Route::get('/reportes/kardex/{articuloId}/pdf', [ReporteController::class, 'kardexPdf'])->name('reportes.kardex.pdf');
    Route::get('/reportes/kardex/{articuloId}/excel', [ReporteController::class, 'kardexExcel'])->name('reportes.kardex.excel');

    // ===== ADMIN Y ALMACENERO PUEDEN EDITAR =====
    Route::middleware('puede_editar')->group(function () {
        // Inventario
        Route::post('/inventario', [InventarioController::class, 'store'])->name('inventario.store');
        Route::put('/inventario/{articulo}', [InventarioController::class, 'update'])->name('inventario.update');
        Route::post('/inventario/grupos', [InventarioController::class, 'storeGrupo'])->name('inventario.grupos.store');
        Route::put('/inventario/grupos/{id}', [InventarioController::class, 'updateGrupo'])->name('inventario.grupos.update');
        Route::get('/inventario/siguiente-codigo/{grupo_id}', [InventarioController::class, 'siguienteCodigo'])->name('inventario.siguiente-codigo');

        // Rotación / Clasificación de Materiales
        Route::get('/inventario/rotacion', [InventarioController::class, 'rotacionIndex'])->name('inventario.rotacion.index');
        Route::post('/inventario/rotacion/cambiar/{articulo}', [InventarioController::class, 'cambiarRotacion'])->name('inventario.rotacion.cambiar');

        // Movimientos
        Route::post('/movimientos', [MovimientoController::class, 'store'])->name('movimientos.store');
        Route::get('/movimientos/lotes', [MovimientoController::class, 'getLotes'])->name('movimientos.lotes');
        // Edición completa de movimiento por el Administrador o usuario autorizado
        Route::put('/movimientos/{movimiento}', [MovimientoController::class, 'update'])->name('movimientos.update');
        Route::patch('/movimientos/{movimiento}/precio', [MovimientoController::class, 'actualizarPrecio'])->name('movimientos.actualizar-precio');
        Route::patch('/movimientos/{movimiento}/nota', [MovimientoController::class, 'updateNota'])->name('movimientos.update-nota');

        // Galería
        Route::post('/galeria/upload', [GaleriaController::class, 'upload'])->name('galeria.upload');

        // Trabajadores
        Route::post('/trabajadores', [TrabajadorController::class, 'store'])->name('trabajadores.store');
        Route::put('/trabajadores/{trabajador}', [TrabajadorController::class, 'update'])->name('trabajadores.update');
    });

    // ===== SOLO ADMIN (acciones críticas) =====
    Route::middleware('solo_admin')->group(function () {
        // Inventario
        Route::delete('/inventario/{articulo}', [InventarioController::class, 'destroy'])->name('inventario.destroy');
        Route::delete('/grupos/{id}', [InventarioController::class, 'destroyGrupo'])->name('grupos.destroy');
        Route::delete('/galeria/{articulo}', [GaleriaController::class, 'destroy'])->name('galeria.destroy');
        Route::patch('/trabajadores/{trabajador}/toggle', [TrabajadorController::class, 'toggleActivo'])->name('trabajadores.toggle');
        Route::delete('/trabajadores/{trabajador}', [TrabajadorController::class, 'destroy'])->name('trabajadores.destroy');

        // ── Gestión de usuarios del sistema (solo admin) ──
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::patch('/usuarios/{usuario}/toggle', [UsuarioController::class, 'toggleActivo'])->name('usuarios.toggle');
        Route::post('/usuarios/{usuario}/desbloquear', [UsuarioController::class, 'desbloquear'])->name('usuarios.desbloquear');
        Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

        // ── Copias de seguridad de la base de datos (solo admin) ──
        Route::get('/backups', [BackupController::class, 'index'])->name('backups.index');
        Route::post('/backups/generar', [BackupController::class, 'create'])->name('backups.create');
        Route::get('/backups/descargar/{filename}', [BackupController::class, 'download'])->name('backups.download');
        Route::delete('/backups/eliminar/{filename}', [BackupController::class, 'destroy'])->name('backups.destroy');
        Route::post('/backups/restaurar/{filename}', [BackupController::class, 'restore'])->name('backups.restore');
        Route::post('/backups/subir-restaurar', [BackupController::class, 'uploadRestore'])->name('backups.uploadRestore');
        Route::post('/backups/configurar', [BackupController::class, 'saveSettings'])->name('backups.saveSettings');
        Route::post('/backups/probar-cron', [BackupController::class, 'testCron'])->name('backups.testCron');
    });

    // ===== ADMIN Y REPORTES PUEDEN VER/DESCARGAR REPORTES =====
    Route::middleware('puede_reportes')->group(function () {
        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
        Route::get('/reportes/inventario/excel', [ReporteController::class, 'inventarioExcel'])->name('reportes.inventario.excel');
        Route::get('/reportes/inventario/pdf', [ReporteController::class, 'inventarioPdf'])->name('reportes.inventario.pdf');
        Route::get('/reportes/movimientos/preview', [ReporteController::class, 'movimientosPreview'])->name('reportes.movimientos.preview');
        Route::get('/reportes/movimientos/excel', [ReporteController::class, 'movimientosExcel'])->name('reportes.movimientos.excel');
        Route::get('/reportes/movimientos/pdf', [ReporteController::class, 'movimientosPdf'])->name('reportes.movimientos.pdf');

        // Descargar movimientos de un mes específico
        Route::get('/reportes/mes/{periodo}/excel', [ReporteController::class, 'movimientosMesExcel'])->name('reportes.mes.excel');
        Route::get('/reportes/mes/{periodo}/pdf', [ReporteController::class, 'movimientosMesPdf'])->name('reportes.mes.pdf');

        // Reporte por Rotación / Clasificación
        Route::get('/reportes/rotacion/excel', [ReporteController::class, 'rotacionExcel'])->name('reportes.rotacion.excel');
        Route::get('/reportes/rotacion/pdf', [ReporteController::class, 'rotacionPdf'])->name('reportes.rotacion.pdf');

        // Bitácora de Auditoría
        Route::get('/reportes/bitacora', [ReporteController::class, 'bitacoraIndex'])->name('reportes.bitacora');
    });

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/limpiar-accesos', [ProfileController::class, 'limpiarAccesos'])->name('profile.limpiar-accesos');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notificaciones
    Route::get('/notificaciones/get-nuevas', [NotificacionController::class, 'getNuevas'])->name('notificaciones.get-nuevas');
    Route::post('/notificaciones/marcar-leidas', [NotificacionController::class, 'marcarLeidas'])->name('notificaciones.marcar-leidas');
    Route::post('/notificaciones/{notificacion}/leer', [NotificacionController::class, 'marcarUnaLeida'])->name('notificaciones.marcar-una-leida');
    Route::delete('/notificaciones/limpiar-todas', [NotificacionController::class, 'limpiarTodas'])->name('notificaciones.limpiar-todas');
    Route::delete('/notificaciones/{notificacion}', [NotificacionController::class, 'destroy'])->name('notificaciones.destroy');
});

require __DIR__.'/auth.php';