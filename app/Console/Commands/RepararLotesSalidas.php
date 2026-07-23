<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Movimiento;
use Illuminate\Support\Facades\DB;

class RepararLotesSalidas extends Command
{
    protected $signature   = 'inventario:reparar-lotes';
    protected $description = 'Asigna lote_id a salidas antiguas que no lo tienen y recalcula cantidad_restante de cada lote de entrada.';

    public function handle()
    {
        $this->info('=== REPARACIÓN DE LOTES ===');

        // 1. Resetear cantidad_restante de todos los lotes de entrada al valor original
        $this->info('Paso 1: Restaurando cantidad_restante al valor original de cada lote de entrada...');
        DB::statement("UPDATE movimientos SET cantidad_restante = cantidad WHERE tipo = 'entrada'");
        $this->info('  ✓ Listo');

        // 2. Recorrer TODAS las salidas ordenadas por fecha y descontar FIFO
        $this->info('Paso 2: Aplicando todas las salidas en orden FIFO sobre los lotes...');
        $salidas = Movimiento::where('tipo', 'salida')
            ->orderBy('fecha', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        $reparadas = 0;
        $errores   = 0;

        foreach ($salidas as $salida) {
            $cantidadADescontar = $salida->cantidad;
            $loteAsignado       = null;

            // FIFO: descontar de los lotes más antiguos con stock disponible
            $lotes = Movimiento::where('articulo_id', $salida->articulo_id)
                ->where('tipo', 'entrada')
                ->where('cantidad_restante', '>', 0)
                ->orderBy('created_at', 'asc')
                ->get();

            foreach ($lotes as $lote) {
                if ($cantidadADescontar <= 0) break;

                $descontar = min($lote->cantidad_restante, $cantidadADescontar);
                $lote->cantidad_restante -= $descontar;
                $lote->save();

                if ($loteAsignado === null) {
                    $loteAsignado = $lote->id;
                }

                $cantidadADescontar -= $descontar;
            }

            // Actualizar lote_id en la salida
            if ($loteAsignado && $salida->lote_id !== $loteAsignado) {
                $salida->lote_id = $loteAsignado;
                $salida->save();
                $reparadas++;
            }

            if ($cantidadADescontar > 0) {
                $this->warn("  ⚠ Salida ID {$salida->id} - No se encontró suficiente stock en lotes para descontar {$cantidadADescontar} unidades.");
                $errores++;
            }
        }

        $this->info("  ✓ Salidas procesadas: {$salidas->count()} | Reparadas: {$reparadas} | Advertencias: {$errores}");

        // 3. Verificación final
        $this->info('Paso 3: Verificación...');
        $articulos = DB::select("
            SELECT a.id, a.codigo, a.nombre, a.cantidad as stock_articulo,
                   COALESCE(SUM(m.cantidad_restante),0) as suma_lotes
            FROM articulos a
            LEFT JOIN movimientos m ON m.articulo_id = a.id AND m.tipo = 'entrada' AND m.cantidad_restante > 0
            GROUP BY a.id, a.codigo, a.nombre, a.cantidad
            HAVING ABS(a.cantidad - COALESCE(SUM(m.cantidad_restante),0)) > 0.01
        ");

        if (empty($articulos)) {
            $this->info('  ✓ Todos los artículos coinciden: stock = suma de lotes restantes.');
        } else {
            $this->warn('  Artículos con diferencia residual:');
            foreach ($articulos as $a) {
                $this->line("    {$a->codigo} {$a->nombre}: stock={$a->stock_articulo}, lotes={$a->suma_lotes}");
            }
        }

        // Paso 4: crear lotes de entrada para artículos con stock sin respaldo
        $this->info('Paso 4: Creando lotes faltantes para stock sin respaldo en movimientos...');
        $articulosConDiff = \App\Models\Articulo::all()->filter(function ($art) {
            $sumaLotes = Movimiento::where('articulo_id', $art->id)
                ->where('tipo', 'entrada')
                ->where('cantidad_restante', '>', 0)
                ->sum('cantidad_restante');
            return abs($art->cantidad - $sumaLotes) > 0.001 && $art->cantidad > 0;
        });

        foreach ($articulosConDiff as $art) {
            $sumaLotes = Movimiento::where('articulo_id', $art->id)
                ->where('tipo', 'entrada')
                ->where('cantidad_restante', '>', 0)
                ->sum('cantidad_restante');
            $diferencia = $art->cantidad - $sumaLotes;
            if ($diferencia > 0) {
                Movimiento::create([
                    'numero_nota'      => Movimiento::siguienteNumeroNota(),
                    'articulo_id'      => $art->id,
                    'tipo'             => 'entrada',
                    'cantidad'         => $diferencia,
                    'cantidad_restante'=> $diferencia,
                    'precio_unitario'  => $art->precio,
                    'fecha'            => now()->toDateString(),
                    'notas'            => 'Stock inicial al implementar el sistema',
                    'user_id'          => 1,
                ]);
                $this->line("    ✓ Lote creado para {$art->codigo} {$art->nombre}: {$diferencia} unidades @ Bs. {$art->precio}");
            }
        }

        $this->info('=== FIN ===');
        return 0;
    }
}
