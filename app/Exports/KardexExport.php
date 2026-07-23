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
    protected $tipo;
    protected $incluirInicial;
    protected $articulo;

    protected $filas = [];
    protected $totalEntradas = 0;
    protected $totalSalidas = 0;
    protected $montoTotal = 0;
    protected $montoTotalEntradas = 0;
    protected $montoTotalSalidas = 0;

    public function __construct($articuloId, $desde = null, $hasta = null, $tipo = null, $incluirInicial = false)
    {
        $this->articuloId      = $articuloId;
        $this->desde           = $desde;
        $this->hasta           = $hasta;
        $this->tipo            = $tipo;
        $this->incluirInicial  = $incluirInicial;
        $this->articulo        = Articulo::findOrFail($articuloId);
    }

    public function array(): array
    {
        $query = Movimiento::with(['user', 'trabajador'])
            ->where('articulo_id', $this->articuloId);

        if ($this->desde) $query->whereDate('fecha', '>=', $this->desde);
        if ($this->hasta) $query->whereDate('fecha', '<=', $this->hasta);

        // Ocultar Stock Inicial a menos que se pida explícitamente
        if (!$this->incluirInicial) {
            $query->where(function ($q) {
                $q->where('numero_nota', '!=', 'STOCK-INI')
                  ->orWhereNull('numero_nota');
            });
        }

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

        // Invertir: más reciente arriba para la visualización del listado
        $movimientos = $movimientos->reverse();

        // Filtrar por tipo DESPUÉS de calcular el saldo acumulado
        if ($this->tipo) {
            $movimientos = $movimientos->filter(fn($m) => $m->tipo === $this->tipo)->values();
        }

        $datos = [];
        foreach ($movimientos as $mov) {
            $totalFila = (float)(($mov->precio_unitario ?? 0) * $mov->cantidad);

            $nota      = $mov->numero_nota ?? '—';
            $fecha     = $mov->created_at->format('d/m/Y H:i');
            $tipoLabel = strtoupper($mov->tipo);
            $entradaVal = $mov->tipo === 'entrada' ? (float) $mov->cantidad : '';
            $salidaVal  = $mov->tipo === 'salida' ? (float) $mov->cantidad : '';
            $precio     = (float) ($mov->precio_unitario ?? 0);
            $total      = $totalFila;
            $turno      = $mov->turno ?? '—';
            $saldo      = (float) $mov->saldo_acumulado;
            
            $entregado  = $mov->tipo === 'entrada' 
                ? (($mov->entregado_por ?? '—') . ' a ' . ($mov->recibido_por ?? $mov->user->name ?? 'Almacén')) 
                : ($mov->trabajador?->nombre ?? $mov->trabajador_nombre ?? '—');
            
            $notas      = $mov->notas ?? '';
            $registrado = $mov->user->name ?? '—';

            if ($this->tipo === 'entrada') {
                $fila = [$nota, $fecha, $tipoLabel, $entradaVal, $precio, $total, $turno, $saldo, $entregado, $notas, $registrado];
            } elseif ($this->tipo === 'salida') {
                $fila = [$nota, $fecha, $tipoLabel, $salidaVal, $precio, $total, $turno, $saldo, $entregado, $notas, $registrado];
            } else {
                $fila = [$nota, $fecha, $tipoLabel, $entradaVal, $salidaVal, $precio, $total, $turno, $saldo, $entregado, $notas, $registrado];
            }

            $datos[] = $fila;

            if ($mov->tipo === 'entrada') {
                $this->totalEntradas += $mov->cantidad;
                $this->montoTotalEntradas += $totalFila;
            } else {
                $this->totalSalidas += $mov->cantidad;
                $this->montoTotalSalidas += $totalFila;
            }
            $this->montoTotal += $totalFila;
        }

        // Fila de totales al final (Igual al PDF)
        if ($this->tipo === 'entrada') {
            $datos[] = [
                'TOTAL ENTRADAS', '', '',
                (float) $this->totalEntradas,
                '', 
                (float) $this->montoTotalEntradas,
                '', 
                (float) $this->totalEntradas, 
                '', '', '',
            ];
        } elseif ($this->tipo === 'salida') {
            $datos[] = [
                'TOTAL SALIDAS (GASTO)', '', '',
                (float) $this->totalSalidas,
                '', 
                (float) $this->montoTotalSalidas,
                '', 
                (float) (-$this->totalSalidas), 
                '', '', '',
            ];
        } else {
            // Ambos: se agregan los dos totales igual que en el diseño de reporte dual
            $datos[] = [
                'TOTAL ENTRADAS', '', '',
                (float) $this->totalEntradas,
                '', // salida vacía
                '', // precio vacío
                (float) $this->montoTotalEntradas,
                '', // turno vacío
                (float) $this->articulo->cantidad, // saldo actual
                '', '', '',
            ];
            $datos[] = [
                'TOTAL SALIDAS (GASTO)', '', '',
                '', // entrada vacía
                (float) $this->totalSalidas,
                '', // precio vacío
                (float) $this->montoTotalSalidas,
                '', // turno vacío
                (float) $this->articulo->cantidad, // saldo actual
                '', '', '',
            ];
        }

        $this->filas = $datos;
        return $datos;
    }

    public function headings(): array
    {
        if ($this->tipo === 'entrada') {
            return [
                'N° NOTA',
                'FECHA',
                'TIPO',
                'ENTRADA',
                'PRECIO UNIT. (Bs.)',
                'TOTAL (Bs.)',
                'TURNO',
                'SALDO',
                'ENTREGADO A / POR',
                'NOTAS',
                'REGISTRADO POR',
            ];
        } elseif ($this->tipo === 'salida') {
            return [
                'N° NOTA',
                'FECHA',
                'TIPO',
                'SALIDA',
                'PRECIO UNIT. (Bs.)',
                'TOTAL (Bs.)',
                'TURNO',
                'SALDO',
                'ENTREGADO A / POR',
                'NOTAS',
                'REGISTRADO POR',
            ];
        } else {
            return [
                'N° NOTA',
                'FECHA',
                'TIPO',
                'ENTRADA',
                'SALIDA',
                'PRECIO UNIT. (Bs.)',
                'TOTAL (Bs.)',
                'TURNO',
                'SALDO',
                'ENTREGADO A / POR',
                'NOTAS',
                'REGISTRADO POR',
            ];
        }
    }

    public function title(): string
    {
        return 'Kardex ' . $this->articulo->codigo;
    }

    public function columnWidths(): array
    {
        if ($this->tipo) {
            return [
                'A' => 12, 'B' => 18, 'C' => 12, 'D' => 14, 'E' => 16,
                'F' => 15, 'G' => 12, 'H' => 14, 'I' => 28, 'J' => 30,
                'K' => 20,
            ];
        } else {
            return [
                'A' => 12, 'B' => 18, 'C' => 12, 'D' => 13, 'E' => 13,
                'F' => 16, 'G' => 15, 'H' => 12, 'I' => 14, 'J' => 28,
                'K' => 30, 'L' => 20,
            ];
        }
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $cantidadTotales = ($this->tipo === 'entrada' || $this->tipo === 'salida') ? 1 : 2;
                $ultimaFila = count($this->filas) + 1; // +1 por encabezado
                $ultimaFilaDatos = $ultimaFila - $cantidadTotales;
                $maxLetter = $this->tipo ? 'K' : 'L';

                // ===== ENCABEZADO =====
                $sheet->getStyle("A1:{$maxLetter}1")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(26);

                // ===== BORDES =====
                $sheet->getStyle("A1:{$maxLetter}{$ultimaFila}")->applyFromArray([
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
                        $sheet->getStyle("A{$fila}:{$maxLetter}{$fila}")->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F5F6FA']],
                        ]);
                    }
                }

                // ===== COLORES BAJITOS EN COLUMNAS NUMÉRICAS Y ALINEACIONES =====
                if ($this->tipo === 'entrada') {
                    // D: Entrada, E: Precio, F: Total, H: Saldo
                    $sheet->getStyle("D2:D{$ultimaFilaDatos}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F0FDF4');
                    $sheet->getStyle("E2:E{$ultimaFilaDatos}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('EFF6FF');
                    $sheet->getStyle("F2:F{$ultimaFilaDatos}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F5F3FF');
                    $sheet->getStyle("H2:H{$ultimaFilaDatos}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFFBEB');

                    $sheet->getStyle("D2:D{$ultimaFilaDatos}")->getNumberFormat()->setFormatCode('#,##0.000');
                    $sheet->getStyle("H2:H{$ultimaFilaDatos}")->getNumberFormat()->setFormatCode('#,##0.000');
                    $sheet->getStyle("E2:F{$ultimaFilaDatos}")->getNumberFormat()->setFormatCode('#,##0.00');

                    $sheet->getStyle("D2:D{$ultimaFilaDatos}")->getFont()->getColor()->setRGB('2B8A3E');
                    $sheet->getStyle("C2:C{$ultimaFilaDatos}")->getFont()->getColor()->setRGB('2B8A3E');
                    $sheet->getStyle("H2:H{$ultimaFilaDatos}")->getFont()->setBold(true);

                    // Alineaciones
                    $sheet->getStyle("A2:C{$ultimaFilaDatos}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("G2:G{$ultimaFilaDatos}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("D2:F{$ultimaFilaDatos}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle("H2:H{$ultimaFilaDatos}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                } elseif ($this->tipo === 'salida') {
                    // D: Salida, E: Precio, F: Total, H: Saldo
                    $sheet->getStyle("D2:D{$ultimaFilaDatos}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FEF2F2');
                    $sheet->getStyle("E2:E{$ultimaFilaDatos}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('EFF6FF');
                    $sheet->getStyle("F2:F{$ultimaFilaDatos}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F5F3FF');
                    $sheet->getStyle("H2:H{$ultimaFilaDatos}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFFBEB');

                    $sheet->getStyle("D2:D{$ultimaFilaDatos}")->getNumberFormat()->setFormatCode('#,##0.000');
                    $sheet->getStyle("H2:H{$ultimaFilaDatos}")->getNumberFormat()->setFormatCode('#,##0.000');
                    $sheet->getStyle("E2:F{$ultimaFilaDatos}")->getNumberFormat()->setFormatCode('#,##0.00');

                    $sheet->getStyle("D2:D{$ultimaFilaDatos}")->getFont()->getColor()->setRGB('C92A2A');
                    $sheet->getStyle("C2:C{$ultimaFilaDatos}")->getFont()->getColor()->setRGB('C92A2A');
                    $sheet->getStyle("H2:H{$ultimaFilaDatos}")->getFont()->setBold(true);

                    // Alineaciones
                    $sheet->getStyle("A2:C{$ultimaFilaDatos}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("G2:G{$ultimaFilaDatos}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("D2:F{$ultimaFilaDatos}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle("H2:H{$ultimaFilaDatos}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                } else {
                    // Ambos: D: Entrada, E: Salida, F: Precio, G: Total, I: Saldo
                    $sheet->getStyle("D2:D{$ultimaFilaDatos}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F0FDF4');
                    $sheet->getStyle("E2:E{$ultimaFilaDatos}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FEF2F2');
                    $sheet->getStyle("F2:F{$ultimaFilaDatos}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('EFF6FF');
                    $sheet->getStyle("G2:G{$ultimaFilaDatos}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F5F3FF');
                    $sheet->getStyle("I2:I{$ultimaFilaDatos}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFFBEB');

                    $sheet->getStyle("D2:E{$ultimaFilaDatos}")->getNumberFormat()->setFormatCode('#,##0.000');
                    $sheet->getStyle("I2:I{$ultimaFilaDatos}")->getNumberFormat()->setFormatCode('#,##0.000');
                    $sheet->getStyle("F2:G{$ultimaFilaDatos}")->getNumberFormat()->setFormatCode('#,##0.00');

                    $sheet->getStyle("I2:I{$ultimaFilaDatos}")->getFont()->setBold(true);

                    // Alineaciones
                    $sheet->getStyle("A2:C{$ultimaFilaDatos}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("H2:H{$ultimaFilaDatos}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("D2:G{$ultimaFilaDatos}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle("I2:I{$ultimaFilaDatos}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                    for ($fila = 2; $fila <= $ultimaFilaDatos; $fila++) {
                        $tipoVal = $sheet->getCell("C{$fila}")->getValue();
                        if ($tipoVal === 'ENTRADA') {
                            $sheet->getStyle("C{$fila}")->getFont()->getColor()->setRGB('2B8A3E');
                            $sheet->getStyle("D{$fila}")->getFont()->getColor()->setRGB('2B8A3E');
                        } elseif ($tipoVal === 'SALIDA') {
                            $sheet->getStyle("C{$fila}")->getFont()->getColor()->setRGB('C92A2A');
                            $sheet->getStyle("E{$fila}")->getFont()->getColor()->setRGB('C92A2A');
                        }
                    }
                }

                // ===== FILAS DE TOTALES =====
                $primeraFilaTotales = $ultimaFilaDatos + 1;
                $sheet->getStyle("A{$primeraFilaTotales}:{$maxLetter}{$ultimaFila}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2D3748']],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);

                for ($f = $primeraFilaTotales; $f <= $ultimaFila; $f++) {
                    $sheet->getStyle("A{$f}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    if ($this->tipo === 'entrada') {
                        // D: Entrada, F: Total, H: Saldo
                        $sheet->getStyle("D{$f}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $sheet->getStyle("F{$f}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $sheet->getStyle("H{$f}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                        $sheet->getStyle("D{$f}")->getNumberFormat()->setFormatCode('#,##0.000');
                        $sheet->getStyle("H{$f}")->getNumberFormat()->setFormatCode('#,##0.000');
                        $sheet->getStyle("F{$f}")->getNumberFormat()->setFormatCode('#,##0.00');
                    } elseif ($this->tipo === 'salida') {
                        // D: Salida, F: Total, H: Saldo
                        $sheet->getStyle("D{$f}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $sheet->getStyle("F{$f}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $sheet->getStyle("H{$f}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                        $sheet->getStyle("D{$f}")->getNumberFormat()->setFormatCode('#,##0.000');
                        $sheet->getStyle("H{$f}")->getNumberFormat()->setFormatCode('#,##0.000');
                        $sheet->getStyle("F{$f}")->getNumberFormat()->setFormatCode('#,##0.00');
                    } else {
                        // Ambos: D: Entrada, E: Salida, G: Total, I: Saldo
                        $sheet->getStyle("D{$f}:E{$f}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $sheet->getStyle("G{$f}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $sheet->getStyle("I{$f}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                        $sheet->getStyle("D{$f}:E{$f}")->getNumberFormat()->setFormatCode('#,##0.000');
                        $sheet->getStyle("I{$f}")->getNumberFormat()->setFormatCode('#,##0.000');
                        $sheet->getStyle("G{$f}")->getNumberFormat()->setFormatCode('#,##0.00');
                    }
                    $sheet->getRowDimension($f)->setRowHeight(24);
                }

                // ===== CONGELAR ENCABEZADO =====
                $sheet->freezePane('A2');
            },
        ];
    }
}