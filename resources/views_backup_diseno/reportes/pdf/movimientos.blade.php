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
        th { background: #667eea; color: white; padding: 6px 5px; text-align: left; font-size: 9px; }
        td { padding: 5px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background: #f9f9f9; }
        .codigo { font-weight: bold; color: #667eea; font-family: monospace; }
        .entrada { color: #2b8a3e; font-weight: bold; }
        .salida { color: #862e2e; font-weight: bold; }
        .trabajador { background: #fff3bf; padding: 2px 6px; border-radius: 4px; font-size: 9px; }
        .footer { margin-top: 15px; font-size: 9px; color: #999; text-align: center; }
    </style>
</head>
<body>
    <h1>Reporte de Movimientos</h1>
    <div class="subtitulo">
    Santa Catalina — Mina Tres Amigos<br>
    @if(!empty($desde) || !empty($hasta))
        Período:
        {{ !empty($desde) ? \Carbon\Carbon::parse($desde)->format('d/m/Y') : '...' }}
        al
        {{ !empty($hasta) ? \Carbon\Carbon::parse($hasta)->format('d/m/Y') : '...' }}<br>
    @endif
    @if(!empty($trabajadorFiltro))
        <strong style="color:#7f6b0d;">Filtrado por trabajador: {{ $trabajadorFiltro->nombre }} (CI: {{ $trabajadorFiltro->ci }})</strong><br>
    @endif
    Generado: {{ now()->format('d/m/Y H:i') }} — Total: {{ $movimientos->count() }} movimientos
</div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Código</th>
                <th>Artículo</th>
                <th>Tipo</th>
                <th style="text-align:right;">Cant.</th>
                <th>Unidad</th>
                <th>Entregado a</th>
                <th>Notas</th>
                <th>Registró</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movimientos as $mov)
                <tr>
                    <td>{{ $mov->fecha->format('d/m/Y') }}</td>
                    <td class="codigo">{{ $mov->articulo->codigo }}</td>
                    <td>{{ $mov->articulo->nombre }}</td>
                    <td class="{{ $mov->tipo }}">{{ strtoupper($mov->tipo) }}</td>
                    <td style="text-align:right;">{{ number_format($mov->cantidad, 2) }}</td>
                    <td>{{ $mov->articulo->unidad }}</td>
                    <td>
                        @if($mov->trabajador)
                            <span class="trabajador">{{ $mov->trabajador->nombre }}</span>
                        @else
                            —
                        @endif
                    </td>
                    <td>{{ $mov->notas ?? '—' }}</td>
                    <td>{{ $mov->user->name ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Sistema de Gestión de Inventario — Santa Catalina, Mina Tres Amigos
    </div>
</body>
</html>