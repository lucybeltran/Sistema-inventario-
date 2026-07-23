<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
     <title>Historial de {{ $trabajador->nombre }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; }
        h1 { color: #1e293b; font-size: 18px; margin-bottom: 5px; }
        .subtitulo { color: #666; font-size: 10px; margin-bottom: 15px; }

        .trabajador-info {
            background: #f8fafc;
            border-left: 4px solid #d97706;
            padding: 12px 15px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
        }
        .trabajador-info h3 { color: #1e293b; margin: 0 0 6px 0; font-size: 14px; }
        .trabajador-info .meta { font-size: 10px; color: #555; line-height: 1.5; }

        table { width: 100%; border-collapse: collapse; }
        th {
            background: #1e293b; color: white;
            padding: 7px 5px; text-align: left; font-size: 9.5px;
        }
        td { padding: 6px 5px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background: #f8fafc; }
        .codigo { font-weight: bold; color: #d97706; font-family: monospace; }
        .right { text-align: right; }
        .footer { margin-top: 15px; font-size: 9px; color: #999; text-align: center; }

        .resumen {
            background: #fef3c7;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 12px;
            font-size: 11px;
            border-left: 4px solid #f59e0b;
        }
        .resumen strong { color: #b45309; font-size: 13px; }
    </style>
</head>
<body>
    <table style="width:100%; border:none; margin-bottom:10px;">
    <tr>
        <td style="border:none; width:80px; vertical-align:middle;">
            <img src="{{ public_path('img/logo.png') }}" style="width:75px; height:auto;">
        </td>
        <td style="border:none; vertical-align:middle; padding-left:10px;">
            <h1 style="margin:0;">Historial del Contratista</h1>
            <div class="subtitulo" style="margin-top:3px;">
                Sección Catalina — Empresa Minera Torrez S.R.L.<br>
                Generado: {{ now()->format('d/m/Y H:i') }}
            </div>
        </td>
    </tr>
</table>

    {{-- INFO DEL TRABAJADOR --}}
    <div class="trabajador-info">
        <h3>Contratista: {{ $trabajador->nombre }}</h3>
        <div class="meta">
            @if($trabajador->ayudante)
                <strong>Ayudante:</strong> {{ $trabajador->ayudante }} &nbsp;|&nbsp;
            @endif
            <strong>Cargo:</strong> {{ $trabajador->cargo ?? 'No especificado' }} &nbsp;|&nbsp;
            @if($trabajador->nivel)
                <strong>Nivel:</strong> {{ $trabajador->nivel }} &nbsp;|&nbsp;
            @endif
            @if($trabajador->labor)
                <strong>Labor:</strong> {{ $trabajador->labor }} &nbsp;|&nbsp;
            @endif
            @if($trabajador->area_trabajo)
                <strong>Ubicación:</strong> {{ $trabajador->area_trabajo }} &nbsp;|&nbsp;
            @endif
            <strong>Estado:</strong> {{ $trabajador->activo ? 'ACTIVO' : 'INACTIVO' }}
        </div>
    </div>

    {{-- RESUMEN --}}
    <div class="resumen">
        @if(!empty($desde) || !empty($hasta))
            <strong>Período:</strong>
            {{ !empty($desde) ? \Carbon\Carbon::parse($desde)->format('d/m/Y') : '...' }}
            al
            {{ !empty($hasta) ? \Carbon\Carbon::parse($hasta)->format('d/m/Y') : '...' }}
            <br>
        @endif
        <strong>Total de salidas en el período:</strong> {{ $movimientos->count() }}
    </div>

    {{-- TABLA DE SALIDAS --}}
    @if($movimientos->isEmpty())
        <p style="text-align:center; color:#999; padding:30px;">
            No hay salidas registradas para este trabajador en el período seleccionado.
        </p>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width: 100px;">Fecha y Hora</th>
                    <th style="width: 70px;">Código</th>
                    <th>Artículo</th>
                    <th class="right" style="width: 60px;">Cantidad</th>
                    <th style="width: 50px;">Unidad</th>
                    <th class="right" style="width: 75px;">P. Unitario</th>
                    <th class="right" style="width: 75px;">Precio Total</th>
                    <th style="width: 100px;">Notas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($movimientos as $mov)
                    @php
                        $pUnitario = $mov->precio_unitario ?? $mov->articulo->precio ?? 0;
                        $pTotal = $mov->cantidad * $pUnitario;
                    @endphp
                    <tr>
                         <td>
                            {{ $mov->created_at->format('d/m/Y H:i') }}
                            @if($mov->turno)
                                <div style="font-size: 8px; color: #555; margin-top: 2px;">Turno: {{ $mov->turno }}</div>
                            @endif
                            @if($mov->entregado_por)
                                <div style="font-size: 8px; color: #555;">Entregó: {{ $mov->entregado_por }}</div>
                            @endif
                        </td>
                        <td class="codigo">{{ $mov->articulo->codigo }}</td>
                        <td>{{ $mov->articulo->nombre }}</td>
                        <td class="right">{{ number_format($mov->cantidad, 2) }}</td>
                        <td>{{ $mov->articulo->unidad }}</td>
                        <td class="right">Bs. {{ number_format($pUnitario, 2) }}</td>
                        <td class="right" style="font-weight: bold;">Bs. {{ number_format($pTotal, 2) }}</td>
                        <td>{{ $mov->notas ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                @php
                    $totalCant = $movimientos->sum('cantidad');
                    $totalGastado = $movimientos->sum(fn($m) => ($m->precio_unitario ?? $m->articulo->precio ?? 0) * $m->cantidad);
                @endphp
                <tr style="background: #1e293b; color: white; font-weight: bold;">
                    <td colspan="3" style="text-align: right; padding: 7px 5px; border: 1px solid #1e293b;">TOTALES:</td>
                    <td class="right" style="padding: 7px 5px; border: 1px solid #1e293b;">{{ number_format($totalCant, 2) }}</td>
                    <td colspan="2" style="padding: 7px 5px; border: 1px solid #1e293b;"></td>
                    <td class="right" style="padding: 7px 5px; border: 1px solid #1e293b;">Bs. {{ number_format($totalGastado, 2) }}</td>
                    <td style="padding: 7px 5px; border: 1px solid #1e293b;"></td>
                </tr>
            </tfoot>
        </table>
    @endif

    <div class="footer">
        Sistema de Gestión de Inventario — Sección Catalina, Empresa Minera Torrez S.R.L.
    </div>
</body>
</html>