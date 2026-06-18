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

    public function array(): array
    {
        $articulos = Articulo::with('grupo')
            ->orderByRaw('CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(codigo, "/", 1), "-", -1) AS UNSIGNED)')
            ->orderByRaw('CAST(SUBSTRING_INDEX(codigo, "/", -1) AS UNSIGNED)')
            ->get();

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
            $valorTotal = $art->precio * $art->cantidad;

            $datos[] = [
                $art->codigo,
                $art->nombre,
                $art->grupo_id,
                $art->grupo->nombre ?? '',
                $art->unidad,
                (float) $art->cantidad,
                (float) $art->precio,
                (float) $valorTotal,
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
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '667EEA']],
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

                // ===== FILAS SEPARADORAS DE GRUPO =====
                foreach ($this->filasSeparadoras as $filaSep) {
                    $sheet->mergeCells("A{$filaSep}:H{$filaSep}");
                    $sheet->getStyle("A{$filaSep}:H{$filaSep}")->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '764BA2']],
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