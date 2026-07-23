<?php

namespace App\Exports;

use App\Models\Articulo;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class InventarioExport implements FromArray, WithHeadings, WithTitle, WithColumnWidths, WithEvents
{
    protected $filas = [];
    protected $filasSeparadoras = []; // guarda en qué número de fila va un separador
    protected $totalCantidad = 0;
    protected $totalValor = 0;
    protected $stockFilter;
    protected $grupoId;

    public function __construct($stockFilter = 'todos', $grupoId = 'todos')
    {
        $this->stockFilter = $stockFilter;
        $this->grupoId = $grupoId;
    }

    public function array(): array
    {
        $query = Articulo::with('grupo');
        if ($this->stockFilter === 'con_stock') {
            $query->where('cantidad', '>', 0);
        } elseif ($this->stockFilter === 'sin_stock') {
            $query->where('cantidad', '<=', 0);
        }

        if ($this->grupoId && $this->grupoId !== 'todos') {
            $query->where('grupo_id', $this->grupoId);
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

        $datos = [];
        $grupoActual = null;
        $numeroFila = 1; // fila 1 es el encabezado, los datos empiezan en la 2

        foreach ($articulos as $art) {
            // ¿Cambió de grupo? → insertar fila separadora
            if ($art->grupo_id !== $grupoActual) {
                $grupoActual = $art->grupo_id;
                $numeroFila++;

                $nombreGrupo = $art->grupo->nombre ?? '';
                $datos[] = [
                    "  {$art->grupo_id}  —  {$nombreGrupo}",
                    '', '', '', '', '', '', '',
                ];
                // Guardamos el número de fila del separador (para darle estilo después)
                $this->filasSeparadoras[] = $numeroFila;
            }

            $numeroFila++;
            
            if (isset($preciosPorArticulo[$art->id]) && count($preciosPorArticulo[$art->id]) > 1) {
                $cantidadesStr = [];
                $preciosStr = [];
                $totalesStr = [];
                $valorTotal = 0;
                foreach ($preciosPorArticulo[$art->id] as $p) {
                    $cantidadesStr[] = number_format($p['cantidad'], 3);
                    $preciosStr[] = number_format($p['precio'], 2);
                    $totValItem = $p['precio'] * $p['cantidad'];
                    $totalesStr[] = number_format($totValItem, 2);
                    $valorTotal += $totValItem;
                }
                $cantVal = implode("\n", $cantidadesStr);
                $precVal = implode("\n", $preciosStr);
                $totVal = implode("\n", $totalesStr);
            } else {
                $valorTotal = $art->precio * $art->cantidad;
                $cantVal = (float) $art->cantidad;
                $precVal = (float) $art->precio;
                $totVal = (float) $valorTotal;
            }

            $datos[] = [
                $art->codigo,
                $art->nombre,
                $art->grupo_id,
                $art->grupo->nombre ?? '',
                $art->unidad,
                $cantVal,
                $precVal,
                $totVal,
            ];

            $this->totalCantidad += $art->cantidad;
            $this->totalValor += $valorTotal;
        }

        // Fila de totales al final
        $numeroFila++;
        $datos[] = [
            'TOTALES',
            '', '', '', '',
            (float) $this->totalCantidad,
            '',
            (float) $this->totalValor,
        ];
        $this->filaTotales = $numeroFila;

        $this->filas = $datos;
        return $datos;
    }

    public function headings(): array
    {
        return [
            'CÓDIGO',
            'DESCRIPCIÓN',
            'GRUPO',
            'CATEGORÍA',
            'UNIDAD',
            'CANTIDAD',
            'PRECIO UNIT. (Bs.)',
            'VALOR TOTAL (Bs.)',
        ];
    }

    public function title(): string
    {
        return 'Inventario';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 16,
            'B' => 42,
            'C' => 10,
            'D' => 24,
            'E' => 12,
            'F' => 13,
            'G' => 18,
            'H' => 18,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $ultimaFila = count($this->filas) + 1; // +1 por encabezado

                // ===== ENCABEZADO =====
                $sheet->getStyle('A1:H1')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(26);

                // ===== BORDES en toda la tabla =====
                $sheet->getStyle("A1:H{$ultimaFila}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D0D0D0'],
                        ],
                    ],
                ]);

                // ===== COLORES BAJITOS EN COLUMNAS NUMÉRICAS =====
                // Cantidad (F): Verde claro muy suave
                $sheet->getStyle("F2:F".($ultimaFila - 1))->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0FDF4']],
                ]);
                // Precio (G): Azul claro muy suave
                $sheet->getStyle("G2:G".($ultimaFila - 1))->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EFF6FF']],
                ]);
                // Valor Total (H): Púrpura/violeta claro muy suave
                $sheet->getStyle("H2:H".($ultimaFila - 1))->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F5F3FF']],
                ]);

                // ===== FILAS SEPARADORAS DE GRUPO =====
                foreach ($this->filasSeparadoras as $filaSep) {
                    $sheet->mergeCells("A{$filaSep}:H{$filaSep}");
                    $sheet->getStyle("A{$filaSep}:H{$filaSep}")->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '475569']],
                        'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                    ]);
                    $sheet->getRowDimension($filaSep)->setRowHeight(22);
                }

                // ===== ALINEACIÓN columnas numéricas =====
                $sheet->getStyle("F2:H{$ultimaFila}")
                      ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("C2:C{$ultimaFila}")
                      ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("E2:E{$ultimaFila}")
                      ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Habilitar wrapText y alineación vertical centrado
                $sheet->getStyle("F2:H{$ultimaFila}")->getAlignment()->setWrapText(true);
                $sheet->getStyle("A2:H{$ultimaFila}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                // ===== FORMATO de números =====
                // Cantidad con 3 decimales
                $sheet->getStyle("F2:F{$ultimaFila}")
                      ->getNumberFormat()->setFormatCode('#,##0.000');
                // Precios y valor total con 2 decimales
                $sheet->getStyle("G2:H{$ultimaFila}")
                      ->getNumberFormat()->setFormatCode('#,##0.00');

                // ===== FILA DE TOTALES =====
                $filaTotales = $this->filaTotales;
                $sheet->mergeCells("A{$filaTotales}:E{$filaTotales}");
                $sheet->getStyle("A{$filaTotales}:H{$filaTotales}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2D3748']],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getStyle("A{$filaTotales}")
                      ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getRowDimension($filaTotales)->setRowHeight(24);

                // ===== CONGELAR encabezado =====
                $sheet->freezePane('A2');
            },
        ];
    }
}