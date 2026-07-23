<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Clasificación de Activos</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #2d3748; }
        h1 { color: #1e293b; font-size: 18px; margin-bottom: 5px; }
        .subtitulo { color: #666; font-size: 10px; margin-bottom: 20px; }
        
        .section-header {
            background: #f1f5f9;
            padding: 6px 10px;
            font-size: 11px;
            font-weight: bold;
            color: #1e293b;
            margin-top: 25px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .header-consumible { border-left: 4px solid #22c55e; }
        .header-repuesto { border-left: 4px solid #6366f1; }
        .header-equipo { border-left: 4px solid #f97316; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th {
            background: #1e293b; color: white;
            padding: 7px 5px; text-align: left; font-size: 9px;
            text-transform: uppercase;
        }
        td { padding: 5px; border: 1px solid #e2e8f0; }
        tr:nth-child(even) td { background: #f8fafc; }
        
        .codigo { font-weight: bold; color: #d97706; font-family: monospace; }
        .right { text-align: right; }
        .center { text-align: center; }
        
        .total-row td {
            background: #1e293b;
            color: white;
            font-weight: bold;
            font-size: 9px;
            padding: 8px 5px;
        }
        
        .footer {
            margin-top: 30px;
            font-size: 8px;
            color: #94a3b8;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }
    </style>
</head>
<body>
    <table style="width:100%; border:none; margin-bottom:15px;">
        <tr>
            <td style="border:none; width:80px; vertical-align:middle;">
                <img src="{{ public_path('img/logo.png') }}" style="width:75px; height:auto;">
            </td>
            <td style="border:none; vertical-align:middle; padding-left:10px;">
                <h1 style="margin:0;">Reporte de Clasificación de Activos</h1>
                <div class="subtitulo" style="margin-top:3px;">
                    Sección Catalina — Empresa Minera Torrez S.R.L.<br>
                    Generado: {{ now()->format('d/m/Y H:i') }}
                </div>
            </td>
        </tr>
    </table>

    {{-- SECCIÓN: CONSUMIBLES --}}
    @if(!$diarios->isEmpty())
        <div class="section-header header-consumible">
            Consumibles (Salida Definitiva) — {{ $diarios->count() }} artículos
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 100px;">Código</th>
                    <th>Material / Artículo</th>
                    <th>Grupo</th>
                    <th class="center" style="width: 70px;">Unidad</th>
                    <th class="right" style="width: 80px;">Stock Actual</th>
                    <th class="right" style="width: 80px;">Precio Unit.</th>
                    <th class="right" style="width: 90px;">Valor Total</th>
                </tr>
            </thead>
            <tbody>
        @php $totalValorDiario = 0; @endphp
        @foreach($diarios as $art)
            @php
                if (isset($preciosPorArticulo[$art->id]) && count($preciosPorArticulo[$art->id]) > 1) {
                    $valor = collect($preciosPorArticulo[$art->id])->sum(fn($p) => $p['precio'] * $p['cantidad']);
                } else {
                    $valor = $art->precio * $art->cantidad;
                }
                $totalValorDiario += $valor;
            @endphp
            <tr>
                <td class="codigo">{{ $art->codigo }}</td>
                <td style="font-weight: 500;">{{ $art->nombre }}</td>
                <td>{{ $art->grupo?->nombre ?? 'Sin Grupo' }}</td>
                <td class="center">{{ $art->unidad }}</td>
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
                            <div style="font-size: 9.5px; line-height: 1.2;">Bs. {{ number_format($p['precio'], 2) }}</div>
                        @endforeach
                    @else
                        Bs. {{ number_format($art->precio, 2) }}
                    @endif
                </td>
                <td class="right" style="font-weight: 600; color: #1e3a8a;">
                    @if(isset($preciosPorArticulo[$art->id]) && count($preciosPorArticulo[$art->id]) > 1)
                        @foreach($preciosPorArticulo[$art->id] as $p)
                            <div style="font-size: 9.5px; line-height: 1.2;">Bs. {{ number_format($p['precio'] * $p['cantidad'], 2) }}</div>
                        @endforeach
                    @else
                        Bs. {{ number_format($valor, 2) }}
                    @endif
                </td>
            </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="4">TOTAL CONSUMIBLES</td>
            <td class="right">{{ number_format($diarios->sum('cantidad'), 3) }}</td>
            <td></td>
            <td class="right">Bs. {{ number_format($totalValorDiario, 2) }}</td>
        </tr>
    </tbody>
</table>
@endif

{{-- SECCIÓN: REPUESTOS Y RESERVA --}}
@if(!$ocasionales->isEmpty())
<div class="section-header header-repuesto" style="margin-top: 30px;">
    Repuestos y Reserva (Baja Rotación) — {{ $ocasionales->count() }} artículos
</div>
<table>
    <thead>
        <tr>
            <th style="width: 100px;">Código</th>
            <th>Material / Artículo</th>
            <th>Grupo</th>
            <th class="center" style="width: 70px;">Unidad</th>
            <th class="right" style="width: 80px;">Stock Actual</th>
            <th class="right" style="width: 80px;">Precio Unit.</th>
            <th class="right" style="width: 90px;">Valor Total</th>
        </tr>
    </thead>
    <tbody>
        @php $totalValorOcasional = 0; @endphp
        @foreach($ocasionales as $art)
            @php
                if (isset($preciosPorArticulo[$art->id]) && count($preciosPorArticulo[$art->id]) > 1) {
                    $valor = collect($preciosPorArticulo[$art->id])->sum(fn($p) => $p['precio'] * $p['cantidad']);
                } else {
                    $valor = $art->precio * $art->cantidad;
                }
                $totalValorOcasional += $valor;
            @endphp
            <tr>
                <td class="codigo">{{ $art->codigo }}</td>
                <td style="font-weight: 500;">{{ $art->nombre }}</td>
                <td>{{ $art->grupo?->nombre ?? 'Sin Grupo' }}</td>
                <td class="center">{{ $art->unidad }}</td>
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
                            <div style="font-size: 9.5px; line-height: 1.2;">Bs. {{ number_format($p['precio'], 2) }}</div>
                        @endforeach
                    @else
                        Bs. {{ number_format($art->precio, 2) }}
                    @endif
                </td>
                <td class="right" style="font-weight: 600; color: #1e3a8a;">
                    @if(isset($preciosPorArticulo[$art->id]) && count($preciosPorArticulo[$art->id]) > 1)
                        @foreach($preciosPorArticulo[$art->id] as $p)
                            <div style="font-size: 9.5px; line-height: 1.2;">Bs. {{ number_format($p['precio'] * $p['cantidad'], 2) }}</div>
                        @endforeach
                    @else
                        Bs. {{ number_format($valor, 2) }}
                    @endif
                </td>
            </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="4">TOTAL REPUESTOS Y RESERVA</td>
            <td class="right">{{ number_format($ocasionales->sum('cantidad'), 3) }}</td>
            <td></td>
            <td class="right">Bs. {{ number_format($totalValorOcasional, 2) }}</td>
        </tr>
    </tbody>
</table>
@endif

{{-- SECCIÓN: EQUIPOS Y HERRAMIENTAS --}}
@if(!$prestamos->isEmpty())
<div class="section-header header-equipo" style="margin-top: 30px;">
    Equipos y Herramientas (Devoluciones/Préstamos) — {{ $prestamos->count() }} artículos
</div>
<table>
    <thead>
        <tr>
            <th style="width: 100px;">Código</th>
            <th>Material / Artículo</th>
            <th>Grupo</th>
            <th class="center" style="width: 70px;">Unidad</th>
            <th class="right" style="width: 80px;">Stock Actual</th>
            <th class="right" style="width: 80px;">Precio Unit.</th>
            <th class="right" style="width: 90px;">Valor Total</th>
        </tr>
    </thead>
    <tbody>
        @php $totalValorPrestamo = 0; @endphp
        @foreach($prestamos as $art)
            @php
                if (isset($preciosPorArticulo[$art->id]) && count($preciosPorArticulo[$art->id]) > 1) {
                    $valor = collect($preciosPorArticulo[$art->id])->sum(fn($p) => $p['precio'] * $p['cantidad']);
                } else {
                    $valor = $art->precio * $art->cantidad;
                }
                $totalValorPrestamo += $valor;
            @endphp
            <tr>
                <td class="codigo">{{ $art->codigo }}</td>
                <td style="font-weight: 500;">{{ $art->nombre }}</td>
                <td>{{ $art->grupo?->nombre ?? 'Sin Grupo' }}</td>
                <td class="center">{{ $art->unidad }}</td>
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
                            <div style="font-size: 9.5px; line-height: 1.2;">Bs. {{ number_format($p['precio'], 2) }}</div>
                        @endforeach
                    @else
                        Bs. {{ number_format($art->precio, 2) }}
                    @endif
                </td>
                <td class="right" style="font-weight: 600; color: #1e3a8a;">
                    @if(isset($preciosPorArticulo[$art->id]) && count($preciosPorArticulo[$art->id]) > 1)
                        @foreach($preciosPorArticulo[$art->id] as $p)
                            <div style="font-size: 9.5px; line-height: 1.2;">Bs. {{ number_format($p['precio'] * $p['cantidad'], 2) }}</div>
                        @endforeach
                    @else
                        Bs. {{ number_format($valor, 2) }}
                    @endif
                </td>
            </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="4">TOTAL EQUIPOS Y HERRAMIENTAS</td>
            <td class="right">{{ number_format($prestamos->sum('cantidad'), 3) }}</td>
            <td></td>
            <td class="right">Bs. {{ number_format($totalValorPrestamo, 2) }}</td>
        </tr>
    </tbody>
</table>
    @endif

    <div class="footer">
        Sistema de Gestión de Inventario — Sección Catalina, Empresa Minera Torrez S.R.L.
    </div>
</body>
</html>
