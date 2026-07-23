@extends('layouts.mina')

@section('titulo', 'Historial del Contratista: ' . $trabajador->nombre)

@push('styles')
<style>
/* =================================================
   HISTORIAL CONTRATISTA — DISEÑO PREMIUM EMPRESARIAL
   ================================================= */

/* ----- BREADCRUMB ----- */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13.5px;
    color: var(--text-muted);
    margin-bottom: 22px;
}
.breadcrumb a {
    color: #6366f1;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 5px;
    font-weight: 600;
}
.breadcrumb a:hover { text-decoration: underline; }
.breadcrumb-sep { color: var(--border); }

/* ----- PROFILE CARD PREMIUM ----- */
.worker-card {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px;
    padding: 28px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.3);
    position: relative;
    overflow: hidden;
}
.worker-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, #6366f1, #a855f7);
}
.worker-card-info h3 {
    font-size: 24px;
    font-weight: 800;
    color: white;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}
.worker-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.5px;
}
.badge-activo   { background: rgba(34, 197, 94, 0.2); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); }
.badge-inactivo { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }
.worker-meta {
    font-size: 14px;
    color: #94a3b8;
    line-height: 1.8;
}
.worker-meta strong { color: #f1f5f9; font-weight: 600; }
.worker-code-badge {
    font-family: monospace;
    font-size: 13px;
    background: rgba(99, 102, 241, 0.15);
    color: #818cf8;
    border: 1px solid rgba(99, 102, 241, 0.3);
    padding: 2px 8px;
    border-radius: 6px;
    font-weight: bold;
}

/* ----- ESTADÍSTICAS COMPACTAS ----- */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
@media (max-width: 900px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px) { .stats-grid { grid-template-columns: 1fr; } }
.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 20px;
    box-shadow: var(--shadow-sm);
    transition: all 0.25s ease-in-out;
    position: relative;
}
.stat-card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0; height: 3px;
    border-bottom-left-radius: 14px;
    border-bottom-right-radius: 14px;
}
.stat-card-danger::after { background: #ef4444; }
.stat-card-info::after { background: #3b82f6; }
.stat-card-warning::after { background: #f59e0b; }
.stat-card-success::after { background: #10b981; }

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow);
}
.stat-card .sc-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.stat-card .sc-val {
    font-size: 26px;
    font-weight: 800;
    line-height: 1.1;
    margin-bottom: 4px;
}
.stat-card-danger .sc-val { color: #ef4444; }
.stat-card-info .sc-val { color: #3b82f6; }
.stat-card-warning .sc-val { color: #d97706; }
.stat-card-success .sc-val { color: #10b981; }

.stat-card .sc-sub {
    font-size: 11px;
    color: var(--text-muted);
}

/* ----- FILTROS REALTIME ----- */
.filters-bar {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 16px 20px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}
.form-field { display: flex; flex-direction: column; gap: 6px; }
.form-field label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 6px;
}
.form-field label i { color: #6366f1; }
.form-field input {
    padding: 10px 14px;
    border: 1.5px solid var(--border);
    border-radius: 8px;
    font-size: 13.5px;
    font-family: inherit;
    background: var(--bg-card);
    color: var(--text-primary);
    transition: all 0.2s;
    width: 170px;
}
.form-field input:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
}
.btn-filter-secondary {
    background: transparent;
    border: 1.5px solid var(--border);
    color: var(--text-secondary);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s;
}
.btn-filter-secondary:hover {
    border-color: #6366f1;
    color: #6366f1;
}

/* ----- EXPORT LINK BUTTON ----- */
.export-panel-link {
    margin-left: auto;
}
.btn-export-link {
    background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
    color: white;
    border: none;
    padding: 11px 20px;
    border-radius: 10px;
    font-size: 13.5px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease-in-out;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
}
.btn-export-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(99, 102, 241, 0.45);
    color: white;
}

/* ----- TABLA ----- */
.table-wrap {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.data-table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
.data-table thead th {
    background: var(--bg-hover);
    padding: 14px 16px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-secondary);
    border-bottom: 2px solid var(--border);
}
.data-table tbody td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--border);
    color: var(--text-secondary);
}
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover { background: var(--bg-hover); }
.data-table tfoot td {
    padding: 14px 16px;
    font-weight: 700;
    background: var(--bg-hover);
    border-top: 2px solid var(--border);
}
.cod-span {
    font-family: monospace;
    font-weight: 700;
    color: #6366f1;
}

/* ----- VACÍO ----- */
.empty-state {
    text-align: center;
    padding: 70px 20px;
    color: var(--text-muted);
}
.empty-state i {
    font-size: 56px;
    opacity: 0.2;
    margin-bottom: 16px;
    display: block;
    animation: float 3s ease-in-out infinite;
}
@keyframes float {
    0%,100% { transform: translateY(0); }
    50%     { transform: translateY(-8px); }
}
.empty-state p { font-size: 15px; }

.text-right  { text-align: right !important; }
.text-center { text-align: center !important; }
</style>
@endpush

@section('contenido')

{{-- BREADCRUMB --}}
<div class="breadcrumb">
    <a href="{{ route('trabajadores.index') }}">
        <i class="fas fa-arrow-left"></i> Contratistas
    </a>
    <span class="breadcrumb-sep">/</span>
    <span>Historial</span>
    <span class="breadcrumb-sep">/</span>
    <span><strong>{{ $trabajador->nombre }}</strong></span>
</div>

{{-- TARJETA DEL TRABAJADOR PREMIUM --}}
<div class="worker-card">
    <div class="worker-card-info">
        <h3>
            <i class="fas fa-hard-hat" style="color:#6366f1;"></i>
            {{ $trabajador->nombre }}
            @if($trabajador->codigo)
                <span class="worker-code-badge">{{ $trabajador->codigo }}</span>
            @endif
            @if($trabajador->activo)
                <span class="worker-badge badge-activo"><i class="fas fa-circle" style="font-size:7px; margin-right:4px;"></i> ACTIVO</span>
            @else
                <span class="worker-badge badge-inactivo">INACTIVO</span>
            @endif
        </h3>
        <div class="worker-meta">
            @if($trabajador->ayudante)
                <strong>Ayudante asignado:</strong> {{ $trabajador->ayudante }} <br>
            @endif
            @if($trabajador->cargo)
                <strong>Cargo:</strong> {{ $trabajador->cargo }} &nbsp;·&nbsp;
            @endif
            <strong>Registrado en sistema:</strong> {{ $trabajador->created_at->format('d/m/Y') }}
            
            @if($trabajador->nivel || $trabajador->labor || $trabajador->area_trabajo)
                <div style="margin-top:14px;">
                    <strong style="color:white; display:block; margin-bottom:6px;"><i class="fas fa-map-marked-alt" style="color:#818cf8; margin-right:6px;"></i>Sectores de Trabajo Asignados:</strong>
                    <div style="display:flex; flex-direction:column; gap:8px;">
                        @php
                            $niveles = explode(',', $trabajador->nivel ?? '');
                            $labores = explode(',', $trabajador->labor ?? '');
                            $areas = explode(',', $trabajador->area_trabajo ?? '');
                            $max = max(count($niveles), count($labores), count($areas));
                        @endphp
                        @for($i = 0; $i < $max; $i++)
                            @php
                                $n = trim($niveles[$i] ?? '');
                                $l = trim($labores[$i] ?? '');
                                $a = trim($areas[$i] ?? '');
                            @endphp
                            @if($n || $l || $a)
                                <div style="display:inline-flex; align-items:center; gap:12px; font-size:12px; background:rgba(255,255,255,0.06); padding:6px 14px; border-radius:8px; width:fit-content; border: 1px solid rgba(255,255,255,0.1);">
                                    @if($n) <span><i class="fas fa-layer-group" style="color:#fbbf24; margin-right:3px;"></i> <strong>{{ $n }}</strong></span> @endif
                                    @if($l) <span><i class="fas fa-hammer" style="color:#60a5fa; margin-right:3px;"></i> {{ $l }}</span> @endif
                                    @if($a) <span><i class="fas fa-map-marker-alt" style="color:#f87171; margin-right:3px;"></i> {{ $a }}</span> @endif
                                </div>
                            @endif
                        @endfor
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- RANGO FILTRADO TEXTO --}}
<div style="margin-bottom:12px; font-size:12px; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; display:flex; align-items:center; gap:6px;">
    <i class="fas fa-info-circle" style="color:#6366f1;"></i>
    @if($filtradoPorDefectoMes)
        Mostrando movimientos de los últimos 2 meses ({{ \Carbon\Carbon::parse($desde)->locale('es')->isoFormat('MMMM YYYY') }} - {{ \Carbon\Carbon::parse($hasta)->locale('es')->isoFormat('MMMM YYYY') }})
    @else
        Movimientos filtrados desde {{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }} hasta {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }}
    @endif
</div>

{{-- ESTADÍSTICAS PREMIUM COMPACTAS --}}
<div class="stats-grid">
    <div class="stat-card stat-card-danger">
        <div class="sc-label"><i class="fas fa-arrow-up"></i> Salidas registradas</div>
        <div class="sc-val">{{ $totalSalidas }}</div>
        <div class="sc-sub">{{ $filtradoPorDefectoMes ? 'Últimos 2 meses' : 'En el período filtrado' }}</div>
    </div>
    <div class="stat-card stat-card-info">
        <div class="sc-label"><i class="fas fa-boxes"></i> Insumos Diferentes</div>
        <div class="sc-val">{{ $articulosUnicos }}</div>
        <div class="sc-sub">Artículos únicos entregados</div>
    </div>
    <div class="stat-card stat-card-warning">
        <div class="sc-label"><i class="fas fa-money-bill-wave"></i> Costo Valorado</div>
        <div class="sc-val" style="font-size:20px;">Bs. {{ number_format($totalGastado, 2) }}</div>
        <div class="sc-sub">Inversión en el período</div>
    </div>
    <div class="stat-card stat-card-success">
        <div class="sc-label"><i class="fas fa-calculator"></i> Cantidad Entregada</div>
        <div class="sc-val">{{ number_format($totalCantidad, 2) }}</div>
        <div class="sc-sub">Total unidades de insumos</div>
    </div>
</div>

{{-- FILTROS CON AUTO-ENVIO (TIEMPO REAL) E IR AL PANEL DE EXPORTACION --}}
<div class="filters-bar">
    <form method="GET" action="{{ route('reportes.trabajador', $trabajador) }}" style="display:flex; align-items:center; gap:16px; flex-wrap:wrap; margin:0;">
        <div class="form-field">
            <label><i class="fas fa-calendar"></i> Fecha Desde</label>
            <input type="date" name="desde" value="{{ $desde }}" onchange="this.form.submit()">
        </div>
        <div class="form-field">
            <label><i class="fas fa-calendar"></i> Fecha Hasta</label>
            <input type="date" name="hasta" value="{{ $hasta }}" onchange="this.form.submit()">
        </div>
        
        <a href="{{ route('reportes.trabajador', $trabajador) }}" class="btn-filter-secondary" title="Limpiar rango de fechas">
            <i class="fas fa-times"></i> Restablecer Rango (2 Meses)
        </a>
    </form>

    <div class="export-panel-link">
        <a href="{{ route('reportes.index') }}?tab=movimientos&trabajador_id={{ $trabajador->id }}&desde={{ $desde }}&hasta={{ $hasta }}" 
           class="btn-export-link" 
           title="Configurar y descargar este historial en Excel o PDF">
            <i class="fas fa-file-export"></i> Configurar y Exportar Reporte
        </a>
    </div>
</div>

{{-- TABLA DE SALIDAS --}}
@if($movimientos->isEmpty())
    <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <p>No se encontraron entregas de material para este contratista en el rango de fechas seleccionado.</p>
    </div>
@else
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:145px;">Fecha y Hora</th>
                    <th style="width:115px;">Código</th>
                    <th>Artículo / Material</th>
                    <th style="width:120px;" class="text-right">Cantidad</th>
                    <th style="width:130px;" class="text-right">Precio Unitario</th>
                    <th style="width:130px;" class="text-right">Importe Total</th>
                    <th>Notas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($movimientos as $mov)
                    @php
                        $pUnitario = $mov->precio_unitario ?? $mov->articulo->precio ?? 0;
                        $pTotal    = $mov->cantidad * $pUnitario;
                    @endphp
                    <tr>
                        <td>
                            {{ $mov->created_at->format('d/m/Y H:i') }}
                            @if($mov->turno)
                                <div style="font-size: 11px; margin-top: 2px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                    @if($mov->turno === 'Primera')
                                        <span style="color: #b45309;"><i class="fas fa-sun" style="color: #d97706; font-size: 10px;"></i> Primera</span>
                                    @elseif($mov->turno === 'Segunda')
                                        <span style="color: #c2410c;"><i class="fas fa-cloud-sun" style="color: #ea580c; font-size: 10px;"></i> Segunda</span>
                                    @elseif($mov->turno === 'Tercera')
                                        <span style="color: #4338ca;"><i class="fas fa-moon" style="color: #4f46e5; font-size: 10px;"></i> Tercera</span>
                                    @endif
                                </div>
                            @endif
                            @if($mov->entregado_por)
                                <div style="font-size: 11px; color: var(--text-muted);">
                                    <i class="fas fa-user-check" style="font-size: 10px;"></i> {{ $mov->entregado_por }}
                                </div>
                            @endif
                        </td>
                        <td><span class="cod-span">{{ $mov->articulo->codigo }}</span></td>
                        <td>{{ $mov->articulo->nombre }}</td>
                        <td class="text-right">
                            <strong>{{ number_format($mov->cantidad, 2) }}</strong>
                            <span style="font-size:11px; color:var(--text-muted); margin-left:3px;">{{ $mov->articulo->unidad }}</span>
                        </td>
                        <td class="text-right" style="color:#10b981; font-weight:600;">
                            Bs. {{ number_format($pUnitario, 2) }}
                        </td>
                        <td class="text-right" style="color:#6366f1; font-weight:700;">
                            Bs. {{ number_format($pTotal, 2) }}
                        </td>
                        <td style="color:var(--text-muted); font-size:13px;">{{ $mov->notas ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                @php
                    $pageCant = $movimientos->sum('cantidad');
                    $pageVal  = $movimientos->sum(fn($m) => $m->cantidad * ($m->precio_unitario ?? $m->articulo->precio ?? 0));
                @endphp
                <tr>
                    <td colspan="3" class="text-right" style="color:var(--text-secondary);">
                        Subtotal (esta página):
                    </td>
                    <td class="text-right" style="color:#6366f1;">
                        {{ number_format($pageCant, 2) }}
                    </td>
                    <td></td>
                    <td class="text-right" style="color:#6366f1;">
                        Bs. {{ number_format($pageVal, 2) }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div style="margin-top: 22px; display: flex; justify-content: center;">
        {{ $movimientos->links() }}
    </div>
@endif

@endsection