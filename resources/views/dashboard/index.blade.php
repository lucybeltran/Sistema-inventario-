@extends('layouts.mina')

@section('titulo', 'Dashboard')

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px 20px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: default;
        position: relative;
        overflow: hidden;
        animation: cardEnter 0.6s ease-out backwards;
    }

    @keyframes cardEnter {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .stat-card:nth-child(1) { animation-delay: 0.0s; }
    .stat-card:nth-child(2) { animation-delay: 0.1s; }
    .stat-card:nth-child(3) { animation-delay: 0.2s; }
    .stat-card:nth-child(4) { animation-delay: 0.3s; }

    .stat-card::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 60%);
        opacity: 0;
        transition: opacity 0.4s;
    }

    .stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.5);
    }

    .stat-card:hover::after {
        opacity: 1;
    }

    .stat-card h3 {
        font-size: 14px;
        opacity: 0.9;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        position: relative;
        z-index: 1;
    }

    .stat-card .number {
        font-size: 42px;
        font-weight: bold;
        position: relative;
        z-index: 1;
        text-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    /* Variaciones de color por tarjeta */
    .stat-card.green { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); box-shadow: 0 5px 15px rgba(17, 153, 142, 0.3); }
    .stat-card.green:hover { box-shadow: 0 15px 35px rgba(17, 153, 142, 0.5); }

    .stat-card.orange { background: linear-gradient(135deg, #f12711 0%, #f5af19 100%); box-shadow: 0 5px 15px rgba(241, 39, 17, 0.3); }
    .stat-card.orange:hover { box-shadow: 0 15px 35px rgba(241, 39, 17, 0.5); }

    .stat-card.blue { background: linear-gradient(135deg, #2980b9 0%, #6dd5fa 100%); box-shadow: 0 5px 15px rgba(41, 128, 185, 0.3); }
    .stat-card.blue:hover { box-shadow: 0 15px 35px rgba(41, 128, 185, 0.5); }

    /* SECCIÓN */
    .section {
        background: #f8f9fa;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 20px;
        animation: fadeIn 0.6s 0.4s ease-out backwards;
    }

    .section-header {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 18px;
        color: #333;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-header i { color: #667eea; }

    /* TABLA */
    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    table th {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 14px 12px;
        text-align: left;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #e0e0e0;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    table td {
        padding: 14px 12px;
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.2s;
    }

    table tbody tr {
        transition: all 0.2s;
    }

    table tbody tr:hover {
        background: #f8f9fa;
        transform: scale(1.005);
    }

    table tbody tr:last-child td { border-bottom: none; }

    /* BADGES */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-success {
        background: #d3f9d8;
        color: #2b8a3e;
    }

    .badge-danger {
        background: #ffe3e3;
        color: #862e2e;
    }

    /* EMPTY STATE */
    .empty {
        text-align: center;
        padding: 50px 20px;
        color: #999;
    }

    .empty i {
        font-size: 64px;
        opacity: 0.3;
        margin-bottom: 15px;
        animation: floatIcon 3s ease-in-out infinite;
    }

    @keyframes floatIcon {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .empty p { font-size: 15px; }
</style>
@endpush

@section('contenido')

    {{-- TARJETAS DE ESTADÍSTICAS --}}
    <div class="stats-grid">
        <div class="stat-card">
            <h3><i class="fas fa-boxes"></i> Total Artículos</h3>
            <div class="number">{{ $totalArticulos }}</div>
        </div>
        <div class="stat-card blue">
            <h3><i class="fas fa-exchange-alt"></i> Movimientos</h3>
            <div class="number">{{ $totalMovimientos }}</div>
        </div>
        <div class="stat-card green">
            <h3><i class="fas fa-arrow-down"></i> Entradas Hoy</h3>
            <div class="number">{{ $entradasHoy }}</div>
        </div>
        <div class="stat-card orange">
            <h3><i class="fas fa-arrow-up"></i> Salidas Hoy</h3>
            <div class="number">{{ $salidasHoy }}</div>
        </div>
    </div>

    {{-- ÚLTIMOS MOVIMIENTOS --}}
    <div class="section">
        <div class="section-header">
            <i class="fas fa-history"></i> Últimos Movimientos
        </div>

        @if ($ultimosMovimientos->isEmpty())
            <div class="empty">
                <i class="fas fa-inbox"></i>
                <p>No hay movimientos registrados todavía.</p>
                <p style="font-size:13px; margin-top:8px;">
                    Cuando registres entradas o salidas, aparecerán aquí.
                </p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Código</th>
                        <th>Artículo</th>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Entregado a</th>
                        <th>Registró</th>
                    </tr>
                </thead>
               @foreach ($ultimosMovimientos as $mov)
    <tr>
        <td>{{ $mov->fecha->format('d/m/Y') }}</td>
        <td><strong>{{ $mov->articulo->codigo }}</strong></td>
        <td>{{ $mov->articulo->nombre }}</td>
        <td>
            @if ($mov->tipo === 'entrada')
                <span class="badge badge-success">
                    <i class="fas fa-arrow-down"></i> Entrada
                </span>
            @else
                <span class="badge badge-danger">
                    <i class="fas fa-arrow-up"></i> Salida
                </span>
            @endif
        </td>
        <td>
            <strong>{{ number_format($mov->cantidad, 2) }}</strong>
            <span style="color:#999; font-size:12px;">{{ $mov->articulo->unidad }}</span>
        </td>
        <td>
            @if($mov->trabajador)
                <span style="background:#fff3bf; color:#7f6b0d; padding:3px 10px; border-radius:10px; font-size:12px;">
                    <i class="fas fa-hard-hat"></i> {{ $mov->trabajador->nombre }}
                </span>
            @else
                <span style="color:#bbb;">—</span>
            @endif
        </td>
        <td style="font-size:13px;">{{ $mov->user?->name ?? '-' }}</td>
    </tr>
@endforeach
                </tbody>
            </table>
        @endif
    </div>

@endsection