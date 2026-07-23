<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kardex - {{ $articulo->codigo }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        h1 { color: #1e293b; font-size: 18px; margin-bottom: 5px; }
        .subtitulo { color: #666; font-size: 10px; margin-bottom: 15px; }

        .articulo-info {
            background: #f8fafc;
            border-left: 4px solid #d97706;
            padding: 12px 15px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
        }
        .articulo-info h3 { color: #1e293b; margin: 0 0 6px 0; font-size: 14px; }
        .articulo-info .meta { font-size: 10px; color: #555; line-height: 1.6; }
        .articulo-info .codigo {
            background: #1e293b; color: white;
            padding: 2px 8px; border-radius: 4px;
            font-family: monospace; margin-right: 8px;
        }

        .resumen { display: table; width: 100%; margin-bottom: 12px; }
        .resumen-item {
            display: table-cell;
            padding: 8px 12px;
            background: #f8fafc;
            text-align: center;
            width: 25%;
            border: 1px solid #e2e8f0;
        }
        .resumen-label {
            font-size: 9px; color: #777;
            text-transform: uppercase; margin-bottom: 3px;
        }
        .resumen-num { font-weight: bold; color: #2d3748; font-size: 13px; }
        .resumen-num.entrada { color: #2b8a3e; }
        .resumen-num.salida { color: #c92a2a; }

        table { width: 100%; border-collapse: collapse; }
        th {
            background: #1e293b; color: white;
            padding: 7px 5px; text-align: left; font-size: 9px;
        }
        td { padding: 5px; border: 1px solid #e2e8f0; }
        tr:nth-child(even) td { background: #f8fafc; }

        .codigo-cell { font-weight: bold; color: #d97706; font-family: monospace; }
        .right { text-align: right; }
        .center { text-align: center; }
        .entrada { color: #2b8a3e; font-weight: bold; }
        .salida { color: #c92a2a; font-weight: bold; }
        .saldo { font-weight: bold; }
        .tipo-entrada {
            background: #c6f6d5; color: #22543d;
            padding: 2px 5px; border-radius: 3px;
            font-size: 9px; font-weight: bold;
        }
        .tipo-salida {
            background: #fed7d7; color: #742a2a;
            padding: 2px 5px; border-radius: 3px;
            font-size: 9px; font-weight: bold;
        }
        .total-row td {
            background: #1e293b; color: white;
            font-weight: bold; font-size: 10px;
            padding: 8px 5px;
        }
        .trabajador { background: #fef3c7; color: #b45309; padding: 2px 6px; border-radius: 4px; font-size: 9px; border: 1px solid #fde68a; }
        .entregado-por { background: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 4px; font-size: 9px; border: 1px solid #bae6fd; }
        .footer { margin-top: 15px; font-size: 9px; color: #999; text-align: center; }
    </style>
</head>
<body>
    <table style="width:100%; border:none; margin-bottom:10px;">
    <tr>
        <td style="border:none; width:80px; vertical-align:middle;">
            <img src="{{ public_path('img/logo.png') }}" style="width:75px; height:auto;">
        </td>
        <td style="border:none; vertical-align:middle; padding-left:10px;">
            <h1 style="margin:0;">
                @if(isset($tipo) && $tipo === 'entrada')
                    Kardex de Entradas — {{ $articulo->codigo }}
                @elseif(isset($tipo) && $tipo === 'salida')
                    Kardex de Salidas — {{ $articulo->codigo }}
                @else
                    Kardex de Producto — {{ $articulo->codigo }}
                @endif
            </h1>
            <div class="subtitulo" style="margin-top:3px;">
                Sección Catalina — Empresa Minera Torrez S.R.L.<br>
                Generado: {{ now()->format('d/m/Y H:i') }}
            </div>
        </td>
    </tr>
</table>

    <div class="articulo-info">
        <h3>
            <span class="codigo">{{ $articulo->codigo }}</span>
            {{ $articulo->nombre }}
        </h3>
        <div class="meta">
            <strong>Grupo:</strong> {{ $articulo->grupo_id }} — {{ $articulo->grupo->nombre ?? '' }}
            &nbsp;|&nbsp;
            <strong>Unidad:</strong> {{ $articulo->unidad }}
            &nbsp;|&nbsp;
            <strong>Precio:</strong> 
            @php
                $preciosActivos = \App\Models\Movimiento::where('articulo_id', $articulo->id)
                    ->where('tipo', 'entrada')
                    ->where('cantidad_restante', '>', 0)
                    ->whereNotNull('precio_unitario')
                    ->orderBy('created_at', 'asc')
                    ->pluck('precio_unitario')
                    ->unique()
                    ->values();
            @endphp
            @if($preciosActivos->isNotEmpty())
                {{ $preciosActivos->map(fn($p) => 'Bs. ' . number_format($p, 2))->join(', ') }}
            @else
                Bs. {{ number_format($articulo->precio, 2) }}
            @endif
            &nbsp;|&nbsp;
            <strong>Stock actual:</strong> {{ number_format($articulo->cantidad, 3) }} {{ $articulo->unidad }}
        </div>
        @if(!empty($desde) || !empty($hasta))
            <div class="meta" style="margin-top:5px;">
                <strong>Período:</strong>
                {{ !empty($desde) ? \Carbon\Carbon::parse($desde)->format('d/m/Y') : '...' }}
                al
                {{ !empty($hasta) ? \Carbon\Carbon::parse($hasta)->format('d/m/Y') : '...' }}
            </div>
        @endif
        @if($articulo->notas)
            <div class="meta" style="margin-top: 5px; color: #7c3aed; font-style: italic; border-left: 2px solid #7c3aed; padding-left: 6px;">
                <strong>Nota/Observación del Material:</strong> {{ $articulo->notas }}
            </div>
        @endif
    </div>

    @php
        $sumaCantEntradas = 0;
        $sumaCantSalidas = 0;
        $sumaValorEntradas = 0;
        $sumaValorSalidas = 0;
        foreach($movimientos as $mov) {
            $subtotal = ($mov->precio_unitario ?? 0) * $mov->cantidad;
            if ($mov->tipo === 'entrada') {
                $sumaCantEntradas += $mov->cantidad;
                $sumaValorEntradas += $subtotal;
            } else {
                $sumaCantSalidas += $mov->cantidad;
                $sumaValorSalidas += $subtotal;
            }
        }
    @endphp

    <div class="resumen">
        @if(!isset($tipo) || $tipo !== 'salida')
            <div class="resumen-item" style="width: {{ (isset($tipo) && $tipo) ? '50%' : '25%' }};">
                <div class="resumen-label">Total Entradas</div>
                <div class="resumen-num entrada">+ {{ number_format($sumaCantEntradas, 3) }}</div>
            </div>
        @endif
        @if(!isset($tipo) || $tipo !== 'entrada')
            <div class="resumen-item" style="width: {{ (isset($tipo) && $tipo) ? '50%' : '25%' }};">
                <div class="resumen-label">Total Salidas</div>
                <div class="resumen-num salida">− {{ number_format($sumaCantSalidas, 3) }}</div>
            </div>
        @endif
        @if(!isset($tipo) || $tipo !== 'salida')
            <div class="resumen-item" style="width: {{ (isset($tipo) && $tipo) ? '50%' : '25%' }};">
                <div class="resumen-label">Total Comprado (In)</div>
                <div class="resumen-num" style="color: #2b8a3e;">Bs. {{ number_format($sumaValorEntradas, 2) }}</div>
            </div>
        @endif
        @if(!isset($tipo) || $tipo !== 'entrada')
            <div class="resumen-item" style="background:#fff5f5; border: 1px solid #ffc9c9; width: {{ (isset($tipo) && $tipo) ? '50%' : '25%' }};">
                <div class="resumen-label" style="color:#c92a2a; font-weight:bold;">Total Gastado (Out)</div>
                <div class="resumen-num" style="color:#c92a2a;">Bs. {{ number_format($sumaValorSalidas, 2) }}</div>
            </div>
        @endif
    </div>

    @if($movimientos->isEmpty())
        <p style="text-align:center; color:#999; padding:30px;">
            Sin movimientos para este artículo en el período seleccionado.
        </p>
    @else
        <table>
            <thead>
                <tr>
                    <th class="center">N° Nota</th>
                    <th>Fecha y Hora</th>
                    <th class="center">Tipo</th>
                    @if(!isset($tipo) || $tipo !== 'salida')
                        <th class="right">Entrada</th>
                    @endif
                    @if(!isset($tipo) || $tipo !== 'entrada')
                        <th class="right">Salida</th>
                    @endif
                    <th class="right">Precio Bs.</th>
                    <th class="right">Total Bs.</th>
                    <th class="right">Saldo</th>
                    <th>Entregado A / Por</th>
                    <th>Notas</th>
                    <th>Registró</th>
                </tr>
            </thead>
            <tbody>
                @foreach($movimientos as $mov)
                    <tr>
                        <td class="codigo-cell center">{{ $mov->numero_nota ?? '—' }}</td>
                        <td>{{ $mov->created_at->format('d/m/Y H:i') }}</td>
                        <td class="center">
                            @if($mov->tipo === 'entrada')
                                <span class="tipo-entrada">ENTRADA</span>
                            @else
                                <span class="tipo-salida">SALIDA</span>
                            @endif
                        </td>
                        @if(!isset($tipo) || $tipo !== 'salida')
                            <td class="right entrada">
                                @if($mov->tipo === 'entrada')
                                    + {{ number_format($mov->cantidad, 3) }}
                                @else
                                    —
                                @endif
                            </td>
                        @endif
                        @if(!isset($tipo) || $tipo !== 'entrada')
                            <td class="right salida">
                                @if($mov->tipo === 'salida')
                                    − {{ number_format($mov->cantidad, 3) }}
                                @else
                                    —
                                @endif
                            </td>
                        @endif
                        <td class="right" style="color:#2b8a3e; font-weight:bold;">
                            Bs. {{ number_format($mov->precio_unitario ?? 0, 2) }}
                        </td>
                        <td class="right" style="color:#1971c2; font-weight:bold;">
                            Bs. {{ number_format(($mov->precio_unitario ?? 0) * $mov->cantidad, 2) }}
                        </td>
                        <td class="right saldo">{{ number_format($mov->saldo_acumulado, 3) }}</td>
                        <td>
                             @if($mov->tipo === 'entrada')
                                <span class="entregado-por">{{ $mov->entregado_por ?? '—' }} a {{ $mov->recibido_por ?? ($mov->user->name ?? 'Almacén') }}</span>
                            @else
                                @if($mov->entregado_por)
                                    <span class="entregado-por" style="font-size:10px; color:#555; display:block;">{{ $mov->entregado_por }} a </span>
                                @endif
                                @if($mov->trabajador)
                                    <span class="trabajador">{{ $mov->trabajador->nombre }}</span>
                                @elseif($mov->trabajador_nombre)
                                    <span class="trabajador">{{ $mov->trabajador_nombre }}</span>
                                @else
                                    —
                                 @endif
                                 @if($mov->turno)
                                     <span class="turno" style="font-size:10px; color:#666; font-style:italic; display:block; margin-top:2px;">(Turno: {{ $mov->turno }})</span>
                                 @endif
                            @endif
                        </td>
                        <td>{{ $mov->notas ?? '—' }}</td>
                        <td>{{ $mov->user->name ?? '—' }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="3" class="right">TOTALES:</td>
                    @if(!isset($tipo) || $tipo !== 'salida')
                        <td class="right" style="color:#68d391;">+ {{ number_format($sumaCantEntradas, 3) }}</td>
                    @endif
                    @if(!isset($tipo) || $tipo !== 'entrada')
                        <td class="right" style="color:#fc8181;">− {{ number_format($sumaCantSalidas, 3) }}</td>
                    @endif
                    <td></td>
                    <td class="right" style="font-size: 8px; line-height: 1.2;">
                        @if(!isset($tipo) || $tipo !== 'salida')
                            <span style="color:#68d391;">Entrada: Bs. {{ number_format($sumaValorEntradas, 2) }}</span>
                            @if(!isset($tipo) || $tipo !== 'entrada') <br> @endif
                        @endif
                        @if(!isset($tipo) || $tipo !== 'entrada')
                            <span style="color:#fc8181;">Salida: Bs. {{ number_format($sumaValorSalidas, 2) }}</span>
                        @endif
                    </td>
                    <td class="right">{{ number_format($articulo->cantidad, 3) }}</td>
                    <td colspan="3"></td>
                </tr>
            </tbody>
        </table>
    @endif

    <div class="footer">
        Sistema de Gestión de Inventario — Sección Catalina, Empresa Minera Torrez S.R.L.
    </div>
</body>
</html>