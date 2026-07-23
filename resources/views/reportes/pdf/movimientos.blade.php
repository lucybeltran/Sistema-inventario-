<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>
        @if(isset($tipo) && $tipo === 'entrada')
            Reporte de Entradas
        @elseif(isset($tipo) && $tipo === 'salida')
            Reporte de Salidas
        @else
            Reporte de Movimientos
        @endif
    </title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; }
        h1 { color: #1e293b; font-size: 18px; margin-bottom: 5px; }
        .subtitulo { color: #666; font-size: 10px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th {
            background: #1e293b; color: white;
            padding: 7px 5px; text-align: left; font-size: 9px;
        }
        td { padding: 5px; border: 1px solid #e2e8f0; }
        tr:nth-child(even) td { background: #f8fafc; }
        .codigo { font-weight: bold; color: #d97706; font-family: monospace; }
        .entrada { color: #2b8a3e; font-weight: bold; }
        .salida { color: #c92a2a; font-weight: bold; }
        .right { text-align: right; }
        .center { text-align: center; }
        .trabajador { background: #fef3c7; color: #b45309; padding: 2px 6px; border-radius: 4px; font-size: 9px; border: 1px solid #fde68a; }
        .entregado-por { background: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 4px; font-size: 9px; border: 1px solid #bae6fd; }
        .total-row td {
            background: #1e293b;
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
                <h1 style="margin:0;">
                    @if(isset($tipo) && $tipo === 'entrada')
                        Reporte de Entradas de Almacén
                    @elseif(isset($tipo) && $tipo === 'salida')
                        Reporte de Salidas de Almacén
                    @else
                        Reporte de Movimientos
                    @endif
                </h1>
                <div class="subtitulo" style="margin-top:3px;">
                    Sección Catalina — Empresa Minera Torrez S.R.L.<br>
                    Generado: {{ now()->format('d/m/Y H:i') }} — Total: {{ $movimientos->count() }} movimientos
                    @if(isset($articuloFiltro) && $articuloFiltro)
                        &nbsp;·&nbsp; Material: <strong>{{ $articuloFiltro->codigo }} — {{ $articuloFiltro->nombre }}</strong>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th class="center">N° Nota</th>
                <th>Fecha y Hora</th>
                <th>Código</th>
                <th>Artículo</th>
                <th class="center">Tipo</th>
                <th class="right">Cant.</th>
                <th>Unidad</th>
                <th class="right">Precio Bs.</th>
                <th class="right">Total Bs.</th>
                <th>Entregado A / Por</th>
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
                    <td>{{ $mov->fecha ? \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y') : $mov->created_at->format('d/m/Y H:i') }}</td>
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
                    <td>{{ $mov->user->name ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            @php
                $totalCant = $movimientos->sum('cantidad');
                $totalVal = $movimientos->sum(fn($m) => ($m->precio_unitario ?? 0) * $m->cantidad);
                $totalEntradas = $movimientos->where('tipo', 'entrada')->sum(fn($m) => ($m->precio_unitario ?? 0) * $m->cantidad);
                $totalSalidas = $movimientos->where('tipo', 'salida')->sum(fn($m) => ($m->precio_unitario ?? 0) * $m->cantidad);
            @endphp
            <tr class="total-row">
                <td colspan="5" style="text-align: right; font-weight: bold; background: #1e293b; color: white;">TOTALES:</td>
                <td class="right" style="font-weight: bold; background: #1e293b; color: white;">{{ number_format($totalCant, 3) }}</td>
                <td style="background: #1e293b; color: white;"></td>
                <td style="background: #1e293b; color: white;"></td>
                <td class="right" style="font-weight: bold; background: #1e293b; color: white;">Bs. {{ number_format($totalVal, 2) }}</td>
                <td colspan="2" style="font-size: 8.5px; background: #1e293b; color: white; vertical-align: middle;">
                    {{ $entradas }} ent. (Bs. {{ number_format($totalEntradas, 2) }})<br>
                    {{ $salidas }} sal. (Bs. {{ number_format($totalSalidas, 2) }})
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Sistema de Gestión de Inventario — Sección Catalina, Empresa Minera Torrez S.R.L.
    </div>
</body>
</html>