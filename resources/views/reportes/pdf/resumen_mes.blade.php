<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Resumen Mensual</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1e293b; }
        h1 { font-size: 18px; margin: 0 0 3px 0; color: #1e293b; }
        .subtitulo { color: #64748b; font-size: 10px; margin-bottom: 0; }
        .kpi-grid { width: 100%; border-collapse: collapse; margin: 12px 0; }
        .kpi-grid td { width: 25%; padding: 4px 6px; border: none; text-align: center; }
        .kpi-box { background: #f1f5f9; border-radius: 5px; padding: 7px 5px; border-left: 3px solid #3b82f6; }
        .kpi-box.verde { border-left-color: #16a34a; }
        .kpi-box.rojo  { border-left-color: #dc2626; }
        .kpi-box.naranja { border-left-color: #d97706; }
        .kpi-label { font-size: 8px; color: #64748b; text-transform: uppercase; margin-bottom: 3px; }
        .kpi-value { font-size: 13px; font-weight: bold; color: #1e293b; }
        .sec-title { background: #1e293b; color: white; font-size: 10px; font-weight: bold; padding: 5px 10px; margin: 12px 0 0 0; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 0; }
        table.data th { background: #334155; color: white; padding: 5px 7px; font-size: 9px; text-align: left; }
        table.data td { padding: 4px 7px; border-bottom: 1px solid #e2e8f0; font-size: 9px; }
        table.data tr:nth-child(even) td { background: #f8fafc; }
        .right { text-align: right; }
        .center { text-align: center; }
        .verde-txt { color: #16a34a; font-weight: bold; }
        .rojo-txt  { color: #dc2626; font-weight: bold; }
        .badge { background: #e2e8f0; border-radius: 3px; padding: 1px 5px; font-size: 8px; font-weight: bold; }
        .rank { background: #1e293b; color: white; border-radius: 3px; padding: 1px 5px; font-size: 8px; font-weight: bold; }
        .comp-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 5px; padding: 7px 12px; margin: 12px 0 0 0; font-size: 9px; }
        .agotado-badge { background: #fee2e2; color: #dc2626; border-radius: 3px; padding: 2px 6px; font-size: 8px; font-weight: bold; margin: 2px; display: inline-block; }
        .footer { margin-top: 14px; font-size: 8px; color: #94a3b8; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 7px; }
    </style>
</head>
<body>

<table style="width:100%; border:none; margin-bottom:8px;">
    <tr>
        <td style="border:none; width:70px; vertical-align:middle;">
            <img src="{{ public_path('img/logo.png') }}" style="width:65px; height:auto;">
        </td>
        <td style="border:none; vertical-align:middle; padding-left:10px;">
            <h1>Resumen Mensual &mdash; {{ strtoupper($nombreMes) }}</h1>
            <div class="subtitulo">
                Sección Catalina — Empresa Minera Torrez S.R.L.<br>
                Período: {{ $inicio->format('d/m/Y') }} al {{ $fin->format('d/m/Y') }} &nbsp;|&nbsp; Generado: {{ now()->format('d/m/Y H:i') }}
            </div>
        </td>
    </tr>
</table>

{{-- KPIs --}}
<table class="kpi-grid">
    <tr>
        <td><div class="kpi-box"><div class="kpi-label">Total Movimientos</div><div class="kpi-value">{{ $totalMovs }}</div></div></td>
        <td><div class="kpi-box rojo"><div class="kpi-label">Valor Salidas</div><div class="kpi-value">Bs. {{ number_format($valorSalidas, 2) }}</div></div></td>
        <td><div class="kpi-box verde"><div class="kpi-label">Valor Entradas</div><div class="kpi-value">Bs. {{ number_format($valorEntradas, 2) }}</div></div></td>
        <td>
            @php $neto = $valorEntradas - $valorSalidas; @endphp
            <div class="kpi-box naranja">
                <div class="kpi-label">Balance Neto</div>
                <div class="kpi-value" style="color:{{ $neto >= 0 ? '#16a34a' : '#dc2626' }};">Bs. {{ number_format($neto, 2) }}</div>
            </div>
        </td>
    </tr>
</table>

{{-- Comparativa --}}
<div class="comp-box">
    @php $signo = $variacionPorc >= 0 ? '+' : ''; @endphp
    <strong>Comparativa vs mes anterior:</strong>
    {{ $totalMovsPrev }} movimientos el mes anterior &rarr;
    <span class="{{ $variacionPorc >= 0 ? 'verde-txt' : 'rojo-txt' }}">{{ $signo }}{{ $variacionPorc }}%</span>
    {{ $variacionPorc >= 0 ? '&uarr; Más actividad' : '&darr; Menos actividad' }}
</div>

{{-- Top 5 Materiales --}}
<div class="sec-title">Top 5 Materiales Más Consumidos</div>
@if($topMateriales->isNotEmpty())
<table class="data">
    <thead><tr><th class="center" style="width:25px;">#</th><th>Artículo</th><th>Código</th><th class="center">Unidad</th><th class="right">Total Salida</th></tr></thead>
    <tbody>
        @foreach($topMateriales as $i => $m)
        <tr>
            <td class="center"><span class="rank">{{ $i+1 }}</span></td>
            <td>{{ $m->nombre }}</td>
            <td style="font-family:monospace; color:#d97706;">{{ $m->codigo }}</td>
            <td class="center"><span class="badge">{{ $m->unidad }}</span></td>
            <td class="right verde-txt">{{ number_format($m->total_salida, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p style="color:#94a3b8; padding:6px 10px; font-size:9px;">Sin datos de salidas este mes.</p>
@endif

{{-- Top 5 Trabajadores --}}
<div class="sec-title">Top 5 Trabajadores / Contratistas</div>
@if($topTrabajadores->isNotEmpty())
<table class="data">
    <thead><tr><th class="center" style="width:25px;">#</th><th>Nombre</th><th>Código</th><th class="center">N° Salidas</th><th class="right">Valor Recibido (Bs.)</th></tr></thead>
    <tbody>
        @foreach($topTrabajadores as $i => $t)
        <tr>
            <td class="center"><span class="rank">{{ $i+1 }}</span></td>
            <td>{{ $t->nombre }}</td>
            <td style="font-family:monospace;">{{ $t->codigo ?? '—' }}</td>
            <td class="center"><strong>{{ $t->total_movs }}</strong></td>
            <td class="right rojo-txt">Bs. {{ number_format($t->valor_total, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p style="color:#94a3b8; padding:6px 10px; font-size:9px;">Sin entregas registradas a trabajadores.</p>
@endif

{{-- Agotados --}}
@if($articulosAgotados->isNotEmpty())
<div class="sec-title">Artículos Agotados este Mes</div>
<div style="padding:7px 10px; background:#fef2f2; border:1px solid #fecaca; margin-top:0;">
    @foreach($articulosAgotados as $a)
        <span class="agotado-badge">{{ $a->codigo }} — {{ $a->nombre }}</span>
    @endforeach
</div>
@endif

{{-- Detalle por Unidad --}}
<div class="sec-title">Detalle por Unidad de Medida</div>
@if($resumenUnidad->isNotEmpty())
<table class="data">
    <thead><tr><th>Unidad</th><th class="right">Entradas</th><th class="right">Salidas</th><th class="right">Neto</th></tr></thead>
    <tbody>
        @foreach($resumenUnidad as $u)
        @php $netoU = $u->entradas - $u->salidas; @endphp
        <tr>
            <td><span class="badge">{{ $u->unidad }}</span></td>
            <td class="right verde-txt">{{ number_format($u->entradas, 2) }}</td>
            <td class="right rojo-txt">{{ number_format($u->salidas, 2) }}</td>
            <td class="right" style="color:{{ $netoU >= 0 ? '#16a34a' : '#dc2626' }};font-weight:bold;">{{ number_format($netoU, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p style="color:#94a3b8; padding:6px 10px; font-size:9px;">Sin movimientos en este período.</p>
@endif

<div class="footer">Sistema de Gestión de Inventario — Sección Catalina, Empresa Minera Torrez S.R.L.</div>
</body>
</html>
