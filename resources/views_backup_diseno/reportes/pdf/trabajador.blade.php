<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Historial de {{ $trabajador->nombre }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        h1 { color: #667eea; font-size: 18px; margin-bottom: 5px; }
        .subtitulo { color: #666; font-size: 10px; margin-bottom: 15px; }

        .trabajador-info {
            background: #fff8e1;
            border-left: 4px solid #f5af19;
            padding: 12px 15px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .trabajador-info h3 { color: #7f6b0d; margin: 0 0 6px 0; font-size: 14px; }
        .trabajador-info .meta { font-size: 10px; color: #555; line-height: 1.5; }

        table { width: 100%; border-collapse: collapse; }
        th {
            background: #667eea; color: white;
            padding: 7px 5px; text-align: left; font-size: 10px;
        }
        td { padding: 6px 5px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background: #f9f9f9; }
        .codigo { font-weight: bold; color: #667eea; font-family: monospace; }
        .right { text-align: right; }
        .footer { margin-top: 15px; font-size: 9px; color: #999; text-align: center; }

        .resumen {
            background: #f0e9ff;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 12px;
            font-size: 11px;
        }
        .resumen strong { color: #667eea; font-size: 14px; }
    </style>
</head>
<body>
    <h1>Historial de Trabajador</h1>
    <div class="subtitulo">
        Santa Catalina — Mina Tres Amigos<br>
        Generado: {{ now()->format('d/m/Y H:i') }}
    </div>

    {{-- INFO DEL TRABAJADOR --}}
    <div class="trabajador-info">
        <h3>{{ $trabajador->nombre }}</h3>
        <div class="meta">
            <strong>CI:</strong> {{ $trabajador->ci }} &nbsp;|&nbsp;
            <strong>Cargo:</strong> {{ $trabajador->cargo ?? 'No especificado' }} &nbsp;|&nbsp;
            <strong>Teléfono:</strong> {{ $trabajador->telefono ?? '—' }} &nbsp;|&nbsp;
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
                    <th>Fecha</th>
                    <th>Código</th>
                    <th>Artículo</th>
                    <th class="right">Cantidad</th>
                    <th>Unidad</th>
                    <th>Notas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($movimientos as $mov)
                    <tr>
                        <td>{{ $mov->fecha->format('d/m/Y') }}</td>
                        <td class="codigo">{{ $mov->articulo->codigo }}</td>
                        <td>{{ $mov->articulo->nombre }}</td>
                        <td class="right">{{ number_format($mov->cantidad, 2) }}</td>
                        <td>{{ $mov->articulo->unidad }}</td>
                        <td>{{ $mov->notas ?? '—' }}</td>
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