<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Movimiento;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = now()->toDateString();
        $mesActual = now()->month;
        $anioActual = now()->year;

        $totalArticulos = Articulo::count();
        
        // Movimientos correspondientes solo al mes actual para evitar acumulación excesiva
        $totalMovimientos = Movimiento::whereMonth('fecha', $mesActual)
            ->whereYear('fecha', $anioActual)
            ->count();
            
        $entradasHoy = Movimiento::where('tipo', 'entrada')->where('fecha', $hoy)->count();
        $salidasHoy = Movimiento::where('tipo', 'salida')->where('fecha', $hoy)->count();

        // Artículos bajo stock mínimo
        $articulosBajoStock = Articulo::with('grupo')
            ->whereColumn('cantidad', '<=', 'stock_minimo')
            ->where('stock_minimo', '>', 0)
            ->get();

        // Flujo mensual de últimos 6 meses (Bs.)
        $meses = collect();
        for ($i = 5; $i >= 0; $i--) {
            $meses->push(now()->subMonths($i)->format('Y-m'));
        }

        // Entradas agrupadas por mes (Bs.)
        $flujoEntradas = Movimiento::where('tipo', 'entrada')
            ->where('fecha', '>=', now()->subMonths(5)->startOfMonth()->toDateString())
            ->selectRaw('DATE_FORMAT(fecha, "%Y-%m") as mes, SUM(cantidad * precio_unitario) as total')
            ->groupBy('mes')
            ->pluck('total', 'mes');

        // Salidas agrupadas por mes (Bs.)
        $flujoSalidas = Movimiento::where('tipo', 'salida')
            ->where('fecha', '>=', now()->subMonths(5)->startOfMonth()->toDateString())
            ->selectRaw('DATE_FORMAT(fecha, "%Y-%m") as mes, SUM(cantidad * precio_unitario) as total')
            ->groupBy('mes')
            ->pluck('total', 'mes');

        $chartMeses = [];
        $chartEntradas = [];
        $chartSalidas = [];

        foreach ($meses as $m) {
            $date = \Carbon\Carbon::parse($m . '-01');
            $chartMeses[] = ucfirst($date->translatedFormat('F'));
            $chartEntradas[] = (float) ($flujoEntradas->get($m) ?? 0);
            $chartSalidas[] = (float) ($flujoSalidas->get($m) ?? 0);
        }

        // Top 5 artículos más consumidos (Salidas)
        $topConsumidos = Movimiento::where('tipo', 'salida')
            ->selectRaw('articulo_id, SUM(cantidad) as total')
            ->groupBy('articulo_id')
            ->orderBy('total', 'desc')
            ->take(5)
            ->with('articulo')
            ->get();

        $topNombres = [];
        $topCantidades = [];
        foreach ($topConsumidos as $tc) {
            if ($tc->articulo) {
                $topNombres[] = $tc->articulo->codigo . ' — ' . $tc->articulo->nombre;
                $topCantidades[] = (float) $tc->total;
            }
        }

        // TOP 5 Contratistas que más material retiran (por cantidad acumulada en salidas)
        $topContratistas = Movimiento::where('tipo', 'salida')
            ->whereNotNull('trabajador_id')
            ->selectRaw('trabajador_id, COUNT(*) as retiros, SUM(cantidad) as total_cantidad')
            ->groupBy('trabajador_id')
            ->orderBy('total_cantidad', 'desc')
            ->take(5)
            ->with('trabajador')
            ->get();

        // Últimos movimientos — limitados también al mes actual para no acumular
        $ultimosMovimientos = Movimiento::with(['articulo', 'user', 'trabajador'])
            ->whereMonth('fecha', $mesActual)
            ->whereYear('fecha', $anioActual)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalArticulos',
            'totalMovimientos',
            'entradasHoy',
            'salidasHoy',
            'ultimosMovimientos',
            'articulosBajoStock',
            'chartMeses',
            'chartEntradas',
            'chartSalidas',
            'topNombres',
            'topCantidades',
            'topContratistas'
        ));
    }
}