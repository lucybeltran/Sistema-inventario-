<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Movimientos</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; }
        h1 { color: #667eea; font-size: 18px; margin-bottom: 5px; }
        .subtitulo { color: #666; font-size: 10px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th {
            background: #667eea; color: white;
            padding: 7px 5px; text-align: left; font-size: 9px;
        }
        td { padding: 5px; border: 1px solid #e0e0e0; }
        tr:nth-child(even) td { background: #f5f6fa; }
        .codigo { font-weight: bold; color: #667eea; font-family: monospace; }
        .entrada { color: #2b8a3e; font-weight: bold; }
        .salida { color: #c92a2a; font-weight: bold; }
        .right { text-align: right; }
        .center { text-align: center; }
        .trabajador { background: #fff3bf; padding: 2px 6px; border-radius: 4px; font-size: 9px; }
        .total-row td {
            background: #2d3748;
            color: white;
            font-weight: bold;
            font-size: 10px;
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
                <h1 style="margin:0;">Reporte de Movimientos</h1>
                <div class="subtitulo" style="margin-top:3px;">
                    Santa Catalina — Mina Tres Amigos<br>
                    Generado: {{ now()->format('d/m/Y H:i') }} — Total: {{ $movimientos->count() }} movimientos
                </div>
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th class="center">N° Nota</th>
                <th>Fecha</th>
                <th>Código</th>
                <th>Artículo</th>
                <th class="center">Tipo</th>
                <th class="right">Cant.</th>
                <th>Unidad</th>
                <th class="right">Precio Bs.</th>
                <th class="right">Total Bs.</th>
                <th>Entregado a</th>
                <th>Registró</th>
            </tr>
        </thead>
        <tbody>
            @php $entradas = 0; $salidas = 0; @endphp
            @foreach($movimientos as $mov)
                @php
                    $mov->tipo === 'entrada' ? $entradas++ : $salidas++;
                @endphp
                <tr>
                    <td class="codigo center">{{ $mov->numero_nota ?? '—' }}</td>
                    <td>{{ $mov->fecha->format('d/m/Y') }}</td>
                    <td class="codigo">{{ $mov->articulo->codigo }}</td>
                    <td>{{ $mov->articulo->nombre }}</td>
                    <td class="center {{ $mov->tipo }}">{{ strtoupper($mov->tipo) }}</td>
                    <td class="right">{{ number_format($mov->cantidad, 3) }}</td>
                    <td>{{ $mov->articulo->unidad }}</td>
                    <td class="right" style="color:#2b8a3e; font-weight:600;">
                        Bs. {{ number_format($mov->precio_unitario ?? 0, 2) }}
                    </td>
                    <td class="right" style="color:#1971c2; font-weight:600;">
                        Bs. {{ number_format(($mov->precio_unitario ?? 0) * $mov->cantidad, 2) }}
                    </td>
                    <td>
                        @if($mov->trabajador)
                            <span class="trabajador">{{ $mov->trabajador->nombre }}</span>
                        @elseif($mov->trabajador_nombre)
                            <span class="trabajador">{{ $mov->trabajador_nombre }}</span>
                        @else
                            —
                        @endif
                    </td>
                    <td>{{ $mov->user->name ?? '—' }}</td>
                </tr>
            @endforeach
            @php
                $totalEntradas = $movimientos->where('tipo', 'entrada')->sum(fn($m) => ($m->precio_unitario ?? 0) * $m->cantidad);
                $totalSalidas = $movimientos->where('tipo', 'salida')->sum(fn($m) => ($m->precio_unitario ?? 0) * $m->cantidad);
            @endphp
            <tr class="total-row">
                <td colspan="11">
                    TOTALES: {{ $movimientos->count() }} mov.
                    &nbsp;·&nbsp; {{ $entradas }} entradas (Bs. {{ number_format($totalEntradas, 2) }})
                    &nbsp;·&nbsp; {{ $salidas }} salidas (Bs. {{ number_format($totalSalidas, 2) }})
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Sistema de Gestión de Inventario — Santa Catalina, Mina Tres Amigos
    </div>
</body>
</html>