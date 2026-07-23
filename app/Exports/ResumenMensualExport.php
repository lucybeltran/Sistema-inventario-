<?php

namespace App\Exports;

use App\Models\Movimiento;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * Export principal: genera Excel con 4 hojas para el Resumen Mensual.
 */
class ResumenMensualExport implements WithMultipleSheets
{
    public function __construct(protected string $periodo) {}

    public function sheets(): array
    {
        $inicio    = Carbon::createFromFormat('Y-m', $this->periodo)->startOfMonth();
        $nombreMes = $inicio->locale('es')->isoFormat('MMMM YYYY');

        return [
            new ResumenEjecutivoSheet($this->periodo, $nombreMes),
            new ResumenMaterialesSheet($this->periodo, $nombreMes),
            new ResumenTrabajadoresSheet($this->periodo, $nombreMes),
            new ResumenUnidadSheet($this->periodo, $nombreMes),
        ];
    }
}

// ─── HOJA 1: Resumen Ejecutivo ────────────────────────────────────────────────
class ResumenEjecutivoSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(private string $periodo, private string $nombreMes) {}
    public function title(): string { return 'Resumen Ejecutivo'; }
    public function columnWidths(): array { return ['A' => 38, 'B' => 28]; }

    public function array(): array
    {
        $inicio = Carbon::createFromFormat('Y-m', $this->periodo)->startOfMonth();
        $fin    = $inicio->copy()->endOfMonth();

        $base = fn() => Movimiento::whereBetween('movimientos.fecha', [$inicio, $fin])
            ->where(fn($q) => $q->whereNull('movimientos.notas')->orWhere('movimientos.notas', 'NOT LIKE', 'Stock inicial%'))
            ->where(fn($q) => $q->whereNull('movimientos.entregado_por')->orWhere('movimientos.entregado_por', '!=', 'CARGA EXCEL'));

        $totalMovs     = $base()->count();
        $valorSalidas  = (float) ($base()->where('tipo', 'salida')
            ->selectRaw('SUM(cantidad * COALESCE(precio_unitario,0)) as t')->value('t') ?? 0);
        $valorEntradas = (float) ($base()->where('tipo', 'entrada')
            ->selectRaw('SUM(cantidad * COALESCE(precio_unitario,0)) as t')->value('t') ?? 0);

        $inicioPrev    = $inicio->copy()->subMonth()->startOfMonth();
        $finPrev       = $inicioPrev->copy()->endOfMonth();
        $totalMovsPrev = Movimiento::whereBetween('fecha', [$inicioPrev, $finPrev])
            ->where(fn($q) => $q->whereNull('notas')->orWhere('notas', 'NOT LIKE', 'Stock inicial%'))
            ->where(fn($q) => $q->whereNull('entregado_por')->orWhere('entregado_por', '!=', 'CARGA EXCEL'))
            ->count();

        $variacion = $totalMovsPrev > 0
            ? round((($totalMovs - $totalMovsPrev) / $totalMovsPrev) * 100, 1)
            : ($totalMovs > 0 ? 100 : 0);
        $signo = $variacion >= 0 ? '+' : '';

        return [
            ['RESUMEN MENSUAL - ' . strtoupper($this->nombreMes), ''],
            ['', ''],
            ['INDICADOR', 'VALOR'],
            ['Total de Movimientos del Mes', $totalMovs],
            ['Valor Total de Salidas (Bs.)', number_format($valorSalidas, 2)],
            ['Valor Total de Entradas (Bs.)', number_format($valorEntradas, 2)],
            ['Balance Neto (Bs.)', number_format($valorEntradas - $valorSalidas, 2)],
            ['', ''],
            ['COMPARATIVA VS MES ANTERIOR', ''],
            ['Movimientos mes anterior', $totalMovsPrev],
            ['Variacion porcentual', $signo . $variacion . '%'],
            ['', ''],
            ['Generado el:', now()->format('d/m/Y H:i:s')],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1e293b']]],
            3 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '334155']]],
            9 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '334155']]],
        ];
    }
}

// ─── HOJA 2: Top 5 Materiales ────────────────────────────────────────────────
class ResumenMaterialesSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(private string $periodo, private string $nombreMes) {}
    public function title(): string { return 'Top Materiales'; }
    public function columnWidths(): array { return ['A' => 6, 'B' => 42, 'C' => 20, 'D' => 16, 'E' => 18]; }

    public function array(): array
    {
        $inicio = Carbon::createFromFormat('Y-m', $this->periodo)->startOfMonth();
        $fin    = $inicio->copy()->endOfMonth();

        $data = Movimiento::whereBetween('movimientos.fecha', [$inicio, $fin])
            ->where('movimientos.tipo', 'salida')
            ->where(fn($q) => $q->whereNull('movimientos.notas')->orWhere('movimientos.notas', 'NOT LIKE', 'Stock inicial%'))
            ->where(fn($q) => $q->whereNull('movimientos.entregado_por')->orWhere('movimientos.entregado_por', '!=', 'CARGA EXCEL'))
            ->join('articulos as art', 'movimientos.articulo_id', '=', 'art.id')
            ->selectRaw('art.nombre, art.codigo, art.unidad, SUM(movimientos.cantidad) as total_salida')
            ->groupBy('movimientos.articulo_id', 'art.nombre', 'art.codigo', 'art.unidad')
            ->orderByDesc('total_salida')
            ->limit(5)->get();

        $rows = [
            ['TOP 5 MATERIALES MAS CONSUMIDOS - ' . strtoupper($this->nombreMes), '', '', '', ''],
            [''],
            ['#', 'Articulo', 'Codigo', 'Unidad', 'Total Salida'],
        ];
        foreach ($data as $i => $m) {
            $rows[] = [$i + 1, $m->nombre, $m->codigo, $m->unidad, number_format($m->total_salida, 2)];
        }
        if ($data->isEmpty()) {
            $rows[] = ['', 'Sin datos para este periodo', '', '', ''];
        }
        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1e293b']]],
            3 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '334155']]],
        ];
    }
}

// ─── HOJA 3: Top 5 Trabajadores ──────────────────────────────────────────────
class ResumenTrabajadoresSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(private string $periodo, private string $nombreMes) {}
    public function title(): string { return 'Top Trabajadores'; }
    public function columnWidths(): array { return ['A' => 6, 'B' => 38, 'C' => 18, 'D' => 18, 'E' => 26]; }

    public function array(): array
    {
        $inicio = Carbon::createFromFormat('Y-m', $this->periodo)->startOfMonth();
        $fin    = $inicio->copy()->endOfMonth();

        $data = Movimiento::whereBetween('movimientos.fecha', [$inicio, $fin])
            ->where('movimientos.tipo', 'salida')
            ->whereNotNull('movimientos.trabajador_id')
            ->where(fn($q) => $q->whereNull('movimientos.notas')->orWhere('movimientos.notas', 'NOT LIKE', 'Stock inicial%'))
            ->where(fn($q) => $q->whereNull('movimientos.entregado_por')->orWhere('movimientos.entregado_por', '!=', 'CARGA EXCEL'))
            ->join('trabajadores as tr', 'movimientos.trabajador_id', '=', 'tr.id')
            ->selectRaw('tr.nombre, tr.codigo, COUNT(*) as total_movs, SUM(movimientos.cantidad * COALESCE(movimientos.precio_unitario,0)) as valor_total')
            ->groupBy('movimientos.trabajador_id', 'tr.nombre', 'tr.codigo')
            ->orderByDesc('total_movs')
            ->limit(5)->get();

        $rows = [
            ['TOP 5 TRABAJADORES / CONTRATISTAS - ' . strtoupper($this->nombreMes), '', '', '', ''],
            [''],
            ['#', 'Nombre', 'Codigo', 'N Salidas', 'Valor Recibido (Bs.)'],
        ];
        foreach ($data as $i => $t) {
            $rows[] = [$i + 1, $t->nombre, $t->codigo ?? '-', $t->total_movs, number_format($t->valor_total, 2)];
        }
        if ($data->isEmpty()) {
            $rows[] = ['', 'Sin datos para este periodo', '', '', ''];
        }
        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1e293b']]],
            3 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '334155']]],
        ];
    }
}

// ─── HOJA 4: Detalle por Unidad ───────────────────────────────────────────────
class ResumenUnidadSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(private string $periodo, private string $nombreMes) {}
    public function title(): string { return 'Detalle por Unidad'; }
    public function columnWidths(): array { return ['A' => 24, 'B' => 20, 'C' => 20, 'D' => 20]; }

    public function array(): array
    {
        $inicio = Carbon::createFromFormat('Y-m', $this->periodo)->startOfMonth();
        $fin    = $inicio->copy()->endOfMonth();

        $data = Movimiento::selectRaw('
                art2.unidad,
                SUM(CASE WHEN movimientos.tipo = "entrada" THEN movimientos.cantidad ELSE 0 END) as entradas,
                SUM(CASE WHEN movimientos.tipo = "salida" THEN movimientos.cantidad ELSE 0 END) as salidas
            ')
            ->join('articulos as art2', 'movimientos.articulo_id', '=', 'art2.id')
            ->whereBetween('movimientos.fecha', [$inicio, $fin])
            ->where(fn($q) => $q->whereNull('movimientos.notas')
                ->orWhere('movimientos.notas', 'NOT LIKE', 'Stock inicial%'))
            ->groupBy('art2.unidad')
            ->orderBy('art2.unidad')
            ->get();

        $rows = [
            ['DETALLE POR UNIDAD DE MEDIDA - ' . strtoupper($this->nombreMes), '', '', ''],
            [''],
            ['Unidad', 'Entradas', 'Salidas', 'Neto'],
        ];
        foreach ($data as $u) {
            $neto   = $u->entradas - $u->salidas;
            $rows[] = [$u->unidad, number_format($u->entradas, 2), number_format($u->salidas, 2), number_format($neto, 2)];
        }
        if ($data->isEmpty()) {
            $rows[] = ['Sin datos para este periodo', '', '', ''];
        }
        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1e293b']]],
            3 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '334155']]],
        ];
    }
}
