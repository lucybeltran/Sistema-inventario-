<?php

namespace App\Exports;

use App\Models\Articulo;
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

class KardexExport implements FromArray, WithHeadings, WithTitle, WithColumnWidths, WithEvents
{
    protected $articuloId;
    protected $desde;
    protected $hasta;
    protected $articulo;

    protected $filas = [];
    protected $totalEntradas = 0;
    protected $totalSalidas = 0;
    protected $filaTotales = 0;

    public function __construct($articuloId, $desde = null, $hasta = null)
    {
        $this->articuloId = $articuloId;
        $this->desde = $desde;
        $this->hasta = $hasta;
        $this->articulo = Articulo::findOrFail($articuloId);
    }

    public function array(): array
    {
        $query = Movimiento::with(['user', 'trabajador'])
            ->where('articulo_id', $this->articuloId);

        if ($this->desde) $query->whereDate('fecha', '>=', $this->desde);
        if ($this->hasta) $query->whereDate('fecha', '<=', $this->hasta);

        $movimientos = $query->orderBy('fecha', 'asc')
                             ->orderBy('created_at', 'asc')
                             ->get();

        // Calcular saldo acumulado (orden cronológico)
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

        // Invertir: más reciente arriba
        $movimientos = $movimientos->reverse();

        $datos = [];
        foreach ($movimientos as $mov) {
            $datos[] = [
                $mov->numero_nota ?? '—',
                $mov->fecha->format('d/m/Y'),
                strtoupper($mov->tipo),
                $mov->tipo === 'entrada' ? (float) $mov->cantidad : '',
                $mov->tipo === 'salida' ? (float) $mov->cantidad : '',
                (float) ($mov->precio_unitario ?? 0),
                (float) $mov->saldo_acumulado,
                $mov->trabajador?->nombre ?? $mov->trabajador_nombre ?? '—',
                $mov->trabajador?->ci ?? '—',
                $mov->notas ?? '',
                $mov->user->name ?? '—',
            ];

            if ($mov->tipo === 'entrada') {
                $this->totalEntradas += $mov->cantidad;
            } else {
                $this->totalSalidas += $mov->cantidad;
            }
        }

        // Fila de totales
        $datos[] = [
            'TOTALES',
            '',
            '',
            (float) $this->totalEntradas,
            (float) $this->totalSalidas,
            '',
            (float) ($this->totalEntradas - $this->totalSalidas),
            '', '', '', '',
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
            'TIPO',
            'ENTRADA',
            'SALIDA',
            'PRECIO UNIT. (Bs.)',
            'SALDO',
            'ENTREGADO A',
            'CI',
            'NOTAS',
            'REGISTRADO POR',
        ];
    }

    public function title(): string
    {
        return 'Kardex ' . $this->articulo->codigo;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12, 'B' => 13, 'C' => 12, 'D' => 13, 'E' => 13,
            'F' => 15, 'G' => 14, 'H' => 24, 'I' => 14, 'J' => 28, 'K' => 20,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $ultimaFila = count($this->filas) + 1; // +1 por encabezado
                $filaTotales = $this->filaTotales;
                $ultimaFilaDatos = $ultimaFila - 1; // sin la fila de totales

                // ===== ENCABEZADO =====
                $sheet->getStyle('A1:K1')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '667EEA']],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(26);

                // ===== BORDES =====
                $sheet->getStyle("A1:K{$ultimaFila}")->applyFromArray([
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
                        $sheet->getStyle("A{$fila}:K{$fila}")->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F5F6FA']],
                        ]);
                    }
                }

                // ===== ALINEACIÓN =====
                $sheet->getStyle("A2:A{$ultimaFilaDatos}")
                      ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("C2:C{$ultimaFilaDatos}")
                      ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("D2:G{$ultimaFilaDatos}")
                      ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // ===== FORMATO de números =====
                $sheet->getStyle("D2:E{$ultimaFilaDatos}")
                      ->getNumberFormat()->setFormatCode('#,##0.000');
                $sheet->getStyle("F2:F{$ultimaFilaDatos}")
                      ->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle("G2:G{$ultimaFilaDatos}")
                      ->getNumberFormat()->setFormatCode('#,##0.000');

                // ===== COLORES entrada (verde) / salida (rojo) =====
                for ($fila = 2; $fila <= $ultimaFilaDatos; $fila++) {
                    $tipo = $sheet->getCell("C{$fila}")->getValue();
                    if ($tipo === 'ENTRADA') {
                        $sheet->getStyle("C{$fila}")->getFont()->getColor()->setRGB('2B8A3E');
                        $sheet->getStyle("D{$fila}")->getFont()->getColor()->setRGB('2B8A3E');
                    } elseif ($tipo === 'SALIDA') {
                        $sheet->getStyle("C{$fila}")->getFont()->getColor()->setRGB('C92A2A');
                        $sheet->getStyle("E{$fila}")->getFont()->getColor()->setRGB('C92A2A');
                    }
                }

                // ===== COLUMNA SALDO destacada =====
                $sheet->getStyle("G2:G{$ultimaFilaDatos}")->applyFromArray([
                    'font' => ['bold' => true],
                ]);

                // ===== FILA DE TOTALES =====
                $sheet->getStyle("A{$filaTotales}:K{$filaTotales}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2D3748']],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getStyle("A{$filaTotales}")
                      ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("D{$filaTotales}:G{$filaTotales}")
                      ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("D{$filaTotales}:E{$filaTotales}")
                      ->getNumberFormat()->setFormatCode('#,##0.000');
                $sheet->getStyle("G{$filaTotales}")
                      ->getNumberFormat()->setFormatCode('#,##0.000');
                $sheet->getRowDimension($filaTotales)->setRowHeight(24);

                // ===== CONGELAR encabezado =====
                $sheet->freezePane('A2');
            },
        ];
    }
}