@extends('layouts.mina')

@section('titulo', 'Movimientos')

@push('styles')

<style>
    .nota-badge {
        font-family: 'Courier New', monospace;
        font-weight: bold;
        background: #e7f5ff;
        color: #1971c2;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
    }
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .page-title {
        color: #667eea;
        font-size: 24px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .page-counter {
        background: #f0e9ff;
        color: #667eea;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .filters {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: flex-end;
    }
    .filters .form-field {
        flex: 1 1 160px;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    .filters .form-field.search-field {
        flex: 2 1 260px;
    }
    .filters .form-field.date-field {
        display: none;
    }
    .filters .form-field.date-field.visible {
        display: flex;
    }
    .filters .btn-group {
        flex: 0 0 auto;
    }

    .filters .form-field { display: flex; flex-direction: column; gap: 5px; }
    .filters label {
        font-size: 12px; font-weight: 600; color: #555;
        text-transform: uppercase;
    }
    .filters input, .filters select {
        padding: 10px 14px; border: 2px solid #e0e0e0;
        border-radius: 8px; font-size: 14px;
    }

    .btn {
        padding: 10px 18px; border: none; border-radius: 8px;
        font-size: 14px; font-weight: 500; cursor: pointer;
        transition: all 0.3s; display: inline-flex; align-items: center;
        gap: 6px; text-decoration: none;
    }
    .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4); }
    .btn-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; }
    .btn-success:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(17, 153, 142, 0.4); }
    .btn-secondary { background: #f0f0f0; color: #555; }

    .btn-group { display: flex; gap: 8px; }

    .table-container {
        background: white; border-radius: 10px;
        overflow-x: auto; box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    table { width: 100%; border-collapse: collapse; }
    table th {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 14px 12px; text-align: left;
        font-weight: 600; color: #333;
        border-bottom: 2px solid #e0e0e0;
        font-size: 13px; text-transform: uppercase;
    }
    table td { padding: 14px 12px; border-bottom: 1px solid #f0f0f0; }
    table tbody tr:hover { background: #faf9ff; }

    .codigo {
        font-family: 'Outfit', monospace !important;
        font-weight: 700 !important;
        background: rgba(217,119,6,0.08) !important;
        color: var(--primary) !important;
        -webkit-text-fill-color: var(--primary) !important;
        padding: 4px 10px !important;
        border-radius: 8px !important;
        white-space: nowrap !important;
        display: inline-block !important;
        font-size: 13.5px !important;
        letter-spacing: 0.5px !important;
        border: 1px solid rgba(217,119,6,0.18) !important;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .badge-success { background: #d3f9d8; color: #2b8a3e; }
    .badge-danger { background: #ffe3e3; color: #862e2e; }

    .trabajador-tag {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #fff3bf;
        color: #7f6b0d;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }
    .entrega-info-tag {
        display: inline-flex;
        flex-direction: column;
        gap: 3px;
        font-size: 11px;
        text-align: left;
    }
    .entrega-info-tag .tag-item {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        line-height: 1.2;
    }
    .entrega-info-tag .entregado-txt {
        color: #0369a1;
        background: rgba(3, 105, 161, 0.08);
        border: 1px solid rgba(3, 105, 161, 0.15);
        padding: 3px 8px;
        border-radius: 8px;
        font-weight: 600;
    }
    .entrega-info-tag .recibido-txt {
        color: #047857;
        background: rgba(4, 120, 87, 0.08);
        border: 1px solid rgba(4, 120, 87, 0.15);
        padding: 3px 8px;
        border-radius: 8px;
        font-weight: 600;
    }
    [data-theme="dark"] .entrega-info-tag .entregado-txt {
        color: #7dd3fc !important;
        background: rgba(3, 105, 161, 0.15) !important;
        border-color: rgba(3, 105, 161, 0.25) !important;
    }
    [data-theme="dark"] .entrega-info-tag .recibido-txt {
        color: #6ee7b7 !important;
        background: rgba(4, 120, 87, 0.15) !important;
        border-color: rgba(4, 120, 87, 0.25) !important;
    }

    .empty { text-align: center; padding: 50px 20px; color: #999; }
    .empty i {
        font-size: 64px; opacity: 0.3; margin-bottom: 15px;
        animation: floatIcon 3s ease-in-out infinite;
    }
    @keyframes floatIcon {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    /* MODAL */
    .modal {
        display: none;
        position: fixed; top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(4px);
        z-index: 1000;
        align-items: center; justify-content: center;
        animation: fadeIn 0.2s;
    }
    .modal.active { display: flex; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

    .modal-content {
        background: var(--bg-card) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
        border: 1.5px solid var(--border) !important;
        border-radius: 16px; padding: 30px;
        max-width: 960px; width: 95%;
        max-height: 90vh; overflow-y: auto;
        box-shadow: var(--shadow-lg) !important;
        animation: slideDown 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        color: var(--text-primary) !important;
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .modal-header {
        font-size: 20px; font-weight: 800; margin-bottom: 20px;
        display: flex; justify-content: space-between; align-items: center;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -0.01em;
    }
    .modal-close {
        background: none; border: none; font-size: 28px;
        cursor: pointer; color: var(--text-muted);
    }

    .form-group { margin-bottom: 20px; }
    .form-group label {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        font-weight: 700;
        color: var(--text-primary) !important;
        font-size: 15px;
        letter-spacing: -0.01em;
    }
    .form-group label i {
        color: var(--primary) !important;
        margin-right: 8px;
        font-size: 16.5px;
        opacity: 0.95;
    }
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%; padding: 14px 16px;
        border: 1.5px solid var(--border) !important;
        border-radius: 12px;
        font-size: 15.5px; font-weight: 500; font-family: inherit;
        background: var(--bg-input) !important;
        color: var(--text-primary) !important;
        transition: all 0.2s ease;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.02);
    }
    .form-group input::placeholder {
        color: var(--text-muted) !important;
        opacity: 0.75;
    }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 3.5px rgba(217, 119, 6, 0.22) !important;
        background: var(--bg-card) !important;
    }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; align-items: start; }
    .modal-footer { display: flex; gap: 10px; margin-top: 25px; }

    .error-list {
        background: #ffe3e3; border-left: 4px solid #ff6b6b;
        padding: 12px 16px; border-radius: 8px; margin-bottom: 18px;
        color: #862e2e; font-size: 13px;
    }

    /* Campo de trabajador con animación */
    .field-trabajador {
        background: #fff8e1;
        padding: 15px;
        border-radius: 10px;
        border: 2px solid #ffd54f;
        margin-bottom: 18px;
        transition: all 0.3s;
    }

    .oculto {
        display: none !important;
    }

    .numero-nota-display {
        background: linear-gradient(135deg, #e7f5ff 0%, #d0ebff 100%);
        border: 2px dashed #4dabf7;
        border-radius: 8px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-family: 'Courier New', monospace;
        font-weight: bold;
        font-size: 18px;
        color: #1971c2;
    }

    .numero-nota-display i {
        font-size: 16px;
    }

    .field-trabajador label {
        color: #7f6b0d !important;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .alert-warning {
        background: #fff3bf;
        color: #7f6b0d;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 12px;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    @media (max-width: 768px) {
        .filters { flex-direction: column; align-items: stretch; }
        .filters .form-field { flex: 1 1 auto; }
        .form-row { grid-template-columns: 1fr; }
    }

    .buscador-container {
        position: relative;
    }

    .buscador-input {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s;
    }

    .buscador-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.15);
    }

    .buscador-lista {
        display: none;
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        min-width: 300px;
        right: 0;
        background: var(--bg-card, #fff);
        border: 1.5px solid var(--border, #e2e8f0);
        border-radius: 10px;
        max-height: 250px;
        overflow-y: auto;
        z-index: 999999;
        box-shadow: 0 14px 36px rgba(0,0,0,0.22), 0 4px 12px rgba(0,0,0,0.08);
    }

    .buscador-lista.activo {
        display: block;
    }

    .buscador-item {
        padding: 9px 14px;
        cursor: pointer;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13.5px;
        color: var(--text-primary);
        transition: background .12s;
    }

    .buscador-item:last-child {
        border-bottom: none;
    }

    .buscador-item:hover {
        background: rgba(99,102,241,0.07);
    }

    .buscador-item.oculto {
        display: none;
    }

    .buscador-item strong {
        color: #6366f1;
        min-width: 82px;
        font-size: 12px;
        font-family: monospace;
        background: rgba(99,102,241,0.08);
        padding: 2px 6px;
        border-radius: 4px;
    }

    [data-theme="dark"] .buscador-input {
        background: #1e293b;
        color: #e2e8f0;
        border-color: #334155;
    }

    [data-theme="dark"] .buscador-lista {
        background: #1e293b;
        border-color: #334155;
    }

    [data-theme="dark"] .buscador-item {
        color: #e2e8f0;
        border-bottom-color: #334155;
    }

    [data-theme="dark"] .buscador-item:hover {
        background: #334155;
    }

    .lote-opcion {
        padding: 10px 14px;
        cursor: pointer;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        transition: background .12s;
    }

    .lote-opcion:last-child {
        border-bottom: none;
    }

    .lote-opcion:hover {
        background: rgba(5,150,105,0.06);
    }

    .lote-trigger:hover {
        border-color: var(--primary, #d97706) !important;
    }

    /* ── Dark mode: modal Registrar Movimiento ─────────────── */
    [data-theme="dark"] .form-group label {
        color: var(--text-primary) !important;
    }

    [data-theme="dark"] .numero-nota-display {
        background: var(--bg-input) !important;
        color: #818cf8 !important;
        border-color: #6366f1 !important;
    }

    [data-theme="dark"] .numero-nota-display i {
        color: #818cf8 !important;
    }

    [data-theme="dark"] .field-trabajador {
        background: rgba(245, 159, 0, 0.1) !important;
        border-color: rgba(245, 159, 0, 0.4) !important;
    }

    [data-theme="dark"] .field-trabajador label {
        color: #fbd38d !important;
    }

    [data-theme="dark"] .alert-warning {
        background: rgba(245, 159, 0, 0.12) !important;
        color: #fbd38d !important;
    }

    [data-theme="dark"] #articuloSeleccionado,
    [data-theme="dark"] #trabajadorSeleccionado {
        background: rgba(99, 102, 241, 0.15) !important;
        border: 1px solid rgba(99, 102, 241, 0.3) !important;
    }

    [data-theme="dark"] #articuloSeleccionado strong,
    [data-theme="dark"] #trabajadorSeleccionado strong {
        color: var(--text-primary) !important;
    }

    [data-theme="dark"] #articuloSeleccionado i,
    [data-theme="dark"] #trabajadorSeleccionado i {
        color: #818cf8 !important;
    }

    [data-theme="dark"] #precioInfo {
        color: var(--text-muted) !important;
    }

    [data-theme="dark"] #precioInfo strong {
        color: var(--text-primary) !important;
    }

    [data-theme="dark"] #unidadHint,
    [data-theme="dark"] #precioHint {
        color: var(--primary) !important;
    }

    [data-theme="dark"] #avisoUnidad {
        background: rgba(245, 159, 0, 0.12) !important;
        color: #fbd38d !important;
    }

    /* ── Dark mode: tabla de movimientos ───────────────────── */
    [data-theme="dark"] .nota-badge {
        background: rgba(25, 113, 194, 0.2) !important;
        color: #74c0fc !important;
    }

    [data-theme="dark"] .trabajador-tag {
        background: rgba(245, 159, 0, 0.15) !important;
        color: #fbd38d !important;
    }

    /* ── Columnas numéricas destacadas con colores suaves ── */
    .col-cantidad {
        background-color: rgba(59, 130, 246, 0.05) !important;
        border-left: 1.5px solid rgba(0, 0, 0, 0.05) !important;
        border-right: 1.5px solid rgba(0, 0, 0, 0.05) !important;
    }
    .col-precio-unit {
        background-color: rgba(16, 185, 129, 0.06) !important;
        border-right: 1.5px solid rgba(0, 0, 0, 0.05) !important;
    }
    .col-valor-total {
        background-color: rgba(99, 102, 241, 0.07) !important;
        border-right: 1.5px solid rgba(0, 0, 0, 0.05) !important;
    }

    [data-theme="dark"] .col-cantidad {
        background-color: rgba(96, 165, 250, 0.08) !important;
        border-left: 1.5px solid rgba(255, 255, 255, 0.08) !important;
        border-right: 1.5px solid rgba(255, 255, 255, 0.08) !important;
    }
    [data-theme="dark"] .col-precio-unit {
        background-color: rgba(52, 211, 153, 0.09) !important;
        border-right: 1.5px solid rgba(255, 255, 255, 0.08) !important;
    }
    [data-theme="dark"] .col-valor-total {
        background-color: rgba(129, 140, 248, 0.10) !important;
        border-right: 1.5px solid rgba(255, 255, 255, 0.08) !important;
    }

    /* Ajuste en la etiqueta del precio anterior */
    .precio-anterior-line {
        font-size: 10.5px !important;
        color: #71717a !important;
        font-weight: 600 !important;
        white-space: nowrap !important;
        margin-top: 1px !important;
        display: inline-flex !important;
        align-items: center !important;
    }
    .precio-anterior-line .antes-label {
        opacity: 0.75 !important;
        margin-right: 3px !important;
    }
    .precio-anterior-line .antes-num {
        font-family: 'Courier New', monospace !important;
        color: #4b5563 !important;
    }
    [data-theme="dark"] .precio-anterior-line {
        color: #a1a1aa !important;
    }
    [data-theme="dark"] .precio-anterior-line .antes-num {
        color: #d1d5db !important;
    }
</style>
@endpush

@section('contenido')

    <div class="page-header">
        <h2 class="page-title">
            <i class="fas fa-exchange-alt"></i> Movimientos
            <span id="header-registros-counter" class="page-counter">{{ $movimientos->total() }} registros</span>
        </h2>
        @if(Auth::user()->puedeEditar())
            <button class="btn btn-success" onclick="abrirModal()">
                <i class="fas fa-plus"></i> Registrar Movimiento
            </button>
        @endif
    </div>

    <form method="GET" action="{{ route('movimientos.index') }}" class="filters">
        <div class="form-field search-field">
            <label><i class="fas fa-search"></i> Buscar artículo</label>
            <input type="text" name="buscar" placeholder="Código o nombre..." value="{{ request('buscar') }}">
        </div>

        @if(request('tipo'))
            <input type="hidden" name="tipo" value="{{ request('tipo') }}">
        @else
            <div class="form-field">
                <label><i class="fas fa-tag"></i> Tipo</label>
                <select name="tipo">
                    <option value="">Todos</option>
                    <option value="entrada" {{ request('tipo') == 'entrada' ? 'selected' : '' }}>Entradas</option>
                    <option value="salida" {{ request('tipo') == 'salida' ? 'selected' : '' }}>Salidas</option>
                </select>
            </div>
        @endif

        @if(request('tipo') !== 'entrada')
            <div class="form-field">
                <label><i class="fas fa-hard-hat"></i> Trabajador</label>
                <select name="trabajador_id">
                    <option value="">Todos</option>
                    @foreach($trabajadores as $t)
                        <option value="{{ $t->id }}" {{ request('trabajador_id') == $t->id ? 'selected' : '' }}>
                            {{ $t->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="form-field">
            <label><i class="fas fa-clock"></i> Período</label>
            <select name="periodo" id="filtro_periodo">
                <option value="todos" {{ request('periodo') == 'todos' ? 'selected' : '' }}>Todo el historial</option>
                <option value="personalizado" {{ request('periodo') == 'personalizado' ? 'selected' : '' }}>Personalizado</option>
                <option value="diario" {{ request('periodo') == 'diario' ? 'selected' : '' }}>Hoy</option>
                <option value="semanal" {{ request('periodo') == 'semanal' ? 'selected' : '' }}>Esta semana</option>
                <option value="mensual" {{ (!request()->has('periodo') || request('periodo') === 'mensual') ? 'selected' : '' }}>Este mes</option>
            </select>
        </div>

        <div class="form-field date-field" id="container_desde">
            <label><i class="fas fa-calendar"></i> Desde</label>
            <input type="date" name="fecha_desde" id="fecha_desde" value="{{ request('fecha_desde') }}">
        </div>

        <div class="form-field date-field" id="container_hasta">
            <label><i class="fas fa-calendar"></i> Hasta</label>
            <input type="date" name="fecha_hasta" id="fecha_hasta" value="{{ request('fecha_hasta') }}">
        </div>

        <div class="btn-group">
            <a href="{{ route('movimientos.index') }}?tipo={{ request('tipo') }}" class="btn btn-secondary" title="Limpiar filtros" style="display: inline-flex; align-items: center; gap: 6px;">
                <i class="fas fa-times"></i> Limpiar
            </a>
        </div>
    </form>

    <div id="movimientos-table-wrapper" style="transition: opacity 0.15s ease-in-out;">
        @if($movimientos->isEmpty())
            <div class="empty">
                <i class="fas fa-inbox"></i>
                <p>No hay movimientos registrados.</p>
            </div>
        @else
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 120px;">N° Nota</th>
                            <th style="width: 130px;">Fecha y Hora</th>
                            <th>Código</th>
                            <th>Artículo</th>
                            @if(!request('tipo'))
                                <th>Tipo</th>
                            @endif
                            <th class="col-cantidad">Cantidad</th>
                            <th class="col-precio-unit">Precio Unit.</th>
                            <th class="col-valor-total">Valor Total</th>
                            <th style="width: 220px;">Personal / Entrega</th>
                            <th>Notas</th>
                            <th>Registró</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movimientos as $mov)
                            <tr>
                                <td>
                                    @if(str_contains(strtoupper($mov->numero_nota ?? ''), 'STOCK') || strtoupper($mov->numero_nota ?? '') === 'INICIAL')
                                        <span style="background: rgba(99,102,241,0.08); color: #4f46e5; border: 1.5px solid rgba(99,102,241,0.25); padding: 4px 8px; border-radius: 7px; font-size: 11px; font-weight: 700; white-space: nowrap; display: inline-flex; align-items: center; gap: 4px;" title="Stock Inicial">
                                            <i class="fas fa-box-open" style="font-size: 11px; color: #6366f1;"></i> Stock Inicial
                                        </span>
                                    @else
                                        <span class="nota-badge" style="white-space: nowrap;">{{ $mov->numero_nota ?? '—' }}</span>
                                    @endif
                                </td>
                                <td style="font-size: 13px; color: var(--text-muted); white-space: nowrap;">
                                    {{ $mov->fecha->format('d/m/Y') }} <span style="opacity: 0.75; font-weight: 600; margin-left: 2px;">{{ $mov->created_at->format('H:i') }}</span>
                                </td>
                                <td><span class="codigo">{{ $mov->articulo->codigo }}</span></td>
                                <td>{{ $mov->articulo->nombre }}</td>
                                @if(!request('tipo'))
                                    <td>
                                        @if($mov->tipo === 'entrada')
                                            <span class="badge badge-success">
                                                <i class="fas fa-arrow-down"></i> Entrada
                                            </span>
                                        @else
                                            <span class="badge badge-danger">
                                                <i class="fas fa-arrow-up"></i> Salida
                                            </span>
                                        @endif
                                    </td>
                                @endif
                                <td class="col-cantidad">
                                     <strong>{{ $mov->cantidad_formateada }}</strong>
                                    <span style="color:#999; font-size:12px;">{{ $mov->articulo->unidad }}</span>
                                </td>
                                <td class="col-precio-unit">
                                    <div style="display: flex; flex-direction: column; gap: 2px;">
                                        <span style="font-family:'Courier New', monospace; color:#2b8a3e; font-weight:600; white-space: nowrap;">
                                            Bs. {{ number_format($mov->precio_unitario ?? 0, 2) }}
                                        </span>
                                        @if(isset($mov->precio_cambio))
                                            @if($mov->precio_cambio === 'subio')
                                                <span style="font-size: 10px; font-weight: 700; color: #dc2626; display: inline-flex; align-items: center; gap: 2px; white-space: nowrap;" title="El precio subió con respecto a la entrada anterior">
                                                    <i class="fas fa-arrow-up" style="font-size: 9px;"></i> +{{ number_format($mov->precio_diferencia_porcentaje, 1) }}%
                                                </span>
                                                @if(isset($mov->precio_anterior))
                                                    <span class="precio-anterior-line">
                                                        <span class="antes-label">Antes:</span>
                                                        <span class="antes-num">Bs. {{ number_format($mov->precio_anterior, 2) }}</span>
                                                    </span>
                                                @endif
                                            @elseif($mov->precio_cambio === 'bajo')
                                                <span style="font-size: 10px; font-weight: 700; color: #16a34a; display: inline-flex; align-items: center; gap: 2px; white-space: nowrap;" title="El precio bajó con respecto a la entrada anterior">
                                                    <i class="fas fa-arrow-down" style="font-size: 9px;"></i> -{{ number_format($mov->precio_diferencia_porcentaje, 1) }}%
                                                </span>
                                                @if(isset($mov->precio_anterior))
                                                    <span class="precio-anterior-line">
                                                        <span class="antes-label">Antes:</span>
                                                        <span class="antes-num">Bs. {{ number_format($mov->precio_anterior, 2) }}</span>
                                                    </span>
                                                @endif
                                            @elseif($mov->precio_cambio === 'nuevo')
                                                <span style="font-size: 9.5px; font-weight: 600; color: #2563eb; opacity: 0.8; white-space: nowrap;" title="Primer precio registrado para este artículo">
                                                    NUEVO PRECIO
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="col-valor-total">
                                    <span style="font-family:'Courier New', monospace; color:#4338ca; font-weight:700;">
                                        Bs. {{ number_format(($mov->precio_unitario ?? 0) * $mov->cantidad, 2) }}
                                    </span>
                                </td>
                                <td>
                                    @if($mov->tipo === 'entrada')
                                        <div class="entrega-info-tag">
                                            <div class="tag-item">
                                                <span class="entregado-txt" title="Entregado por">
                                                    <i class="fas fa-truck"></i> {{ $mov->entregado_por ?? '—' }}
                                                </span>
                                            </div>
                                            <div class="tag-item">
                                                <span class="recibido-txt" title="Recibido por">
                                                    <i class="fas fa-user-check"></i> {{ $mov->recibido_por ?? '—' }}
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="entrega-info-tag">
                                            @if($mov->entregado_por)
                                                <div class="tag-item">
                                                    <span class="recibido-txt" style="background: rgba(3,105,161,0.08) !important; color: #0284c7 !important; border-color: rgba(3,105,161,0.18) !important;" title="Entregado por">
                                                        <i class="fas fa-user-check"></i> {{ $mov->entregado_por }}
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="tag-item" style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                                @if($mov->trabajador)
                                                    <span class="trabajador-tag" title="Entregado a">
                                                        <i class="fas fa-hard-hat"></i> {{ $mov->trabajador->nombre }}
                                                    </span>
                                                @elseif($mov->trabajador_nombre)
                                                    <span class="trabajador-tag" title="Entregado a">
                                                        <i class="fas fa-hard-hat"></i> {{ $mov->trabajador_nombre }}
                                                    </span>
                                                @else
                                                    <span style="color:#bbb; font-size:13px;">—</span>
                                                @endif

                                                @if($mov->turno)
                                                    @if($mov->turno === 'Primera')
                                                        <span class="badge-turno turno-primera" title="Turno Primera" style="background: rgba(234,179,8,0.08); color: #b45309; border: 1px solid rgba(234,179,8,0.25); padding: 2px 6px; border-radius: 6px; font-size: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 3px; white-space: nowrap; height: 18px; line-height: 1;">
                                                            <i class="fas fa-sun" style="color: #d97706; font-size: 9px;"></i> Primera
                                                        </span>
                                                    @elseif($mov->turno === 'Segunda')
                                                        <span class="badge-turno turno-segunda" title="Turno Segunda" style="background: rgba(249,115,22,0.08); color: #c2410c; border: 1px solid rgba(249,115,22,0.25); padding: 2px 6px; border-radius: 6px; font-size: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 3px; white-space: nowrap; height: 18px; line-height: 1;">
                                                            <i class="fas fa-cloud-sun" style="color: #ea580c; font-size: 9px;"></i> Segunda
                                                        </span>
                                                    @elseif($mov->turno === 'Tercera')
                                                        <span class="badge-turno turno-tercera" title="Turno Tercera" style="background: rgba(99,102,241,0.08); color: #4338ca; border: 1px solid rgba(99,102,241,0.25); padding: 2px 6px; border-radius: 6px; font-size: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 3px; white-space: nowrap; height: 18px; line-height: 1;">
                                                            <i class="fas fa-moon" style="color: #4f46e5; font-size: 9px;"></i> Tercera
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td style="color:#666; font-size:13px; position:relative;">
                                    <span id="texto-nota-{{ $mov->id }}">{{ $mov->notas ?? '—' }}</span>
                                    @if(Auth::user()->puedeEditar())
                                        <button type="button"
                                                onclick="editarNotaMovimiento({{ $mov->id }}, '{{ addslashes($mov->notas ?? '') }}')"
                                                style="background:none; border:none; color:#6366f1; cursor:pointer; font-size:11px; margin-left:6px; opacity:0.7;"
                                                title="Editar solo la nota de este movimiento">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                    @endif
                                </td>
                                <td style="font-size:13px;">
                                    <div>{{ $mov->user?->name ?? '—' }}</div>
                                    @if($mov->editado_at)
                                        @php
                                            $editorRol = $mov->editadoPor ? $mov->editadoPor->nombreRol() : 'Usuario';
                                            $editorNombre = $mov->editadoPor ? $mov->editadoPor->name : 'Sistema';
                                        @endphp
                                        <div style="font-size:10px; color:#d97706; font-weight:700; margin-top:2px; display:inline-flex; align-items:center; gap:3px; background:rgba(217,119,6,0.08); padding:1px 5px; border-radius:4px; border:1px solid rgba(217,119,6,0.2);"
                                             title="Editado por el {{ $editorRol }} {{ $editorNombre }} el {{ $mov->editado_at->format('d/m/Y H:i') }}">
                                            <i class="fas fa-history"></i> Editado por {{ $editorRol }} ({{ $editorNombre }})
                                        </div>
                                    @endif
                                    @if(Auth::user()->puedeEditarMovimientos())
                                        <div style="margin-top: 3px;">
                                            <button type="button"
                                                    onclick='abrirModalEditarMovimientoAdmin(@json($mov))'
                                                    style="background:rgba(99,102,241,0.08); border:1px solid rgba(99,102,241,0.25); color:#4f46e5; border-radius:5px; padding:2px 7px; font-size:10.5px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:4px;"
                                                    title="Editar movimiento completo">
                                                <i class="fas fa-pencil-alt"></i> Editar Mov.
                                            </button>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top:20px; display:flex; justify-content:center;">
                {{ $movimientos->links() }}
            </div>
        @endif
    </div>

    @if(Auth::user()->puedeEditar())
    <div class="modal {{ $errors->any() ? 'active' : '' }}" id="modalMovimiento">
        <div class="modal-content">
            <div class="modal-header">
                <span><i class="fas fa-plus-circle"></i> Registrar Movimientos</span>
                <button class="modal-close" onclick="cerrarModal()">&times;</button>
            </div>

            @if($errors->any())
                <div class="error-list">
                    <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('movimientos.store') }}" id="formMovimiento">
                @csrf

                <div class="form-row">
                    <div class="form-group" style="flex: 1;">
                        <label><i class="fas fa-file-alt"></i> N° de Nota</label>
                        <div class="numero-nota-display" style="padding: 10px 14px; background: rgba(0,0,0,0.03); border-radius: 8px; font-weight: 700;">
                            <i class="fas fa-hashtag"></i>
                            <span>{{ $proximoNumeroNota }}</span>
                        </div>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label><i class="fas fa-calendar"></i> Fecha</label>
                        <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-exchange-alt"></i> Tipo de movimiento</label>
                    @if(request('tipo') === 'entrada' || request('tipo') === 'salida')
                        <input type="hidden" name="tipo" id="tipoMovimiento" value="{{ request('tipo') }}">
                        <div class="tipo-movimiento-badge-locked" style="border-radius: 12px; padding: 10px 14px; font-weight: 700; font-size: 15px; display: flex; align-items: center; gap: 10px; @if(request('tipo') === 'entrada') background: rgba(5, 150, 105, 0.08); border: 1.5px solid rgba(5, 150, 105, 0.25); color: #059669; @else background: rgba(239, 68, 68, 0.08); border: 1.5px solid rgba(239, 68, 68, 0.25); color: #ef4444; @endif">
                            @if(request('tipo') === 'entrada')
                                <i class="fas fa-arrow-alt-circle-down" style="font-size: 16px;"></i>
                                <span>⬇ Entrada (Compra / Recepción)</span>
                            @else
                                <i class="fas fa-arrow-alt-circle-up" style="font-size: 16px;"></i>
                                <span>⬆ Salida (Entrega a contratista)</span>
                            @endif
                        </div>
                    @else
                        <select name="tipo" id="tipoMovimiento" required onchange="actualizarCamposPorTipo()">
                            <option value="entrada" {{ old('tipo', request('tipo')) == 'entrada' ? 'selected' : '' }}>
                                ⬇ Entrada (Compra / Recepción)
                            </option>
                            <option value="salida" {{ old('tipo', request('tipo')) == 'salida' ? 'selected' : '' }}>
                                ⬆ Salida (Entrega a contratista)
                            </option>
                        </select>
                    @endif
                </div>

                {{-- Campos de Entrega y Recepción (solo en ENTRADAS) --}}
                <div id="campoEntradaPersonal" class="{{ old('tipo', request('tipo', 'entrada')) == 'entrada' ? '' : 'oculto' }}">
                    <div style="background: rgba(5,150,105,0.04); border: 1.5px solid rgba(5,150,105,0.18); border-radius: 12px; padding: 16px 18px; margin-bottom: 4px;">
                        <div style="font-size: 12px; font-weight: 700; color: #059669; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 14px;">
                            <i class="fas fa-truck"></i> Datos de la Recepción
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label style="font-size:13px; font-weight:600; margin-bottom:6px; display:block;">
                                    <i class="fas fa-industry" style="color:#059669;"></i> Entregado por (Proveedor) <span style="color:#e03131;">*</span>
                                </label>
                                <input type="text" name="entregado_por" id="inputEntregadoPor"
                                    value="{{ old('entregado_por') }}"
                                    placeholder="Nombre del proveedor o empresa"
                                    style="width:100%; box-sizing:border-box; padding:11px 14px; font-size:14px; border:1.5px solid var(--border); border-radius:9px; background:var(--bg-input); color:var(--text-primary); font-family:inherit; transition:border-color .2s;">
                            </div>
                            <div class="form-group">
                                <label style="font-size:13px; font-weight:600; margin-bottom:6px; display:block;">
                                    <i class="fas fa-user-check" style="color:#059669;"></i> Recibido por (Almacén) <span style="color:#e03131;">*</span>
                                </label>
                                <input type="text" name="recibido_por" id="inputRecibidoPor"
                                    value="{{ old('recibido_por', Auth::user()->name) }}"
                                    placeholder="Nombre del almacenero que recibe"
                                    style="width:100%; box-sizing:border-box; padding:11px 14px; font-size:14px; border:1.5px solid var(--border); border-radius:9px; background:var(--bg-input); color:var(--text-primary); font-family:inherit; transition:border-color .2s;">
                            </div>
                        </div>
                    </div>
                </div>

                <div id="campoSalidaPersonal" class="{{ old('tipo', request('tipo')) == 'salida' ? '' : 'oculto' }}">

                    {{-- ① Selector de Destino (ancho completo) --}}
                    <div style="margin-bottom: 14px; display: flex; align-items: center; gap: 24px; background: rgba(99,102,241,0.05); padding: 10px 14px; border-radius: 10px; border: 1.5px dashed rgba(99,102,241,0.25);">
                        <span style="font-size: 12px; font-weight: 700; color: var(--text-secondary); white-space: nowrap;">
                            <i class="fas fa-route"></i> Destino de Salida:
                        </span>
                        <label style="display: inline-flex; align-items: center; gap: 7px; cursor: pointer; font-size: 13px; font-weight: 600; color: var(--text-primary); margin: 0; padding: 5px 12px; border-radius: 7px; border: 1.5px solid transparent; transition: all .2s;" id="labelDestinoContratista">
                            <input type="radio" name="destino_tipo" id="radio_destino_contratista" value="contratista"
                                {{ old('destino_tipo', 'contratista') == 'contratista' ? 'checked' : '' }}
                                onchange="toggleDestinoSalida('contratista')" style="accent-color: #6366f1;">
                            👷 Contratista / Trabajador
                        </label>
                        <label style="display: inline-flex; align-items: center; gap: 7px; cursor: pointer; font-size: 13px; font-weight: 600; color: var(--text-primary); margin: 0; padding: 5px 12px; border-radius: 7px; border: 1.5px solid transparent; transition: all .2s;" id="labelDestinoNivel">
                            <input type="radio" name="destino_tipo" id="radio_destino_nivel" value="nivel"
                                {{ old('destino_tipo') == 'nivel' ? 'checked' : '' }}
                                onchange="toggleDestinoSalida('nivel')" style="accent-color: #f97316;">
                            ⛏️ Nivel de la Mina
                        </label>
                    </div>

                    {{-- ② Fila: Campo condicional (Contratista O Nivel) + Turno --}}
                    <div class="form-row" style="align-items: flex-start; gap: 14px;">

                        {{-- Panel Contratista --}}
                        <div id="destinoContratistaContainer" style="flex: 1; min-width: 0; {{ old('destino_tipo') === 'nivel' ? 'display:none;' : '' }}">
                            <label style="display:block; margin-bottom:6px; font-size:13px; font-weight:600;">
                                <i class="fas fa-hard-hat"></i> Entregado a (Contratista) <span style="color:#e03131;">*</span>
                            </label>
                            <div class="buscador-container">
                                <input type="text" id="buscadorTrabajador" class="buscador-input"
                                    placeholder="🔍 Escribe nombre de contratista..."
                                    value="{{ old('trabajador_nombre_display') }}" autocomplete="off"
                                    style="width:100%; box-sizing:border-box; height:44px; padding:10px 14px; font-size:14px; border:1.5px solid var(--border); border-radius:9px; background:var(--bg-input); color:var(--text-primary); font-family:inherit;">
                                <input type="hidden" name="trabajador_id" id="selectTrabajador" value="{{ old('trabajador_id') }}">

                                <div id="listaTrabajadores" class="buscador-lista">
                                    @foreach($trabajadores as $t)
                                        <div class="buscador-item"
                                             data-id="{{ $t->id }}"
                                             data-busqueda="{{ strtolower(($t->codigo ?? '') . ' ' . $t->nombre . ' ' . ($t->ayudante ?? '') . ' ' . ($t->nivel ?? '') . ' ' . ($t->labor ?? '') . ' ' . ($t->area_trabajo ?? '')) }}">
                                            <strong>{{ $t->nombre }}</strong>
                                            @if($t->codigo)
                                                <span style="font-family: monospace; font-size: 11px; background: rgba(99,102,241,0.08); padding: 2px 6px; border-radius: 4px; color: #6366f1; border: 1px solid rgba(99,102,241,0.15); margin-left: 6px; font-weight: bold;">
                                                    {{ $t->codigo }}
                                                </span>
                                            @endif
                                            @if($t->ayudante)
                                                <span style="display:block; font-size:11px; color:#5c7cfa; margin-top:2px;">
                                                    <i class="fas fa-user-friends"></i> Ayudante: {{ $t->ayudante }}
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <div id="trabajadorSeleccionado" style="display:none; margin-top:7px; padding:8px 12px; background:#e7f5ff; border-radius:8px; font-size:13px;">
                                    <i class="fas fa-check-circle" style="color:#1971c2;"></i>
                                    <strong id="textoSeleccionadoTrab">—</strong>
                                    <button type="button" onclick="limpiarBuscadorTrabajador()"
                                        style="float:right; background:none; border:none; color:#e03131; cursor:pointer; font-size:16px; line-height:1; padding:0;">×</button>
                                </div>
                            </div>
                        </div>

                        {{-- Panel Nivel de la Mina --}}
                        <div id="destinoNivelContainer" style="flex: 1; min-width: 0; {{ old('destino_tipo') !== 'nivel' ? 'display:none;' : '' }}">
                            <label style="display:block; margin-bottom:6px; font-size:13px; font-weight:600;">
                                <i class="fas fa-layer-group"></i> Nivel de la Mina <span style="color:#e03131;">*</span>
                            </label>
                            <input type="text" name="nivel" id="inputNivel"
                                placeholder="Ej: Nivel -50"
                                value="{{ old('nivel') }}" style="width:100%; box-sizing:border-box; height:44px; padding:10px 14px; font-size:14px; border:1.5px solid var(--border); border-radius:9px; background:var(--bg-input); color:var(--text-primary); font-family:inherit; transition:all .2s;">
                            <small style="display:flex; align-items:center; gap:5px; color:#f97316; font-size:11px; margin-top:5px; font-weight:500;">
                                <i class="fas fa-info-circle"></i> El material saldrá al nivel indicado. No se requiere contratista.
                            </small>
                        </div>

                        {{-- Turno --}}
                        <div style="flex: 1; min-width: 0;">
                            <label style="display:block; margin-bottom:6px; font-size:13px; font-weight:600;">
                                <i class="fas fa-clock"></i> Turno <span style="color:#e03131;">*</span>
                            </label>
                            <select name="turno" id="selectTurno" style="width:100%; box-sizing:border-box; height:44px; padding:10px 14px; font-size:14px; border:1.5px solid var(--border); border-radius:9px; background:var(--bg-input); color:var(--text-primary); font-family:inherit; transition:all .2s; cursor:pointer;">
                                <option value="">-- Turno --</option>
                                <option value="Primera" {{ old('turno') == 'Primera' ? 'selected' : '' }}>Primera</option>
                                <option value="Segunda" {{ old('turno') == 'Segunda' ? 'selected' : '' }}>Segunda</option>
                                <option value="Tercera" {{ old('turno') == 'Tercera' ? 'selected' : '' }}>Tercera</option>
                            </select>
                        </div>
                    </div>

                    {{-- ③ Fila: Entregado por --}}
                    <div style="margin-top: 12px;">
                        <label style="display:block; margin-bottom:6px; font-size:13px; font-weight:600;">
                            <i class="fas fa-user-check"></i> Entregado por <span style="color:#e03131;">*</span>
                        </label>
                        <input type="text" name="entregado_por" id="inputEntregadoPorSalida"
                            value="{{ old('entregado_por', Auth::user()->name) }}"
                            placeholder="Nombre del personal que entrega" style="width:100%; box-sizing:border-box; padding:12px 16px; font-size:14.5px; border:1.5px solid var(--border); border-radius:10px; background:var(--bg-input); color:var(--text-primary); font-family:inherit; transition:all .2s;">
                    </div>
                </div>


                {{-- Tabla Dinámica de Materiales --}}
                <div class="form-group" style="margin-top: 20px;">
                    <label style="font-weight: 700; font-size: 14px; margin-bottom: 10px; display: block; color: var(--primary);">
                        <i class="fas fa-boxes"></i> Detalle de Materiales a Registrar
                    </label>

                    <div style="overflow: visible; width: 100%; border: 1.5px solid var(--border); border-radius: 8px; background: rgba(0,0,0,0.01);">
                        <table class="table-items" style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="background: rgba(0,0,0,0.02); border-bottom: 1.5px solid var(--border);">
                                    <th style="padding: 10px; text-align: left; font-size: 12px; font-weight: 700; color: #555; min-width: 180px;">Artículo</th>
                                    <th class="col-header-lote {{ old('tipo', request('tipo', 'entrada')) == 'salida' ? '' : 'oculto' }}" style="padding: 10px; text-align: left; font-size: 12px; font-weight: 700; color: #555; width: 170px;">Lote / Precio</th>
                                    <th class="col-header-precio {{ old('tipo', request('tipo', 'entrada')) == 'entrada' ? '' : 'oculto' }}" style="padding: 10px; text-align: left; font-size: 12px; font-weight: 700; color: #555; width: 100px;">P. Unitario (Bs.)</th>
                                    <th style="padding: 10px; text-align: left; font-size: 12px; font-weight: 700; color: #555; width: 125px; min-width: 120px;">Cantidad</th>
                                    <th style="padding: 10px; text-align: left; font-size: 12px; font-weight: 700; color: #555; width: 65px;">Unidad</th>
                                    <th style="padding: 10px; text-align: left; font-size: 12px; font-weight: 700; color: #555; width: 120px; max-width: 130px;">Observación <span style="font-weight:400; color:#999; font-size:10px;">(opcional)</span></th>
                                    <th style="padding: 10px; text-align: center; font-size: 12px; width: 40px;"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                {{-- Las filas se insertarán dinámicamente con JavaScript --}}
                            </tbody>
                        </table>
                    </div>

                    <button type="button" class="btn btn-secondary" id="btnAddRow" style="margin-top: 12px; padding: 8px 16px; font-size: 12.5px; width: auto; background: var(--bg-hover); color: var(--text-primary); border: 1px solid var(--border);">
                        <i class="fas fa-plus"></i> Agregar Material
                    </button>
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label style="font-weight: 600;"><i class="fas fa-sticky-note"></i> Notas / Observaciones (opcional)</label>
                    <textarea name="notas" id="inputNotas" rows="2" placeholder="Observaciones generales para este lote de movimientos...">{{ old('notas') }}</textarea>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" style="flex: 1; justify-content: center;">
                        <i class="fas fa-save"></i> Guardar Todo
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="cerrarModal()" style="flex: 1; justify-content: center;">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal para Editar Nota de Movimiento --}}
    <div class="modal" id="modalEditarNota" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:99999; align-items:center; justify-content:center; backdrop-filter:blur(3px);">
        <div class="modal-content" style="background:var(--bg-card, #fff); width:90%; max-width:460px; border-radius:16px; padding:24px; box-shadow:0 20px 40px rgba(0,0,0,0.2); border:1px solid var(--border);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                <h3 style="margin:0; font-size:18px; color:var(--text-primary); display:flex; align-items:center; gap:8px;">
                    <i class="fas fa-edit" style="color:#6366f1;"></i> Editar Observación
                </h3>
                <button type="button" onclick="cerrarModalNota()" style="background:none; border:none; color:var(--text-muted); font-size:18px; cursor:pointer; padding:4px;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <input type="hidden" id="edit_nota_movimiento_id">
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:13px; font-weight:600; color:var(--text-secondary); margin-bottom:8px;">
                    Observación / Nota:
                </label>
                <textarea id="edit_nota_texto" rows="4" placeholder="Escribe aquí la nota u observación..." style="width:100%; box-sizing:border-box; padding:12px; font-size:14px; border:1.5px solid var(--border); border-radius:10px; background:var(--bg-input); color:var(--text-primary); font-family:inherit; resize:vertical; outline:none; transition:border-color .2s;"></textarea>
            </div>

            <div style="display:flex; gap:12px; justify-content:flex-end;">
                <button type="button" onclick="cerrarModalNota()" class="btn btn-secondary" style="padding:9px 18px; border-radius:8px;">
                    Cancelar
                </button>
                <button type="button" id="btnGuardarNotaModal" onclick="guardarNotaModal()" class="btn btn-primary" style="padding:9px 20px; border-radius:8px; background:linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border:none; color:white; font-weight:600;">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </div>

    {{-- Modal para Edición Completa de Movimiento --}}
    @if(Auth::user()->puedeEditarMovimientos())
    <div class="modal" id="modalEditarMovimientoAdmin" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:99999; align-items:center; justify-content:center; backdrop-filter:blur(3px);">
        <div class="modal-content" style="background:var(--bg-card, #fff); width:90%; max-width:550px; border-radius:16px; padding:24px; box-shadow:0 20px 40px rgba(0,0,0,0.2); border:1px solid var(--border);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; border-bottom:1.5px solid var(--border); padding-bottom:12px;">
                <h3 style="margin:0; font-size:18px; color:var(--text-primary); display:flex; align-items:center; gap:8px;">
                    <i class="fas fa-edit" style="color:#6366f1;"></i> Editar Movimiento
                </h3>
                <button type="button" onclick="cerrarModalEditarMovimientoAdmin()" style="background:none; border:none; color:var(--text-muted); font-size:18px; cursor:pointer; padding:4px;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="formEditarMovimientoAdmin" method="POST" onsubmit="guardarMovimientoAdmin(event)">
                @csrf
                @method('PUT')
                <input type="hidden" id="admin_edit_movimiento_id">

                <div style="display:flex; gap:12px; margin-bottom:14px;">
                    <div style="flex:1;">
                        <label style="display:block; font-size:12.5px; font-weight:600; margin-bottom:4px; color:var(--text-secondary);">Artículo</label>
                        <input type="text" id="admin_edit_articulo_display" readonly style="width:100%; box-sizing:border-box; padding:9px 12px; font-size:13.5px; border:1.5px solid var(--border); border-radius:8px; background:rgba(0,0,0,0.03); color:var(--text-primary); font-weight:600;">
                    </div>
                    <div style="flex:1;">
                        <label style="display:block; font-size:12.5px; font-weight:600; margin-bottom:4px; color:var(--text-secondary);">Tipo</label>
                        <input type="text" id="admin_edit_tipo_display" readonly style="width:100%; box-sizing:border-box; padding:9px 12px; font-size:13.5px; border:1.5px solid var(--border); border-radius:8px; background:rgba(0,0,0,0.03); color:var(--text-primary); font-weight:700;">
                    </div>
                </div>

                <div style="display:flex; gap:12px; margin-bottom:14px;">
                    <div style="flex:1;">
                        <label style="display:block; font-size:12.5px; font-weight:600; margin-bottom:4px; color:var(--text-secondary);">Fecha <span style="color:#e03131;">*</span></label>
                        <input type="date" id="admin_edit_fecha" name="fecha" required style="width:100%; box-sizing:border-box; padding:9px 12px; font-size:13.5px; border:1.5px solid var(--border); border-radius:8px; background:var(--bg-input); color:var(--text-primary);">
                    </div>
                    <div style="flex:1;">
                        <label style="display:block; font-size:12.5px; font-weight:600; margin-bottom:4px; color:var(--text-secondary);">Cantidad <span style="color:#e03131;">*</span></label>
                        <input type="number" id="admin_edit_cantidad" name="cantidad" step="any" min="0.001" required style="width:100%; box-sizing:border-box; padding:9px 12px; font-size:13.5px; border:1.5px solid var(--border); border-radius:8px; background:var(--bg-input); color:var(--text-primary);">
                    </div>
                </div>

                <div id="admin_edit_precio_container" style="margin-bottom:14px;">
                    <label style="display:block; font-size:12.5px; font-weight:600; margin-bottom:4px; color:var(--text-secondary);">Precio Unitario (Bs.)</label>
                    <input type="number" id="admin_edit_precio_unitario" name="precio_unitario" step="0.01" min="0" style="width:100%; box-sizing:border-box; padding:9px 12px; font-size:13.5px; border:1.5px solid var(--border); border-radius:8px; background:var(--bg-input); color:var(--text-primary);">
                </div>

                <div style="display:flex; gap:12px; margin-bottom:14px;">
                    <div style="flex:1;">
                        <label style="display:block; font-size:12.5px; font-weight:600; margin-bottom:4px; color:var(--text-secondary);">Entregado por</label>
                        <input type="text" id="admin_edit_entregado_por" name="entregado_por" placeholder="Nombre de quien entregó" style="width:100%; box-sizing:border-box; padding:9px 12px; font-size:13.5px; border:1.5px solid var(--border); border-radius:8px; background:var(--bg-input); color:var(--text-primary);">
                    </div>
                    <div style="flex:1;" id="admin_edit_recibido_container">
                        <label style="display:block; font-size:12.5px; font-weight:600; margin-bottom:4px; color:var(--text-secondary);">Recibido por</label>
                        <input type="text" id="admin_edit_recibido_por" name="recibido_por" placeholder="Nombre de quien recibió" style="width:100%; box-sizing:border-box; padding:9px 12px; font-size:13.5px; border:1.5px solid var(--border); border-radius:8px; background:var(--bg-input); color:var(--text-primary);">
                    </div>
                </div>

                <div id="admin_edit_salida_container" style="display:flex; gap:12px; margin-bottom:14px;">
                    <div style="flex:1;">
                        <label style="display:block; font-size:12.5px; font-weight:600; margin-bottom:4px; color:var(--text-secondary);">Contratista / Trabajador</label>
                        <select id="admin_edit_trabajador_id" name="trabajador_id" style="width:100%; box-sizing:border-box; padding:9px 12px; font-size:13.5px; border:1.5px solid var(--border); border-radius:8px; background:var(--bg-input); color:var(--text-primary);">
                            <option value="">-- Seleccionar --</option>
                            @foreach($trabajadores as $t)
                                <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="flex:1;">
                        <label style="display:block; font-size:12.5px; font-weight:600; margin-bottom:4px; color:var(--text-secondary);">Turno</label>
                        <select id="admin_edit_turno" name="turno" style="width:100%; box-sizing:border-box; padding:9px 12px; font-size:13.5px; border:1.5px solid var(--border); border-radius:8px; background:var(--bg-input); color:var(--text-primary);">
                            <option value="">-- Turno --</option>
                            <option value="Primera">Primera</option>
                            <option value="Segunda">Segunda</option>
                            <option value="Tercera">Tercera</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom:20px;">
                    <label style="display:block; font-size:12.5px; font-weight:600; margin-bottom:4px; color:var(--text-secondary);">Notas / Observación</label>
                    <textarea id="admin_edit_notas" name="notas" rows="2" placeholder="Observaciones de este movimiento..." style="width:100%; box-sizing:border-box; padding:9px 12px; font-size:13.5px; border:1.5px solid var(--border); border-radius:8px; background:var(--bg-input); color:var(--text-primary); font-family:inherit; resize:vertical;"></textarea>
                </div>

                <div style="display:flex; gap:12px; justify-content:flex-end;">
                    <button type="button" onclick="cerrarModalEditarMovimientoAdmin()" class="btn btn-secondary" style="padding:9px 18px; border-radius:8px;">
                        Cancelar
                    </button>
                    <button type="submit" id="btnGuardarMovAdmin" class="btn btn-primary" style="padding:9px 20px; border-radius:8px; background:linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border:none; color:white; font-weight:600;">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Datalist global para autocompletado rápido de artículos --}}
    <datalist id="datalistArticulos">
        @foreach($articulos as $art)
            <option value="{{ $art->codigo }} — {{ $art->nombre }}" data-id="{{ $art->id }}" data-unidad="{{ $art->unidad }}" data-precio="{{ $art->precio }}"></option>
        @endforeach
    </datalist>
    @endif

@endsection

@push('scripts')
@if(Auth::user()->puedeEditar())
<script>
    let rowIndex = 0;

    function abrirModal() {
        document.getElementById('modalMovimiento').classList.add('active');
        // Limpiar tabla y agregar primera fila
        document.getElementById('itemsTableBody').innerHTML = '';
        addRow();
        
        // Limpiar cabecera
        limpiarBuscadorTrabajador();
        const inputEntregadoPor = document.getElementById('inputEntregadoPor');
        if (inputEntregadoPor) inputEntregadoPor.value = '';
        const inputEntregadoPorSalida = document.getElementById('inputEntregadoPorSalida');
        if (inputEntregadoPorSalida) inputEntregadoPorSalida.value = "{{ Auth::user()->name }}";
        const inputNotas = document.getElementById('inputNotas');
        if (inputNotas) inputNotas.value = '';
        
        actualizarCamposPorTipo();
    }

    function esFormularioMovimientoVacio() {
        // 1. Campos de cabecera generales
        const inputEntregadoPor = document.getElementById('inputEntregadoPor');
        if (inputEntregadoPor && inputEntregadoPor.value.trim() !== '') return false;

        const inputRecibidoPor = document.getElementById('inputRecibidoPor');
        if (inputRecibidoPor && inputRecibidoPor.value.trim() !== '' && inputRecibidoPor.value.trim() !== 'Administrador') return false;

        const inputEntregadoPorSalida = document.getElementById('inputEntregadoPorSalida');
        const nombreUsuarioActual = "{{ Auth::user()->name }}";
        if (inputEntregadoPorSalida && inputEntregadoPorSalida.value.trim() !== '' && inputEntregadoPorSalida.value.trim() !== nombreUsuarioActual) return false;

        const selectTrabajador = document.getElementById('selectTrabajador');
        if (selectTrabajador && selectTrabajador.value.trim() !== '') return false;

        const inputNivel = document.getElementById('inputNivel');
        if (inputNivel && inputNivel.value.trim() !== '') return false;

        const inputNotas = document.getElementById('inputNotas');
        if (inputNotas && inputNotas.value.trim() !== '') return false;

        // 2. Tabla de items
        const filas = document.querySelectorAll('#itemsTableBody tr');
        if (filas.length > 1) return false; // Múltiples filas implican que hay datos

        if (filas.length === 1) {
            const tr = filas[0];
            const searchInput = tr.querySelector('.row-articulo-search');
            if (searchInput && searchInput.value.trim() !== '') return false;

            const cantidadInput = tr.querySelector('.row-cantidad-input');
            if (cantidadInput && cantidadInput.value.trim() !== '') return false;

            const precioInput = tr.querySelector('.row-precio-input');
            if (precioInput && precioInput.value.trim() !== '') return false;

            const obsInput = tr.querySelector('input[name*="nota_item"]');
            if (obsInput && obsInput.value.trim() !== '') return false;
        }

        return true;
    }

    function cerrarModal() {
        document.getElementById('modalMovimiento').classList.remove('active');
    }

    document.getElementById('modalMovimiento').addEventListener('click', function(e) {
        if (e.target === this) {
            if (esFormularioMovimientoVacio()) {
                cerrarModal();
            }
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('modalMovimiento');
            if (modal && modal.classList.contains('active')) {
                if (esFormularioMovimientoVacio()) {
                    cerrarModal();
                }
            }
        }
    });

    // Añadir fila a la tabla
    function addRow() {
        rowIndex++;
        const tbody = document.getElementById('itemsTableBody');
        const tr = document.createElement('tr');
        tr.setAttribute('data-row-id', rowIndex);

        const tipoSelect = document.getElementById('tipoMovimiento');
        const tipo = tipoSelect ? tipoSelect.value : 'entrada';
        const isSalida = (tipo === 'salida');

        tr.innerHTML = `
            <td>
                <div class="buscador-container" style="position: relative;">
                    <input type="text" class="buscador-input row-articulo-search" placeholder="🔍 Escribe código o nombre..." autocomplete="off" required style="width:100% !important;">
                    <input type="hidden" name="items[${rowIndex}][articulo_id]" class="row-articulo-id" required>
                    <div class="buscador-lista row-articulo-list" style="position: absolute; top: 100%; left: 0; right: 0; z-index: 1000;">
                        @foreach($articulos as $art)
                            <div class="buscador-item row-articulo-item" 
                                 data-id="{{ $art->id }}" 
                                 data-codigo="{{ $art->codigo }}" 
                                 data-nombre="{{ $art->nombre }}" 
                                 data-unidad="{{ $art->unidad }}" 
                                 data-precio="{{ $art->precio }}" 
                                 data-busqueda="{{ strtolower($art->codigo . ' ' . $art->nombre) }}">
                                 <strong>{{ $art->codigo }}</strong> {{ $art->nombre }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </td>
            <td class="col-cell-lote ${isSalida ? '' : 'oculto'}" style="position:relative; min-width:220px;">
                <div class="lote-dropdown" style="position:relative;">
                    <div class="lote-trigger" tabindex="0" style="display:flex; align-items:center; justify-content:space-between; gap:8px; padding:9px 12px; border:1.5px solid var(--border); border-radius:10px; background:var(--bg-input); cursor:pointer; font-size:13px; color:var(--text-muted); user-select:none; min-height:42px; transition:border-color .2s;">
                        <span class="lote-trigger-text">-- Selecciona lote --</span>
                        <i class="fas fa-chevron-down" style="font-size:11px; opacity:.5;"></i>
                    </div>
                    <input type="hidden" name="items[${rowIndex}][lote_id]" class="row-lote-id" ${isSalida ? 'required' : ''}>
                    <input type="hidden" class="row-lote-disp" value="">
                    <div class="lote-lista" style="display:none; position:absolute; top:calc(100% + 4px); left:0; right:0; background:var(--bg-card,#fff); border:1.5px solid var(--border); border-radius:10px; box-shadow:0 12px 32px rgba(0,0,0,0.14); z-index:9999; overflow:hidden;">
                        <div class="lote-placeholder" style="padding:10px 14px; color:var(--text-muted); font-size:13px;">Cargando...</div>
                    </div>
                </div>
            </td>
            <td class="col-cell-precio ${!isSalida ? '' : 'oculto'}" style="width:100px;">
                <input type="number" name="items[${rowIndex}][precio_unitario]" class="row-precio-input" step="0.01" min="0" style="width:100% !important; box-sizing:border-box; padding:7px 8px; font-size:13px;" ${!isSalida ? 'required' : ''}>
            </td>
            <td style="width:125px; min-width:120px;">
                <input type="number" name="items[${rowIndex}][cantidad]" class="row-cantidad-input" step="any" min="0.001" required style="width:100% !important; box-sizing:border-box; padding:7px 8px; font-size:13px; font-weight:600;">
            </td>
            <td style="width:65px;">
                <span class="row-unidad-span" style="font-size:12px; color:var(--text-muted); font-weight:600;">—</span>
            </td>
            <td style="width:120px; max-width:130px;">
                <input type="text"
                       name="items[${rowIndex}][nota_item]"
                       class="row-nota-input"
                       placeholder="Ej: sin precio..."
                       style="width:100% !important; padding:7px 8px; font-size:12px; border:1.5px solid var(--border); border-radius:8px; background:var(--bg-input); color:var(--text-primary); font-family:inherit; box-sizing:border-box;">
            </td>
            <td style="text-align: center; vertical-align: middle; width: 40px;">
                <button type="button" class="btn-delete-row" title="Eliminar o limpiar fila" style="background:rgba(224,49,49,0.1); border:1.5px solid rgba(224,49,49,0.3); color:#e03131; border-radius:7px; width:28px; height:28px; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; font-size:15px; font-weight:bold; transition:all .2s; outline:none;">&times;</button>
            </td>
        `;

        tbody.appendChild(tr);

        // ─── Listeners para la nueva fila ─────────────────────────────────────
        const searchInput   = tr.querySelector('.row-articulo-search');
        const hiddenId      = tr.querySelector('.row-articulo-id');
        const unitSpan      = tr.querySelector('.row-unidad-span');
        const loteHidden    = tr.querySelector('.row-lote-id');
        const precioInput   = tr.querySelector('.row-precio-input');
        const cantidadInput = tr.querySelector('.row-cantidad-input');
        const notaInput     = tr.querySelector('.row-nota-input');
        const articuloList  = tr.querySelector('.row-articulo-list');
        const items         = articuloList.querySelectorAll('.row-articulo-item');

        function showDropdown() {
            document.querySelectorAll('.row-articulo-list').forEach(el => el.classList.remove('activo'));
            articuloList.classList.add('activo');
        }

        function hideDropdown() {
            articuloList.classList.remove('activo');
        }

        function filterItems(val) {
            const q = val.toLowerCase().trim();
            let visible = 0;
            items.forEach(item => {
                const match = item.getAttribute('data-busqueda').includes(q);
                item.classList.toggle('oculto', !match);
                if (match) visible++;
            });
            return visible;
        }

        // Mostrar todos al hacer focus
        searchInput.addEventListener('focus', function() {
            filterItems(this.value);
            showDropdown();
        });

        // Filtrar en tiempo real al escribir
        searchInput.addEventListener('input', function() {
            hiddenId.value = '';        // limpiar artículo seleccionado
            unitSpan.textContent = '—';
            // Resetear lote dropdown
            if (loteHidden) loteHidden.value = '';
            const loteTriggerTxt = tr.querySelector('.lote-trigger-text');
            if (loteTriggerTxt) {
                loteTriggerTxt.textContent = '-- Selecciona lote --';
                loteTriggerTxt.style.color = '';
                const loteTrigger = tr.querySelector('.lote-trigger');
                if (loteTrigger) loteTrigger.style.borderColor = '';
            }
            filterItems(this.value);
            showDropdown();
        });

        // Seleccionar artículo al hacer click en la lista
        items.forEach(item => {
            item.addEventListener('mousedown', function(e) {
                e.preventDefault();        // evitar que el blur del input cierre antes
                const id     = this.getAttribute('data-id');
                const codigo = this.getAttribute('data-codigo');
                const nombre = this.getAttribute('data-nombre');
                const unidad = this.getAttribute('data-unidad');
                const precio = parseFloat(this.getAttribute('data-precio') || 0);

                searchInput.value = `${codigo} — ${nombre}`;
                hiddenId.value    = id;
                unitSpan.textContent = unidad;

                // Restricción decimal según unidad
                const isDinamita   = nombre.toLowerCase().includes('dinamita');
                const enteras      = ['UNIDAD', 'UNIDADES'];
                if (enteras.includes(unidad.toUpperCase()) && !isDinamita) {
                    cantidadInput.step = '1';
                    cantidadInput.min  = '1';
                } else {
                    cantidadInput.step = 'any';
                    cantidadInput.min  = '0.001';
                }

                const currentTipo = document.getElementById('tipoMovimiento')?.value || 'entrada';
                if (currentTipo === 'entrada') {
                    if (precioInput) precioInput.value = precio.toFixed(2);
                } else {
                    cargarLotesFila(tr, id);
                }

                hideDropdown();
                cantidadInput.focus();
            });
        });

        // Cerrar al perder el foco del input
        searchInput.addEventListener('blur', function() {
            setTimeout(hideDropdown, 150);
        });

        // Restringir decimales en el campo cantidad en tiempo real y validar límite del lote
        cantidadInput.addEventListener('input', function() {
            const valSearch = searchInput.value.toLowerCase();
            const isDinamita = valSearch.includes('dinamita');
            const unidad = unitSpan.textContent.toUpperCase();
            const unitsArray = ['UNIDAD', 'UNIDADES'];
            if (unitsArray.includes(unidad) && !isDinamita && this.value.includes('.')) {
                this.value = this.value.split('.')[0];
            }
            if (typeof validarLoteStockFila === 'function') {
                validarLoteStockFila(tr);
            }
        });

        // Evento botón eliminar o limpiar fila
        tr.querySelector('.btn-delete-row')?.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const currentTbody = document.getElementById('itemsTableBody');
            if (currentTbody && currentTbody.children.length > 1) {
                tr.remove();
            } else {
                // Si es la última fila restante, limpiar sus campos
                searchInput.value = '';
                hiddenId.value = '';
                unitSpan.textContent = '—';
                if (cantidadInput) cantidadInput.value = '';
                if (precioInput) precioInput.value = '';
                if (notaInput) notaInput.value = '';
                if (loteHidden) loteHidden.value = '';
                const loteTriggerTxt = tr.querySelector('.lote-trigger-text');
                if (loteTriggerTxt) {
                    loteTriggerTxt.textContent = '-- Selecciona lote --';
                    loteTriggerTxt.style.color = '';
                }
            }
        });

        // Atajo teclado: Enter en cantidad agrega nueva fila
        cantidadInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addRow();
                // Enfocar el buscador de la nueva fila
                setTimeout(() => {
                    const lastRow = tbody.lastElementChild;
                    if (lastRow) {
                        lastRow.querySelector('.row-articulo-search').focus();
                    }
                }, 50);
            }
        });
    }

    function cargarLotesFila(tr, articuloId) {
        const dropdown   = tr.querySelector('.lote-dropdown');
        if (!dropdown) return;
        const trigger    = dropdown.querySelector('.lote-trigger');
        const triggerTxt = dropdown.querySelector('.lote-trigger-text');
        const hiddenId   = dropdown.querySelector('.row-lote-id');
        const hiddenDisp = dropdown.querySelector('.row-lote-disp');
        const lista      = dropdown.querySelector('.lote-lista');

        triggerTxt.textContent = 'Cargando...';
        lista.innerHTML = '<div style="padding:10px 14px; color:var(--text-muted); font-size:13px;">Cargando...</div>';

        fetch(`/movimientos/lotes?articulo_id=${articuloId}`)
            .then(res => res.json())
            .then(data => {
                lista.innerHTML = '';

                if (data.length === 0) {
                    lista.innerHTML = `<div style="padding:12px 14px; color:#e03131; font-size:13px; display:flex; align-items:center; gap:8px;"><i class='fas fa-exclamation-triangle'></i> Sin stock disponible</div>`;
                    triggerTxt.textContent = '-- Selecciona lote --';
                    return;
                }

                data.forEach(l => {
                    const precio    = parseFloat(l.precio_unitario).toFixed(2);
                    const disp      = parseFloat(l.cantidad_restante);
                    const nota      = l.numero_nota || 'S/N';
                    const isInicial = nota.includes('INI') || nota.includes('INICIAL') || nota.includes('STOCK');

                    const card = document.createElement('div');
                    card.className = 'lote-opcion';
                    card.setAttribute('data-id',   l.id);
                    card.setAttribute('data-disp',  disp);
                    card.setAttribute('data-precio', precio);
                    card.innerHTML = `
                        <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                            <span style="background:rgba(5,150,105,0.10); color:#059669; font-weight:700; font-size:13px; padding:3px 10px; border-radius:6px; border:1px solid rgba(5,150,105,0.2);">Bs. ${precio}</span>
                            <span style="background:rgba(99,102,241,0.09); color:#6366f1; font-size:12px; font-weight:600; padding:2px 8px; border-radius:5px;">Disp: ${disp}</span>
                            <span style="font-size:11px; color:var(--text-muted); margin-left:auto;">${isInicial ? '🏛️ Stock Inicial' : '📋 Nota: ' + nota}</span>
                        </div>
                    `;

                    card.addEventListener('click', function() {
                        hiddenId.value   = l.id;
                        hiddenDisp.value = disp;
                        triggerTxt.textContent = `Bs. ${precio}  —  Disp: ${disp}`;
                        triggerTxt.style.color = 'var(--text-primary)';
                        trigger.style.borderColor = '#059669';
                        lista.style.display = 'none';

                        if (typeof validarLoteStockFila === 'function') {
                            validarLoteStockFila(tr);
                        }
                    });

                    lista.appendChild(card);
                });

                triggerTxt.textContent = '-- Selecciona lote --';

                // Si solo hay un lote, seleccionarlo automáticamente
                if (data.length === 1) {
                    lista.querySelector('.lote-opcion').click();
                }
            })
            .catch(err => {
                console.error(err);
                lista.innerHTML = '<div style="padding:12px 14px; color:#e03131; font-size:13px;">⚠️ Error al cargar lotes</div>';
            });

        // Abrir/cerrar dropdown al hacer click en el trigger
        trigger.addEventListener('click', function() {
            const visible = lista.style.display === 'block';
            // Cerrar todos los otros dropdowns de lote abiertos
            document.querySelectorAll('.lote-lista').forEach(l => l.style.display = 'none');
            lista.style.display = visible ? 'none' : 'block';
        });

        // Cerrar al hacer click fuera
        document.addEventListener('click', function closeLote(e) {
            if (!dropdown.contains(e.target)) {
                lista.style.display = 'none';
            }
        });
    }

    function actualizarCamposPorTipo() {
        const tipoSelect = document.getElementById('tipoMovimiento');
        if (!tipoSelect) return;
        const tipo = tipoSelect.value;
        const isSalida = (tipo === 'salida');

        // Toggle cabecera secciones
        const campoEntradaPersonal = document.getElementById('campoEntradaPersonal');
        const campoSalidaPersonal = document.getElementById('campoSalidaPersonal');
        
        if (isSalida) {
            if (campoSalidaPersonal) campoSalidaPersonal.classList.remove('oculto');
            if (campoEntradaPersonal) campoEntradaPersonal.classList.add('oculto');
            
            // Determinar qué destino está seleccionado actualmente
            const radioNivel = document.getElementById('radio_destino_nivel');
            const isNivel = radioNivel && radioNivel.checked;

            // Required inputs
            document.getElementById('selectTrabajador').required = !isNivel;
            const inputNivel = document.getElementById('inputNivel');
            if (inputNivel) inputNivel.required = isNivel;
            document.getElementById('selectTurno').required = true;
            
            const inputEntregadoPorSalida = document.getElementById('inputEntregadoPorSalida');
            if (inputEntregadoPorSalida) {
                inputEntregadoPorSalida.required = true;
                inputEntregadoPorSalida.disabled = false;
            }

            const inputEntregadoPor = document.getElementById('inputEntregadoPor');
            if (inputEntregadoPor) {
                inputEntregadoPor.required = false;
                inputEntregadoPor.disabled = true;
            }
            const inputRecibidoPor = document.getElementById('inputRecibidoPor');
            if (inputRecibidoPor) {
                inputRecibidoPor.required = false;
                inputRecibidoPor.disabled = true;
            }
        } else {
            if (campoSalidaPersonal) campoSalidaPersonal.classList.add('oculto');
            if (campoEntradaPersonal) campoEntradaPersonal.classList.remove('oculto');
            
            // Required inputs
            document.getElementById('selectTrabajador').required = false;
            const inputNivel = document.getElementById('inputNivel');
            if (inputNivel) inputNivel.required = false;
            document.getElementById('selectTurno').required = false;
            
            const inputEntregadoPorSalida = document.getElementById('inputEntregadoPorSalida');
            if (inputEntregadoPorSalida) {
                inputEntregadoPorSalida.required = false;
                inputEntregadoPorSalida.disabled = true;
            }

            const inputEntregadoPor = document.getElementById('inputEntregadoPor');
            if (inputEntregadoPor) {
                inputEntregadoPor.required = true;
                inputEntregadoPor.disabled = false;
            }
            const inputRecibidoPor = document.getElementById('inputRecibidoPor');
            if (inputRecibidoPor) {
                inputRecibidoPor.required = true;
                inputRecibidoPor.disabled = false;
            }
        }

        // Toggle table headers
        document.querySelectorAll('.col-header-lote').forEach(el => el.classList.toggle('oculto', !isSalida));
        document.querySelectorAll('.col-header-precio').forEach(el => el.classList.toggle('oculto', isSalida));

        // Toggle table rows cells
        document.getElementById('itemsTableBody').querySelectorAll('tr').forEach(tr => {
            const cellLote     = tr.querySelector('.col-cell-lote');
            const cellPrecio   = tr.querySelector('.col-cell-precio');
            const loteHiddenId = tr.querySelector('.row-lote-id');
            const inputPrecio  = tr.querySelector('.row-precio-input');
            const artHiddenId  = tr.querySelector('.row-articulo-id');
            const hiddenId     = artHiddenId ? artHiddenId.value : null;

            if (cellLote)   cellLote.classList.toggle('oculto', !isSalida);
            if (cellPrecio) cellPrecio.classList.toggle('oculto', isSalida);

            if (loteHiddenId) loteHiddenId.required = isSalida;
            if (inputPrecio)  inputPrecio.required   = !isSalida;

            // Refrescar lote/precio si cambiaron tipo
            if (hiddenId) {
                if (isSalida) {
                    cargarLotesFila(tr, hiddenId);
                } else {
                    const option = Array.from(document.getElementById('datalistArticulos').options).find(opt => opt.getAttribute('data-id') === hiddenId);
                    if (option && inputPrecio) {
                        inputPrecio.value = parseFloat(option.getAttribute('data-precio') || 0).toFixed(2);
                    }
                }
            }
        });
    }

    document.getElementById('btnAddRow').addEventListener('click', addRow);

    window.toggleDestinoSalida = function(tipo) {
        const contratistaContainer = document.getElementById('destinoContratistaContainer');
        const nivelContainer = document.getElementById('destinoNivelContainer');
        const inputNivel = document.getElementById('inputNivel');
        const selectTrabajador = document.getElementById('selectTrabajador');
        const radioContratista = document.getElementById('radio_destino_contratista');
        const radioNivel = document.getElementById('radio_destino_nivel');

        if (tipo === 'nivel') {
            if (contratistaContainer) contratistaContainer.style.display = 'none';
            if (nivelContainer) nivelContainer.style.display = 'block';
            if (radioNivel) radioNivel.checked = true;
            
            // Configurar obligatorios
            selectTrabajador.required = false;
            if (inputNivel) inputNivel.required = true;
            
            // Limpiar selección de trabajador
            if (window.limpiarBuscadorTrabajador) window.limpiarBuscadorTrabajador();
        } else {
            if (contratistaContainer) contratistaContainer.style.display = 'block';
            if (nivelContainer) nivelContainer.style.display = 'none';
            if (radioContratista) radioContratista.checked = true;
            
            // Configurar obligatorios
            selectTrabajador.required = true;
            if (inputNivel) {
                inputNivel.required = false;
                inputNivel.value = '';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Enlazar evento al select de tipo si existe y no está bloqueado
        const tipoSelect = document.getElementById('tipoMovimiento');
        if (tipoSelect && tipoSelect.tagName === 'SELECT') {
            tipoSelect.addEventListener('change', actualizarCamposPorTipo);
        }

        // Inicializar Destino de Salida según el valor seleccionado (en caso de redirección por error de validación)
        const radioNivelSelected = document.getElementById('radio_destino_nivel');
        if (radioNivelSelected && radioNivelSelected.checked) {
            window.toggleDestinoSalida('nivel');
        } else {
            window.toggleDestinoSalida('contratista');
        }
    });

    // ════════════════════════════════════════
    // BUSCADOR DE TRABAJADORES (Cabecera de Nota de Salida)
    // ════════════════════════════════════════
    const buscadorTrab = document.getElementById('buscadorTrabajador');
    if (buscadorTrab) {
        const listaTrab = document.getElementById('listaTrabajadores');
        const selectTrabHidden = document.getElementById('selectTrabajador');
        const seleccionadoTrab = document.getElementById('trabajadorSeleccionado');
        const textoTrab = document.getElementById('textoSeleccionadoTrab');

        buscadorTrab.addEventListener('focus', function() {
            listaTrab.classList.add('activo');
        });

        buscadorTrab.addEventListener('input', function() {
            const texto = this.value.toLowerCase().trim();
            listaTrab.querySelectorAll('.buscador-item').forEach(item => {
                const busqueda = item.getAttribute('data-busqueda');
                if (busqueda.includes(texto)) {
                    item.classList.remove('oculto');
                } else {
                    item.classList.add('oculto');
                }
            });
            listaTrab.classList.add('activo');
        });

        listaTrab.querySelectorAll('.buscador-item').forEach(item => {
            item.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nombre = this.querySelector('strong').textContent;

                selectTrabHidden.value = id;
                buscadorTrab.style.display = 'none';
                listaTrab.classList.remove('activo');
                textoTrab.textContent = nombre;
                seleccionadoTrab.style.display = 'block';
            });
        });

        window.limpiarBuscadorTrabajador = function() {
            selectTrabHidden.value = '';
            buscadorTrab.value = '';
            buscadorTrab.style.display = 'block';
            buscadorTrab.focus();
            seleccionadoTrab.style.display = 'none';
            listaTrab.querySelectorAll('.buscador-item').forEach(i => i.classList.remove('oculto'));
        };

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.buscador-container')) {
                listaTrab.classList.remove('activo');
            }
        });
    }

    // ════════════════════════════════════════
    // INFORMACIÓN Y VALIDACIÓN DE DESGLOSE MULTILOTE EN TIEMPO REAL
    // ════════════════════════════════════════
    function validarLoteStockFila(tr) {
        const tipoSelect = document.getElementById('tipoMovimiento');
        const tipo = tipoSelect ? tipoSelect.value : 'entrada';
        if (tipo !== 'salida') return true;

        const hiddenDisp = tr.querySelector('.row-lote-disp');
        const cantidadInput = tr.querySelector('.row-cantidad-input');
        const trigger = tr.querySelector('.lote-trigger');
        const triggerTxt = tr.querySelector('.lote-trigger-text');

        if (!hiddenDisp || !cantidadInput || !trigger) return true;

        const disp = parseFloat(hiddenDisp.value || 0);
        const cant = parseFloat(cantidadInput.value || 0);

        if (hiddenDisp.value !== '' && cant > 0 && cant > disp) {
            // Indicar suavemente que se desglosará automáticamente
            trigger.style.borderColor = '#6366f1';
            trigger.style.backgroundColor = 'rgba(99, 102, 241, 0.08)';
            cantidadInput.style.borderColor = '#6366f1';
            return true;
        } else {
            if (hiddenDisp.value !== '') {
                trigger.style.borderColor = '#059669';
                trigger.style.backgroundColor = '';
            } else {
                trigger.style.borderColor = '';
                trigger.style.backgroundColor = '';
            }
            cantidadInput.style.borderColor = '';
            cantidadInput.style.backgroundColor = '';
            return true;
        }
    }
</script>
@endif
@endpush

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.querySelector('input[name="buscar"]');
        const workerSelect = document.querySelector('select[name="trabajador_id"]');
        const fromDateInput = document.querySelector('input[name="fecha_desde"]');
        const toDateInput = document.querySelector('input[name="fecha_hasta"]');
        const searchForm = document.querySelector('.filters');
        
        let debounceTimer;
        
        function performSearch() {
            if (!searchForm) return;
            const formData = new FormData(searchForm);
            const params = new URLSearchParams(formData);
            const url = searchForm.action + '?' + params.toString();
            
            // Update browser URL
            window.history.replaceState({}, '', url);
            
            const wrapper = document.getElementById('movimientos-table-wrapper');
            if (wrapper) wrapper.style.opacity = '0.6';
            
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Swap table wrapper
                const newWrapper = doc.getElementById('movimientos-table-wrapper');
                if (wrapper && newWrapper) {
                    wrapper.innerHTML = newWrapper.innerHTML;
                    wrapper.style.opacity = '1';
                }
                
                // Swap registros counter in header
                const counter = document.getElementById('header-registros-counter');
                const newCounter = doc.getElementById('header-registros-counter');
                if (counter && newCounter) {
                    counter.innerHTML = newCounter.innerHTML;
                }
                
                // Swap tab links hrefs
                const tabs = document.querySelectorAll('.tab-link');
                const newTabs = doc.querySelectorAll('.tab-link');
                tabs.forEach((tab, index) => {
                    if (newTabs[index]) {
                        tab.setAttribute('href', newTabs[index].getAttribute('href'));
                    }
                });
            })
            .catch(err => {
                console.error(err);
                if (wrapper) wrapper.style.opacity = '1';
            });
        }
        
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(performSearch, 250);
            });
            if (searchForm) {
                searchForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    performSearch();
                });
            }
        }
        
        if (workerSelect) {
            workerSelect.addEventListener('change', performSearch);
        }
        if (fromDateInput) {
            fromDateInput.addEventListener('change', performSearch);
        }
        if (toDateInput) {
            toDateInput.addEventListener('change', performSearch);
        }

        const periodSelect = document.getElementById('filtro_periodo');
        if (periodSelect) {
            function actualizarVisibilidadFechas() {
                const opt = periodSelect.value;
                const containerDesde = document.getElementById('container_desde');
                const containerHasta = document.getElementById('container_hasta');
                if (containerDesde) containerDesde.classList.toggle('visible', opt === 'personalizado');
                if (containerHasta) containerHasta.classList.toggle('visible', opt === 'personalizado');
            }

            periodSelect.addEventListener('change', function() {
                actualizarVisibilidadFechas();
                const opt = this.value;
                const hoy = new Date();
                const fmt = d => `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
                
                if (opt === 'todos') {
                    fromDateInput.value = '';
                    toDateInput.value = '';
                } else if (opt === 'personalizado') {
                    // Esperar a que el usuario introduzca fechas
                } else if (opt === 'diario') {
                    fromDateInput.value = fmt(hoy);
                    toDateInput.value = fmt(hoy);
                } else if (opt === 'semanal') {
                    const diff = hoy.getDay() === 0 ? -6 : 1 - hoy.getDay();
                    const lunes = new Date(hoy);
                    lunes.setDate(hoy.getDate() + diff);
                    const dom = new Date(lunes);
                    dom.setDate(lunes.getDate() + 6);
                    fromDateInput.value = fmt(lunes);
                    toDateInput.value = fmt(dom);
                } else if (opt === 'mensual') {
                    fromDateInput.value = fmt(new Date(hoy.getFullYear(), hoy.getMonth(), 1));
                    toDateInput.value = fmt(new Date(hoy.getFullYear(), hoy.getMonth() + 1, 0));
                }
                
                performSearch();
            });

            // Visibilidad inicial
            actualizarVisibilidadFechas();
        }
        // Cerrar buscador de artículos al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!e.target.classList.contains('row-articulo-search')) {
                document.querySelectorAll('.row-articulo-list').forEach(el => el.classList.remove('activo'));
            }
        });
    });

    function editarNotaMovimiento(movimientoId, notaActual) {
        document.getElementById('edit_nota_movimiento_id').value = movimientoId;
        document.getElementById('edit_nota_texto').value = (notaActual === 'null' || notaActual === '—') ? '' : notaActual;
        const modal = document.getElementById('modalEditarNota');
        modal.style.display = 'flex';
    }

    function cerrarModalNota() {
        const modal = document.getElementById('modalEditarNota');
        modal.style.display = 'none';
    }

    function guardarNotaModal() {
        const movimientoId = document.getElementById('edit_nota_movimiento_id').value;
        const nuevaNota = document.getElementById('edit_nota_texto').value;
        const btn = document.getElementById('btnGuardarNotaModal');

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

        fetch(`/movimientos/${movimientoId}/nota`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ notas: nuevaNota })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Guardar Cambios';

            if (data.success) {
                const spanNota = document.getElementById(`texto-nota-${movimientoId}`);
                if (spanNota) spanNota.textContent = data.nueva_nota;
                cerrarModalNota();
            } else {
                alert(data.error || 'Error al guardar la nota.');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Guardar Cambios';
            console.error(err);
            alert('Error al conectar con el servidor.');
        });
    }

    // Cerrar modal al presionar Escape o hacer click fuera del contenido
    document.getElementById('modalEditarNota')?.addEventListener('click', function(e) {
        if (e.target === this) cerrarModalNota();
    });

    // ─── EDICIÓN COMPLETA DE MOVIMIENTO POR ADMINISTRADOR ───────────────────────
    function abrirModalEditarMovimientoAdmin(mov) {
        const modal = document.getElementById('modalEditarMovimientoAdmin');
        if (!modal) return;

        document.getElementById('admin_edit_movimiento_id').value = mov.id;
        document.getElementById('admin_edit_articulo_display').value = `${mov.articulo?.codigo || ''} — ${mov.articulo?.nombre || ''}`;
        document.getElementById('admin_edit_tipo_display').value = mov.tipo ? mov.tipo.toUpperCase() : '';

        let fechaStr = mov.fecha;
        if (fechaStr && fechaStr.includes('T')) {
            fechaStr = fechaStr.split('T')[0];
        }
        document.getElementById('admin_edit_fecha').value = fechaStr || '';
        document.getElementById('admin_edit_cantidad').value = mov.cantidad || '';
        document.getElementById('admin_edit_precio_unitario').value = mov.precio_unitario || '';
        document.getElementById('admin_edit_entregado_por').value = mov.entregado_por || '';
        document.getElementById('admin_edit_recibido_por').value = mov.recibido_por || '';
        document.getElementById('admin_edit_trabajador_id').value = mov.trabajador_id || '';
        document.getElementById('admin_edit_turno').value = mov.turno || '';
        document.getElementById('admin_edit_notas').value = mov.notas || '';

        const isEntrada = (mov.tipo === 'entrada');
        const precioCont = document.getElementById('admin_edit_precio_container');
        const recibCont = document.getElementById('admin_edit_recibido_container');
        const salidCont = document.getElementById('admin_edit_salida_container');

        if (precioCont) precioCont.style.display = isEntrada ? 'block' : 'none';
        if (recibCont) recibCont.style.display = isEntrada ? 'block' : 'none';
        if (salidCont) salidCont.style.display = !isEntrada ? 'flex' : 'none';

        modal.style.display = 'flex';
    }

    function cerrarModalEditarMovimientoAdmin() {
        const modal = document.getElementById('modalEditarMovimientoAdmin');
        if (modal) modal.style.display = 'none';
    }

    function guardarMovimientoAdmin(e) {
        e.preventDefault();
        const movId = document.getElementById('admin_edit_movimiento_id').value;
        const btn = document.getElementById('btnGuardarMovAdmin');

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

        const formData = {
            fecha: document.getElementById('admin_edit_fecha').value,
            cantidad: document.getElementById('admin_edit_cantidad').value,
            precio_unitario: document.getElementById('admin_edit_precio_unitario').value,
            entregado_por: document.getElementById('admin_edit_entregado_por').value,
            recibido_por: document.getElementById('admin_edit_recibido_por').value,
            trabajador_id: document.getElementById('admin_edit_trabajador_id').value,
            turno: document.getElementById('admin_edit_turno').value,
            notas: document.getElementById('admin_edit_notas').value,
        };

        fetch(`/movimientos/${movId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Guardar Cambios';

            if (data.success) {
                cerrarModalEditarMovimientoAdmin();
                window.location.reload();
            } else {
                alert(data.error || 'Ocurrió un error al intentar guardar la edición.');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Guardar Cambios';
            console.error(err);
            alert('Error al conectar con el servidor.');
        });
    }

    document.getElementById('modalEditarMovimientoAdmin')?.addEventListener('click', function(e) {
        if (e.target === this) cerrarModalEditarMovimientoAdmin();
    });
</script>
@endpush