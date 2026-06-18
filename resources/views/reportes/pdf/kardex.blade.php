<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kardex - {{ $articulo->codigo }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        h1 { color: #667eea; font-size: 18px; margin-bottom: 5px; }
        .subtitulo { color: #666; font-size: 10px; margin-bottom: 15px; }

        .articulo-info {
            background: #f0e9ff;
            border-left: 4px solid #667eea;
            padding: 12px 15px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .articulo-info h3 { color: #667eea; margin: 0 0 6px 0; font-size: 14px; }
        .articulo-info .meta { font-size: 10px; color: #555; line-height: 1.6; }
        .articulo-info .codigo {
            background: #667eea; color: white;
            padding: 2px 8px; border-radius: 4px;
            font-family: monospace; margin-right: 8px;
        }

        .resumen { display: table; width: 100%; margin-bottom: 12px; }
        .resumen-item {
            display: table-cell;
            padding: 8px 12px;
            background: #f5f6fa;
            text-align: center;
            width: 25%;
            border: 1px solid #e0e0e0;
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
            background: #667eea; color: white;
            padding: 7px 5px; text-align: left; font-size: 9px;
        }
        td { padding: 5px; border: 1px solid #e0e0e0; }
        tr:nth-child(even) td { background: #f5f6fa; }

        .codigo-cell { font-weight: bold; color: #667eea; font-family: monospace; }
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
            background: #2d3748; color: white;
            font-weight: bold; font-size: 10px;
            padding: 8px 5px;
        }
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
            <h1 style="margin:0;">Kardex de Producto</h1>
            <div class="subtitulo" style="margin-top:3px;">
                Santa Catalina — Mina Tres Amigos<br>
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
            <strong>Precio:</strong> Bs. {{ number_format($articulo->precio, 2) }}
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
    </div>

    <div class="resumen">
        <div class="resumen-item">
            <div class="resumen-label">Total Entradas</div>
            <div class="resumen-num entrada">+ {{ number_format($totalEntradas, 3) }}</div>
        </div>
        <div class="resumen-item">
            <div class="resumen-label">Total Salidas</div>
            <div class="resumen-num salida">− {{ number_format($totalSalidas, 3) }}</div>
        </div>
        <div class="resumen-item">
            <div class="resumen-label">Movimientos</div>
            <div class="resumen-num">{{ $movimientos->count() }}</div>
        </div>
        <div class="resumen-item">
            <div class="resumen-label">Stock Final</div>
            <div class="resumen-num">{{ number_format($articulo->cantidad, 3) }}</div>
        </div>
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
                    <th>Fecha</th>
                    <th class="center">Tipo</th>
                    <th class="right">Entrada</th>
                    <th class="right">Salida</th>
                    <th class="right">Precio Bs.</th>
                    <th class="right">Saldo</th>
                    <th>Entregado a</th>
                    <th>CI</th>
                    <th>Notas</th>
                    <th>Registró</th>
                </tr>
            </thead>
            <tbody>
                @foreach($movimientos as $mov)
                    <tr>
                        <td class="codigo-cell center">{{ $mov->numero_nota ?? '—' }}</td>
                        <td>{{ $mov->fecha->format('d/m/Y') }}</td>
                        <td class="center">
                            @if($mov->tipo === 'entrada')
                                <span class="tipo-entrada">ENTRADA</span>
                            @else
                                <span class="tipo-salida">SALIDA</span>
                            @endif
                        </td>
                        <td class="right entrada">
                            @if($mov->tipo === 'entrada')
                                + {{ number_format($mov->cantidad, 3) }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="right salida">
                            @if($mov->tipo === 'salida')
                                − {{ number_format($mov->cantidad, 3) }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="right" style="color:#2b8a3e; font-weight:bold;">
                            Bs. {{ number_format($mov->precio_unitario ?? 0, 2) }}
                        </td>
                        <td class="right saldo">{{ number_format($mov->saldo_acumulado, 3) }}</td>
                        <td>{{ $mov->trabajador->nombre ?? '—' }}</td>
                        <td>{{ $mov->trabajador->ci ?? '—' }}</td>
                        <td>{{ $mov->notas ?? '—' }}</td>
                        <td>{{ $mov->user->name ?? '—' }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="3" class="right">TOTALES:</td>
                    <td class="right">+ {{ number_format($totalEntradas, 3) }}</td>
                    <td class="right">− {{ number_format($totalSalidas, 3) }}</td>
                    <td></td>
                    <td class="right">{{ number_format($articulo->cantidad, 3) }}</td>
                    <td colspan="4"></td>
                </tr>
            </tbody>
        </table>
    @endif

    <div class="footer">
        Sistema de Gestión de Inventario — Santa Catalina, Mina Tres Amigos
    </div>
</body>
</html>