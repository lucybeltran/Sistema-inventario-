<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Inventario</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        h1 { color: #667eea; font-size: 18px; margin-bottom: 5px; }
        .subtitulo { color: #666; font-size: 11px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #667eea; color: white; padding: 7px; text-align: left; font-size: 9px; }
        td { padding: 5px 7px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background: #f9f9f9; }
        .codigo { font-weight: bold; color: #667eea; font-family: monospace; }
        .right { text-align: right; }
        .total-row {
            background: #11998e !important;
            color: white;
            font-weight: bold;
        }
        .footer { margin-top: 15px; font-size: 9px; color: #999; text-align: center; }
    </style>
</head>
<body>
    <h1>Reporte de Inventario</h1>
    <div class="subtitulo">
        Santa Catalina — Mina Tres Amigos<br>
        Generado: {{ now()->format('d/m/Y H:i') }} — Total: {{ $articulos->count() }} artículos
    </div>

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
            @php $totalGeneral = 0; @endphp
            @foreach($articulos as $art)
                @php $valorTotal = $art->precio * $art->cantidad; $totalGeneral += $valorTotal; @endphp
                <tr>
                    <td class="codigo">{{ $art->codigo }}</td>
                    <td>{{ $art->nombre }}</td>
                    <td>{{ $art->grupo_id }}</td>
                    <td>{{ $art->unidad }}</td>
                    <td class="right">{{ number_format($art->cantidad, 2) }}</td>
                    <td class="right">{{ number_format($art->precio, 2) }}</td>
                    <td class="right">{{ number_format($valorTotal, 2) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="6" class="right">VALOR TOTAL DEL INVENTARIO:</td>
                <td class="right">Bs. {{ number_format($totalGeneral, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Sistema de Gestión de Inventario — Santa Catalina, Mina Tres Amigos
    </div>
</body>
</html>