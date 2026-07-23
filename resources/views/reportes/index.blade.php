@extends('layouts.mina')

@section('titulo', 'Reportes y Exportaciones')

@push('styles')
<style>
/* ======================================================
   REPORTES — Diseño Profesional v3
   ====================================================== */

/* ----- ENCABEZADO DE PÁGINA ----- */
.rpt-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 28px;
}
.rpt-header-left h2 {
    font-size: 26px;
    font-weight: 800;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 4px;
    line-height: 1.2;
}
.rpt-header-left h2 i {
    background: var(--gradient);
    color: white;
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
    transition: background 0.3s;
}
.rpt-header-left p {
    color: var(--text-muted);
    font-size: 14px;
    padding-left: 56px;
}

/* Dark mode — reducir naranja en iconos de campo y panel */
[data-theme="dark"] .rpt-section-title i { color: #58a6ff !important; }
[data-theme="dark"] .rpt-field label i    { color: #6e7681 !important; }
[data-theme="dark"] .rpt-panel-head i     { color: #6e7681 !important; }
[data-theme="dark"] .rpt-header-left h2 i {
    background: linear-gradient(135deg, #21262d 0%, #30363d 100%) !important;
    color: #e29b3a !important;
    border: 1px solid #30363d;
}
[data-theme="dark"] .rpt-tabs-wrapper     { border-color: #30363d !important; }
[data-theme="dark"] .rpt-tabs-nav         { background: #161b22 !important; border-color: #30363d !important; }
[data-theme="dark"] .rpt-tab-btn          { color: #8b949e !important; }
[data-theme="dark"] .rpt-tab-btn.active   {
    color: #e29b3a !important;
    background: #1c2230 !important;
    border-bottom-color: #e29b3a !important;
}
[data-theme="dark"] .rpt-panel           { background: #161b22 !important; border-color: #30363d !important; }
[data-theme="dark"] .rpt-action-panel    { background: #161b22 !important; border-color: #30363d !important; }
[data-theme="dark"] .rpt-field input,
[data-theme="dark"] .rpt-field select    { background: #0d1117 !important; border-color: #30363d !important; color: #e6edf3 !important; }
[data-theme="dark"] .rpt-stat            { background: #1c2230 !important; border-color: #30363d !important; }
[data-theme="dark"] .rpt-pstat          { background: #1c2230 !important; border-color: #30363d !important; }
[data-theme="dark"] .rpt-table-wrap     { border-color: #30363d !important; background: #1c2230 !important; }
[data-theme="dark"] .rpt-table thead th { background: #161b22 !important; color: #8b949e !important; border-color: #30363d !important; }
[data-theme="dark"] .rpt-table tbody td { border-color: #21262d !important; }
[data-theme="dark"] .rpt-table tfoot td { background: #161b22 !important; border-color: #30363d !important; }
[data-theme="dark"] .rpt-checkbox-label { background: #0d1117 !important; border-color: #30363d !important; color: #8b949e !important; }
[data-theme="dark"] .mes-card           { background: #1c2230 !important; border-color: #30363d !important; }
[data-theme="dark"] .mes-card-header    { background: #161b22 !important; border-color: #30363d !important; }
[data-theme="dark"] .mes-card-header h4 { color: #e6edf3 !important; }
[data-theme="dark"] .mes-table th       { color: #8b949e !important; border-color: #30363d !important; }
[data-theme="dark"] .mes-table td       { border-color: #21262d !important; color: #c9d1d9 !important; }
[data-theme="dark"] .unidad-pill        { background: #252d3d !important; border-color: #30363d !important; color: #8b949e !important; }

/* ----- ESTADÍSTICAS RÁPIDAS ----- */
.rpt-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 28px;
}
@media (max-width: 900px) {
    .rpt-stats { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 480px) {
    .rpt-stats { grid-template-columns: 1fr 1fr; }
}
.rpt-stat {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: var(--shadow-sm);
    transition: all 0.25s;
}
.rpt-stat:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow);
}
.rpt-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}
.rpt-stat-icon.naranja { background: #fff7ed; color: #ea580c; }
.rpt-stat-icon.verde   { background: #f0fdf4; color: #16a34a; }
.rpt-stat-icon.rojo    { background: #fef2f2; color: #dc2626; }
.rpt-stat-icon.azul    { background: #eff6ff; color: #2563eb; }
.rpt-stat-body { min-width: 0; }
.rpt-stat-body .label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: var(--text-muted);
    margin-bottom: 2px;
}
.rpt-stat-body .val {
    font-size: 26px;
    font-weight: 800;
    color: var(--text-primary);
    line-height: 1;
}

/* ----- TABS ----- */
.rpt-tabs-wrapper {
    background: var(--bg-card);
    border-radius: 18px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.rpt-tabs-nav {
    display: flex;
    background: var(--bg-hover);
    border-bottom: 1px solid var(--border);
    overflow-x: auto;
    padding: 8px 8px 0;
    gap: 4px;
}
.rpt-tab-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border: none;
    border-radius: 10px 10px 0 0;
    background: transparent;
    color: var(--text-muted);
    font-size: 14px;
    font-weight: 600;
    font-family: inherit;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
    border-bottom: 3px solid transparent;
    position: relative;
    bottom: -1px;
}
.rpt-tab-btn i { font-size: 15px; }
.rpt-tab-btn:hover {
    color: var(--primary);
    background: rgba(255,255,255,0.6);
}
.rpt-tab-btn.active {
    color: var(--primary);
    background: var(--bg-card);
    border-bottom-color: var(--primary);
    box-shadow: 0 -2px 8px rgba(0,0,0,0.04);
}
.rpt-tab-btn .shortcut {
    font-size: 9px;
    background: rgba(217,119,6,0.12);
    color: var(--primary);
    padding: 2px 6px;
    border-radius: 4px;
    font-weight: 700;
}
.rpt-tab-btn.active .shortcut {
    background: var(--primary);
    color: white;
}

.rpt-tab-content {
    display: none;
    padding: 32px;
    animation: rptFadeIn 0.3s ease;
}
.rpt-tab-content.active { display: block; }

@keyframes rptFadeIn {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ----- TAB TITLE ----- */
.rpt-section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 18px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 6px;
}
.rpt-section-title i { color: var(--primary); font-size: 17px; }
.rpt-section-desc {
    color: var(--text-muted);
    font-size: 13.5px;
    margin-bottom: 24px;
    line-height: 1.5;
}

/* ----- SEPARADOR VISUAL ----- */
.rpt-divider {
    height: 1px;
    background: var(--border);
    margin: 24px 0;
}

/* ----- PANEL GRID (filtros + acciones) ----- */
.rpt-panel-grid {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 18px;
    margin-bottom: 22px;
}
@media (max-width: 880px) {
    .rpt-panel-grid { grid-template-columns: 1fr; }
}

/* ----- PANEL ----- */
.rpt-panel {
    background: var(--bg-hover);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 22px;
}
.rpt-panel-head {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: var(--text-secondary);
    margin-bottom: 16px;
}
.rpt-panel-head i { color: var(--primary); font-size: 14px; }

/* ----- FORMULARIOS ----- */
.rpt-form-grid {
    display: grid;
    gap: 12px;
}
.rpt-form-grid.cols-2 { grid-template-columns: 1fr 1fr; }
.rpt-form-grid.cols-3 { grid-template-columns: 1fr 1fr 1fr; }

.rpt-field {
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.rpt-field label {
    font-size: 11.5px;
    font-weight: 600;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.4px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.rpt-field label i { color: var(--primary); font-size: 11px; }
.rpt-field input,
.rpt-field select {
    padding: 10px 13px;
    border: 1.5px solid var(--border);
    border-radius: 9px;
    font-size: 14px;
    font-family: inherit;
    background: var(--bg-card);
    color: var(--text-primary);
    transition: all 0.2s;
}
.rpt-field input:focus,
.rpt-field select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(217,119,6,0.1);
}

/* ----- FECHAS OCULTAS ----- */
.rpt-dates-row {
    display: none;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-top: 12px;
}
.rpt-dates-row.visible { display: grid; }

/* ----- PANEL ACCIÓN ----- */
.rpt-action-panel {
    background: var(--bg-hover);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 22px;
    display: flex;
    flex-direction: column;
    gap: 14px;
}

/* ----- AYUDA INFO ----- */
.rpt-help {
    background: #fffbeb;
    border-left: 3px solid #f59e0b;
    border-radius: 8px;
    padding: 11px 14px;
    font-size: 12.5px;
    color: #7c2d12;
    display: flex;
    align-items: flex-start;
    gap: 8px;
    line-height: 1.4;
}
.rpt-help i { color: #f59e0b; font-size: 15px; margin-top: 1px; flex-shrink: 0; }

/* ----- INFO BOX (azul) ----- */
.rpt-info {
    background: #eff6ff;
    border-left: 3px solid #3b82f6;
    border-radius: 8px;
    padding: 11px 14px;
    font-size: 12.5px;
    color: #1e40af;
    display: flex;
    align-items: flex-start;
    gap: 8px;
    line-height: 1.4;
}
.rpt-info i { color: #3b82f6; font-size: 15px; margin-top: 1px; flex-shrink: 0; }

/* ----- CHECKBOX PERSONALIZADO ----- */
.rpt-checkbox-label {
    display: flex;
    align-items: center;
    gap: 9px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-secondary);
    user-select: none;
    padding: 10px 12px;
    background: var(--bg-card);
    border: 1.5px solid var(--border);
    border-radius: 9px;
    transition: all 0.2s;
}
.rpt-checkbox-label:hover {
    border-color: var(--primary);
    background: #fffbeb;
    color: var(--primary);
}
.rpt-checkbox-label input[type="checkbox"] {
    width: 16px;
    height: 16px;
    cursor: pointer;
    accent-color: var(--primary);
}

/* ----- BOTONES ----- */
.btn-rpt {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 18px;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
    font-family: inherit;
    cursor: pointer;
    text-decoration: none;
    color: white;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    white-space: nowrap;
}
.btn-rpt:disabled,
.btn-rpt.disabled {
    opacity: 0.35;
    cursor: not-allowed;
    pointer-events: none;
    background: #94a3b8 !important;
    box-shadow: none !important;
}
.btn-rpt-excel {
    background: linear-gradient(135deg, #16a34a 0%, #10b981 100%);
}
.btn-rpt-excel:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(22,163,74,0.35);
}
.btn-rpt-pdf {
    background: linear-gradient(135deg, #dc2626 0%, #f43f5e 100%);
}
.btn-rpt-pdf:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(220,38,38,0.35);
}
.btn-rpt-primary {
    background: var(--gradient);
}
.btn-rpt-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(217,119,6,0.35);
}
.btn-rpt-secondary {
    background: transparent;
    border: 1.5px solid var(--border);
    color: var(--text-secondary);
    box-shadow: none;
}
.btn-rpt-secondary:hover {
    border-color: var(--primary);
    color: var(--primary);
    background: #fffbeb;
}

/* Grupo de botones en fila */
.rpt-btn-row {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.rpt-btn-row .btn-rpt { flex: 1; min-width: 100px; }

/* ----- PREVIEW INFO BOX ----- */
.rpt-preview-bar {
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    border: 1px solid rgba(217,119,6,0.2);
    border-radius: 10px;
    padding: 13px 18px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 13px;
    color: #92400e;
    margin-bottom: 20px;
}
.rpt-preview-bar i { color: var(--primary); font-size: 17px; flex-shrink: 0; }
.rpt-preview-bar strong { color: #78350f; }

/* ----- LIVE PREVIEW SECTION ----- */
.rpt-preview-section {
    margin-top: 28px;
    padding-top: 24px;
    border-top: 1px solid var(--border);
}
.rpt-preview-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
}
.rpt-preview-header h4 {
    font-size: 15px;
    font-weight: 700;
    color: var(--text-primary);
}
.rpt-preview-header .badge-live {
    background: #dcfce7;
    color: #166534;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    animation: pulse-live 2s ease infinite;
}
@keyframes pulse-live {
    0%,100% { opacity: 1; }
    50% { opacity: 0.6; }
}

/* ----- STATS CARDS PREVIEW ----- */
.rpt-preview-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-bottom: 18px;
}
@media (max-width: 640px) {
    .rpt-preview-stats { grid-template-columns: 1fr; }
}
.rpt-pstat {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 14px 16px;
    border-left: 5px solid var(--primary);
    transition: all 0.2s;
}
.rpt-pstat:hover { transform: translateY(-2px); box-shadow: var(--shadow); }
.rpt-pstat.entradas { border-left-color: #059669; }
.rpt-pstat.salidas  { border-left-color: #e53e3e; }
.rpt-pstat .ps-label {
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
    margin-bottom: 5px;
}
.rpt-pstat .ps-val {
    font-size: 20px;
    font-weight: 800;
    color: var(--text-primary);
    line-height: 1;
}
.rpt-pstat .ps-desc {
    font-size: 11px;
    color: var(--text-light);
    margin-top: 3px;
}

/* ----- TABLA PREVIEW ----- */
.rpt-table-wrap {
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.rpt-table-wrap .rpt-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
.rpt-table thead th {
    background: var(--bg-hover);
    color: var(--text-secondary);
    font-weight: 700;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 11px 12px;
    border-bottom: 2px solid var(--border);
}
.rpt-table tbody td {
    padding: 11px 12px;
    border-bottom: 1px solid var(--border);
    color: var(--text-secondary);
}
.rpt-table tbody tr:last-child td { border-bottom: none; }
.rpt-table tbody tr:hover { background: var(--bg-hover); }
.rpt-table tfoot td {
    padding: 12px;
    font-weight: 700;
    background: var(--bg-hover);
    border-top: 2px solid var(--border);
}

.badge-tipo {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 9px;
    border-radius: 8px;
    font-size: 10.5px;
    font-weight: 700;
}
.badge-entrada { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
.badge-salida  { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

.text-right { text-align: right !important; }
.text-center { text-align: center !important; }
.font-mono { font-family: 'Courier New', monospace; }

/* ----- TABLA SCROLL ----- */
.rpt-table-scroll {
    max-height: 420px;
    overflow-y: auto;
    overflow-x: auto;
}

/* ----- MENSUAL CARDS ----- */
.mes-grid {
    display: grid;
    gap: 14px;
}
.mes-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: all 0.2s;
}
.mes-card:hover { transform: translateY(-2px); box-shadow: var(--shadow); }
.mes-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    background: var(--bg-hover);
    border-bottom: 1px solid var(--border);
    flex-wrap: wrap;
    gap: 12px;
}
.mes-card-header h4 {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 15px;
    font-weight: 700;
    color: var(--text-primary);
}
.mes-card-header h4 i { color: var(--primary); }
.mes-card-actions { display: flex; gap: 8px; }
.btn-mes {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    border-radius: 8px;
    font-size: 12.5px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
    color: white;
}
.btn-mes-excel { background: #16a34a; }
.btn-mes-excel:hover { background: #15803d; transform: translateY(-1px); }
.btn-mes-pdf   { background: #dc2626; }
.btn-mes-pdf:hover { background: #b91c1c; transform: translateY(-1px); }
.mes-card-body { padding: 16px 20px; }
.mes-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
.mes-table th {
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    color: var(--text-muted);
    padding: 8px 10px;
    border-bottom: 1px solid var(--border);
}
.mes-table td {
    padding: 9px 10px;
    border-bottom: 1px solid var(--border-light);
    color: var(--text-secondary);
}
.mes-table tr:last-child td { border-bottom: none; }
.unidad-pill {
    background: var(--bg-hover);
    color: var(--text-secondary);
    border: 1px solid var(--border);
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

/* ----- INVENTARIO TAB ----- */
.rpt-inv-layout {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}
.rpt-inv-left { flex: 1; min-width: 260px; }
.rpt-inv-right {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

/* ----- ATAJOS ----- */
.rpt-shortcuts {
    text-align: center;
    padding: 14px;
    color: var(--text-light);
    font-size: 12px;
    border-top: 1px solid var(--border);
    margin-top: -1px;
}
.rpt-shortcuts kbd {
    background: var(--bg-hover);
    border: 1px solid var(--border);
    border-radius: 4px;
    padding: 2px 7px;
    font-family: monospace;
    font-size: 10px;
    font-weight: 600;
    color: var(--text-secondary);
    margin: 0 2px;
}

/* ----- RESPONSIVE ----- */
@media (max-width: 768px) {
    .rpt-tab-content { padding: 20px; }
    .rpt-form-grid.cols-2,
    .rpt-form-grid.cols-3 { grid-template-columns: 1fr; }
    .rpt-inv-right { width: 100%; }
    .rpt-inv-right .btn-rpt { flex: 1; }
}
</style>
@endpush

@section('contenido')

{{-- ===== ENCABEZADO ===== --}}
<div class="rpt-header">
    <div class="rpt-header-left">
        <h2>
            <i class="fas fa-file-export"></i>
            Reportes y Exportaciones
        </h2>
        <p>Genera y descarga reportes detallados del inventario, movimientos, kardex y más.</p>
    </div>
</div>

{{-- ===== ESTADÍSTICAS RÁPIDAS ===== --}}
<div class="rpt-stats">
    <div class="rpt-stat">
        <div class="rpt-stat-icon naranja"><i class="fas fa-boxes-stacked"></i></div>
        <div class="rpt-stat-body">
            <div class="label">Artículos</div>
            <div class="val">{{ $totalArticulos }}</div>
        </div>
    </div>
    <div class="rpt-stat">
        <div class="rpt-stat-icon verde"><i class="fas fa-exchange-alt"></i></div>
        <div class="rpt-stat-body">
            <div class="label">Movimientos</div>
            <div class="val">{{ $totalMovimientos }}</div>
        </div>
    </div>
    <div class="rpt-stat">
        <div class="rpt-stat-icon rojo"><i class="fas fa-triangle-exclamation"></i></div>
        <div class="rpt-stat-body">
            <div class="label">Sin Stock</div>
            <div class="val">{{ $articulosSinStock }}</div>
        </div>
    </div>
    <div class="rpt-stat">
        <div class="rpt-stat-icon azul"><i class="fas fa-hard-hat"></i></div>
        <div class="rpt-stat-body">
            <div class="label">Trabajadores</div>
            <div class="val">{{ $trabajadores->count() }}</div>
        </div>
    </div>
</div>

{{-- ===== CONTENEDOR TABS ===== --}}
<div class="rpt-tabs-wrapper">
    {{-- NAV --}}
    <div class="rpt-tabs-nav">
        <button class="rpt-tab-btn active" data-tab="inventario" id="tab-btn-inventario">
            <i class="fas fa-boxes-stacked"></i> Inventario
            <span class="shortcut">Ctrl+1</span>
        </button>
        <button class="rpt-tab-btn" data-tab="movimientos" id="tab-btn-movimientos">
            <i class="fas fa-exchange-alt"></i> Movimientos
            <span class="shortcut">Ctrl+2</span>
        </button>
        <button class="rpt-tab-btn" data-tab="kardex" id="tab-btn-kardex">
            <i class="fas fa-clipboard-list"></i> Kardex
            <span class="shortcut">Ctrl+3</span>
        </button>
        <button class="rpt-tab-btn" data-tab="mensual" id="tab-btn-mensual">
            <i class="fas fa-calendar-alt"></i> Resumen Mensual
            <span class="shortcut">Ctrl+4</span>
        </button>
        @if(Auth::user()->esAdmin())
            <button class="rpt-tab-btn" data-tab="bitacora" id="tab-btn-bitacora">
                <i class="fas fa-user-shield"></i> Bitácora
                <span class="shortcut">Ctrl+5</span>
            </button>
        @endif
    </div>

    {{-- ========= TAB 1: INVENTARIO ========= --}}
    <div class="rpt-tab-content active" id="tab-inventario">
        <div class="rpt-section-title">
            <i class="fas fa-boxes-stacked"></i> Reporte de Inventario General
        </div>
        <p class="rpt-section-desc">
            Exporta la lista de todos los artículos con su stock actual, precio unitario y valor total valorizado.
        </p>

        <div class="rpt-inv-filters" style="margin-bottom: 24px; max-width: 650px; background: white; border: 1px solid var(--border); padding: 18px; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); display: flex; gap: 16px; flex-wrap: wrap;">
            <div class="form-field" style="margin: 0; flex: 1; min-width: 220px;">
                <label style="font-weight: 600; font-size: 13.5px; color: var(--text-muted); margin-bottom: 8px; display: block;">
                    <i class="fas fa-filter"></i> Filtrar materiales a exportar:
                </label>
                <select id="filtro_stock_inventario" style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 10px; font-size: 14px; background: var(--bg-input); color: var(--text-primary); outline: none; transition: border-color 0.2s; font-weight: 600;">
                    <option value="todos" data-count="{{ $totalArticulos }}" selected>Todos los materiales ({{ $totalArticulos }})</option>
                    <option value="con_stock" data-count="{{ $totalArticulos - $articulosSinStock }}">Solo materiales con stock ({{ $totalArticulos - $articulosSinStock }})</option>
                    <option value="sin_stock" data-count="{{ $articulosSinStock }}">Solo materiales sin stock ({{ $articulosSinStock }})</option>
                </select>
            </div>
            
            <div class="form-field" style="margin: 0; flex: 1; min-width: 220px;">
                <label style="font-weight: 600; font-size: 13.5px; color: var(--text-muted); margin-bottom: 8px; display: block;">
                    <i class="fas fa-folder"></i> Filtrar por Grupo:
                </label>
                <select id="filtro_grupo_inventario" style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 10px; font-size: 14px; background: var(--bg-input); color: var(--text-primary); outline: none; transition: border-color 0.2s; font-weight: 600;">
                    <option value="todos" selected>Todos los grupos</option>
                    @foreach($resumenCategoria as $grupo)
                        <option value="{{ $grupo->id }}">{{ $grupo->nombre }} ({{ $grupo->articulos_count }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="rpt-inv-layout">
            <div class="rpt-inv-left">
                <div class="rpt-preview-bar" style="margin-bottom: 0;">
                    <i class="fas fa-info-circle"></i>
                    <div id="rpt-inv-count-label">
                        El reporte incluirá los <strong>{{ $totalArticulos }} artículos</strong> registrados en el inventario.
                    </div>
                </div>
            </div>
            <div class="rpt-inv-right">
                <a href="{{ route('reportes.inventario.excel') }}" id="btn-rpt-excel-link" class="btn-rpt btn-rpt-excel">
                    <i class="fas fa-file-excel"></i> Exportar Excel
                </a>
                <a href="{{ route('reportes.inventario.pdf') }}" id="btn-rpt-pdf-link" class="btn-rpt btn-rpt-pdf">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </a>
            </div>
        </div>
    </div>

    {{-- ========= TAB 2: MOVIMIENTOS ========= --}}
    <div class="rpt-tab-content" id="tab-movimientos">
        <div class="rpt-section-title">
            <i class="fas fa-exchange-alt"></i> Reporte de Movimientos
        </div>
        <p class="rpt-section-desc">
            Filtra y exporta entradas y salidas del inventario. Aplica filtros por tipo, trabajador y período de tiempo.
        </p>

        <div class="rpt-panel-grid">
            {{-- Panel Filtros --}}
            <div class="rpt-panel">
                <div class="rpt-panel-head">
                    <i class="fas fa-filter"></i> Filtros de Búsqueda
                </div>

                <div class="rpt-form-grid cols-3" style="margin-bottom: 12px;">
                    <div class="rpt-field">
                        <label><i class="fas fa-exchange-alt"></i> Tipo de movimiento</label>
                        <select id="filtro_tipo_mov">
                            <option value="">Todos los movimientos</option>
                            <option value="entrada">Solo Entradas</option>
                            <option value="salida">Solo Salidas</option>
                        </select>
                    </div>
                    <div class="rpt-field">
                        <label><i class="fas fa-hard-hat"></i> Contratista / Personal</label>
                        <select id="filtro_trabajador_mov">
                            <option value="">Todos los contratistas</option>
                            @foreach($trabajadores as $t)
                                <option value="{{ $t->id }}">
                                    {{ $t->nombre }} @if($t->codigo)[{{ $t->codigo }}]@endif @if($t->ayudante)(Ayudante: {{ $t->ayudante }})@endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="rpt-field">
                        <label><i class="fas fa-clock"></i> Período</label>
                        <select id="filtro_periodo_mov">
                            <option value="todos">Todo el historial</option>
                            <option value="personalizado">Personalizado (fechas)</option>
                            <option value="diario">Hoy</option>
                            <option value="semanal">Esta semana</option>
                            <option value="mensual" selected>Este mes</option>
                        </select>
                    </div>
                </div>

                {{-- Fila: Filtro por Material --}}
                <div class="rpt-field" style="margin-bottom: 12px;">
                    <label><i class="fas fa-box"></i> Material / Artículo <span style="font-size:11px; color:var(--text-muted); font-weight:400;">(opcional)</span></label>
                    <div style="position:relative;">
                        <input type="text"
                               id="filtro_articulo_mov_search"
                               list="mov-articulos-datalist"
                               placeholder="🔍 Escribe código o nombre para filtrar por material…"
                               autocomplete="off"
                               style="width:100%; padding:9px 36px 9px 12px; border:1.5px solid var(--border); border-radius:8px; font-size:13.5px; background:var(--bg-input); color:var(--text-primary); box-sizing:border-box;"
                               oninput="onFiltroArticuloMovInput(this.value)">
                        <datalist id="mov-articulos-datalist">
                            @foreach(\App\Models\Articulo::orderByRaw('CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(codigo, "/", 1), "-", -1) AS UNSIGNED)')->orderByRaw('CAST(SUBSTRING_INDEX(codigo, "/", -1) AS UNSIGNED)')->get() as $art)
                                <option value="{{ $art->codigo }} — {{ $art->nombre }}" data-id="{{ $art->id }}"></option>
                            @endforeach
                        </datalist>
                        <button type="button" id="btn_limpiar_articulo_mov"
                                onclick="limpiarFiltroArticuloMov()"
                                title="Quitar filtro de material"
                                style="display:none; position:absolute; right:8px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#94a3b8; font-size:16px; padding:4px;">
                            <i class="fas fa-times-circle"></i>
                        </button>
                    </div>
                    <input type="hidden" id="filtro_articulo_mov_id" value="">
                </div>


                <div id="container_fechas_mov" class="rpt-dates-row">
                    <div class="rpt-field">
                        <label><i class="fas fa-calendar"></i> Desde</label>
                        <input type="date" id="mov_desde">
                    </div>
                    <div class="rpt-field">
                        <label><i class="fas fa-calendar"></i> Hasta</label>
                        <input type="date" id="mov_hasta">
                    </div>
                </div>
            </div>

            {{-- Panel Acciones --}}
            <div class="rpt-action-panel">
                <div class="rpt-panel-head">
                    <i class="fas fa-download"></i> Exportar
                </div>

                <label class="rpt-checkbox-label">
                    <input type="checkbox" id="mov_incluir_inicial">
                    <span><i class="fas fa-layer-group" style="color:var(--primary);"></i> Incluir Stock Inicial</span>
                </label>

                <div id="preview-movimientos-text-box" class="rpt-info" style="font-size: 12px;">
                    <i class="fas fa-info-circle"></i>
                    <span id="preview-movimientos-text">Se incluirán los movimientos de <strong>este mes</strong> (por defecto). Modifica el período si requieres otro intervalo.</span>
                </div>

                <div class="rpt-btn-row">
                    <a href="#" onclick="descargarMovimientos('excel'); return false;" class="btn-rpt btn-rpt-excel">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                    <a href="#" onclick="descargarMovimientos('pdf'); return false;" class="btn-rpt btn-rpt-pdf">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                </div>
            </div>
        </div>

        {{-- Vista previa interactiva --}}
        <div class="rpt-preview-section" id="preview-live-movimientos">
            <div class="rpt-preview-header">
                <i class="fas fa-eye" style="color:var(--primary); font-size:17px;"></i>
                <h4>Vista Previa en Tiempo Real</h4>
                <span class="badge-live">En vivo</span>
                <span style="font-size:12px; color:var(--text-muted); margin-left:auto;">Mostrando hasta 100 registros</span>
            </div>

            <div class="rpt-preview-stats">
                <div class="rpt-pstat">
                    <div class="ps-label">Total coincidencias</div>
                    <div id="sum_preview_movs" class="ps-val">0</div>
                    <div class="ps-desc">En el período seleccionado</div>
                </div>
                <div class="rpt-pstat entradas">
                    <div class="ps-label">Total Entradas (Valor)</div>
                    <div id="sum_preview_entradas_val" class="ps-val" style="color:#059669;">Bs. 0.00</div>
                    <div class="ps-desc">Suma de ingresos</div>
                </div>
                <div class="rpt-pstat salidas">
                    <div class="ps-label">Total Salidas (Gasto)</div>
                    <div id="sum_preview_salidas_val" class="ps-val" style="color:#e53e3e;">Bs. 0.00</div>
                    <div class="ps-desc">Suma de egresos</div>
                </div>
            </div>

            <div class="rpt-table-wrap">
                <div class="rpt-table-scroll">
                    <table class="rpt-table">
                        <thead>
                            <tr>
                                <th style="width:85px;" class="text-center">N° Nota</th>
                                <th style="width:105px;">Fecha</th>
                                <th style="width:100px;">Código</th>
                                <th>Artículo</th>
                                <th style="width:85px;" class="text-center">Tipo</th>
                                <th style="width:110px;" class="text-right">Cantidad</th>
                                <th style="width:105px;" class="text-right">P. Unit.</th>
                                <th style="width:115px;" class="text-right">Total Bs.</th>
                                <th style="width:170px;">Entregado A / Por</th>
                            </tr>
                        </thead>
                        <tbody id="preview-table-body">
                            <tr>
                                <td colspan="9" class="text-center" style="padding:40px; color:var(--text-muted);">
                                    <i class="fas fa-spinner fa-spin" style="font-size:24px; margin-bottom:10px; display:block;"></i>
                                    Cargando vista previa…
                                </td>
                            </tr>
                        </tbody>
                        <tfoot id="preview-table-foot"></tfoot>
                    </table>
                </div>
            </div>

            <div class="rpt-help" style="margin-top:16px;">
                <i class="fas fa-lightbulb"></i>
                <div>
                    <strong>Tip:</strong> Para ver el historial completo de un trabajador, ve a la pestaña
                    <strong>Trabajadores</strong> y usa «Ver historial».
                </div>
            </div>
        </div>
    </div>

    {{-- ========= TAB 3: KARDEX ========= --}}
    <div class="rpt-tab-content" id="tab-kardex">
        <div class="rpt-section-title">
            <i class="fas fa-clipboard-list"></i> Kardex por Producto
        </div>
        <p class="rpt-section-desc">
            Selecciona un artículo para ver su historial detallado de entradas y salidas con saldo acumulado.
        </p>

        <div class="rpt-panel-grid">
            {{-- Panel Selección --}}
            <div class="rpt-panel">
                <div class="rpt-panel-head">
                    <i class="fas fa-sliders"></i> Parámetros de Selección
                </div>

                    <!-- Search input using native datalist -->
                    <input list="articulos-datalist" id="kardex_articulo_search" placeholder="🔍 Escribe código o nombre del artículo..." autocomplete="off" style="width: 100% !important; padding: 10px 14px !important; border: 1.5px solid var(--border) !important; border-radius: 8px !important; font-size: 14px !important; background: var(--bg-input) !important; color: var(--text-primary) !important; margin-bottom: 8px !important;">
                    <datalist id="articulos-datalist">
                        @foreach(\App\Models\Articulo::orderByRaw('CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(codigo, "/", 1), "-", -1) AS UNSIGNED)')->orderByRaw('CAST(SUBSTRING_INDEX(codigo, "/", -1) AS UNSIGNED)')->get() as $art)
                            <option value="{{ $art->codigo }} — {{ $art->nombre }}" data-id="{{ $art->id }}"></option>
                        @endforeach
                    </datalist>

                    <select id="kardex_producto" onchange="actualizarBotonesKardex()" style="display: none;">
                        <option value="">— Seleccionar artículo —</option>
                        @foreach(\App\Models\Articulo::orderByRaw('CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(codigo, "/", 1), "-", -1) AS UNSIGNED)')->orderByRaw('CAST(SUBSTRING_INDEX(codigo, "/", -1) AS UNSIGNED)')->get() as $art)
                            <option value="{{ $art->id }}">
                                {{ $art->codigo }} — {{ $art->nombre }}
                            </option>
                        @endforeach
                    </select>

                <div class="rpt-form-grid cols-2" style="margin-bottom: 12px;">
                    <div class="rpt-field">
                        <label><i class="fas fa-exchange-alt"></i> Tipo</label>
                        <select id="kardex_tipo" onchange="actualizarBotonesKardex()">
                            <option value="">Entradas y Salidas</option>
                            <option value="entrada">Solo Entradas</option>
                            <option value="salida">Solo Salidas</option>
                        </select>
                    </div>
                    <div class="rpt-field">
                        <label><i class="fas fa-clock"></i> Período</label>
                        <select id="kardex_periodo">
                            <option value="todos" selected>Todo el historial</option>
                            <option value="personalizado">Personalizado (fechas)</option>
                            <option value="diario">Hoy</option>
                            <option value="semanal">Esta semana</option>
                            <option value="mensual">Este mes</option>
                        </select>
                    </div>
                </div>

                <div id="container_fechas_kardex" class="rpt-dates-row">
                    <div class="rpt-field">
                        <label><i class="fas fa-calendar"></i> Desde</label>
                        <input type="date" id="kardex_desde" onchange="actualizarBotonesKardex()">
                    </div>
                    <div class="rpt-field">
                        <label><i class="fas fa-calendar"></i> Hasta</label>
                        <input type="date" id="kardex_hasta" onchange="actualizarBotonesKardex()">
                    </div>
                </div>
            </div>

            {{-- Panel Acciones Kardex --}}
            <div class="rpt-action-panel">
                <div class="rpt-panel-head">
                    <i class="fas fa-arrow-pointer"></i> Acciones
                </div>

                <div id="kardex_help" class="rpt-help">
                    <i class="fas fa-info-circle"></i>
                    Selecciona un artículo para habilitar las opciones de exportación y visualización.
                </div>

                <label class="rpt-checkbox-label" style="margin-bottom:8px;">
                    <input type="checkbox" id="kardex_incluir_inicial" onchange="actualizarBotonesKardex()">
                    <span><i class="fas fa-layer-group" style="color:var(--primary);"></i> Ver Stock Inicial</span>
                </label>

                <div id="preview-kardex" class="rpt-info" style="display:none;">
                    <i class="fas fa-check-circle"></i>
                    <span id="preview-kardex-text" style="font-size:12px;"></span>
                </div>

                <div class="rpt-btn-row">
                    <a href="#" onclick="descargarKardex('excel'); return false;"
                       class="btn-rpt btn-rpt-excel disabled" id="btn_kardex_excel">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                    <a href="#" onclick="descargarKardex('pdf'); return false;"
                       class="btn-rpt btn-rpt-pdf disabled" id="btn_kardex_pdf">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                </div>
                <a href="#" onclick="verKardexPantalla(); return false;"
                   class="btn-rpt btn-rpt-primary disabled" id="btn_kardex_ver"
                   style="width:100%; justify-content:center;">
                    <i class="fas fa-magnifying-glass"></i> Ver en Pantalla
                </a>
            </div>
        </div>

        {{-- Vista previa interactiva de Kardex --}}
        <div class="rpt-preview-section" id="preview-live-kardex" style="display:none; margin-top: 28px; padding-top: 24px; border-top: 1px solid var(--border);">
            <div class="rpt-preview-header">
                <i class="fas fa-eye" style="color:var(--primary); font-size:17px;"></i>
                <h4>Vista Previa del Kardex</h4>
                <span class="badge-live">En vivo</span>
                <span style="font-size:12px; color:var(--text-muted); margin-left:auto;">Mostrando hasta 100 registros</span>
            </div>

            <div class="rpt-preview-stats">
                <div class="rpt-pstat">
                    <div class="ps-label">Total Entradas (Cantidad)</div>
                    <div id="kardex_preview_entradas_cant" class="ps-val">0.00</div>
                    <div id="kardex_preview_entradas_val" class="ps-desc" style="color:#059669; font-weight:bold;">Bs. 0.00</div>
                </div>
                <div class="rpt-pstat salidas">
                    <div class="ps-label">Total Salidas (Cantidad)</div>
                    <div id="kardex_preview_salidas_cant" class="ps-val">0.00</div>
                    <div id="kardex_preview_salidas_val" class="ps-desc" style="color:#e53e3e; font-weight:bold;">Bs. 0.00</div>
                </div>
                <div class="rpt-pstat valor">
                    <div class="ps-label">Stock / Valor Actual</div>
                    <div id="kardex_preview_stock_actual" class="ps-val">0.00</div>
                    <div id="kardex_preview_valor_actual" class="ps-desc" style="color:var(--primary); font-weight:bold;">Bs. 0.00</div>
                </div>
            </div>

            <div class="rpt-table-wrap">
                <div class="rpt-table-scroll">
                    <table class="rpt-table">
                        <thead>
                            <tr>
                                <th style="width:85px;" class="text-center">N° Nota</th>
                                <th style="width:105px;">Fecha</th>
                                <th style="width:85px;" class="text-center">Tipo</th>
                                <th style="width:110px;" class="text-right">Entrada</th>
                                <th style="width:110px;" class="text-right">Salida</th>
                                <th style="width:105px;" class="text-right">P. Unit.</th>
                                <th style="width:115px;" class="text-right">Total Bs.</th>
                                <th style="width:100px;" class="text-right">Saldo</th>
                                <th style="width:170px;">Entregado A / Por</th>
                                <th>Notas</th>
                            </tr>
                        </thead>
                        <tbody id="kardex-preview-table-body">
                            <tr>
                                <td colspan="10" class="text-center" style="padding:40px; color:var(--text-muted);">
                                    <i class="fas fa-spinner fa-spin" style="font-size:24px; margin-bottom:10px; display:block;"></i>
                                    Cargando vista previa…
                                </td>
                            </tr>
                        </tbody>
                        <tfoot id="kardex-preview-table-foot"></tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ========= TAB 4: MENSUAL ========= --}}
    <div class="rpt-tab-content" id="tab-mensual">
        <div class="rpt-section-title">
            <i class="fas fa-calendar-alt"></i> Resumen Mensual
        </div>
        <p class="rpt-section-desc">
            Análisis mensual completo: KPIs económicos, top materiales consumidos, top trabajadores y comparativa con el mes anterior.
        </p>

        <div class="mes-grid">
            @forelse($resumenMensual as $periodo => $unidades)
                @php
                    $fechaMes = \Carbon\Carbon::parse($periodo . '-01');
                    $kpi = $resumenMensualKpis[$periodo] ?? null;
                @endphp
                <div class="mes-card">
                    {{-- Header --}}
                    <div class="mes-card-header">
                        <h4>
                            <i class="fas fa-calendar-days"></i>
                            {{ strtoupper($fechaMes->translatedFormat('F Y')) }}
                        </h4>
                        <div class="mes-card-actions">
                            <a href="{{ route('reportes.mes.excel', $periodo) }}" class="btn-mes btn-mes-excel">
                                <i class="fas fa-file-excel"></i> Excel
                            </a>
                            <a href="{{ route('reportes.mes.pdf', $periodo) }}" class="btn-mes btn-mes-pdf">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                        </div>
                    </div>

                    <div class="mes-card-body">

                        {{-- KPIs del mes --}}
                        @if($kpi)
                        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:8px; margin-bottom:14px;">
                            <div style="background:var(--surface-hover); border-radius:8px; padding:10px 12px; border-left:3px solid var(--primary); text-align:center;">
                                <div style="font-size:11px; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;">Movimientos</div>
                                <div style="font-size:20px; font-weight:800; color:var(--primary);">{{ $kpi['total_movs'] }}</div>
                            </div>
                            <div style="background:var(--surface-hover); border-radius:8px; padding:10px 12px; border-left:3px solid #dc2626; text-align:center;">
                                <div style="font-size:11px; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;">Salidas Bs.</div>
                                <div style="font-size:16px; font-weight:800; color:#dc2626;">{{ number_format($kpi['valor_salidas'], 0) }}</div>
                            </div>
                            <div style="background:var(--surface-hover); border-radius:8px; padding:10px 12px; border-left:3px solid #16a34a; text-align:center;">
                                <div style="font-size:11px; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;">Entradas Bs.</div>
                                <div style="font-size:16px; font-weight:800; color:#16a34a;">{{ number_format($kpi['valor_entradas'], 0) }}</div>
                            </div>
                        </div>

                        {{-- Comparativa mes anterior --}}
                        @php
                            $variacion = $kpi['variacion_porc'];
                            $signo = $variacion >= 0 ? '+' : '';
                            $colorVar = $variacion >= 0 ? '#16a34a' : '#dc2626';
                            $iconVar  = $variacion >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down';
                        @endphp
                        <div style="background:var(--surface-hover); border-radius:7px; padding:8px 12px; margin-bottom:14px; display:flex; align-items:center; gap:10px; font-size:12px;">
                            <i class="fas {{ $iconVar }}" style="color:{{ $colorVar }}; font-size:16px;"></i>
                            <span style="color:var(--text-secondary);">vs mes anterior:</span>
                            <strong style="color:{{ $colorVar }};">{{ $signo }}{{ $variacion }}%</strong>
                            <span style="color:var(--text-muted); font-size:11px;">({{ $kpi['movs_mes_anterior'] }} movs. prev.)</span>
                        </div>

                        {{-- Top 5 Materiales --}}
                        @if($kpi['top_materiales']->isNotEmpty())
                        <div style="margin-bottom:14px;">
                            <div style="font-size:11px; font-weight:700; color:var(--text-secondary); margin-bottom:7px; display:flex; align-items:center; gap:6px;">
                                <i class="fas fa-trophy" style="color:#d97706;"></i> Top 5 Materiales Consumidos
                            </div>
                            @foreach($kpi['top_materiales'] as $idx => $mat)
                            <div style="display:flex; align-items:center; gap:8px; padding:5px 0; border-bottom:1px solid var(--border);">
                                <span style="background:#1e293b; color:white; border-radius:4px; padding:1px 6px; font-size:10px; font-weight:700; min-width:18px; text-align:center;">{{ $idx+1 }}</span>
                                <span style="flex:1; font-size:12px; color:var(--text-primary);">{{ $mat->nombre }}</span>
                                <span style="font-size:11px; color:#dc2626; font-weight:700;">{{ number_format($mat->total_salida, 1) }}</span>
                                <span style="font-size:10px; color:var(--text-muted);">{{ $mat->unidad }}</span>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        {{-- Top 5 Trabajadores --}}
                        @if($kpi['top_trabajadores']->isNotEmpty())
                        <div style="margin-bottom:14px;">
                            <div style="font-size:11px; font-weight:700; color:var(--text-secondary); margin-bottom:7px; display:flex; align-items:center; gap:6px;">
                                <i class="fas fa-hard-hat" style="color:var(--primary);"></i> Top 5 Trabajadores / Contratistas
                            </div>
                            @foreach($kpi['top_trabajadores'] as $idx => $trab)
                            <div style="display:flex; align-items:center; gap:8px; padding:5px 0; border-bottom:1px solid var(--border);">
                                <span style="background:#334155; color:white; border-radius:4px; padding:1px 6px; font-size:10px; font-weight:700; min-width:18px; text-align:center;">{{ $idx+1 }}</span>
                                <span style="flex:1; font-size:12px; color:var(--text-primary);">{{ $trab->nombre }}</span>
                                <span style="font-size:11px; color:var(--primary); font-weight:700;">{{ $trab->total_movs }} salidas</span>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        {{-- Artículos agotados --}}
                        @if($kpi['agotados']->isNotEmpty())
                        <div style="margin-bottom:14px;">
                            <div style="font-size:11px; font-weight:700; color:#dc2626; margin-bottom:7px; display:flex; align-items:center; gap:6px;">
                                <i class="fas fa-exclamation-triangle"></i> Artículos Agotados este Mes
                            </div>
                            <div style="display:flex; flex-wrap:wrap; gap:5px;">
                                @foreach($kpi['agotados'] as $agot)
                                <span style="background:#fee2e2; color:#dc2626; border-radius:5px; padding:3px 8px; font-size:10px; font-weight:600;">{{ $agot->codigo }} — {{ $agot->nombre }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        @endif

                        {{-- Detalle por Unidad (tabla original) --}}
                        <div style="font-size:11px; font-weight:700; color:var(--text-secondary); margin-bottom:7px; display:flex; align-items:center; gap:6px;">
                            <i class="fas fa-table" style="color:var(--text-muted);"></i> Detalle por Unidad
                        </div>
                        <table class="mes-table">
                            <thead>
                                <tr>
                                    <th>Unidad</th>
                                    <th class="text-right">Entradas</th>
                                    <th class="text-right">Salidas</th>
                                    <th class="text-right">Neto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($unidades as $row)
                                    <tr>
                                        <td><span class="unidad-pill">{{ $row->unidad }}</span></td>
                                        <td class="text-right" style="color:#16a34a; font-weight:600;">
                                            <i class="fas fa-arrow-down" style="font-size:10px;"></i>
                                            {{ number_format($row->entradas, 2) }}
                                        </td>
                                        <td class="text-right" style="color:#dc2626; font-weight:600;">
                                            <i class="fas fa-arrow-up" style="font-size:10px;"></i>
                                            {{ number_format($row->salidas, 2) }}
                                        </td>
                                        <td class="text-right">
                                            <strong style="color:{{ ($row->entradas - $row->salidas) >= 0 ? '#16a34a' : '#dc2626' }};">
                                                {{ number_format($row->entradas - $row->salidas, 2) }}
                                            </strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div style="text-align:center; padding:60px 20px; color:var(--text-muted);">
                    <i class="fas fa-inbox" style="font-size:52px; opacity:0.25; margin-bottom:16px; display:block;"></i>
                    <p style="font-size:15px;">Sin movimientos registrados aún.</p>
                </div>
            @endforelse
        </div>
    </div>


    @if(Auth::user()->esAdmin())
    {{-- ========= TAB 5: BITÁCORA ========= --}}
    <div class="rpt-tab-content" id="tab-bitacora">
        <div class="rpt-section-title">
            <i class="fas fa-user-shield"></i> Bitácora de Auditoría
        </div>
        <p class="rpt-section-desc">
            Registro cronológico detallado de todas las operaciones realizadas en el sistema (artículos, grupos, movimientos, trabajadores).
        </p>

        {{-- Aviso de política de retención --}}
        <div style="display:flex; align-items:flex-start; gap:12px; background:#fffbeb; border:1px solid #fcd34d; border-left:4px solid #f59e0b; border-radius:8px; padding:12px 16px; margin-bottom:18px;">
            <i class="fas fa-clock" style="color:#f59e0b; font-size:18px; margin-top:1px; flex-shrink:0;"></i>
            <div>
                <strong style="color:#92400e; font-size:13px;">Retención automática: 1 mes</strong>
                <p style="margin:3px 0 0; font-size:12px; color:#78350f; line-height:1.5;">
                    Los registros de esta bitácora se eliminan automáticamente al cumplir <strong>30 días</strong>.
                    Esto mantiene el sistema ágil y protege el almacenamiento. Si necesitas conservar un registro,
                    <strong>descárgalo antes de su vencimiento</strong>.
                </p>
            </div>
        </div>
        {{-- Filtro / Búsqueda en Bitácora en Tiempo Real --}}
        <div class="rpt-action-panel" style="margin-bottom:20px; padding:18px 22px; background:var(--surface); border:1px solid var(--border); border-radius:12px;">
            <div style="display:flex; flex-wrap:wrap; gap:16px; align-items:flex-end; width:100%;">
                
                {{-- Campo Búsqueda Principal (Tallo robusto y prominente) --}}
                <div style="flex:2; min-width:260px;">
                    <label style="font-size:12px; font-weight:700; color:var(--text-secondary); margin-bottom:6px; display:block;">
                        <i class="fas fa-search" style="color:var(--primary); margin-right:4px;"></i> Buscar en la Bitácora
                    </label>
                    <div style="position:relative; display:flex; align-items:center;">
                        <i class="fas fa-search" style="position:absolute; left:14px; color:var(--text-muted); font-size:14px; pointer-events:none;"></i>
                        <input type="text" 
                               id="bitacora-search-input" 
                               name="search_logs" 
                               placeholder="Buscar por usuario, acción, IP o detalles..." 
                               value="{{ request('search_logs') }}" 
                               oninput="liveSearchBitacora()"
                               style="width:100%; height:44px; padding:0 14px 0 40px; font-size:13.5px; border-radius:10px; border:1.5px solid var(--border); background:var(--bg-main); color:var(--text-primary); transition:all 0.2s ease; box-shadow:inset 0 1px 2px rgba(0,0,0,0.03);">
                    </div>
                </div>

                {{-- Filtro Fecha Desde --}}
                <div style="flex:1; min-width:140px;">
                    <label style="font-size:12px; font-weight:700; color:var(--text-secondary); margin-bottom:6px; display:block;">
                        <i class="fas fa-calendar-day" style="color:var(--primary); margin-right:4px;"></i> Fecha Desde
                    </label>
                    <input type="date" 
                           id="bitacora-fecha-desde" 
                           name="fecha_desde" 
                           value="{{ request('fecha_desde') }}" 
                           onchange="liveSearchBitacora()"
                           style="width:100%; height:44px; padding:0 12px; font-size:13px; border-radius:10px; border:1.5px solid var(--border); background:var(--bg-main); color:var(--text-primary);">
                </div>

                {{-- Filtro Fecha Hasta --}}
                <div style="flex:1; min-width:140px;">
                    <label style="font-size:12px; font-weight:700; color:var(--text-secondary); margin-bottom:6px; display:block;">
                        <i class="fas fa-calendar-check" style="color:var(--primary); margin-right:4px;"></i> Fecha Hasta
                    </label>
                    <input type="date" 
                           id="bitacora-fecha-hasta" 
                           name="fecha_hasta" 
                           value="{{ request('fecha_hasta') }}" 
                           onchange="liveSearchBitacora()"
                           style="width:100%; height:44px; padding:0 12px; font-size:13px; border-radius:10px; border:1.5px solid var(--border); background:var(--bg-main); color:var(--text-primary);">
                </div>

                {{-- Botón Limpiar Únicamente --}}
                <div style="display:flex; align-items:center;">
                    <button type="button" 
                            id="btn-limpiar-bitacora" 
                            onclick="limpiarFiltrosBitacora()" 
                            style="height:44px; padding:0 20px; font-size:13px; font-weight:600; background:#f1f5f9; color:#475569; border:1px solid #cbd5e1; border-radius:10px; cursor:pointer; display:inline-flex; align-items:center; gap:8px; transition:all 0.2s ease;"
                            onmouseover="this.style.background='#e2e8f0';"
                            onmouseout="this.style.background='#f1f5f9';">
                        <i class="fas fa-rotate-left"></i> Limpiar
                    </button>
                </div>
            </div>
        </div>

        {{-- Resultados de Bitácora Wrapper para AJAX Swap --}}
        <div id="bitacora-results-container" style="transition: opacity 0.2s ease;">
            <div class="rpt-table-wrap">
                <div class="rpt-table-scroll">
                    <table class="rpt-table">
                        <thead>
                            <tr>
                                <th style="width:140px;">Fecha y Hora</th>
                                <th style="width:120px;">Usuario</th>
                                <th style="width:180px;">Acción</th>
                                <th>Detalles descriptivos</th>
                                <th style="width:110px;" class="text-center">Dirección IP</th>
                                <th style="width:100px;" class="text-center">Expira en</th>
                            </tr>
                        </thead>
                        <tbody id="bitacora-table-body">
                            @forelse($logs ?? [] as $log)
                                @php
                                    $diasRestantes = (int) now()->diffInDays($log->created_at->addMonth(), false);
                                    $expiraColor = $diasRestantes <= 3 ? '#dc2626' : ($diasRestantes <= 7 ? '#d97706' : '#64748b');
                                    $expiraIcon  = $diasRestantes <= 3 ? 'fa-fire' : ($diasRestantes <= 7 ? 'fa-exclamation-circle' : 'fa-hourglass-half');
                                @endphp
                                <tr>
                                    <td class="font-mono text-muted" style="font-size:12.5px;">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td><strong>{{ $log->user->name ?? 'Sistema' }}</strong></td>
                                    <td>
                                        <span class="badge-tipo" style="background:#edf2f7; color:#4a5568; font-weight:700; font-size:11px; padding:3px 8px; border-radius:6px; border:1px solid #cbd5e0;">
                                            {{ $log->accion }}
                                        </span>
                                    </td>
                                    <td style="font-size:13px; color:var(--text-secondary); line-height:1.4;">{{ $log->detalles }}</td>
                                    <td class="text-center font-mono" style="font-size:12px; color:#495057;">{{ $log->ip_address ?? '—' }}</td>
                                    <td class="text-center">
                                        <span style="display:inline-flex; align-items:center; gap:4px; font-size:11px; font-weight:700; color:{{ $expiraColor }};">
                                            <i class="fas {{ $expiraIcon }}" style="font-size:10px;"></i>
                                            @if($diasRestantes <= 0)
                                                Hoy
                                            @else
                                                {{ $diasRestantes }}d
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center" style="padding:50px; color:var(--text-muted);">
                                        <i class="fas fa-user-shield" style="font-size:42px; opacity:0.25; display:block; margin-bottom:12px;"></i>
                                        No se encontraron registros de auditoría para el filtro seleccionado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Paginación personalizada --}}
            @if(isset($logs) && $logs instanceof \Illuminate\Pagination\LengthAwarePaginator && $logs->hasPages())
                <div style="margin-top:20px;">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
    @endif

    {{-- ATAJOS --}}
    <div class="rpt-shortcuts">
        <i class="fas fa-keyboard" style="margin-right:6px;"></i>
        Atajos: <kbd>Ctrl</kbd>+<kbd>1</kbd> Inventario &nbsp;·&nbsp;
        <kbd>Ctrl</kbd>+<kbd>2</kbd> Movimientos &nbsp;·&nbsp;
        <kbd>Ctrl</kbd>+<kbd>3</kbd> Kardex &nbsp;·&nbsp;
        <kbd>Ctrl</kbd>+<kbd>4</kbd> Mensual
        @if(Auth::user()->esAdmin())
            &nbsp;·&nbsp; <kbd>Ctrl</kbd>+<kbd>5</kbd> Bitácora
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
// ==============================================
// SISTEMA DE PESTAÑAS
// ==============================================
const tabBtns     = document.querySelectorAll('.rpt-tab-btn');
const tabContents = document.querySelectorAll('.rpt-tab-content');

function activarTab(name) {
    tabBtns.forEach(b => b.classList.remove('active'));
    tabContents.forEach(c => c.classList.remove('active'));

    const btn     = document.querySelector(`.rpt-tab-btn[data-tab="${name}"]`);
    const content = document.getElementById('tab-' + name);

    if (btn && content) {
        btn.classList.add('active');
        content.classList.add('active');
        try { localStorage.setItem('rpt_ultima_tab', name); } catch(e) {}
        if (name === 'movimientos') setTimeout(cargarVistaPreviaMovimientos, 80);
    }
}

tabBtns.forEach(btn => btn.addEventListener('click', () => activarTab(btn.dataset.tab)));

// El bloque de inicialización de parámetros de la URL fue movido al final del script para evitar errores de inicialización temporal (TDZ).

// Atajos de teclado
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && !e.shiftKey && !e.altKey) {
        const mapa = {'1':'inventario','2':'movimientos','3':'kardex','4':'mensual','5':'bitacora'};
        if (mapa[e.key]) { e.preventDefault(); activarTab(mapa[e.key]); }
    }
});

// ==============================================
// VISIBILIDAD DE FECHAS
// ==============================================
function actualizarVisibilidadFechasMov() {
    const period    = document.getElementById('filtro_periodo_mov').value;
    const container = document.getElementById('container_fechas_mov');
    container.classList.toggle('visible', period === 'personalizado');
}

function actualizarVisibilidadFechasKardex() {
    const period    = document.getElementById('kardex_periodo').value;
    const container = document.getElementById('container_fechas_kardex');
    container.classList.toggle('visible', period === 'personalizado');
}

// ==============================================
// MOVIMIENTOS — PREVIEW INFO TEXT
// ==============================================
function actualizarPreviewMovimientos() {
    const trab      = document.getElementById('filtro_trabajador_mov');
    const desde     = document.getElementById('mov_desde').value;
    const hasta     = document.getElementById('mov_hasta').value;
    const tipoSel   = document.getElementById('filtro_tipo_mov');
    const textEl    = document.getElementById('preview-movimientos-text');

    const articuloSearch = document.getElementById('filtro_articulo_mov_search').value.trim();
    const articuloId     = document.getElementById('filtro_articulo_mov_id').value;

    let msg = '';
    if (tipoSel.value === 'entrada')      msg += 'Solo <strong>Entradas</strong>';
    else if (tipoSel.value === 'salida')  msg += 'Solo <strong>Salidas</strong>';
    else                                   msg += '<strong>Todos los movimientos</strong>';

    if (tipoSel.value !== 'entrada' && trab.value) {
        msg += ` de <strong>${trab.options[trab.selectedIndex].text}</strong>`;
    }

    if (articuloId && articuloSearch) {
        msg += ` · Material: <strong>${articuloSearch}</strong>`;
    }

    const period = document.getElementById('filtro_periodo_mov').value;
    if (period === 'personalizado' && (desde || hasta)) {
        msg += ` · Desde <strong>${desde || '—'}</strong> hasta <strong>${hasta || 'hoy'}</strong>`;
    } else if (period !== 'todos' && period !== 'personalizado') {
        const labels = {diario:'Hoy', semanal:'Esta semana', mensual:'Este mes'};
        msg += ` · <strong>${labels[period] || ''}</strong>`;
    }

    textEl.innerHTML = msg + '.';
}

// ==============================================
// MOVIMIENTOS — VISTA PREVIA EN VIVO
// ==============================================
let previewTimeout = null;
function cargarVistaPreviaMovimientos() {
    if (previewTimeout) clearTimeout(previewTimeout);

    previewTimeout = setTimeout(() => {
        const desde        = document.getElementById('mov_desde').value;
        const hasta        = document.getElementById('mov_hasta').value;
        const trabajadorId = document.getElementById('filtro_trabajador_mov').value;
        const tipo         = document.getElementById('filtro_tipo_mov').value;
        const incluirIni   = document.getElementById('mov_incluir_inicial').checked ? '1' : '0';
        const previewArea  = document.getElementById('preview-live-movimientos');
        const tbody        = document.getElementById('preview-table-body');
        const tfoot        = document.getElementById('preview-table-foot');

        if (!previewArea) return;
        previewArea.style.opacity = '0.6';

        const articuloId = document.getElementById('filtro_articulo_mov_id').value;

        const params = new URLSearchParams();
        if (desde)        params.append('desde', desde);
        if (hasta)        params.append('hasta', hasta);
        if (trabajadorId) params.append('trabajador_id', trabajadorId);
        if (tipo)         params.append('tipo', tipo);
        if (articuloId)   params.append('articulo_id', articuloId);
        if (incluirIni === '1') params.append('incluir_inicial', '1');

        fetch(`/reportes/movimientos/preview?${params.toString()}`)
            .then(r => r.json())
            .then(data => {
                previewArea.style.opacity = '1';
                if (data.error) { console.error(data.error); return; }

                const fmtBs   = v => 'Bs. ' + Number(v).toLocaleString('es-BO', {minimumFractionDigits:2, maximumFractionDigits:2});
                const fmtCant = v => Number(v).toLocaleString('es-BO', {minimumFractionDigits:2, maximumFractionDigits:3});

                document.getElementById('sum_preview_movs').innerText    = data.total_count + ' movs.';
                document.getElementById('sum_preview_entradas_val').innerText = fmtBs(data.entradas_valor);
                document.getElementById('sum_preview_salidas_val').innerText  = fmtBs(data.salidas_valor);

                if (data.movimientos.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="9" class="text-center" style="padding:50px; color:var(--text-muted);">
                                <i class="fas fa-search" style="font-size:36px; opacity:0.25; display:block; margin-bottom:12px;"></i>
                                No se encontraron movimientos con los filtros aplicados.
                            </td>
                        </tr>`;
                    tfoot.innerHTML = '';
                } else {
                    let html = '';
                    data.movimientos.forEach(m => {
                        const bc = m.tipo === 'entrada' ? 'badge-entrada' : 'badge-salida';
                        const bl = m.tipo === 'entrada' ? '<i class="fas fa-arrow-down"></i> ENTRADA'
                                                        : '<i class="fas fa-arrow-up"></i> SALIDA';
                        html += `
                            <tr>
                                <td class="text-center font-mono" style="font-weight:700; color:var(--primary);">${m.numero_nota}</td>
                                <td>${m.fecha_formateada}</td>
                                <td class="font-mono" style="font-weight:600; color:#555;">${m.codigo}</td>
                                <td><strong>${m.articulo_nombre}</strong></td>
                                <td class="text-center"><span class="badge-tipo ${bc}">${bl}</span></td>
                                <td class="text-right"><strong>${fmtCant(m.cantidad)}</strong> <span style="font-size:10.5px;color:#999;">${m.unidad}</span></td>
                                <td class="text-right" style="color:#16a34a; font-weight:600;">${fmtBs(m.precio_unitario)}</td>
                                <td class="text-right" style="color:var(--primary); font-weight:700;">${fmtBs(m.total)}</td>
                                <td style="font-size:12.5px;">${m.entregado_a_por}</td>
                            </tr>`;
                    });
                    tbody.innerHTML = html;

                    tfoot.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-right" style="padding:12px; color:var(--text-secondary);">
                                TOTAL (${data.total_count} registros):
                            </td>
                            <td class="text-right font-mono" style="padding:12px; color:var(--primary); font-weight:800;">${fmtCant(data.suma_cantidad)}</td>
                            <td></td>
                            <td class="text-right font-mono" style="padding:12px; color:var(--primary); font-weight:800;">${fmtBs(data.suma_valores)}</td>
                            <td></td>
                        </tr>`;
                }
            })
            .catch(err => {
                console.error('Error preview:', err);
                previewArea.style.opacity = '1';
            });
    }, 600);
}

// Escuchadores
['filtro_trabajador_mov','mov_desde','mov_hasta','mov_incluir_inicial'].forEach(id => {
    document.getElementById(id)?.addEventListener('change', () => {
        actualizarPreviewMovimientos();
        cargarVistaPreviaMovimientos();
    });
});

document.getElementById('filtro_tipo_mov').addEventListener('change', function() {
    const trab = document.getElementById('filtro_trabajador_mov');
    if (this.value === 'entrada') { trab.value = ''; trab.disabled = true; }
    else                          { trab.disabled = false; }
    actualizarPreviewMovimientos();
    cargarVistaPreviaMovimientos();
});

document.getElementById('filtro_periodo_mov').addEventListener('change', function() {
    actualizarVisibilidadFechasMov();
    const opt   = this.value;
    const desde = document.getElementById('mov_desde');
    const hasta = document.getElementById('mov_hasta');

    if (opt === 'todos')         { desde.value = ''; hasta.value = ''; }
    else if (opt !== 'personalizado') {
        const hoy = new Date();
        const fmt = d => `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
        if (opt === 'diario') {
            desde.value = fmt(hoy); hasta.value = fmt(hoy);
        } else if (opt === 'semanal') {
            const diff  = hoy.getDay() === 0 ? -6 : 1 - hoy.getDay();
            const lunes = new Date(hoy); lunes.setDate(hoy.getDate() + diff);
            const dom   = new Date(lunes); dom.setDate(lunes.getDate() + 6);
            desde.value = fmt(lunes); hasta.value = fmt(dom);
        } else if (opt === 'mensual') {
            desde.value = fmt(new Date(hoy.getFullYear(), hoy.getMonth(), 1));
            hasta.value = fmt(new Date(hoy.getFullYear(), hoy.getMonth() + 1, 0));
        }
    }
    actualizarPreviewMovimientos();
    cargarVistaPreviaMovimientos();
});

function descargarMovimientos(formato) {
    const desde       = document.getElementById('mov_desde').value;
    const hasta       = document.getElementById('mov_hasta').value;
    const trab        = document.getElementById('filtro_trabajador_mov').value;
    const tipo        = document.getElementById('filtro_tipo_mov').value;
    const ini         = document.getElementById('mov_incluir_inicial').checked ? '1' : '0';
    const articuloId  = document.getElementById('filtro_articulo_mov_id').value;

    let url = formato === 'excel'
        ? "{{ route('reportes.movimientos.excel') }}"
        : "{{ route('reportes.movimientos.pdf') }}";

    const p = [];
    if (desde)       p.push('desde='  + desde);
    if (hasta)       p.push('hasta='  + hasta);
    if (trab)        p.push('trabajador_id=' + trab);
    if (tipo)        p.push('tipo='   + tipo);
    if (articuloId)  p.push('articulo_id=' + articuloId);
    if (ini === '1') p.push('incluir_inicial=1');
    if (p.length) url += '?' + p.join('&');
    window.location.href = url;
}

// ==============================================
// FILTRO MATERIAL EN MOVIMIENTOS
// ==============================================
const _movArticulosMap = {};
document.querySelectorAll('#mov-articulos-datalist option').forEach(opt => {
    _movArticulosMap[opt.value] = opt.getAttribute('data-id');
});

function onFiltroArticuloMovInput(val) {
    const id = _movArticulosMap[val] || '';
    document.getElementById('filtro_articulo_mov_id').value = id;
    document.getElementById('btn_limpiar_articulo_mov').style.display = id ? 'block' : 'none';
    actualizarPreviewMovimientos();
    cargarVistaPreviaMovimientos();
}

function limpiarFiltroArticuloMov() {
    document.getElementById('filtro_articulo_mov_search').value = '';
    document.getElementById('filtro_articulo_mov_id').value = '';
    document.getElementById('btn_limpiar_articulo_mov').style.display = 'none';
    actualizarPreviewMovimientos();
    cargarVistaPreviaMovimientos();
}

// ==============================================
// KARDEX
// ==============================================
function actualizarBotonesKardex() {
    const prodSel   = document.getElementById('kardex_producto');
    const prodId    = prodSel.value;
    const btnExcel  = document.getElementById('btn_kardex_excel');
    const btnPdf    = document.getElementById('btn_kardex_pdf');
    const btnVer    = document.getElementById('btn_kardex_ver');
    const helpEl    = document.getElementById('kardex_help');
    const prevEl    = document.getElementById('preview-kardex');
    const prevText  = document.getElementById('preview-kardex-text');

    if (prodId) {
        [btnExcel, btnPdf, btnVer].forEach(b => b.classList.remove('disabled'));
        helpEl.style.display = 'none';
        prevEl.style.display = 'flex';

        const nombre = prodSel.options[prodSel.selectedIndex].text;
        const tipo   = document.getElementById('kardex_tipo');
        const tipoTx = tipo.value === 'entrada' ? ' — Solo Entradas'
                     : tipo.value === 'salida'  ? ' — Solo Salidas' : '';
        const desde  = document.getElementById('kardex_desde').value;
        const hasta  = document.getElementById('kardex_hasta').value;
        let periTx   = '';
        if (desde || hasta) periTx = ` · Período: <strong>${desde || '—'}</strong> al <strong>${hasta || 'hoy'}</strong>`;
        prevText.innerHTML = `Kardex de <strong>${nombre}</strong>${tipoTx}.${periTx}`;

        // Mostrar vista previa en vivo
        document.getElementById('preview-live-kardex').style.display = 'block';
        cargarVistaPreviaKardex();
    } else {
        [btnExcel, btnPdf, btnVer].forEach(b => b.classList.add('disabled'));
        helpEl.style.display = 'flex';
        prevEl.style.display = 'none';

        // Ocultar vista previa en vivo
        document.getElementById('preview-live-kardex').style.display = 'none';
    }
}

let kardexPreviewTimeout = null;
function cargarVistaPreviaKardex() {
    if (kardexPreviewTimeout) clearTimeout(kardexPreviewTimeout);

    kardexPreviewTimeout = setTimeout(() => {
        const id = document.getElementById('kardex_producto').value;
        if (!id) return;

        const desde          = document.getElementById('kardex_desde').value;
        const hasta          = document.getElementById('kardex_hasta').value;
        const tipo           = document.getElementById('kardex_tipo').value;
        const incluirInicial = document.getElementById('kardex_incluir_inicial')?.checked ? '1' : '0';
        const previewArea    = document.getElementById('preview-live-kardex');
        const tbody          = document.getElementById('kardex-preview-table-body');
        const tfoot          = document.getElementById('kardex-preview-table-foot');

        if (!previewArea) return;
        previewArea.style.opacity = '0.6';

        const params = new URLSearchParams();
        params.append('articulo_id', id);
        if (desde)                  params.append('desde', desde);
        if (hasta)                  params.append('hasta', hasta);
        if (tipo)                   params.append('tipo', tipo);
        if (incluirInicial === '1') params.append('incluir_inicial', '1');

        fetch(`/reportes/kardex/preview?${params.toString()}`)
            .then(r => r.json())
            .then(data => {
                previewArea.style.opacity = '1';
                if (data.error) {
                    console.error(data.error);
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="10" class="text-center" style="padding:40px; color:var(--text-danger); font-weight:600;">
                                <i class="fas fa-exclamation-circle" style="font-size:28px; display:block; margin-bottom:8px;"></i>
                                Error: ${data.error}
                            </td>
                        </tr>`;
                    return;
                }

                const fmtBs   = v => 'Bs. ' + Number(v).toLocaleString('es-BO', {minimumFractionDigits:2, maximumFractionDigits:2});
                const fmtCant = v => Number(v).toLocaleString('es-BO', {minimumFractionDigits:2, maximumFractionDigits:3});

                // Actualizar estadísticas rápidas
                const u = data.articulo.unidad;
                document.getElementById('kardex_preview_entradas_cant').innerHTML = `<strong>${fmtCant(data.estadisticas.total_entradas)}</strong> <span style="font-size:10.5px;color:#999;">${u}</span>`;
                document.getElementById('kardex_preview_entradas_val').innerText = fmtBs(data.estadisticas.entradas_valor);

                document.getElementById('kardex_preview_salidas_cant').innerHTML = `<strong>${fmtCant(data.estadisticas.total_salidas)}</strong> <span style="font-size:10.5px;color:#999;">${u}</span>`;
                document.getElementById('kardex_preview_salidas_val').innerText = fmtBs(data.estadisticas.salidas_valor);

                document.getElementById('kardex_preview_stock_actual').innerHTML = `<strong>${fmtCant(data.articulo.stock_actual)}</strong> <span style="font-size:10.5px;color:#999;">${u}</span>`;
                document.getElementById('kardex_preview_valor_actual').innerText = fmtBs(data.articulo.valor_actual);

                if (data.movimientos.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="10" class="text-center" style="padding:50px; color:var(--text-muted);">
                                <i class="fas fa-search" style="font-size:36px; opacity:0.25; display:block; margin-bottom:12px;"></i>
                                No se encontraron movimientos para este artículo en el rango de fechas.
                            </td>
                        </tr>`;
                    tfoot.innerHTML = '';
                } else {
                    let html = '';
                    data.movimientos.forEach(m => {
                        const bc = m.tipo === 'entrada' ? 'badge-entrada' : 'badge-salida';
                        const bl = m.tipo === 'entrada' ? '<i class="fas fa-arrow-down"></i> ENTRADA'
                                                        : '<i class="fas fa-arrow-up"></i> SALIDA';

                        const entradaVal = m.tipo === 'entrada' ? `+ ${fmtCant(m.entrada)}` : '—';
                        const salidaVal  = m.tipo === 'salida'  ? `− ${fmtCant(m.salida)}` : '—';

                        html += `
                            <tr>
                                <td class="text-center font-mono" style="font-weight:700; color:var(--primary);">${m.numero_nota}</td>
                                <td>${m.fecha_formateada}</td>
                                <td class="text-center"><span class="badge-tipo ${bc}">${bl}</span></td>
                                <td class="text-right num-entrada"><strong>${entradaVal}</strong></td>
                                <td class="text-right num-salida"><strong>${salidaVal}</strong></td>
                                <td class="text-right" style="color:#2b8a3e; font-weight:600;">${fmtBs(m.precio_unitario)}</td>
                                <td class="text-right" style="color:#1971c2; font-weight:700;">${fmtBs(m.total)}</td>
                                <td class="text-right font-mono" style="font-weight:700;"><span class="num-saldo" style="background:#f7fafc; padding:3px 8px; border-radius:6px;">${fmtCant(m.saldo_acumulado)}</span></td>
                                <td style="font-size:12.5px;">${m.entregado_a_por}</td>
                                <td style="color:#666; font-size:12px;">${m.notas || '—'}</td>
                            </tr>`;
                    });
                    tbody.innerHTML = html;

                    // Totales del footer de la tabla de vista previa
                    const showEntradas = (tipo === '' || tipo === 'entrada');
                    const showSalidas  = (tipo === '' || tipo === 'salida');

                    const footerEntradasHtml = showEntradas ? `+ ${fmtCant(data.estadisticas.total_entradas)}` : '—';
                    const footerSalidasHtml  = showSalidas  ? `− ${fmtCant(data.estadisticas.total_salidas)}` : '—';

                    let footerValorHtml = '';
                    if (showEntradas) {
                        footerValorHtml += `<div style="color: #16a34a; margin-bottom: 2px;">Entrada: ${fmtBs(data.estadisticas.entradas_valor)}</div>`;
                    }
                    if (showSalidas) {
                        footerValorHtml += `<div style="color: #dc2626;">Salida: ${fmtBs(data.estadisticas.salidas_valor)}</div>`;
                    }

                    tfoot.innerHTML = `
                        <tr style="background: var(--bg-hover); font-weight: bold; border-top: 2px solid var(--border); border-bottom: 2px solid var(--border);">
                            <td colspan="3" class="text-right" style="padding:12px; color:var(--text-secondary);">
                                TOTALES:
                            </td>
                            <td class="text-right font-mono num-entrada" style="padding:12px;">${footerEntradasHtml}</td>
                            <td class="text-right font-mono num-salida" style="padding:12px;">${footerSalidasHtml}</td>
                            <td></td>
                            <td class="text-right font-mono" style="padding:12px;">${footerValorHtml}</td>
                            <td class="text-right font-mono" style="padding:12px; font-weight:bold; color:var(--text-secondary);">${fmtCant(data.articulo.stock_actual)}</td>
                            <td colspan="2"></td>
                        </tr>`;
                }
            })
            .catch(err => {
                console.error('Error preview Kardex:', err);
                previewArea.style.opacity = '1';
                tbody.innerHTML = `
                    <tr>
                        <td colspan="10" class="text-center" style="padding:40px; color:var(--text-danger); font-weight:600;">
                            <i class="fas fa-exclamation-triangle" style="font-size:28px; display:block; margin-bottom:8px;"></i>
                            Error de red o de servidor al cargar la vista previa.
                        </td>
                    </tr>`;
            });
    }, 600);
}

document.getElementById('kardex_periodo').addEventListener('change', function() {
    actualizarVisibilidadFechasKardex();
    const opt   = this.value;
    const desde = document.getElementById('kardex_desde');
    const hasta = document.getElementById('kardex_hasta');

    if (opt === 'todos') { desde.value = ''; hasta.value = ''; actualizarBotonesKardex(); return; }
    if (opt === 'personalizado') { actualizarBotonesKardex(); return; }

    const hoy = new Date();
    const fmt = d => `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
    if (opt === 'diario') {
        desde.value = fmt(hoy); hasta.value = fmt(hoy);
    } else if (opt === 'semanal') {
        const diff  = hoy.getDay() === 0 ? -6 : 1 - hoy.getDay();
        const lunes = new Date(hoy); lunes.setDate(hoy.getDate() + diff);
        const dom   = new Date(lunes); dom.setDate(lunes.getDate() + 6);
        desde.value = fmt(lunes); hasta.value = fmt(dom);
    } else if (opt === 'mensual') {
        desde.value = fmt(new Date(hoy.getFullYear(), hoy.getMonth(), 1));
        hasta.value = fmt(new Date(hoy.getFullYear(), hoy.getMonth() + 1, 0));
    }
    actualizarBotonesKardex();
});

['kardex_desde','kardex_hasta'].forEach(id => {
    document.getElementById(id)?.addEventListener('change', actualizarBotonesKardex);
});

function descargarKardex(formato) {
    const id    = document.getElementById('kardex_producto').value;
    if (!id) { alert('Selecciona un artículo.'); return; }
    const desde          = document.getElementById('kardex_desde').value;
    const hasta          = document.getElementById('kardex_hasta').value;
    const tipo           = document.getElementById('kardex_tipo').value;
    const incluirInicial = document.getElementById('kardex_incluir_inicial')?.checked ? '1' : '0';

    let url = formato === 'excel'
        ? '/reportes/kardex/' + id + '/excel'
        : '/reportes/kardex/' + id + '/pdf';
    const p = [];
    if (desde)                  p.push('desde=' + desde);
    if (hasta)                  p.push('hasta=' + hasta);
    if (tipo)                   p.push('tipo='  + tipo);
    if (incluirInicial === '1') p.push('incluir_inicial=1');
    if (p.length) url += '?' + p.join('&');
    window.location.href = url;
}

function verKardexPantalla() {
    const id = document.getElementById('kardex_producto').value;
    if (!id) return;
    const desde          = document.getElementById('kardex_desde').value;
    const hasta          = document.getElementById('kardex_hasta').value;
    const tipo           = document.getElementById('kardex_tipo').value;
    const incluirInicial = document.getElementById('kardex_incluir_inicial')?.checked ? '1' : '0';
    let url = '/reportes/kardex/' + id;
    const p = [];
    if (desde)                  p.push('desde=' + desde);
    if (hasta)                  p.push('hasta=' + hasta);
    if (tipo)                   p.push('tipo='  + tipo);
    if (incluirInicial === '1') p.push('incluir_inicial=1');
    if (p.length) url += '?' + p.join('&');
    window.location.href = url;
}

// Sincronizar el select oculto de Kardex con el buscador datalist
function sincronizarBuscadorKardex() {
    const select = document.getElementById('kardex_producto');
    const searchInput = document.getElementById('kardex_articulo_search');
    if (select && searchInput) {
        const selectedOpt = select.options[select.selectedIndex];
        if (selectedOpt && selectedOpt.value) {
            searchInput.value = selectedOpt.text.trim();
        } else {
            searchInput.value = '';
        }
    }
}

// Escuchar cambios en el input de búsqueda datalist
const searchInputKardex = document.getElementById('kardex_articulo_search');
if (searchInputKardex) {
    searchInputKardex.addEventListener('input', function(e) {
        const val = this.value;
        const datalist = document.getElementById('articulos-datalist');
        if (!datalist) return;
        const options = datalist.options;
        let selectedId = '';
        
        for (let i = 0; i < options.length; i++) {
            if (options[i].value === val) {
                selectedId = options[i].getAttribute('data-id');
                break;
            }
        }
        
        // Solo actualizar el select si se encontró una coincidencia exacta o si se limpió el buscador
        if (selectedId || val === '') {
            const select = document.getElementById('kardex_producto');
            if (select) {
                select.value = selectedId;
                select.dispatchEvent(new Event('change'));
            }
        }
    });

    // Mantener sincronizado el input de búsqueda si el select oculto cambia externamente
    document.getElementById('kardex_producto')?.addEventListener('change', sincronizarBuscadorKardex);
}

// ==============================================
// INICIALIZACIÓN
// ==============================================
// ==============================================
// FILTRO DE STOCK Y GRUPO DE INVENTARIO
// ==============================================
const filtroStock = document.getElementById('filtro_stock_inventario');
const filtroGrupo = document.getElementById('filtro_grupo_inventario');

if (filtroStock && filtroGrupo) {
    const labelCount = document.getElementById('rpt-inv-count-label');
    const linkExcel = document.getElementById('btn-rpt-excel-link');
    const linkPdf = document.getElementById('btn-rpt-pdf-link');
    
    const baseUrlExcel = "{{ route('reportes.inventario.excel') }}";
    const baseUrlPdf = "{{ route('reportes.inventario.pdf') }}";

    function actualizarEnlacesInventario() {
        const valStock = filtroStock.value;
        const valGrupo = filtroGrupo.value;
        
        const optStock = filtroStock.options[filtroStock.selectedIndex];
        const optGrupo = filtroGrupo.options[filtroGrupo.selectedIndex];
        
        const descStock = valStock === 'con_stock' ? 'con stock activo (cantidad > 0)' : (valStock === 'sin_stock' ? 'sin stock (cantidad ≤ 0)' : 'con y sin stock');
        const descGrupo = valGrupo === 'todos' ? 'todos los grupos' : `el grupo "${optGrupo.text.split(' (')[0]}"`;

        if (labelCount) {
            labelCount.innerHTML = `El reporte incluirá materiales filtados por: stock <strong>(${descStock})</strong> y pertenecientes a <strong>(${descGrupo})</strong>.`;
        }
        
        if (linkExcel) linkExcel.href = `${baseUrlExcel}?stock_filter=${valStock}&grupo_id=${valGrupo}`;
        if (linkPdf) linkPdf.href = `${baseUrlPdf}?stock_filter=${valStock}&grupo_id=${valGrupo}`;
    }

    filtroStock.addEventListener('change', actualizarEnlacesInventario);
    filtroGrupo.addEventListener('change', actualizarEnlacesInventario);
    
    // Disparar inicialmente
    actualizarEnlacesInventario();
}

const selectPeriodoMov = document.getElementById('filtro_periodo_mov');
if (selectPeriodoMov) {
    selectPeriodoMov.dispatchEvent(new Event('change'));
} else {
    actualizarVisibilidadFechasMov();
}
actualizarVisibilidadFechasKardex();
sincronizarBuscadorKardex();

// ==============================================
// INICIALIZACIÓN DE PARÁMETROS DE URL
// ==============================================
const urlParams = new URLSearchParams(window.location.search);
const paramTab = urlParams.get('tab');
const paramArticuloId = urlParams.get('articuloId');
const paramDesde = urlParams.get('desde');
const paramHasta = urlParams.get('hasta');
const paramTipo = urlParams.get('tipo');
const paramTrabajadorId = urlParams.get('trabajador_id');

const paramLogsPage = urlParams.get('logs_page');
const paramSearchLogs = urlParams.get('search_logs');
const paramFechaDesde = urlParams.get('fecha_desde');
const paramFechaHasta = urlParams.get('fecha_hasta');

if (paramLogsPage || paramSearchLogs || paramFechaDesde || paramFechaHasta) {
    activarTab('bitacora');
} else if (paramTab) {
    activarTab(paramTab);
    if (paramTab === 'kardex') {
        if (paramArticuloId) {
            const select = document.getElementById('kardex_producto');
            if (select) {
                select.value = paramArticuloId;
                sincronizarBuscadorKardex(); // Sincroniza el buscador visible inmediatamente
            }
        }
        if (paramTipo) {
            const selectTipo = document.getElementById('kardex_tipo');
            if (selectTipo) selectTipo.value = paramTipo;
        }
        if (paramDesde) {
            const inputDesde = document.getElementById('kardex_desde');
            if (inputDesde) inputDesde.value = paramDesde;
        }
        if (paramHasta) {
            const inputHasta = document.getElementById('kardex_hasta');
            if (inputHasta) inputHasta.value = paramHasta;
        }
        if (paramDesde || paramHasta) {
            const selectPeriodo = document.getElementById('kardex_periodo');
            if (selectPeriodo) {
                selectPeriodo.value = 'personalizado';
                actualizarVisibilidadFechasKardex();
            }
        }
        actualizarBotonesKardex();
    } else if (paramTab === 'movimientos') {
        if (paramTrabajadorId) {
            const selectTrab = document.getElementById('filtro_trabajador_mov');
            if (selectTrab) selectTrab.value = paramTrabajadorId;
        }
        if (paramDesde) {
            const inputDesde = document.getElementById('mov_desde');
            if (inputDesde) inputDesde.value = paramDesde;
        }
        if (paramHasta) {
            const inputHasta = document.getElementById('mov_hasta');
            if (inputHasta) inputHasta.value = paramHasta;
        }
        if (paramDesde || paramHasta) {
            const selectPeriodo = document.getElementById('filtro_periodo_mov');
            if (selectPeriodo) {
                selectPeriodo.value = 'personalizado';
                actualizarVisibilidadFechasMov();
            }
        }
    }
} else {
    try {
        activarTab(localStorage.getItem('rpt_ultima_tab') || 'inventario');
    } catch(e) {
        activarTab('inventario');
    }
}

// ==============================================
// BÚSQUEDA Y FILTRADO EN TIEMPO REAL - BITÁCORA
// ==============================================
let bitacoraDebounceTimer = null;

function liveSearchBitacora() {
    clearTimeout(bitacoraDebounceTimer);

    // 1. Filtrado instantáneo visual en la tabla actual
    const termInput = document.getElementById('bitacora-search-input');
    const term = termInput ? termInput.value.toLowerCase().trim() : '';
    const rows = document.querySelectorAll('#bitacora-table-body tr');

    rows.forEach(tr => {
        if (tr.children.length > 1) { // No filtrar la fila vacía
            const content = tr.innerText.toLowerCase();
            tr.style.display = content.includes(term) ? '' : 'none';
        }
    });

    // 2. Consulta AJAX debounced con servidor para paginación y rango de fechas
    bitacoraDebounceTimer = setTimeout(() => {
        ejecutarBusquedaBitacoraServer();
    }, 350);
}

function ejecutarBusquedaBitacoraServer() {
    const textInput  = document.getElementById('bitacora-search-input');
    const desdeInput = document.getElementById('bitacora-fecha-desde');
    const hastaInput = document.getElementById('bitacora-fecha-hasta');

    const search = textInput ? textInput.value : '';
    const desde  = desdeInput ? desdeInput.value : '';
    const hasta  = hastaInput ? hastaInput.value : '';

    const params = new URLSearchParams();
    params.set('tab', 'bitacora');
    if (search) params.set('search_logs', search);
    if (desde)  params.set('fecha_desde', desde);
    if (hasta)  params.set('fecha_hasta', hasta);

    const targetUrl = `${window.location.pathname}?${params.toString()}`;
    const container = document.getElementById('bitacora-results-container');

    if (container) container.style.opacity = '0.5';

    fetch(targetUrl, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newContainer = doc.getElementById('bitacora-results-container');
        if (container && newContainer) {
            container.innerHTML = newContainer.innerHTML;
        }
        if (container) container.style.opacity = '1';
        window.history.replaceState({}, '', targetUrl);
    })
    .catch(err => {
        if (container) container.style.opacity = '1';
    });
}

function limpiarFiltrosBitacora() {
    const textInput  = document.getElementById('bitacora-search-input');
    const desdeInput = document.getElementById('bitacora-fecha-desde');
    const hastaInput = document.getElementById('bitacora-fecha-hasta');

    if (textInput)  textInput.value = '';
    if (desdeInput) desdeInput.value = '';
    if (hastaInput) hastaInput.value = '';

    ejecutarBusquedaBitacoraServer();
}
</script>
@endpush