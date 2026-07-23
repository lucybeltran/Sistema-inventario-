@extends('layouts.mina')

@section('titulo', 'Dashboard')

@push('styles')
<style>
    /* ─────────────────────────────────────────────
       GRID DE ESTADÍSTICAS PRINCIPALES
    ───────────────────────────────────────────── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 18px;
        margin-bottom: 28px;
    }

    .stat-card {
        position: relative;
        padding: 28px 24px 22px;
        border-radius: 20px;
        color: white;
        overflow: hidden;
        cursor: default;
        animation: cardEnter 0.55s cubic-bezier(0.16, 1, 0.3, 1) backwards;
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s ease;
        border: 1px solid rgba(255,255,255,0.15);
    }

    /* Staggered animation delays */
    .stat-card:nth-child(1) { animation-delay: 0.00s; }
    .stat-card:nth-child(2) { animation-delay: 0.08s; }
    .stat-card:nth-child(3) { animation-delay: 0.16s; }
    .stat-card:nth-child(4) { animation-delay: 0.24s; }

    @keyframes cardEnter {
        from { opacity: 0; transform: translateY(24px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* Sheen layer */
    .stat-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.18) 0%, transparent 55%);
        pointer-events: none;
    }

    /* Decorative circle */
    .stat-card::after {
        content: '';
        position: absolute;
        bottom: -28px; right: -28px;
        width: 130px; height: 130px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
        pointer-events: none;
        transition: transform 0.4s ease;
    }

    .stat-card:hover { transform: translateY(-7px); }
    .stat-card:hover::after { transform: scale(1.25); }

    /* Colour variants */
    .stat-card.amber {
        background: linear-gradient(135deg, #b45309 0%, #d97706 45%, #f59e0b 100%);
        box-shadow: 0 8px 28px rgba(180,83,9,0.38), 0 2px 8px rgba(0,0,0,0.15);
    }
    .stat-card.amber:hover {
        box-shadow: 0 18px 48px rgba(180,83,9,0.52), 0 4px 14px rgba(0,0,0,0.2);
    }

    .stat-card.blue {
        background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 55%, #60a5fa 100%);
        box-shadow: 0 8px 28px rgba(29,78,216,0.38), 0 2px 8px rgba(0,0,0,0.15);
    }
    .stat-card.blue:hover {
        box-shadow: 0 18px 48px rgba(29,78,216,0.52), 0 4px 14px rgba(0,0,0,0.2);
    }

    .stat-card.green {
        background: linear-gradient(135deg, #065f46 0%, #059669 55%, #10b981 100%);
        box-shadow: 0 8px 28px rgba(6,95,70,0.38), 0 2px 8px rgba(0,0,0,0.15);
    }
    .stat-card.green:hover {
        box-shadow: 0 18px 48px rgba(6,95,70,0.52), 0 4px 14px rgba(0,0,0,0.2);
    }

    .stat-card.red {
        background: linear-gradient(135deg, #9b1c1c 0%, #dc2626 55%, #ef4444 100%);
        box-shadow: 0 8px 28px rgba(155,28,28,0.38), 0 2px 8px rgba(0,0,0,0.15);
    }
    .stat-card.red:hover {
        box-shadow: 0 18px 48px rgba(155,28,28,0.52), 0 4px 14px rgba(0,0,0,0.2);
    }

    .stat-card-icon {
        position: relative;
        z-index: 1;
        width: 46px; height: 46px;
        background: rgba(255,255,255,0.18);
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
        margin-bottom: 16px;
        backdrop-filter: blur(4px);
    }

    .stat-card-label {
        position: relative;
        z-index: 1;
        font-size: 12.5px;
        font-weight: 600;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        opacity: 0.85;
        margin-bottom: 6px;
    }

    .stat-card-number {
        position: relative;
        z-index: 1;
        font-size: 46px;
        font-weight: 800;
        line-height: 1;
        letter-spacing: -2px;
        text-shadow: 0 2px 12px rgba(0,0,0,0.18);
    }

    .stat-card-sub {
        position: relative;
        z-index: 1;
        font-size: 12px;
        opacity: 0.7;
        margin-top: 8px;
        font-weight: 500;
    }

    /* ─────────────────────────────────────────────
       SECCIONES GLASSMORPHIC
    ───────────────────────────────────────────── */
    .section {
        background: var(--bg-card);
        backdrop-filter: blur(22px) saturate(160%);
        -webkit-backdrop-filter: blur(22px) saturate(160%);
        border: 1.5px solid var(--border);
        padding: 26px 28px;
        border-radius: 20px;
        margin-bottom: 22px;
        box-shadow: var(--shadow);
        animation: cardEnter 0.6s cubic-bezier(0.16, 1, 0.3, 1) backwards;
        transition: all 0.32s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .section:hover {
        box-shadow: var(--shadow-lg);
        border-color: var(--border-strong);
        transform: translateY(-2px);
    }

    .section-header {
        font-size: 16px;
        font-weight: 800;
        margin-bottom: 20px;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: -0.02em;
        padding-bottom: 14px;
        border-bottom: 1px solid var(--border-light);
    }

    .section-header-icon {
        width: 36px; height: 36px;
        background: var(--gradient);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        color: white;
        font-size: 15px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(180,83,9,0.3);
    }

    /* ─────────────────────────────────────────────
       TABLA PREMIUM
    ───────────────────────────────────────────── */
    .premium-table-wrap {
        overflow: hidden;
        border-radius: 14px;
        border: 1px solid var(--border);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: transparent;
    }

    table thead tr {
        background: var(--bg-hover);
    }

    table th {
        padding: 13px 16px;
        text-align: left;
        font-weight: 700;
        color: var(--text-muted);
        border-bottom: 1.5px solid var(--border);
        font-size: 11.5px;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    table td {
        padding: 13px 16px;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-secondary);
        font-size: 14px;
        vertical-align: middle;
    }

    table tbody tr {
        transition: all 0.18s ease;
    }

    table tbody tr:hover {
        background: var(--bg-hover);
    }

    table tbody tr:last-child td { border-bottom: none; }

    /* ─────────────────────────────────────────────
       BADGES PREMIUM
    ───────────────────────────────────────────── */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 11px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    .badge-success {
        background: rgba(5, 150, 105, 0.12);
        color: #059669;
        border: 1px solid rgba(5, 150, 105, 0.2);
    }

    .badge-danger {
        background: rgba(229, 62, 62, 0.1);
        color: var(--danger);
        border: 1px solid rgba(229, 62, 62, 0.18);
    }

    /* ─────────────────────────────────────────────
       ALERTAS DE STOCK BAJO
    ───────────────────────────────────────────── */
    .stock-alert-card {
        background: var(--bg-input);
        padding: 16px 18px;
        border-radius: 14px;
        border: 1.5px solid rgba(229, 62, 62, 0.16);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        transition: all 0.25s ease;
    }
    .stock-alert-card:hover {
        border-color: rgba(229,62,62,0.35);
        transform: translateX(3px);
    }

    .stock-qty {
        font-size: 18px;
        font-weight: 800;
        color: var(--danger);
        letter-spacing: -0.5px;
        line-height: 1;
    }

    .stock-reorder-pill {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: var(--danger);
        background: rgba(229,62,62,0.1);
        border: 1px solid rgba(229,62,62,0.18);
        padding: 3px 8px;
        border-radius: 6px;
        margin-top: 5px;
        display: inline-block;
    }

    /* ─────────────────────────────────────────────
       EMPTY STATE
    ───────────────────────────────────────────── */
    .empty {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }

    .empty i {
        font-size: 52px;
        opacity: 0.25;
        margin-bottom: 18px;
        display: block;
        animation: floatIcon 3.5s ease-in-out infinite;
    }

    @keyframes floatIcon {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .empty p {
        font-size: 15px;
        font-weight: 500;
    }

    /* ─────────────────────────────────────────────
       TRABAJADOR BADGE EN TABLA
    ───────────────────────────────────────────── */
    .worker-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: rgba(217,119,6,0.1);
        color: var(--primary);
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid rgba(217,119,6,0.18);
    }
</style>
@endpush

@section('contenido')

    {{-- ═══ TARJETAS DE ESTADÍSTICAS ═══ --}}
    <div class="stats-grid">

        <div class="stat-card amber">
            <div class="stat-card-icon"><i class="fas fa-boxes"></i></div>
            <div class="stat-card-label">Total Artículos</div>
            <div class="stat-card-number">{{ $totalArticulos }}</div>
            <div class="stat-card-sub">en inventario activo</div>
        </div>

        <div class="stat-card blue">
            <div class="stat-card-icon"><i class="fas fa-exchange-alt"></i></div>
            <div class="stat-card-label">Movimientos (Mes)</div>
            <div class="stat-card-number">{{ $totalMovimientos }}</div>
            <div class="stat-card-sub">registros de este mes</div>
        </div>

        <div class="stat-card green">
            <div class="stat-card-icon"><i class="fas fa-arrow-down"></i></div>
            <div class="stat-card-label">Entradas Hoy</div>
            <div class="stat-card-number">{{ $entradasHoy }}</div>
            <div class="stat-card-sub">ingresos del día</div>
        </div>

        <div class="stat-card red">
            <div class="stat-card-icon"><i class="fas fa-arrow-up"></i></div>
            <div class="stat-card-label">Salidas Hoy</div>
            <div class="stat-card-number">{{ $salidasHoy }}</div>
            <div class="stat-card-sub">despachos del día</div>
        </div>

    </div>

    {{-- ═══ ALERTAS DE STOCK BAJO ═══ --}}
    @if ($articulosBajoStock->isNotEmpty())
        <div class="section" style="border-left: 4px solid var(--danger); background: rgba(229,62,62,0.04); animation-delay: 0.32s;">
            <div class="section-header" style="color: var(--danger); border-bottom-color: rgba(229,62,62,0.14);">
                <div class="section-header-icon" style="background: linear-gradient(135deg,#9b1c1c,#ef4444); box-shadow: 0 4px 12px rgba(229,62,62,0.3);">
                    <i class="fas fa-triangle-exclamation"></i>
                </div>
                Alertas de Stock Bajo
                <span style="margin-left:auto; font-size:12px; font-weight:600; color:var(--danger); background:rgba(229,62,62,0.1); padding:4px 10px; border-radius:20px; border:1px solid rgba(229,62,62,0.18);">
                    {{ $articulosBajoStock->count() }} artículo{{ $articulosBajoStock->count() > 1 ? 's' : '' }}
                </span>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(270px, 1fr)); gap: 12px;">
                @foreach ($articulosBajoStock as $art)
                    <div class="stock-alert-card">
                        <div style="min-width:0;">
                            <div style="font-size:12px; color:var(--primary); font-weight:700; font-family:monospace; margin-bottom:2px;">{{ $art->codigo }}</div>
                            <div style="font-size:14px; color:var(--text-primary); font-weight:700; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $art->nombre }}</div>
                            <div style="font-size:12px; color:var(--text-muted); margin-top:3px;">Mínimo: <strong>{{ number_format($art->stock_minimo,2) }}</strong> {{ $art->unidad }}</div>
                        </div>
                        <div style="text-align:right; flex-shrink:0;">
                            <div class="stock-qty">{{ number_format($art->cantidad, 2) }}</div>
                            <div style="font-size:11px; color:var(--text-muted);">{{ $art->unidad }}</div>
                            <div class="stock-reorder-pill">Reabastecer</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ═══ GRÁFICOS Y ANALÍTICA ═══ --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; margin-bottom: 22px;">
        <div class="section" style="margin-bottom: 0; animation-delay: 0.36s;">
            <div class="section-header">
                <div class="section-header-icon"><i class="fas fa-chart-line"></i></div>
                Flujo de Almacén <span style="font-size:12px;font-weight:500;color:var(--text-muted);margin-left:6px;">(Bs.)</span>
            </div>
            <div style="position: relative; height: 250px; width: 100%;">
                <canvas id="chartFlujo"></canvas>
            </div>
        </div>
        
        <div class="section" style="margin-bottom: 0; animation-delay: 0.42s;">
            <div class="section-header">
                <div class="section-header-icon"><i class="fas fa-chart-pie"></i></div>
                Top 5 Insumos Consumidos
            </div>
            <div style="position: relative; height: 250px; width: 100%;">
                <canvas id="chartTopConsumos"></canvas>
            </div>
        </div>

        <div class="section" style="margin-bottom: 0; animation-delay: 0.46s; display: flex; flex-direction: column;">
            <div class="section-header" style="border-bottom-color: var(--border-light);">
                <div class="section-header-icon" style="background: linear-gradient(135deg, #4f46e5, #6366f1);"><i class="fas fa-hard-hat"></i></div>
                Top Contratistas (Mayor Consumo)
            </div>
            <div style="flex-grow: 1; display: flex; flex-direction: column; justify-content: center; gap: 14px; padding: 10px 0;">
                @if($topContratistas->isEmpty())
                    <div style="text-align:center; color: var(--text-muted); padding: 40px 0;">
                        <i class="fas fa-user-slash" style="font-size: 38px; opacity:0.25; margin-bottom:10px; display:block;"></i>
                        No hay salidas de material registradas este mes.
                    </div>
                @else
                    @php
                        $maxCantidad = $topContratistas->first()->total_cantidad ?? 1;
                    @endphp
                    @foreach($topContratistas as $index => $tc)
                        @php
                            $pct = $maxCantidad > 0 ? ($tc->total_cantidad / $maxCantidad) * 100 : 0;
                            $color = ['#6366f1', '#a855f7', '#ec4899', '#3b82f6', '#10b981'][$index] ?? '#6366f1';
                        @endphp
                        <div style="display:flex; flex-direction:column; gap:4px;">
                            <div style="display:flex; justify-content:space-between; font-size:13px; font-weight:700;">
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <span style="display:inline-flex; width:20px; height:20px; border-radius:50%; background:rgba(99, 102, 241, 0.12); border: 1px solid rgba(99, 102, 241, 0.2); align-items:center; justify-content:center; font-size:10px; color:#818cf8; font-weight:bold;">
                                        {{ $index + 1 }}
                                    </span>
                                    <span style="color:var(--text-primary);">{{ $tc->trabajador->nombre ?? 'Desconocido' }}</span>
                                    @if($tc->trabajador && $tc->trabajador->codigo)
                                        <span style="font-family:monospace; font-size:11px; color:#818cf8; background:rgba(99, 102, 241, 0.08); padding:1px 5px; border-radius:4px; border:1px solid rgba(99, 102, 241, 0.15);">{{ $tc->trabajador->codigo }}</span>
                                    @endif
                                </div>
                                <div style="margin-left: auto; text-align:right;">
                                    <span style="color:{{ $color }}; font-weight:800;">{{ number_format($tc->total_cantidad, 2) }}</span>
                                    <span style="font-size:10.5px; color:var(--text-muted); font-weight:normal;">({{ $tc->retiros }} retiros)</span>
                                </div>
                            </div>
                            <div style="width:100%; height:6px; background:var(--border-light); border-radius:4px; overflow:hidden;">
                                <div style="width:{{ $pct }}%; height:100%; background:linear-gradient(90deg, {{ $color }}, rgba(255, 255, 255, 0.1)); border-radius:4px;"></div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    {{-- ═══ ÚLTIMOS MOVIMIENTOS ═══ --}}
    <div class="section" style="animation-delay: 0.48s;">
        <div class="section-header">
            <div class="section-header-icon"><i class="fas fa-history"></i></div>
            Últimos Movimientos
        </div>

        @if ($ultimosMovimientos->isEmpty())
            <div class="empty">
                <i class="fas fa-inbox"></i>
                <p>No hay movimientos registrados todavía.</p>
                <p style="font-size:13px; margin-top:6px; color:var(--text-muted);">Cuando registres entradas o salidas, aparecerán aquí.</p>
            </div>
        @else
            <div class="premium-table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Fecha y Hora</th>
                            <th>Código</th>
                            <th>Artículo</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Personal / Entrega</th>
                            <th>Registró</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($ultimosMovimientos as $mov)
                        <tr>
                            <td style="font-size:13px; color:var(--text-muted); white-space:nowrap;">
                                <i class="fas fa-clock" style="color:var(--primary); opacity:0.6; font-size:11px; margin-right:4px;"></i>
                                {{ $mov->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                <span style="font-family:monospace; font-size:13px; font-weight:700; color:var(--primary); background:rgba(217,119,6,0.1); padding:3px 8px; border-radius:6px;">{{ $mov->articulo->codigo }}</span>
                            </td>
                            <td style="font-weight:600; color:var(--text-primary); max-width:180px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $mov->articulo->nombre }}</td>
                            <td>
                                @if ($mov->tipo === 'entrada')
                                    <span class="badge badge-success"><i class="fas fa-arrow-down"></i> Entrada</span>
                                @else
                                    <span class="badge badge-danger"><i class="fas fa-arrow-up"></i> Salida</span>
                                @endif
                            </td>
                            <td>
                                <strong style="font-size:15px; color:var(--text-primary);">{{ $mov->cantidad_formateada }}</strong>
                                <span style="color:var(--text-muted); font-size:12px; margin-left:3px;">{{ $mov->articulo->unidad }}</span>
                            </td>
                            <td>
                                @if($mov->tipo === 'entrada')
                                    <div style="display: flex; flex-direction: column; gap: 4px; align-items: flex-start;">
                                        <span class="worker-badge" style="background: rgba(3,105,161,0.08) !important; color: #0284c7 !important; border-color: rgba(3,105,161,0.18) !important;" title="Entregado por">
                                            <i class="fas fa-truck"></i> {{ $mov->entregado_por ?? '—' }}
                                        </span>
                                        <span class="worker-badge" style="background: rgba(4,120,87,0.08) !important; color: #059669 !important; border-color: rgba(4,120,87,0.18) !important;" title="Recibido por">
                                            <i class="fas fa-user-check"></i> {{ $mov->recibido_por ?? '—' }}
                                        </span>
                                    </div>
                                @else
                                    <div style="display: flex; flex-direction: column; gap: 4px; align-items: flex-start;">
                                        @if($mov->entregado_por)
                                            <span class="worker-badge" style="background: rgba(3,105,161,0.08) !important; color: #0284c7 !important; border-color: rgba(3,105,161,0.18) !important;" title="Entregado por">
                                                <i class="fas fa-user-check"></i> {{ $mov->entregado_por }}
                                            </span>
                                        @endif
                                        @if($mov->trabajador)
                                            <span class="worker-badge" title="Entregado a"><i class="fas fa-hard-hat"></i> {{ $mov->trabajador->nombre }} @if($mov->turno) ({{ $mov->turno }}) @endif</span>
                                        @elseif($mov->trabajador_nombre)
                                            <span class="worker-badge" title="Entregado a"><i class="fas fa-hard-hat"></i> {{ $mov->trabajador_nombre }} @if($mov->turno) ({{ $mov->turno }}) @endif</span>
                                        @else
                                            <span style="color:var(--border-strong); font-size:16px;">—</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td style="font-size:13px; color:var(--text-muted);">{{ $mov->user?->name ?? '—' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const isDark     = document.documentElement.getAttribute('data-theme') === 'dark';
    const textColor  = isDark ? '#a09584' : '#7a6a5a';
    const gridColor  = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.06)';
    const fontFamily = "'Outfit', 'Inter', sans-serif";

    // ── 1. Flujo de Almacén
    const ctxFlujo = document.getElementById('chartFlujo').getContext('2d');

    const gradEntrada = ctxFlujo.createLinearGradient(0, 0, 0, 250);
    gradEntrada.addColorStop(0, 'rgba(16,185,129,0.22)');
    gradEntrada.addColorStop(1, 'rgba(16,185,129,0.02)');

    const gradSalida = ctxFlujo.createLinearGradient(0, 0, 0, 250);
    gradSalida.addColorStop(0, 'rgba(239,68,68,0.18)');
    gradSalida.addColorStop(1, 'rgba(239,68,68,0.02)');

    new Chart(ctxFlujo, {
        type: 'line',
        data: {
            labels: @json($chartMeses),
            datasets: [
                {
                    label: 'Entradas (Bs.)',
                    data: @json($chartEntradas),
                    borderColor: '#10b981',
                    backgroundColor: gradEntrada,
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.45,
                    pointBackgroundColor: '#10b981',
                    pointRadius: 4,
                    pointHoverRadius: 7
                },
                {
                    label: 'Salidas (Bs.)',
                    data: @json($chartSalidas),
                    borderColor: '#ef4444',
                    backgroundColor: gradSalida,
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.45,
                    pointBackgroundColor: '#ef4444',
                    pointRadius: 4,
                    pointHoverRadius: 7
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { labels: { color: textColor, font: { family: fontFamily, size: 12, weight: '600' }, usePointStyle: true, pointStyleWidth: 10 } },
                tooltip: {
                    backgroundColor: isDark ? 'rgba(26,22,18,0.95)' : 'rgba(255,253,250,0.97)',
                    titleColor: isDark ? '#fdf8f2' : '#18140f',
                    bodyColor: isDark ? '#a09584' : '#7a6a5a',
                    borderColor: isDark ? 'rgba(251,191,36,0.18)' : 'rgba(180,83,9,0.14)',
                    borderWidth: 1,
                    cornerRadius: 12,
                    titleFont: { family: fontFamily, weight: '700', size: 13 },
                    bodyFont: { family: fontFamily, size: 12 },
                    padding: 12
                }
            },
            scales: {
                x: { grid: { color: gridColor }, ticks: { color: textColor, font: { family: fontFamily, size: 11 } } },
                y: { grid: { color: gridColor }, ticks: { color: textColor, font: { family: fontFamily, size: 11 } } }
            }
        }
    });

    // ── 2. Top 5 Consumos
    const ctxConsumos = document.getElementById('chartTopConsumos').getContext('2d');
    const topNombres   = @json($topNombres);
    const topCantidades = @json($topCantidades);

    if (topCantidades.length === 0) {
        ctxConsumos.canvas.parentNode.innerHTML = `
            <div style="text-align:center; padding:90px 10px; color:var(--text-muted); font-size:14px; font-family:Outfit,sans-serif;">
                <i class="fas fa-chart-pie" style="font-size:36px; opacity:0.2; display:block; margin-bottom:14px;"></i>
                Sin salidas registradas aún
            </div>`;
    } else {
        new Chart(ctxConsumos, {
            type: 'doughnut',
            data: {
                labels: topNombres,
                datasets: [{
                    data: topCantidades,
                    backgroundColor: ['#d97706','#3b82f6','#10b981','#8b5cf6','#ec4899'],
                    hoverBackgroundColor: ['#f59e0b','#60a5fa','#34d399','#a78bfa','#f472b6'],
                    borderWidth: isDark ? 3 : 2,
                    borderColor: isDark ? '#1a1612' : '#fdf8f2',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: textColor,
                            font: { family: fontFamily, size: 11, weight: '600' },
                            usePointStyle: true,
                            pointStyleWidth: 10,
                            padding: 14
                        }
                    },
                    tooltip: {
                        backgroundColor: isDark ? 'rgba(26,22,18,0.95)' : 'rgba(255,253,250,0.97)',
                        titleColor: isDark ? '#fdf8f2' : '#18140f',
                        bodyColor: isDark ? '#a09584' : '#7a6a5a',
                        borderColor: isDark ? 'rgba(251,191,36,0.18)' : 'rgba(180,83,9,0.14)',
                        borderWidth: 1,
                        cornerRadius: 12,
                        titleFont: { family: fontFamily, weight: '700', size: 13 },
                        bodyFont: { family: fontFamily, size: 12 },
                        padding: 12
                    }
                }
            }
        });
    }
});
</script>
@endpush