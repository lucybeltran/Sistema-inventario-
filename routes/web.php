<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GaleriaController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TrabajadorController;
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

    // ===== ADMIN Y ALMACENERO PUEDEN EDITAR =====
    Route::middleware('puede_editar')->group(function () {
        // Inventario
        Route::post('/inventario', [InventarioController::class, 'store'])->name('inventario.store');
        Route::put('/inventario/{articulo}', [InventarioController::class, 'update'])->name('inventario.update');
        Route::post('/inventario/grupos', [InventarioController::class, 'storeGrupo'])->name('inventario.grupos.store');
        Route::put('/inventario/grupos/{id}', [InventarioController::class, 'updateGrupo'])->name('inventario.grupos.update');
        Route::get('/inventario/siguiente-codigo/{grupo_id}', [InventarioController::class, 'siguienteCodigo'])->name('inventario.siguiente-codigo');

        // Movimientos
        Route::post('/movimientos', [MovimientoController::class, 'store'])->name('movimientos.store');

        // Galería
        Route::post('/galeria/upload', [GaleriaController::class, 'upload'])->name('galeria.upload');

        // Trabajadores
        Route::post('/trabajadores', [TrabajadorController::class, 'store'])->name('trabajadores.store');
        Route::put('/trabajadores/{trabajador}', [TrabajadorController::class, 'update'])->name('trabajadores.update');
    });

    // ===== SOLO ADMIN (acciones críticas) =====
    Route::middleware('solo_admin')->group(function () {
        Route::delete('/inventario/{articulo}', [InventarioController::class, 'destroy'])->name('inventario.destroy');
        Route::delete('/grupos/{id}', [InventarioController::class, 'destroyGrupo'])->name('grupos.destroy');
        Route::delete('/galeria/{articulo}', [GaleriaController::class, 'destroy'])->name('galeria.destroy');
        Route::patch('/trabajadores/{trabajador}/toggle', [TrabajadorController::class, 'toggleActivo'])->name('trabajadores.toggle');
        Route::delete('/trabajadores/{trabajador}', [TrabajadorController::class, 'destroy'])->name('trabajadores.destroy');
    });

    // ===== ADMIN Y REPORTES PUEDEN VER/DESCARGAR REPORTES =====
    Route::middleware('puede_reportes')->group(function () {
        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
        Route::get('/reportes/inventario/excel', [ReporteController::class, 'inventarioExcel'])->name('reportes.inventario.excel');
        Route::get('/reportes/inventario/pdf', [ReporteController::class, 'inventarioPdf'])->name('reportes.inventario.pdf');
        Route::get('/reportes/movimientos/excel', [ReporteController::class, 'movimientosExcel'])->name('reportes.movimientos.excel');
        Route::get('/reportes/movimientos/pdf', [ReporteController::class, 'movimientosPdf'])->name('reportes.movimientos.pdf');

        // Reporte individual por trabajador (admin y reportes pueden verlo)
        Route::get('/reportes/trabajador/{trabajador}', [ReporteController::class, 'reporteTrabajador'])->name('reportes.trabajador');
        Route::get('/reportes/trabajador/{trabajador}/pdf', [ReporteController::class, 'reporteTrabajadorPdf'])->name('reportes.trabajador.pdf');
        Route::get('/reportes/trabajador/{trabajador}/excel', [ReporteController::class, 'reporteTrabajadorExcel'])->name('reportes.trabajador.excel');

        // Descargar movimientos de un mes específico
        Route::get('/reportes/mes/{periodo}/excel', [ReporteController::class, 'movimientosMesExcel'])->name('reportes.mes.excel');
        Route::get('/reportes/mes/{periodo}/pdf', [ReporteController::class, 'movimientosMesPdf'])->name('reportes.mes.pdf');

        // Kardex por producto
        Route::get('/reportes/kardex/{articuloId?}', [ReporteController::class, 'kardexProducto'])->name('reportes.kardex');
        Route::get('/reportes/kardex/{articuloId}/pdf', [ReporteController::class, 'kardexPdf'])->name('reportes.kardex.pdf');
        Route::get('/reportes/kardex/{articuloId}/excel', [ReporteController::class, 'kardexExcel'])->name('reportes.kardex.excel');
    });

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';