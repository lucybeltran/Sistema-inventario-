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
        .articulo-info h3 {
            color: #667eea;
            margin: 0 0 6px 0;
            font-size: 14px;
        }
        .articulo-info .meta {
            font-size: 10px;
            color: #555;
            line-height: 1.6;
        }
        .articulo-info .codigo {
            background: #667eea;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-family: monospace;
            margin-right: 8px;
        }

        .resumen {
            display: table;
            width: 100%;
            margin-bottom: 12px;
        }
        .resumen-item {
            display: table-cell;
            padding: 8px 12px;
            background: #f9f9f9;
            border-radius: 4px;
            text-align: center;
            width: 25%;
        }
        .resumen-label {
            font-size: 9px;
            color: #777;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .resumen-num {
            font-weight: bold;
            color: #2d3748;
            font-size: 13px;
        }
        .resumen-num.entrada { color: #38a169; }
        .resumen-num.salida { color: #e53e3e; }

        table { width: 100%; border-collapse: collapse; }
        th {
            background: #667eea; color: white;
            padding: 6px 5px; text-align: left; font-size: 9px;
        }
        td { padding: 5px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background: #f9f9f9; }

        .codigo-cell { font-weight: bold; color: #667eea; font-family: monospace; }
        .right { text-align: right; }
        .entrada { color: #38a169; font-weight: bold; }
        .salida { color: #e53e3e; font-weight: bold; }
        .saldo {
            font-weight: bold;
            background: #e8f4ff;
            padding: 2px 6px;
            border-radius: 3px;
            display: inline-block;
        }
        .tipo-entrada {
            background: #c6f6d5;
            color: #22543d;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .tipo-salida {
            background: #fed7d7;
            color: #742a2a;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .footer { margin-top: 15px; font-size: 9px; color: #999; text-align: center; }
    </style>
</head>
<body>
    <h1>Kardex de Producto</h1>
    <div class="subtitulo">
        Santa Catalina — Mina Tres Amigos<br>
        Generado: {{ now()->format('d/m/Y H:i') }}
    </div>

    {{-- INFO DEL ARTÍCULO --}}
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
            <strong>Stock actual:</strong> {{ number_format($articulo->cantidad, 2) }} {{ $articulo->unidad }}
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

    {{-- RESUMEN --}}
    <div class="resumen">
        <div class="resumen-item">
            <div class="resumen-label">Total Entradas</div>
            <div class="resumen-num entrada">+ {{ number_format($totalEntradas, 2) }}</div>
        </div>
        <div class="resumen-item">
            <div class="resumen-label">Total Salidas</div>
            <div class="resumen-num salida">− {{ number_format($totalSalidas, 2) }}</div>
        </div>
        <div class="resumen-item">
            <div class="resumen-label">Movimientos</div>
            <div class="resumen-num">{{ $movimientos->count() }}</div>
        </div>
        <div class="resumen-item">
            <div class="resumen-label">Stock Final</div>
            <div class="resumen-num">{{ number_format($articulo->cantidad, 2) }}</div>
        </div>
    </div>

    {{-- TABLA --}}
    @if($movimientos->isEmpty())
        <p style="text-align:center; color:#999; padding:30px;">
            Sin movimientos para este artículo en el período seleccionado.
        </p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th class="right">Entrada</th>
                    <th class="right">Salida</th>
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
                        <td>{{ $mov->fecha->format('d/m/Y') }}</td>
                        <td>
                            @if($mov->tipo === 'entrada')
                                <span class="tipo-entrada">ENTRADA</span>
                            @else
                                <span class="tipo-salida">SALIDA</span>
                            @endif
                        </td>
                        <td class="right entrada">
                            @if($mov->tipo === 'entrada')
                                + {{ number_format($mov->cantidad, 2) }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="right salida">
                            @if($mov->tipo === 'salida')
                                − {{ number_format($mov->cantidad, 2) }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="right">
                            <span class="saldo">{{ number_format($mov->saldo_acumulado, 2) }}</span>
                        </td>
                        <td>{{ $mov->trabajador->nombre ?? '—' }}</td>
                        <td>{{ $mov->trabajador->ci ?? '—' }}</td>
                        <td>{{ $mov->notas ?? '—' }}</td>
                        <td>{{ $mov->user->name ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Sistema de Gestión de Inventario — Santa Catalina, Mina Tres Amigos
    </div>
</body>
</html>
