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

class RotacionExport implements FromArray, WithHeadings, WithTitle, WithColumnWidths, WithEvents
{
    protected $buscar;
    protected $grupo;
    protected $rotacion;

    public function __construct($buscar = null, $grupo = null, $rotacion = null)
    {
        $this->buscar = $buscar;
        $this->grupo = $grupo;
        $this->rotacion = $rotacion;
    }

    public function array(): array
    {
        $query = Articulo::with('grupo');

        if ($this->buscar) {
            $termino = $this->buscar;
            $query->where(function ($q) use ($termino) {
                $q->where('codigo', 'LIKE', "%{$termino}%")
                  ->orWhere('nombre', 'LIKE', "%{$termino}%");
            });
        }

        if ($this->grupo) {
            $query->where('grupo_id', $this->grupo);
        }

        if ($this->rotacion === 'diario' || $this->rotacion === 'consumible') {
            $query->whereIn('rotacion', ['diario', 'consumible']);
        } elseif ($this->rotacion === 'prestamo') {
            $query->where('rotacion', 'prestamo');
        } elseif ($this->rotacion === 'ocasional') {
            $query->where(function($q) {
                $q->where('rotacion', 'ocasional')
                  ->orWhereNull('rotacion')
                  ->orWhere('rotacion', '');
            });
        }

        $articulos = $query->orderBy('rotacion', 'asc')
            ->orderByRaw('CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(codigo, "/", 1), "-", -1) AS UNSIGNED)')
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
        foreach ($articulos as $art) {
            $rot = $art->rotacion;
            if ($rot === 'diario' || $rot === 'consumible') {
                $rotacionLabel = 'Consumible (Salida Definitiva)';
            } elseif ($rot === 'prestamo') {
                $rotacionLabel = 'Equipo / Herramienta (Devolutivo)';
            } else {
                $rotacionLabel = 'Repuesto / Reserva (Baja Rotación)';
            }

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
                $art->grupo?->nombre ?? 'Sin Grupo',
                $art->unidad,
                $precVal,
                $cantVal,
                $totVal,
                $rotacionLabel,
            ];
        }

        return $datos;
    }

    public function headings(): array
    {
        return [
            'CÓDIGO',
            'MATERIAL / ARTÍCULO',
            'GRUPO',
            'UNIDAD',
            'PRECIO UNIT. (Bs.)',
            'STOCK ACTUAL',
            'VALOR TOTAL (Bs.)',
            'CLASIFICACIÓN',
        ];
    }

    public function title(): string
    {
        return 'Clasificación de Activos';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, 'B' => 38, 'C' => 24, 'D' => 15, 'E' => 12,
            'F' => 18, 'G' => 18, 'H' => 35,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // ===== ENCABEZADO =====
                $sheet->getStyle('A1:H1')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(28);

                // ===== BORDES =====
                $sheet->getStyle("A1:H{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D0D0D0'],
                        ],
                    ],
                ]);

                // ===== ALINEACIÓN Y FORMATO =====
                $sheet->getStyle("A2:A{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("D2:D{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("H2:H{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle("E2:G{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("F2:F{$highestRow}")->getNumberFormat()->setFormatCode('#,##0.000');
                $sheet->getStyle("E2:G{$highestRow}")->getNumberFormat()->setFormatCode('#,##0.00');

                // Habilitar wrapText y alineación vertical centrado
                $sheet->getStyle("E2:G{$highestRow}")->getAlignment()->setWrapText(true);
                $sheet->getStyle("A2:H{$highestRow}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                // ===== FILAS ALTERNADAS =====
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($row % 2 == 0) {
                        $sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F5F6FA']],
                        ]);
                    }
                }

                // ===== COLORES BAJITOS EN COLUMNAS NUMÉRICAS =====
                // Stock (F): Verde claro muy suave
                $sheet->getStyle("F2:F{$highestRow}")->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0FDF4']],
                ]);
                // Precio Unitario (E): Azul claro muy suave
                $sheet->getStyle("E2:E{$highestRow}")->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EFF6FF']],
                ]);
                // Valor Total (G): Púrpura/violeta claro muy suave
                $sheet->getStyle("G2:G{$highestRow}")->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F5F3FF']],
                ]); 

                for ($row = 2; $row <= $highestRow; $row++) {
                    // Colorear ligeramente según la clasificación
                    $val = $sheet->getCell("H{$row}")->getValue();
                    if ($val === 'Consumible (Salida Definitiva)') {
                        $sheet->getStyle("H{$row}")->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E6FCF5']],
                            'font' => ['bold' => true, 'color' => ['rgb' => '0CA678']],
                        ]);
                    } elseif ($val === 'Equipo / Herramienta (Devolutivo)') {
                        $sheet->getStyle("H{$row}")->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFF4E6']],
                            'font' => ['bold' => true, 'color' => ['rgb' => 'D9480F']],
                        ]);
                    } else {
                        $sheet->getStyle("H{$row}")->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EDF2FF']],
                            'font' => ['bold' => true, 'color' => ['rgb' => '3B5BDB']],
                        ]);
                    }
                }

                $sheet->freezePane('A2');
            },
        ];
    }
}
