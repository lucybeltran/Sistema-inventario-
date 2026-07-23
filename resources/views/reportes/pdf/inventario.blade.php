<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Inventario</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        h1 { color: #1e293b; font-size: 18px; margin-bottom: 5px; }
        .subtitulo { color: #666; font-size: 11px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th {
            background: #1e293b; color: white;
            padding: 7px; text-align: left; font-size: 9px;
        }
        td { padding: 5px 7px; border-bottom: 1px solid #ddd; }
        .codigo { font-weight: bold; color: #d97706; font-family: monospace; }
        .right { text-align: right; }

        /* Fila separadora de grupo */
        .grupo-separador td {
            background: #334155;
            color: white;
            font-weight: bold;
            font-size: 11px;
            padding: 7px;
            border: none;
        }

        /* Subtotal por grupo */
        .subtotal-row td {
            background: #f1f5f9;
            color: #1e293b;
            font-weight: bold;
            font-size: 9px;
            border-top: 1px solid #cbd5e1;
        }

        .total-row td {
            background: #1e293b;
            color: white;
            font-weight: bold;
            font-size: 11px;
            padding: 8px 7px;
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
            <h1 style="margin:0; font-size: 20px;">
                Reporte de Inventario
                @if(isset($stockFilter))
                    @if($stockFilter === 'con_stock')
                        <span style="font-size:13px; font-weight:normal; color:#555;">(Solo con Stock)</span>
                    @elseif($stockFilter === 'sin_stock')
                        <span style="font-size:13px; font-weight:normal; color:#555;">(Solo sin Stock)</span>
                    @else
                        <span style="font-size:13px; font-weight:normal; color:#555;">(General)</span>
                    @endif
                @endif
            </h1>
            <div class="subtitulo" style="margin-top:3px;">
                Sección Catalina — Empresa Minera Torrez S.R.L.<br>
                Generado: {{ now()->format('d/m/Y H:i') }}
            </div>
        </td>
    </tr>
</table>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Descripción</th>
                <th>Grupo</th>
                <th>Unidad</th>
                <th class="right">Cantidad</th>
                <th class="right">Precio Bs.</th>
                <th class="right">Total Bs.</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalGeneral = 0;
                $grupoActual = null;
                $subtotalGrupo = 0;
                $contadorGrupo = 0;
            @endphp

            @foreach($articulos as $art)
                @php
                    if (isset($preciosPorArticulo[$art->id]) && count($preciosPorArticulo[$art->id]) > 1) {
                        $valorTotal = collect($preciosPorArticulo[$art->id])->sum(fn($p) => $p['precio'] * $p['cantidad']);
                    } else {
                        $valorTotal = $art->precio * $art->cantidad;
                    }
                @endphp

                {{-- ¿Cambió de grupo? --}}
                @if($art->grupo_id !== $grupoActual)
                    {{-- Si NO es el primer grupo, cerrar el subtotal del grupo anterior --}}
                    @if($grupoActual !== null)
                        <tr class="subtotal-row">
                            <td colspan="6" class="right">
                                Subtotal {{ $grupoActual }} ({{ $contadorGrupo }} artículos):
                            </td>
                            <td class="right">Bs. {{ number_format($subtotalGrupo, 2) }}</td>
                        </tr>
                    @endif

                    @php
                        $grupoActual = $art->grupo_id;
                        $subtotalGrupo = 0;
                        $contadorGrupo = 0;
                    @endphp

                    {{-- Fila separadora del grupo nuevo --}}
                    <tr class="grupo-separador">
                        <td colspan="7">
                            {{ $art->grupo_id }} — {{ $art->grupo->nombre ?? '' }}
                        </td>
                    </tr>
                @endif

                @php
                    $totalGeneral += $valorTotal;
                    $subtotalGrupo += $valorTotal;
                    $contadorGrupo++;
                @endphp

                <tr>
                    <td class="codigo">{{ $art->codigo }}</td>
                    <td>{{ $art->nombre }}</td>
                    <td>{{ $art->grupo_id }}</td>
                    <td>{{ $art->unidad }}</td>
                    <td class="right">
                        @if(isset($preciosPorArticulo[$art->id]) && count($preciosPorArticulo[$art->id]) > 1)
                            @foreach($preciosPorArticulo[$art->id] as $p)
                                <div style="font-size: 9.5px; line-height: 1.2;">{{ number_format($p['cantidad'], 3) }}</div>
                            @endforeach
                        @else
                            {{ number_format($art->cantidad, 3) }}
                        @endif
                    </td>
                    <td class="right">
                        @if(isset($preciosPorArticulo[$art->id]) && count($preciosPorArticulo[$art->id]) > 1)
                            @foreach($preciosPorArticulo[$art->id] as $p)
                                <div style="font-size: 9.5px; line-height: 1.2;">{{ number_format($p['precio'], 2) }}</div>
                            @endforeach
                        @else
                            {{ number_format($art->precio, 2) }}
                        @endif
                    </td>
                    <td class="right">
                        @if(isset($preciosPorArticulo[$art->id]) && count($preciosPorArticulo[$art->id]) > 1)
                            @php $totValItem = 0; @endphp
                            @foreach($preciosPorArticulo[$art->id] as $p)
                                <div style="font-size: 9.5px; line-height: 1.2;">{{ number_format($p['precio'] * $p['cantidad'], 2) }}</div>
                            @endforeach
                        @else
                            {{ number_format($valorTotal, 2) }}
                        @endif
                    </td>
                </tr>
            @endforeach

            {{-- Subtotal del último grupo --}}
            @if($grupoActual !== null)
                <tr class="subtotal-row">
                    <td colspan="6" class="right">
                        Subtotal {{ $grupoActual }} ({{ $contadorGrupo }} artículos):
                    </td>
                    <td class="right">Bs. {{ number_format($subtotalGrupo, 2) }}</td>
                </tr>
            @endif

            {{-- Total general --}}
            <tr class="total-row">
                <td colspan="6" class="right">VALOR TOTAL DEL INVENTARIO:</td>
                <td class="right">Bs. {{ number_format($totalGeneral, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Sistema de Gestión de Inventario — Sección Catalina, Empresa Minera Torrez S.R.L.
    </div>
</body>
</html>