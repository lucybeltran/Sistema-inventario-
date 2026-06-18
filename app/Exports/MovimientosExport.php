<?php

namespace App\Exports;

use App\Models\Movimiento;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MovimientosExport implements FromArray, WithHeadings, WithTitle, WithColumnWidths, WithEvents
{
    protected $desde;
    protected $hasta;
    protected $trabajadorId;

    protected $filas = [];
    protected $totalMovimientos = 0;
    protected $totalEntradas = 0;
    protected $totalSalidas = 0;
    protected $filaTotales = 0;

    public function __construct($desde = null, $hasta = null, $trabajadorId = null)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
        $this->trabajadorId = $trabajadorId;
    }

    public function array(): array
    {
        $query = Movimiento::with(['articulo', 'user', 'trabajador']);

        if ($this->desde) {
            $query->whereDate('fecha', '>=', $this->desde);
        }
        if ($this->hasta) {
            $query->whereDate('fecha', '<=', $this->hasta);
        }
        if ($this->trabajadorId) {
            $query->where('trabajador_id', $this->trabajadorId);
        }

        $movimientos = $query->orderByRaw('CAST(numero_nota AS UNSIGNED) DESC')->get();

        $datos = [];
        foreach ($movimientos as $mov) {
            $nombreTrabajador = $mov->trabajador?->nombre ?? $mov->trabajador_nombre ?? '—';
            $ciTrabajador = $mov->trabajador?->ci ?? '—';
            $precioUnit = (float) ($mov->precio_unitario ?? 0);
            $total = $precioUnit * $mov->cantidad;

            $datos[] = [
                $mov->numero_nota ?? '—',
                $mov->fecha->format('d/m/Y'),
                $mov->articulo->codigo,
                $mov->articulo->nombre,
                strtoupper($mov->tipo),
                (float) $mov->cantidad,
                $mov->articulo->unidad,
                $precioUnit,
                $total,
                $nombreTrabajador,
                $ciTrabajador,
                $mov->notas ?? '',
                $mov->user->name ?? '—',
            ];

            $this->totalMovimientos++;
            if ($mov->tipo === 'entrada') {
                $this->totalEntradas++;
            } else {
                $this->totalSalidas++;
            }
        }

        // Fila de totales
        $datos[] = [
            'TOTALES',
            "{$this->totalMovimientos} movimientos",
            '',
            "{$this->totalEntradas} entradas",
            "{$this->totalSalidas} salidas",
            '', '', '', '', '', '', '', '',
        ];
        $this->filaTotales = count($datos) + 1; // +1 por el encabezado

        $this->filas = $datos;
        return $datos;
    }

    public function headings(): array
    {
        return [
            'N° NOTA',
            'FECHA',
            'CÓDIGO',
            'ARTÍCULO',
            'TIPO',
            'CANTIDAD',
            'UNIDAD',
            'PRECIO UNIT. (Bs.)',
            'TOTAL (Bs.)',
            'ENTREGADO A',
            'CI TRABAJADOR',
            'NOTAS',
            'REGISTRADO POR',
        ];
    }

    public function title(): string
    {
        return 'Movimientos';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12, 'B' => 13, 'C' => 13, 'D' => 32, 'E' => 12,
            'F' => 12, 'G' => 12, 'H' => 14, 'I' => 14, 'J' => 24, 'K' => 15, 'L' => 28, 'M' => 20,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $ultimaFila = count($this->filas) + 1; // +1 por encabezado
                $filaTotales = $this->filaTotales;
                $ultimaFilaDatos = $ultimaFila - 1; // sin contar la fila de totales

                // ===== ENCABEZADO =====
                $sheet->getStyle('A1:M1')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '667EEA']],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(26);

                // ===== BORDES =====
                $sheet->getStyle("A1:M{$ultimaFila}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D0D0D0'],
                        ],
                    ],
                ]);

                // ===== FILAS ALTERNADAS =====
                for ($fila = 2; $fila <= $ultimaFilaDatos; $fila++) {
                    if ($fila % 2 == 0) {
                        $sheet->getStyle("A{$fila}:M{$fila}")->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F5F6FA']],
                        ]);
                    }
                }

                // ===== ALINEACIÓN =====
                $sheet->getStyle("A2:A{$ultimaFilaDatos}")
                      ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("F2:F{$ultimaFilaDatos}")
                      ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("E2:E{$ultimaFilaDatos}")
                      ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // ===== FORMATO de cantidad =====
                $sheet->getStyle("F2:F{$ultimaFilaDatos}")
                      ->getNumberFormat()->setFormatCode('#,##0.000');
                $sheet->getStyle("H2:I{$ultimaFilaDatos}")
                      ->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle("H2:I{$ultimaFilaDatos}")
                      ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // ===== FILA DE TOTALES =====
                $sheet->getStyle("A{$filaTotales}:M{$filaTotales}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2D3748']],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getRowDimension($filaTotales)->setRowHeight(24);

                // ===== CONGELAR encabezado =====
                $sheet->freezePane('A2');
            },
        ];
    }
}