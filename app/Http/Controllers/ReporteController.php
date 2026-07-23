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
        // Resumen mensual SEPARADO POR UNIDAD (últimos 6 meses) - Excluyendo cargas iniciales
        $resumenMensual = Movimiento::selectRaw('
                DATE_FORMAT(movimientos.fecha, "%Y-%m") as periodo,
                articulos.unidad as unidad,
                SUM(CASE WHEN movimientos.tipo = "entrada" THEN movimientos.cantidad ELSE 0 END) as entradas,
                SUM(CASE WHEN movimientos.tipo = "salida" THEN movimientos.cantidad ELSE 0 END) as salidas
            ')
            ->join('articulos', 'movimientos.articulo_id', '=', 'articulos.id')
            ->where(function ($q) {
                $q->whereNull('movimientos.notas')
                  ->orWhere('movimientos.notas', 'NOT LIKE', 'Stock inicial%');
            })
            ->where(function ($q) {
                $q->whereNull('movimientos.entregado_por')
                  ->orWhere('movimientos.entregado_por', '!=', 'CARGA EXCEL');
            })
            ->groupBy('periodo', 'unidad')
            ->orderBy('periodo', 'desc')
            ->orderBy('unidad')
            ->get()
            ->groupBy('periodo');

        // ── KPIs ENRIQUECIDOS POR MES ──────────────────────────────────────────
        $resumenMensualKpis = [];

        foreach ($resumenMensual->keys() as $periodo) {
            $inicio = \Carbon\Carbon::createFromFormat('Y-m', $periodo)->startOfMonth();
            $fin    = $inicio->copy()->endOfMonth();

            // Filtro base reutilizable (excluir stock inicial y cargas excel)
            $baseQuery = fn() => Movimiento::whereBetween('movimientos.fecha', [$inicio, $fin])
                ->where(function ($q) {
                    $q->whereNull('movimientos.notas')
                      ->orWhere('movimientos.notas', 'NOT LIKE', 'Stock inicial%');
                })
                ->where(function ($q) {
                    $q->whereNull('movimientos.entregado_por')
                      ->orWhere('movimientos.entregado_por', '!=', 'CARGA EXCEL');
                })
                ->where('movimientos.numero_nota', '!=', 'INICIAL');

            // Total movimientos del mes
            $totalMovs = $baseQuery()->count();

            // Valor total salidas en Bs.
            $valorSalidas = $baseQuery()
                ->where('tipo', 'salida')
                ->selectRaw('SUM(cantidad * COALESCE(precio_unitario, 0)) as total')
                ->value('total') ?? 0;

            // Valor total entradas en Bs.
            $valorEntradas = $baseQuery()
                ->where('tipo', 'entrada')
                ->selectRaw('SUM(cantidad * COALESCE(precio_unitario, 0)) as total')
                ->value('total') ?? 0;

            // Top 5 materiales más consumidos (por cantidad de salidas)
            $topMateriales = $baseQuery()
                ->where('tipo', 'salida')
                ->join('articulos as art', 'movimientos.articulo_id', '=', 'art.id')
                ->selectRaw('art.nombre, art.codigo, art.unidad, SUM(movimientos.cantidad) as total_salida')
                ->groupBy('movimientos.articulo_id', 'art.nombre', 'art.codigo', 'art.unidad')
                ->orderByDesc('total_salida')
                ->limit(5)
                ->get();

            // Top 5 trabajadores/contratistas con más salidas
            $topTrabajadores = $baseQuery()
                ->where('tipo', 'salida')
                ->whereNotNull('trabajador_id')
                ->join('trabajadores as tr', 'movimientos.trabajador_id', '=', 'tr.id')
                ->selectRaw('tr.nombre, tr.codigo, COUNT(*) as total_movs, SUM(movimientos.cantidad * COALESCE(movimientos.precio_unitario, 0)) as valor_total')
                ->groupBy('movimientos.trabajador_id', 'tr.nombre', 'tr.codigo')
                ->orderByDesc('total_movs')
                ->limit(5)
                ->get();

            // Artículos que llegaron a stock 0 o menos en este mes (agotados)
            $articulosAgotados = Movimiento::whereBetween('fecha', [$inicio, $fin])
                ->where('tipo', 'salida')
                ->with('articulo')
                ->get()
                ->filter(fn($m) => $m->articulo && $m->articulo->cantidad <= 0)
                ->pluck('articulo')
                ->unique('id')
                ->values();

            // Comparativa con el mes anterior
            $inicioPrev = $inicio->copy()->subMonth()->startOfMonth();
            $finPrev    = $inicioPrev->copy()->endOfMonth();
            $totalMovsPrev = Movimiento::whereBetween('fecha', [$inicioPrev, $finPrev])
                ->where(function ($q) {
                    $q->whereNull('notas')
                      ->orWhere('notas', 'NOT LIKE', 'Stock inicial%');
                })
                ->where(function ($q) {
                    $q->whereNull('entregado_por')
                      ->orWhere('entregado_por', '!=', 'CARGA EXCEL');
                })
                ->count();

            $variacionPorc = $totalMovsPrev > 0
                ? round((($totalMovs - $totalMovsPrev) / $totalMovsPrev) * 100, 1)
                : ($totalMovs > 0 ? 100 : 0);

            $resumenMensualKpis[$periodo] = [
                'total_movs'        => $totalMovs,
                'valor_salidas'     => $valorSalidas,
                'valor_entradas'    => $valorEntradas,
                'top_materiales'    => $topMateriales,
                'top_trabajadores'  => $topTrabajadores,
                'agotados'          => $articulosAgotados,
                'movs_mes_anterior' => $totalMovsPrev,
                'variacion_porc'    => $variacionPorc,
            ];
        }

        $resumenCategoria = Grupo::withCount('articulos')->orderBy('id')->get();
        $totalArticulos = Articulo::count();
        
        // Contar solo movimientos del mes en curso y que no sean stock inicial/carga excel
        $totalMovimientos = Movimiento::whereBetween('fecha', [now()->startOfMonth(), now()->endOfMonth()])
            ->where(function ($q) {
                $q->whereNull('notas')
                  ->orWhere('notas', 'NOT LIKE', 'Stock inicial%');
            })
            ->where(function ($q) {
                $q->whereNull('entregado_por')
                  ->orWhere('entregado_por', '!=', 'CARGA EXCEL');
            })
            ->count();
            
        $articulosSinStock = Articulo::where('cantidad', '<=', 0)->count();

        // Lista de trabajadores para el filtro
        $trabajadores = Trabajador::orderBy('nombre')->get();

        // Cargar bitácora de auditoría si es administrador
        $logs = null;
        if (auth()->user()->esAdmin()) {
            $logsQuery = \App\Models\AuditoriaLog::with('user');
            
            if (request()->filled('search_logs')) {
                $term = request('search_logs');
                $logsQuery->where(function($q) use ($term) {
                    $q->where('accion', 'LIKE', "%{$term}%")
                      ->orWhere('detalles', 'LIKE', "%{$term}%")
                      ->orWhere('ip_address', 'LIKE', "%{$term}%")
                      ->orWhereHas('user', function($qu) use ($term) {
                          $qu->where('name', 'LIKE', "%{$term}%");
                      });
                });
            }

            if (request()->filled('fecha_desde')) {
                $logsQuery->whereDate('created_at', '>=', request('fecha_desde'));
            }

            if (request()->filled('fecha_hasta')) {
                $logsQuery->whereDate('created_at', '<=', request('fecha_hasta'));
            }
            
            $logs = $logsQuery->orderBy('created_at', 'desc')
                              ->paginate(15, ['*'], 'logs_page')
                              ->withQueryString();
        }

        return view('reportes.index', compact(
            'resumenMensual',
            'resumenMensualKpis',
            'resumenCategoria',
            'totalArticulos',
            'totalMovimientos',
            'articulosSinStock',
            'trabajadores',
            'logs'
        ));
    }

    public function bitacoraIndex(Request $request)
    {
        if (!auth()->user()->esAdmin()) {
            abort(403);
        }
        return redirect()->route('reportes.index', array_merge($request->all(), ['tab' => 'bitacora']));
    }

    public function inventarioExcel(Request $request)
    {
        $stockFilter = $request->stock_filter ?? 'todos';
        $grupoId = $request->grupo_id ?? 'todos';
        return Excel::download(
            new InventarioExport($stockFilter, $grupoId), 
            "inventario_" . $stockFilter . "_grupo_" . $grupoId . "_" . now()->format('Y-m-d') . ".xlsx"
        );
    }

    public function movimientosExcel(Request $request)
    {
        $incluirInicial = $request->has('incluir_inicial') && $request->incluir_inicial == '1';
        return Excel::download(
            new MovimientosExport(
                $request->desde,
                $request->hasta,
                $request->trabajador_id,
                $request->tipo,
                $incluirInicial,
                $request->articulo_id
            ),
            "movimientos_" . now()->format('Y-m-d') . ".xlsx"
        );
    }

    public function inventarioPdf(Request $request)
    {
        $query = Articulo::with('grupo');
        
        $stockFilter = $request->stock_filter ?? 'todos';
        if ($stockFilter === 'con_stock') {
            $query->where('cantidad', '>', 0);
        } elseif ($stockFilter === 'sin_stock') {
            $query->where('cantidad', '<=', 0);
        }

        $grupoId = $request->grupo_id ?? 'todos';
        if ($grupoId !== 'todos') {
            $query->where('grupo_id', $grupoId);
        }

        $articulos = $query->orderByRaw('CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(codigo, "/", 1), "-", -1) AS UNSIGNED)')
            ->orderByRaw('CAST(SUBSTRING_INDEX(codigo, "/", -1) AS UNSIGNED)')
            ->get();
            
        // Calcular lotes activos y precios por artículo
        $lotesActivos = \App\Models\Movimiento::where('tipo', 'entrada')
            ->where('cantidad_restante', '>', 0)
            ->whereNotNull('precio_unitario')
            ->orderBy('created_at', 'asc')
            ->get(['articulo_id', 'precio_unitario', 'cantidad_restante', 'created_at'])
            ->groupBy('articulo_id');

        $preciosPorArticulo = $lotesActivos->map(function ($movs) {
            return $movs->groupBy(fn($m) => number_format($m->precio_unitario, 2))
                ->map(fn($g) => [
                    'precio'   => $g->first()->precio_unitario,
                    'cantidad' => $g->sum('cantidad_restante'),
                ])
                ->values();
        });

        $pdf = Pdf::loadView('reportes.pdf.inventario', compact('articulos', 'stockFilter', 'preciosPorArticulo'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('inventario_' . $stockFilter . '_' . now()->format('Y-m-d') . '.pdf');
    }

    public function movimientosPdf(Request $request)
    {
        $query = Movimiento::with(['articulo', 'user', 'trabajador']);

        if ($request->desde)        $query->whereDate('fecha', '>=', $request->desde);
        if ($request->hasta)        $query->whereDate('fecha', '<=', $request->hasta);
        if ($request->trabajador_id) $query->where('trabajador_id', $request->trabajador_id);
        if ($request->tipo)         $query->where('tipo', $request->tipo);
        if ($request->articulo_id)  $query->where('articulo_id', $request->articulo_id);
        if (!$request->has('incluir_inicial') || $request->incluir_inicial != '1') {
            $query->where(function ($q) {
                $q->where('tipo', 'salida')
                  ->orWhere(function ($sub) {
                      $sub->where('tipo', 'entrada')
                          ->where('notas', 'not like', 'Stock inicial%')
                          ->where(function ($qq) {
                              $qq->whereNull('entregado_por')
                                 ->orWhere('entregado_por', '!=', 'CARGA EXCEL');
                          });
                  });
            });
        }

        $movimientos = $query->orderBy('fecha', 'asc')->orderBy('created_at', 'asc')->get();

        $desde = $request->desde;
        $hasta = $request->hasta;
        $tipo  = $request->tipo;

        // Si hay filtro por trabajador, obtenemos sus datos
        $trabajadorFiltro = null;
        if ($request->trabajador_id) {
            $trabajadorFiltro = Trabajador::find($request->trabajador_id);
        }

        // Si hay filtro por artículo, obtenemos sus datos
        $articuloFiltro = null;
        if ($request->articulo_id) {
            $articuloFiltro = \App\Models\Articulo::find($request->articulo_id);
        }

        $pdf = Pdf::loadView('reportes.pdf.movimientos', compact(
            'movimientos', 'desde', 'hasta', 'trabajadorFiltro', 'tipo', 'articuloFiltro'
        ));
        $pdf->setPaper('A4', 'landscape');

        $nombreArchivo = 'movimientos_';
        if ($articuloFiltro) {
            $codigoSlug = strtolower(str_replace(['/', ' '], '_', $articuloFiltro->codigo));
            $nombreArchivo = "movimientos_{$codigoSlug}_";
        } elseif ($tipo) {
            $nombreArchivo = "{$tipo}s_";
        } elseif ($trabajadorFiltro) {
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
        if (!auth()->user()->puedeReportes() && !auth()->user()->esAlmacenero() && !auth()->user()->puedeEditar()) {
            abort(403, 'No tienes permisos para ver reportes de trabajadores.');
        }

        $desde = $request->input('desde');
        $hasta = $request->input('hasta');
        $filtradoPorDefectoMes = false;

        // Por defecto, si no se especifican fechas, filtrar por los últimos 2 meses
        if (!$desde && !$hasta) {
            $desde = now()->subMonth()->startOfMonth()->toDateString();
            $hasta = now()->endOfMonth()->toDateString();
            $filtradoPorDefectoMes = true;
        }

        $query = Movimiento::with('articulo')
            ->where('trabajador_id', $trabajador->id)
            ->where('tipo', 'salida');

        if ($desde) $query->whereDate('fecha', '>=', $desde);
        if ($hasta) $query->whereDate('fecha', '<=', $hasta);

        $movimientos = $query->orderBy('fecha', 'asc')
                             ->orderBy('created_at', 'asc')
                             ->paginate(30)->withQueryString();

        // Estadísticas del trabajador filtradas por fecha
        $totalSalidas = Movimiento::where('trabajador_id', $trabajador->id)
                                    ->where('tipo', 'salida')
                                    ->when($desde, fn($q) => $q->whereDate('fecha', '>=', $desde))
                                    ->when($hasta, fn($q) => $q->whereDate('fecha', '<=', $hasta))
                                    ->count();

        $articulosUnicos = Movimiento::where('trabajador_id', $trabajador->id)
                                       ->where('tipo', 'salida')
                                       ->when($desde, fn($q) => $q->whereDate('fecha', '>=', $desde))
                                       ->when($hasta, fn($q) => $q->whereDate('fecha', '<=', $hasta))
                                       ->distinct('articulo_id')
                                       ->count('articulo_id');

        // Sumas filtradas por fecha
        $totalGastado = Movimiento::where('trabajador_id', $trabajador->id)
            ->where('tipo', 'salida')
            ->when($desde, fn($q) => $q->whereDate('fecha', '>=', $desde))
            ->when($hasta, fn($q) => $q->whereDate('fecha', '<=', $hasta))
            ->get()
            ->sum(fn($m) => $m->cantidad * ($m->precio_unitario ?? 0));

        $totalCantidad = Movimiento::where('trabajador_id', $trabajador->id)
            ->where('tipo', 'salida')
            ->when($desde, fn($q) => $q->whereDate('fecha', '>=', $desde))
            ->when($hasta, fn($q) => $q->whereDate('fecha', '<=', $hasta))
            ->sum('cantidad');

        return view('reportes.trabajador', compact(
            'trabajador',
            'movimientos',
            'totalSalidas',
            'articulosUnicos',
            'totalGastado',
            'totalCantidad',
            'desde',
            'hasta',
            'filtradoPorDefectoMes'
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

        $movimientos = $query->orderBy('fecha', 'asc')->orderBy('created_at', 'asc')->get();

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

        $inicio = \Carbon\Carbon::createFromFormat('Y-m', $periodo)->startOfMonth();
        $nombreMes = $inicio->locale('es')->isoFormat('MMMM YYYY');

        return Excel::download(
            new \App\Exports\ResumenMensualExport($periodo),
            "resumen_mensual_" . str_replace([' ', '/'], '_', $nombreMes) . '.xlsx'
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

        $nombreMes = $inicio->locale('es')->isoFormat('MMMM YYYY');

        // Filtro base (excluir stock inicial / carga excel)
        $baseQuery = fn() => Movimiento::whereBetween('movimientos.fecha', [$inicio, $fin])
            ->where(function ($q) {
                $q->whereNull('movimientos.notas')
                  ->orWhere('movimientos.notas', 'NOT LIKE', 'Stock inicial%');
            })
            ->where(function ($q) {
                $q->whereNull('movimientos.entregado_por')
                  ->orWhere('movimientos.entregado_por', '!=', 'CARGA EXCEL');
            });

        $totalMovs     = $baseQuery()->count();
        $valorSalidas  = $baseQuery()->where('tipo', 'salida')->selectRaw('SUM(cantidad * COALESCE(precio_unitario,0)) as t')->value('t') ?? 0;
        $valorEntradas = $baseQuery()->where('tipo', 'entrada')->selectRaw('SUM(cantidad * COALESCE(precio_unitario,0)) as t')->value('t') ?? 0;

        $topMateriales = $baseQuery()
            ->where('tipo', 'salida')
            ->join('articulos as art', 'movimientos.articulo_id', '=', 'art.id')
            ->selectRaw('art.nombre, art.codigo, art.unidad, SUM(movimientos.cantidad) as total_salida')
            ->groupBy('movimientos.articulo_id', 'art.nombre', 'art.codigo', 'art.unidad')
            ->orderByDesc('total_salida')
            ->limit(5)
            ->get();

        $topTrabajadores = $baseQuery()
            ->where('tipo', 'salida')
            ->whereNotNull('trabajador_id')
            ->join('trabajadores as tr', 'movimientos.trabajador_id', '=', 'tr.id')
            ->selectRaw('tr.nombre, tr.codigo, COUNT(*) as total_movs, SUM(movimientos.cantidad * COALESCE(movimientos.precio_unitario,0)) as valor_total')
            ->groupBy('movimientos.trabajador_id', 'tr.nombre', 'tr.codigo')
            ->orderByDesc('total_movs')
            ->limit(5)
            ->get();

        $articulosAgotados = Movimiento::whereBetween('fecha', [$inicio, $fin])
            ->where('tipo', 'salida')
            ->with('articulo')
            ->get()
            ->filter(fn($m) => $m->articulo && $m->articulo->cantidad <= 0)
            ->pluck('articulo')
            ->unique('id')
            ->values();

        // Resumen por unidad (tabla actual)
        $resumenUnidad = Movimiento::selectRaw('
                articulos.unidad,
                SUM(CASE WHEN movimientos.tipo = "entrada" THEN movimientos.cantidad ELSE 0 END) as entradas,
                SUM(CASE WHEN movimientos.tipo = "salida" THEN movimientos.cantidad ELSE 0 END) as salidas
            ')
            ->join('articulos', 'movimientos.articulo_id', '=', 'articulos.id')
            ->whereBetween('movimientos.fecha', [$inicio, $fin])
            ->where(function ($q) {
                $q->whereNull('movimientos.notas')
                  ->orWhere('movimientos.notas', 'NOT LIKE', 'Stock inicial%');
            })
            ->groupBy('articulos.unidad')
            ->orderBy('articulos.unidad')
            ->get();

        // Comparativa mes anterior
        $inicioPrev    = $inicio->copy()->subMonth()->startOfMonth();
        $finPrev       = $inicioPrev->copy()->endOfMonth();
        $totalMovsPrev = Movimiento::whereBetween('fecha', [$inicioPrev, $finPrev])
            ->where(function ($q) { $q->whereNull('notas')->orWhere('notas', 'NOT LIKE', 'Stock inicial%'); })
            ->where(function ($q) { $q->whereNull('entregado_por')->orWhere('entregado_por', '!=', 'CARGA EXCEL'); })
            ->count();
        $variacionPorc = $totalMovsPrev > 0
            ? round((($totalMovs - $totalMovsPrev) / $totalMovsPrev) * 100, 1)
            : ($totalMovs > 0 ? 100 : 0);

        $pdf = Pdf::loadView('reportes.pdf.resumen_mes', compact(
            'nombreMes', 'inicio', 'fin',
            'totalMovs', 'valorSalidas', 'valorEntradas',
            'topMateriales', 'topTrabajadores', 'articulosAgotados',
            'resumenUnidad', 'totalMovsPrev', 'variacionPorc'
        ));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("resumen_mensual_" . str_replace([' ', '/'], '_', $nombreMes) . '.pdf');
    }

    /**
     * Mostrar el Kardex (historial completo) de un artículo específico.
     */
    public function kardexProducto(Request $request, $articuloId = null)
    {
        if (!auth()->user()->puedeReportes() && !auth()->user()->esAlmacenero() && !auth()->user()->puedeEditar()) {
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

        // Ocultar Stock Inicial a menos que se pida explícitamente
        if (!$request->has('incluir_inicial') || $request->incluir_inicial != '1') {
            $query->where(function ($q) {
                $q->where('numero_nota', '!=', 'STOCK-INI')
                  ->orWhereNull('numero_nota');
            });
        }

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

        // Calcular la operación matemática de desglose si hay múltiples precios
        $lotesParaOperacion = \App\Models\Movimiento::where('articulo_id', $articuloId)
            ->where('tipo', 'entrada')
            ->where('cantidad_restante', '>', 0)
            ->whereNotNull('precio_unitario')
            ->orderBy('created_at', 'asc')
            ->get(['cantidad_restante', 'precio_unitario']);

        $partesOperacion = [];
        $sumaValoresCalculados = 0;
        foreach ($lotesParaOperacion as $l) {
            $cantForm = number_format($l->cantidad_restante, 2);
            $precForm = number_format($l->precio_unitario, 2);
            $partesOperacion[] = "({$cantForm} {$articulo->unidad} × Bs. {$precForm})";
            $sumaValoresCalculados += $l->cantidad_restante * $l->precio_unitario;
        }

        $operacionMatematica = count($partesOperacion) > 0
            ? implode(" + ", $partesOperacion) . " = Bs. " . number_format($sumaValoresCalculados, 2)
            : "No hay lotes con stock activo para calcular la valoración.";

        $estadisticas = [
            'total_entradas'       => $totalEntradas,
            'total_salidas'        => $totalSalidas,
            'entradas_count'       => $entradasCount,
            'salidas_count'        => $salidasCount,
            'cantidad_movimientos' => $cantidadMovimientos,
            'stock_actual'         => $articulo->cantidad,
            'valor_actual'         => $sumaValoresCalculados,
            'operacion_matematica' => $operacionMatematica,
        ];

        // Filtrar por tipo DESPUÉS de calcular estadísticas (para no romper el acumulado ni las estadísticas generales)
        $tipo = $request->tipo;
        if ($tipo && in_array($tipo, ['entrada', 'salida'])) {
            $movimientos = $movimientos->filter(fn($m) => $m->tipo === $tipo)->values();
        }

        // Para mostrar en orden inverso (más reciente arriba) pero con saldo calculado correctamente
        $movimientos = $movimientos->reverse();

        // Historial de precios de entrada: solo lotes que aún tienen stock (cantidad_restante > 0)
        $historialPrecios = \App\Models\Movimiento::where('articulo_id', $articuloId)
            ->where('tipo', 'entrada')
            ->where('cantidad_restante', '>', 0)
            ->whereNotNull('precio_unitario')
            ->orderBy('created_at', 'asc')
            ->get(['precio_unitario', 'cantidad_restante', 'created_at', 'numero_nota'])
            ->groupBy(fn($m) => number_format($m->precio_unitario, 2))
            ->map(function ($grupo) {
                return [
                    'precio'      => $grupo->first()->precio_unitario,
                    'cantidad'    => $grupo->sum('cantidad_restante'), // stock restante de ese precio
                    'veces'       => $grupo->count(),
                    'primera'     => $grupo->first()->created_at->format('d/m/Y'),
                    'ultima'      => $grupo->last()->created_at->format('d/m/Y'),
                    'notas'       => $grupo->pluck('numero_nota')->filter()->values(),
                ];
            })
            ->sortByDesc('precio')
            ->values();

        return view('reportes.kardex', compact('articulos', 'articulo', 'movimientos', 'estadisticas', 'historialPrecios', 'tipo'));
    }

    /**
     * Descargar Kardex en PDF.
     */
    public function kardexPdf(Request $request, $articuloId)
    {
        if (!auth()->user()->puedeReportes() && !auth()->user()->esAlmacenero() && !auth()->user()->puedeEditar()) {
            abort(403, 'No tienes permisos para exportar este Kardex.');
        }

        $articulo = \App\Models\Articulo::with('grupo')->findOrFail($articuloId);

        $query = \App\Models\Movimiento::with(['user', 'trabajador'])
            ->where('articulo_id', $articuloId);

        if ($request->desde) $query->whereDate('fecha', '>=', $request->desde);
        if ($request->hasta) $query->whereDate('fecha', '<=', $request->hasta);
        // Nota: NO filtramos por tipo aquí porque el saldo acumulado necesita TODOS los movimientos.
        // El filtro de tipo se aplica solo a la visualización en el PDF.

        // Ocultar Stock Inicial a menos que se pida explícitamente
        if (!$request->has('incluir_inicial') || $request->incluir_inicial != '1') {
            $query->where(function ($q) {
                $q->where('numero_nota', '!=', 'STOCK-INI')
                  ->orWhereNull('numero_nota');
            });
        }

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

        // Filtrar por tipo DESPUÉS de calcular el saldo (para no romper el acumulado)
        $tipo = $request->tipo;
        if ($tipo) {
            $movimientos = $movimientos->filter(fn($m) => $m->tipo === $tipo)->values();
        }

        $totalEntradas = $movimientos->where('tipo', 'entrada')->sum('cantidad');
        $totalSalidas  = $movimientos->where('tipo', 'salida')->sum('cantidad');

        // Monto total gastado en entradas (precio_unitario × cantidad)
        $montoTotal = $movimientos->where('tipo', 'entrada')
                                  ->sum(fn($m) => ($m->precio_unitario ?? 0) * $m->cantidad);

        $desde = $request->desde;
        $hasta = $request->hasta;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reportes.pdf.kardex', compact(
            'articulo', 'movimientos', 'totalEntradas', 'totalSalidas', 'montoTotal', 'desde', 'hasta', 'tipo'
        ));
        $pdf->setPaper('A4', 'landscape');

        $nombreSlug = strtolower(str_replace([' ', '/'], ['_', '_'], $articulo->codigo));
        $sufijo = $tipo ? "_{$tipo}s" : '';
        return $pdf->download("kardex_{$nombreSlug}{$sufijo}_" . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Descargar Kardex en Excel.
     */
    public function kardexExcel(Request $request, $articuloId)
    {
        if (!auth()->user()->puedeReportes() && !auth()->user()->esAlmacenero() && !auth()->user()->puedeEditar()) {
            abort(403, 'No tienes permisos para exportar este Kardex.');
        }

        $articulo = \App\Models\Articulo::findOrFail($articuloId);
        $nombreSlug = strtolower(str_replace([' ', '/'], ['_', '_'], $articulo->codigo));
        $tipo = $request->tipo;
        $sufijo = $tipo ? "_{$tipo}s" : '';
        $incluirInicial = $request->has('incluir_inicial') && $request->incluir_inicial == '1';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\KardexExport($articuloId, $request->desde, $request->hasta, $tipo, $incluirInicial),
            "kardex_{$nombreSlug}{$sufijo}_" . now()->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Descargar reporte de Rotación/Clasificación en Excel.
     */
    public function rotacionExcel(Request $request)
    {
        if (!auth()->user()->puedeReportes()) {
            abort(403);
        }

        $rotacion = $request->rotacion;
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\RotacionExport($request->buscar, $request->grupo, $rotacion),
            "clasificacion_materiales_" . now()->format('Y-m-d') . ".xlsx"
        );
    }

    /**
     * Descargar reporte de Rotación/Clasificación en PDF.
     */
    public function rotacionPdf(Request $request)
    {
        if (!auth()->user()->puedeReportes()) {
            abort(403);
        }

        $rotacion = $request->rotacion;

        $diarios = collect();
        $ocasionales = collect();
        $prestamos = collect();

        if (!$rotacion || $rotacion === 'todos' || $rotacion === 'diario' || $rotacion === 'consumible') {
            $queryDiario = \App\Models\Articulo::with('grupo')->whereIn('rotacion', ['diario', 'consumible']);
            if ($request->filled('buscar')) {
                $termino = $request->buscar;
                $queryDiario->where(function ($q) use ($termino) {
                    $q->where('codigo', 'LIKE', "%{$termino}%")
                      ->orWhere('nombre', 'LIKE', "%{$termino}%");
                });
            }
            if ($request->filled('grupo')) {
                $queryDiario->where('grupo_id', $request->grupo);
            }
            $diarios = $queryDiario
                ->orderByRaw('CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(codigo, "/", 1), "-", -1) AS UNSIGNED)')
                ->orderByRaw('CAST(SUBSTRING_INDEX(codigo, "/", -1) AS UNSIGNED)')
                ->get();
        }

        if (!$rotacion || $rotacion === 'todos' || $rotacion === 'ocasional') {
            $queryOcasional = \App\Models\Articulo::with('grupo')->where(function($q) {
                $q->where('rotacion', 'ocasional')
                  ->orWhereNull('rotacion')
                  ->orWhere('rotacion', '');
            });
            if ($request->filled('buscar')) {
                $termino = $request->buscar;
                $queryOcasional->where(function ($q) use ($termino) {
                    $q->where('codigo', 'LIKE', "%{$termino}%")
                      ->orWhere('nombre', 'LIKE', "%{$termino}%");
                });
            }
            if ($request->filled('grupo')) {
                $queryOcasional->where('grupo_id', $request->grupo);
            }
            $ocasionales = $queryOcasional
                ->orderByRaw('CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(codigo, "/", 1), "-", -1) AS UNSIGNED)')
                ->orderByRaw('CAST(SUBSTRING_INDEX(codigo, "/", -1) AS UNSIGNED)')
                ->get();
        }

        if (!$rotacion || $rotacion === 'todos' || $rotacion === 'prestamo') {
            $queryPrestamo = \App\Models\Articulo::with('grupo')->where('rotacion', 'prestamo');
            if ($request->filled('buscar')) {
                $termino = $request->buscar;
                $queryPrestamo->where(function ($q) use ($termino) {
                    $q->where('codigo', 'LIKE', "%{$termino}%")
                      ->orWhere('nombre', 'LIKE', "%{$termino}%");
                });
            }
            if ($request->filled('grupo')) {
                $queryPrestamo->where('grupo_id', $request->grupo);
            }
            $prestamos = $queryPrestamo
                ->orderByRaw('CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(codigo, "/", 1), "-", -1) AS UNSIGNED)')
                ->orderByRaw('CAST(SUBSTRING_INDEX(codigo, "/", -1) AS UNSIGNED)')
                ->get();
        }

        // Calcular lotes activos y precios por artículo
        $lotesActivos = \App\Models\Movimiento::where('tipo', 'entrada')
            ->where('cantidad_restante', '>', 0)
            ->whereNotNull('precio_unitario')
            ->orderBy('created_at', 'asc')
            ->get(['articulo_id', 'precio_unitario', 'cantidad_restante', 'created_at'])
            ->groupBy('articulo_id');

        $preciosPorArticulo = $lotesActivos->map(function ($movs) {
            return $movs->groupBy(fn($m) => number_format($m->precio_unitario, 2))
                ->map(fn($g) => [
                    'precio'   => $g->first()->precio_unitario,
                    'cantidad' => $g->sum('cantidad_restante'),
                ])
                ->values();
        });

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reportes.pdf.rotacion', compact('diarios', 'ocasionales', 'prestamos', 'rotacion', 'preciosPorArticulo'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("clasificacion_materiales_" . now()->format('Y-m-d') . ".pdf");
    }

    /**
     * Obtener una vista previa rápida del Kardex de un artículo con totales.
     */
    public function kardexPreview(Request $request)
    {
        if (!auth()->user()->puedeReportes() && !auth()->user()->esAlmacenero() && !auth()->user()->puedeEditar()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $articuloId = $request->articulo_id;
        if (!$articuloId) {
            return response()->json(['error' => 'Artículo no especificado'], 400);
        }

        // Solo columnas necesarias para el preview — evitar cargar datos pesados
        $articulo = \App\Models\Articulo::select('id','codigo','nombre','unidad','precio','cantidad','grupo_id')
            ->find($articuloId);
        if (!$articulo) {
            return response()->json(['error' => 'Artículo no encontrado'], 404);
        }

        // Seleccionar solo columnas necesarias (sin eager load de relaciones pesadas todavía)
        $query = \App\Models\Movimiento::select(
                'id','articulo_id','tipo','cantidad','precio_unitario',
                'fecha','created_at','numero_nota','cantidad_restante',
                'entregado_por','recibido_por','trabajador_id','trabajador_nombre',
                'notas','user_id'
            )
            ->where('articulo_id', $articuloId);

        if ($request->desde) $query->whereDate('fecha', '>=', $request->desde);
        if ($request->hasta) $query->whereDate('fecha', '<=', $request->hasta);

        // Ocultar Stock Inicial a menos que se pida explícitamente
        if (!$request->has('incluir_inicial') || $request->incluir_inicial != '1') {
            $query->where(function ($q) {
                $q->where('numero_nota', '!=', 'STOCK-INI')
                  ->orWhereNull('numero_nota');
            });
        }

        // Traer SOLO los movimientos con eager load selectivo (sin 'user' completo)
        $movimientos = $query->orderBy('fecha', 'asc')
                             ->orderBy('created_at', 'asc')
                             ->get();

        // Calcular saldo acumulado sobre TODOS los movimientos (necesario para precisión)
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

        // Estadísticas calculadas en PHP (ya tenemos la colección)
        $totalEntradas  = $movimientos->where('tipo', 'entrada')->sum('cantidad');
        $totalSalidas   = $movimientos->where('tipo', 'salida')->sum('cantidad');
        $entradasCount  = $movimientos->where('tipo', 'entrada')->count();
        $salidasCount   = $movimientos->where('tipo', 'salida')->count();
        $entradasValor  = $movimientos->where('tipo', 'entrada')
                                      ->sum(fn($m) => ($m->precio_unitario ?? 0) * $m->cantidad);
        $salidasValor   = $movimientos->where('tipo', 'salida')
                                      ->sum(fn($m) => ($m->precio_unitario ?? 0) * $m->cantidad);

        // Valor actual del stock FIFO — consulta optimizada con solo columnas clave
        $valorActual = \App\Models\Movimiento::where('articulo_id', $articuloId)
            ->where('tipo', 'entrada')
            ->where('cantidad_restante', '>', 0)
            ->whereNotNull('precio_unitario')
            ->selectRaw('SUM(cantidad_restante * precio_unitario) as valor')
            ->value('valor') ?? 0;

        // Filtrar por tipo DESPUÉS de calcular estadísticas (para no romper saldo acumulado)
        $tipo = $request->tipo;
        if ($tipo && in_array($tipo, ['entrada', 'salida'])) {
            $movimientos = $movimientos->filter(fn($m) => $m->tipo === $tipo)->values();
        }

        // Más reciente primero — limitar preview a 100 registros
        $movimientosPreview = $movimientos->reverse()->values()->take(100);

        // Cargar relaciones solo para los 100 registros del preview
        $trabajadorIds = $movimientosPreview->whereNotNull('trabajador_id')->pluck('trabajador_id')->unique();
        $trabajadores  = $trabajadorIds->isNotEmpty()
            ? \App\Models\Trabajador::whereIn('id', $trabajadorIds)->pluck('nombre', 'id')
            : collect();

        $data = $movimientosPreview->map(function ($m) use ($trabajadores) {
            $entregadoAPor = $m->tipo === 'entrada'
                ? (($m->entregado_por ?? '—') . ' a ' . ($m->recibido_por ?? 'Almacén'))
                : (($m->entregado_por ? $m->entregado_por . ' a ' : '') . ($trabajadores[$m->trabajador_id] ?? $m->trabajador_nombre ?? '—'));

            return [
                'numero_nota'      => $m->numero_nota ?? '—',
                'fecha_formateada' => $m->fecha
                    ? \Carbon\Carbon::parse($m->fecha)->format('d/m/Y')
                    : $m->created_at->format('d/m/Y'),
                'tipo'             => $m->tipo,
                'entrada'          => $m->tipo === 'entrada' ? (float)$m->cantidad : null,
                'salida'           => $m->tipo === 'salida'  ? (float)$m->cantidad : null,
                'precio_unitario'  => (float)($m->precio_unitario ?? 0),
                'total'            => (float)($m->cantidad * ($m->precio_unitario ?? 0)),
                'saldo_acumulado'  => (float)$m->saldo_acumulado,
                'entregado_a_por'  => $entregadoAPor,
                'notas'            => $m->notas ?? '',
            ];
        });

        return response()->json([
            'articulo' => [
                'codigo'       => $articulo->codigo,
                'nombre'       => $articulo->nombre,
                'unidad'       => $articulo->unidad,
                'precio'       => (float)$articulo->precio,
                'stock_actual' => (float)$articulo->cantidad,
                'valor_actual' => (float)$valorActual,
            ],
            'movimientos'  => $data,
            'total_count'  => $movimientos->count(),
            'estadisticas' => [
                'total_entradas' => (float)$totalEntradas,
                'total_salidas'  => (float)$totalSalidas,
                'entradas_count' => $entradasCount,
                'salidas_count'  => $salidasCount,
                'entradas_valor' => (float)$entradasValor,
                'salidas_valor'  => (float)$salidasValor,
            ],
        ]);
    }

    /**
     * Obtener una vista previa rápida de los movimientos filtrados con totales.
     */
    public function movimientosPreview(Request $request)
    {
        if (!auth()->user()->puedeReportes()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $query = Movimiento::with(['articulo', 'user', 'trabajador']);

        if ($request->desde)         $query->whereDate('fecha', '>=', $request->desde);
        if ($request->hasta)         $query->whereDate('fecha', '<=', $request->hasta);
        if ($request->trabajador_id) $query->where('trabajador_id', $request->trabajador_id);
        if ($request->tipo)          $query->where('tipo', $request->tipo);
        if ($request->articulo_id)   $query->where('articulo_id', $request->articulo_id);
        if (!$request->has('incluir_inicial') || $request->incluir_inicial != '1') {
            $query->where(function ($q) {
                $q->where('tipo', 'salida')
                  ->orWhere(function ($sub) {
                      $sub->where('tipo', 'entrada')
                          ->where('notas', 'not like', 'Stock inicial%')
                          ->where(function ($qq) {
                              $qq->whereNull('entregado_por')
                                 ->orWhere('entregado_por', '!=', 'CARGA EXCEL');
                          });
                  });
            });
        }

        // Contar el total de movimientos que coinciden
        $totalMovimientos = $query->count();

        // Obtener la sumatoria de todas las cantidades y valores monetarios de los registros filtrados
        $sumaCantidad = 0;
        $sumaValores = 0;
        $entradasCount = 0;
        $salidasCount = 0;
        $entradasValor = 0;
        $salidasValor = 0;

        $todosLosMovimientos = (clone $query)->get(['tipo', 'cantidad', 'precio_unitario']);
        foreach ($todosLosMovimientos as $m) {
            $sumaCantidad += $m->cantidad;
            $valor = $m->cantidad * ($m->precio_unitario ?? 0);
            $sumaValores += $valor;
            if ($m->tipo === 'entrada') {
                $entradasCount++;
                $entradasValor += $valor;
            } else {
                $salidasCount++;
                $salidasValor += $valor;
            }
        }

        // Obtener los primeros 100 movimientos ordenados por fecha ascendente para la tabla de vista previa
        $movimientos = $query->orderBy('fecha', 'asc')
                             ->orderBy('created_at', 'asc')
                             ->limit(100)
                             ->get();

        $data = $movimientos->map(function ($m) {
            return [
                'numero_nota' => $m->numero_nota ?? '—',
                'fecha_formateada' => $m->fecha ? \Carbon\Carbon::parse($m->fecha)->format('d/m/Y') : $m->created_at->format('d/m/Y'),
                'codigo' => $m->articulo->codigo,
                'articulo_nombre' => $m->articulo->nombre,
                'unidad' => $m->articulo->unidad,
                'tipo' => $m->tipo,
                'cantidad' => (float)$m->cantidad,
                'precio_unitario' => (float)($m->precio_unitario ?? 0),
                'total' => (float)($m->cantidad * ($m->precio_unitario ?? 0)),
                'entregado_a_por' => $m->tipo === 'entrada'
                    ? (($m->entregado_por ?? '—') . ' a ' . ($m->recibido_por ?? 'Almacén'))
                    : (($m->entregado_por ? $m->entregado_por . ' a ' : '') . ($m->trabajador?->nombre ?? $m->trabajador_nombre ?? '—')),
            ];
        });

        return response()->json([
            'movimientos' => $data,
            'total_count' => $totalMovimientos,
            'suma_cantidad' => $sumaCantidad,
            'suma_valores' => $sumaValores,
            'entradas_count' => $entradasCount,
            'salidas_count' => $salidasCount,
            'entradas_valor' => $entradasValor,
            'salidas_valor' => $salidasValor,
        ]);
    }
}