<?php

namespace App\Console\Commands;

use App\Models\Articulo;
use App\Models\Movimiento;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RegistrarStockInicial extends Command
{
    protected $signature = 'inventario:stock-inicial';
    protected $description = 'Registra el stock inicial como ENTRADA para que el kardex cuadre';

    public function handle()
    {
        $this->info('=== REGISTRAR STOCK INICIAL ===');
        $this->newLine();

        $articulos = Articulo::where('cantidad', '>', 0)->get();
        $totalArticulos = $articulos->count();

        $this->info("Artículos con stock > 0: {$totalArticulos}");
        $this->newLine();

        if (!$this->confirm('¿Continuar con el proceso?', true)) {
            $this->warn('Cancelado por el usuario.');
            return;
        }

        DB::beginTransaction();
        try {
            $creados = 0;
            $omitidos = 0;

            foreach ($articulos as $articulo) {
                // Calcular stock que viene de movimientos
                $entradas = Movimiento::where('articulo_id', $articulo->id)
                    ->where('tipo', 'entrada')
                    ->sum('cantidad');
                $salidas = Movimiento::where('articulo_id', $articulo->id)
                    ->where('tipo', 'salida')
                    ->sum('cantidad');
                $stockDeMovimientos = $entradas - $salidas;

                // Stock que falta explicar
                $stockInicial = $articulo->cantidad - $stockDeMovimientos;

                if ($stockInicial <= 0) {
                    $this->line("  [OMITIDO]  {$articulo->codigo} {$articulo->nombre} → ya cuadra");
                    $omitidos++;
                    continue;
                }

                // Fecha del movimiento más antiguo para poner el stock inicial ANTES
                $primerMov = Movimiento::where('articulo_id', $articulo->id)
                    ->orderBy('fecha')
                    ->first();

                $fecha = $primerMov
                    ? \Carbon\Carbon::parse($primerMov->fecha)->subDay()
                    : now()->subDay();

                Movimiento::create([
                    'numero_nota'      => Movimiento::siguienteNumeroNota(),
                    'articulo_id'      => $articulo->id,
                    'tipo'             => 'entrada',
                    'cantidad'         => $stockInicial,
                    'precio_unitario'  => $articulo->precio,
                    'fecha'            => $fecha,
                    'notas'            => 'Stock inicial al implementar el sistema',
                    'user_id'          => 1,
                    'trabajador_id'    => null,
                    'trabajador_nombre'=> null,
                ]);

                $this->line("  [OK] {$articulo->codigo} {$articulo->nombre} → entrada de {$stockInicial} {$articulo->unidad}");
                $creados++;
            }

            DB::commit();

            $this->newLine();
            $this->info('COMPLETADO');
            $this->info("   Movimientos creados: {$creados}");
            $this->info("   Artículos omitidos:  {$omitidos}");
            $this->newLine();
            $this->info('El kardex de cada artículo ahora debería cuadrar.');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('ERROR: ' . $e->getMessage());
        }
    }
}
