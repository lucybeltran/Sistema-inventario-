<?php

namespace App\Http\Controllers;

use App\Exports\InventarioExport;
use App\Exports\MovimientosExport;
use App\Models\Articulo;
use App\Models\Grupo;
use App\Models\Movimiento;
use App\Models\Trabajador;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReporteController extends Controller
{
    public function index()
    {
        // Resumen mensual SEPARADO POR UNIDAD (últimos 6 meses)
        $resumenMensual = Movimiento::selectRaw('
                DATE_FORMAT(movimientos.fecha, "%Y-%m") as periodo,
                articulos.unidad as unidad,
                SUM(CASE WHEN movimientos.tipo = "entrada" THEN movimientos.cantidad ELSE 0 END) as entradas,
                SUM(CASE WHEN movimientos.tipo = "salida" THEN movimientos.cantidad ELSE 0 END) as salidas
            ')
            ->join('articulos', 'movimientos.articulo_id', '=', 'articulos.id')
            ->groupBy('periodo', 'unidad')
            ->orderBy('periodo', 'desc')
            ->orderBy('unidad')
            ->get()
            ->groupBy('periodo'); // Agrupamos por mes para mostrarlos juntos

        $resumenCategoria = Grupo::withCount('articulos')->orderBy('id')->get();
        $totalArticulos = Articulo::count();
        $totalMovimientos = Movimiento::count();
        $articulosSinStock = Articulo::where('cantidad', '<=', 0)->count();

        // Lista de trabajadores para el filtro
        $trabajadores = Trabajador::orderBy('nombre')->get();

        return view('reportes.index', compact(
            'resumenMensual',
            'resumenCategoria',
            'totalArticulos',
            'totalMovimientos',
            'articulosSinStock',
            'trabajadores'
        ));
    }

    public function inventarioExcel()
    {
        return Excel::download(new InventarioExport, "inventario_" . now()->format('Y-m-d') . ".xlsx");
    }

    public function movimientosExcel(Request $request)
    {
        return Excel::download(
            new MovimientosExport($request->desde, $request->hasta, $request->trabajador_id),
            "movimientos_" . now()->format('Y-m-d') . ".xlsx"
        );
    }

    public function inventarioPdf()
    {
        $articulos = Articulo::with('grupo')
            ->orderByRaw('CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(codigo, "/", 1), "-", -1) AS UNSIGNED)')
            ->orderByRaw('CAST(SUBSTRING_INDEX(codigo, "/", -1) AS UNSIGNED)')
            ->get();
        $pdf = Pdf::loadView('reportes.pdf.inventario', compact('articulos'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('inventario_' . now()->format('Y-m-d') . '.pdf');
    }

    public function movimientosPdf(Request $request)
    {
        $query = Movimiento::with(['articulo', 'user', 'trabajador']);

        if ($request->desde) $query->whereDate('fecha', '>=', $request->desde);
        if ($request->hasta) $query->whereDate('fecha', '<=', $request->hasta);
        if ($request->trabajador_id) $query->where('trabajador_id', $request->trabajador_id);

        $movimientos = $query->orderByRaw('CAST(numero_nota AS UNSIGNED) DESC')->get();

        $desde = $request->desde;
        $hasta = $request->hasta;

        // Si hay filtro por trabajador, obtenemos sus datos
        $trabajadorFiltro = null;
        if ($request->trabajador_id) {
            $trabajadorFiltro = Trabajador::find($request->trabajador_id);
        }

        $pdf = Pdf::loadView('reportes.pdf.movimientos', compact('movimientos', 'desde', 'hasta', 'trabajadorFiltro'));
        $pdf->setPaper('A4', 'landscape');

        $nombreArchivo = 'movimientos_';
        if ($trabajadorFiltro) {
            $nombreSlug = strtolower(str_replace(' ', '_', $trabajadorFiltro->nombre));
            $nombreArchivo = "movimientos_{$nombreSlug}_";
        }
        $nombreArchivo .= now()->format('Y-m-d') . '.pdf';

        return $pdf->download($nombreArchivo);
    }

    /**
     * Reporte detallado de un trabajador específico.
     * Muestra TODO lo que sacó (movimientos de salida).
     */
    public function reporteTrabajador(Trabajador $trabajador, Request $request)
    {
        if (!auth()->user()->puedeReportes()) {
            abort(403, 'No tienes permisos para ver reportes de trabajadores.');
        }

        $query = Movimiento::with('articulo')
            ->where('trabajador_id', $trabajador->id)
            ->where('tipo', 'salida');

        if ($request->desde) $query->whereDate('fecha', '>=', $request->desde);
        if ($request->hasta) $query->whereDate('fecha', '<=', $request->hasta);

        $movimientos = $query->orderBy('fecha', 'desc')
                             ->orderBy('created_at', 'desc')
                             ->paginate(30)->withQueryString();

        // Estadísticas del trabajador
        $totalSalidas = Movimiento::where('trabajador_id', $trabajador->id)
                                    ->where('tipo', 'salida')
                                    ->count();

        $articulosUnicos = Movimiento::where('trabajador_id', $trabajador->id)
                                       ->where('tipo', 'salida')
                                       ->distinct('articulo_id')
                                       ->count('articulo_id');

        return view('reportes.trabajador', compact(
            'trabajador',
            'movimientos',
            'totalSalidas',
            'articulosUnicos'
        ));
    }

    /**
     * Descargar el PDF del reporte de un trabajador específico.
     */
    public function reporteTrabajadorPdf(Trabajador $trabajador, Request $request)
    {
        if (!auth()->user()->puedeReportes()) {
            abort(403, 'No tienes permisos para generar este reporte.');
        }

        $query = Movimiento::with('articulo')
            ->where('trabajador_id', $trabajador->id)
            ->where('tipo', 'salida');

        if ($request->desde) $query->whereDate('fecha', '>=', $request->desde);
        if ($request->hasta) $query->whereDate('fecha', '<=', $request->hasta);

        $movimientos = $query->orderByRaw('CAST(numero_nota AS UNSIGNED) DESC')->get();

        $desde = $request->desde;
        $hasta = $request->hasta;

        $pdf = Pdf::loadView('reportes.pdf.trabajador', compact('trabajador', 'movimientos', 'desde', 'hasta'));
        $pdf->setPaper('A4', 'portrait');

        $nombreSlug = strtolower(str_replace(' ', '_', $trabajador->nombre));
        return $pdf->download("historial_{$nombreSlug}_" . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Descargar el Excel del reporte de un trabajador.
     */
    public function reporteTrabajadorExcel(Trabajador $trabajador, Request $request)
    {
        if (!auth()->user()->puedeReportes()) {
            abort(403);
        }

        return Excel::download(
            new MovimientosExport($request->desde, $request->hasta, $trabajador->id),
            'historial_' . strtolower(str_replace(' ', '_', $trabajador->nombre)) . '_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Descargar movimientos de un mes específico en Excel.
     * @param string $periodo Formato: YYYY-MM (ej: 2026-05)
     */
    public function movimientosMesExcel($periodo)
    {
        if (!auth()->user()->puedeReportes()) {
            abort(403);
        }

        // Validar formato del período
        if (!preg_match('/^\d{4}-\d{2}$/', $periodo)) {
            abort(400, 'Período inválido. Use formato YYYY-MM');
        }

        // Calcular rango de fechas
        $inicio = \Carbon\Carbon::createFromFormat('Y-m', $periodo)->startOfMonth();
        $fin = $inicio->copy()->endOfMonth();

        // Obtener movimientos del mes
        $movimientos = Movimiento::with(['articulo', 'trabajador'])
            ->whereBetween('fecha', [$inicio, $fin])
            ->orderByRaw('CAST(numero_nota AS UNSIGNED) DESC')
            ->get();

        $nombreMes = $inicio->locale('es')->isoFormat('MMMM YYYY');

        return Excel::download(
            new MovimientosExport($inicio->format('Y-m-d'), $fin->format('Y-m-d')),
            "movimientos_" . str_replace([' ', '/'], '_', $nombreMes) . '.xlsx'
        );
    }

    /**
     * Descargar movimientos de un mes específico en PDF.
     * @param string $periodo Formato: YYYY-MM (ej: 2026-05)
     */
    public function movimientosMesPdf($periodo)
    {
        if (!auth()->user()->puedeReportes()) {
            abort(403);
        }

        // Validar formato del período
        if (!preg_match('/^\d{4}-\d{2}$/', $periodo)) {
            abort(400, 'Período inválido. Use formato YYYY-MM');
        }

        // Calcular rango de fechas
        $inicio = \Carbon\Carbon::createFromFormat('Y-m', $periodo)->startOfMonth();
        $fin = $inicio->copy()->endOfMonth();

        // Obtener movimientos del mes
        $movimientos = Movimiento::with(['articulo', 'trabajador'])
            ->whereBetween('fecha', [$inicio, $fin])
            ->orderByRaw('CAST(numero_nota AS UNSIGNED) DESC')
            ->get();

        $nombreMes = $inicio->locale('es')->isoFormat('MMMM YYYY');

        $pdf = Pdf::loadView('reportes.pdf.movimientos', compact('movimientos', 'nombreMes', 'inicio', 'fin'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download("movimientos_" . str_replace([' ', '/'], '_', $nombreMes) . '.pdf');
    }

    /**
     * Mostrar el Kardex (historial completo) de un artículo específico.
     */
    public function kardexProducto(Request $request, $articuloId = null)
    {
        if (!auth()->user()->puedeReportes()) {
            abort(403, 'No tienes permisos para ver el Kardex.');
        }

        // Lista de todos los artículos para el selector
        $articulos = \App\Models\Articulo::with('grupo')
            ->orderByRaw('CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(codigo, "/", 1), "-", -1) AS UNSIGNED)')
            ->orderByRaw('CAST(SUBSTRING_INDEX(codigo, "/", -1) AS UNSIGNED)')
            ->get();

        // Si no se seleccionó artículo, mostrar solo el selector
        if (!$articuloId) {
            return view('reportes.kardex', [
                'articulos' => $articulos,
                'articulo' => null,
                'movimientos' => collect(),
                'estadisticas' => null,
            ]);
        }

        $articulo = \App\Models\Articulo::with('grupo')->findOrFail($articuloId);

        // Obtener movimientos del artículo con filtros opcionales
        $query = \App\Models\Movimiento::with(['user', 'trabajador'])
            ->where('articulo_id', $articuloId);

        if ($request->desde) $query->whereDate('fecha', '>=', $request->desde);
        if ($request->hasta) $query->whereDate('fecha', '<=', $request->hasta);

        // Ordenar cronológicamente para calcular saldo acumulado
        $movimientos = $query->orderBy('fecha', 'asc')
                             ->orderBy('created_at', 'asc')
                             ->get();

        // Calcular saldo acumulado para cada movimiento
        // Empezamos desde el stock actual y vamos hacia atrás para reconstruir
        $saldoAcumulado = 0;
        $movimientos = $movimientos->map(function ($mov) use (&$saldoAcumulado) {
            if ($mov->tipo === 'entrada') {
                $saldoAcumulado += $mov->cantidad;
            } else {
                $saldoAcumulado -= $mov->cantidad;
            }
            $mov->saldo_acumulado = $saldoAcumulado;
            return $mov;
        });

        // Estadísticas del Kardex
        $totalEntradas = $movimientos->where('tipo', 'entrada')->sum('cantidad');
        $totalSalidas = $movimientos->where('tipo', 'salida')->sum('cantidad');
        $cantidadMovimientos = $movimientos->count();
        $entradasCount = $movimientos->where('tipo', 'entrada')->count();
        $salidasCount = $movimientos->where('tipo', 'salida')->count();

        $estadisticas = [
            'total_entradas' => $totalEntradas,
            'total_salidas' => $totalSalidas,
            'entradas_count' => $entradasCount,
            'salidas_count' => $salidasCount,
            'cantidad_movimientos' => $cantidadMovimientos,
            'stock_actual' => $articulo->cantidad,
            'valor_actual' => $articulo->cantidad * $articulo->precio,
        ];

        // Para mostrar en orden inverso (más reciente arriba) pero con saldo calculado correctamente
        $movimientos = $movimientos->reverse();

        return view('reportes.kardex', compact('articulos', 'articulo', 'movimientos', 'estadisticas'));
    }

    /**
     * Descargar Kardex en PDF.
     */
    public function kardexPdf(Request $request, $articuloId)
    {
        if (!auth()->user()->puedeReportes()) {
            abort(403);
        }

        $articulo = \App\Models\Articulo::with('grupo')->findOrFail($articuloId);

        $query = \App\Models\Movimiento::with(['user', 'trabajador'])
            ->where('articulo_id', $articuloId);

        if ($request->desde) $query->whereDate('fecha', '>=', $request->desde);
        if ($request->hasta) $query->whereDate('fecha', '<=', $request->hasta);

        $movimientos = $query->orderBy('fecha', 'asc')
                             ->orderBy('created_at', 'asc')
                             ->get();

        $saldoAcumulado = 0;
        $movimientos = $movimientos->map(function ($mov) use (&$saldoAcumulado) {
            if ($mov->tipo === 'entrada') {
                $saldoAcumulado += $mov->cantidad;
            } else {
                $saldoAcumulado -= $mov->cantidad;
            }
            $mov->saldo_acumulado = $saldoAcumulado;
            return $mov;
        });
        // Invertir para mostrar el más reciente arriba (el saldo ya está calculado)
$movimientos = $movimientos->reverse();

        $totalEntradas = $movimientos->where('tipo', 'entrada')->sum('cantidad');
        $totalSalidas = $movimientos->where('tipo', 'salida')->sum('cantidad');

        $desde = $request->desde;
        $hasta = $request->hasta;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reportes.pdf.kardex', compact(
            'articulo', 'movimientos', 'totalEntradas', 'totalSalidas', 'desde', 'hasta'
        ));
        $pdf->setPaper('A4', 'landscape');

        $nombreSlug = strtolower(str_replace([' ', '/'], ['_', '_'], $articulo->codigo));
        return $pdf->download("kardex_{$nombreSlug}_" . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Descargar Kardex en Excel.
     */
    public function kardexExcel(Request $request, $articuloId)
    {
        if (!auth()->user()->puedeReportes()) {
            abort(403);
        }

        $articulo = \App\Models\Articulo::findOrFail($articuloId);
        $nombreSlug = strtolower(str_replace([' ', '/'], ['_', '_'], $articulo->codigo));

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\KardexExport($articuloId, $request->desde, $request->hasta),
            "kardex_{$nombreSlug}_" . now()->format('Y-m-d') . '.xlsx'
        );
    }
}