@extends('layouts.mina')

@section('titulo', 'Inventario')

@push('styles')
<style>
    .page-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 25px; flex-wrap: wrap; gap: 15px;
    }
    .page-header .page-title {
        color: var(--text-primary) !important;
        -webkit-text-fill-color: var(--text-primary) !important;
        font-size: 24px !important;
        font-weight: 700 !important;
        display: flex; align-items: center; gap: 10px;
    }
    .page-header .page-title i {
        color: #2563eb !important;
    }
    .page-header .page-counter {
        background: rgba(37, 99, 235, 0.08) !important;
        color: #2563eb !important;
        border: 1.5px solid rgba(37, 99, 235, 0.18) !important;
        -webkit-text-fill-color: #2563eb !important;
        padding: 5px 14px !important;
        border-radius: 20px !important;
        font-size: 12.5px !important;
        font-weight: 700 !important;
        letter-spacing: 0.2px !important;
    }
    [data-theme="dark"] .page-header .page-counter {
        background: rgba(96, 165, 250, 0.1) !important;
        color: #60a5fa !important;
        -webkit-text-fill-color: #60a5fa !important;
        border-color: rgba(96, 165, 250, 0.25) !important;
    }
    .valor-total {
        background: rgba(16, 185, 129, 0.08) !important;
        color: #059669 !important;
        border: 1.5px solid rgba(16, 185, 129, 0.18) !important;
        -webkit-text-fill-color: #059669 !important;
        padding: 5px 14px !important;
        border-radius: 20px !important;
        font-size: 12.5px !important;
        font-weight: 700 !important;
        display: inline-flex; align-items: center; gap: 6px;
        letter-spacing: 0.2px !important;
    }
    [data-theme="dark"] .valor-total {
        background: rgba(52, 211, 153, 0.1) !important;
        color: #34d399 !important;
        -webkit-text-fill-color: #34d399 !important;
        border-color: rgba(52, 211, 153, 0.25) !important;
    }
    .filters {
        background: var(--bg-card) !important;
        padding: 20px; border-radius: 16px;
        margin-bottom: 24px; display: grid;
        grid-template-columns: 2fr 1fr 1fr auto; gap: 16px; align-items: end;
        border: 1.5px solid var(--border) !important;
        box-shadow: var(--shadow) !important;
    }
    .filters .form-field { display: flex; flex-direction: column; gap: 5px; }
    .filters label {
        font-size: 11px; font-weight: 700; color: var(--text-muted) !important;
        text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 4px;
    }
    .filters input, .filters select {
        padding: 10px 16px; border: 1.5px solid var(--border) !important;
        border-radius: 10px; font-size: 13.5px; background: var(--bg-input) !important;
        color: var(--text-primary) !important;
        transition: all 0.25s ease;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.02);
    }
    .filters input:focus, .filters select:focus {
        outline: none; border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
    }
    .btn {
        padding: 10px 18px; border: none; border-radius: 12px;
        font-size: 13.5px; font-weight: 600; cursor: pointer;
        transition: all 0.25s ease; display: inline-flex; align-items: center;
        gap: 6px; text-decoration: none;
    }
    .btn-primary {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2) !important;
        border: none !important;
    }
    .btn-primary:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35) !important;
    }
    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2) !important;
        border: none !important;
    }
    .btn-success:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35) !important;
    }
    .btn-secondary {
        background: var(--bg-card) !important;
        color: var(--text-primary) !important;
        border: 1.5px solid var(--border) !important;
        box-shadow: var(--shadow) !important;
    }
    .btn-secondary:hover {
        background: var(--bg-hover) !important;
        transform: translateY(-2px) !important;
    }
    .btn-grupos {
        background: #334155 !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(51, 65, 85, 0.2) !important;
        border: none !important;
    }
    .btn-grupos:hover {
        background: #1e293b !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 16px rgba(51, 65, 85, 0.35) !important;
    }
    .acciones-cell {
        display: flex;
        gap: 6px;
        align-items: center;
    }
    .btn-action {
        width: 32px !important;
        height: 32px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        border: 1px solid var(--border) !important;
        border-radius: 10px !important;
        cursor: pointer !important;
        background: var(--bg-card) !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        text-decoration: none !important;
        padding: 0 !important;
        box-shadow: none !important;
    }
    .btn-action i {
        font-size: 13px !important;
        transition: color 0.2s ease !important;
    }
    .btn-action-kardex {
        color: #2563eb !important;
        -webkit-text-fill-color: #2563eb !important;
        border-color: rgba(37, 99, 235, 0.15) !important;
        background: rgba(37, 99, 235, 0.04) !important;
    }
    .btn-action-kardex:hover {
        background: #2563eb !important;
        color: white !important;
        -webkit-text-fill-color: white !important;
        border-color: transparent !important;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25) !important;
        transform: translateY(-2px) !important;
    }
    .btn-action-edit {
        color: #059669 !important;
        -webkit-text-fill-color: #059669 !important;
        border-color: rgba(5, 150, 105, 0.15) !important;
        background: rgba(5, 150, 105, 0.04) !important;
    }
    .btn-action-edit:hover {
        background: #059669 !important;
        color: white !important;
        -webkit-text-fill-color: white !important;
        border-color: transparent !important;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25) !important;
        transform: translateY(-2px) !important;
    }
    .btn-action-delete {
        color: #dc2626 !important;
        -webkit-text-fill-color: #dc2626 !important;
        border-color: rgba(220, 38, 38, 0.15) !important;
        background: rgba(220, 38, 38, 0.04) !important;
    }
    .btn-action-delete:hover {
        background: #dc2626 !important;
        color: white !important;
        -webkit-text-fill-color: white !important;
        border-color: transparent !important;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25) !important;
        transform: translateY(-2px) !important;
    }

    .btn-group { display: flex; gap: 8px; }


    /* ─────────────── SEPARADORES DE COLUMNA CON COLORES BAJITOS ─────────────── */
    /* Azul — Stock */
    .col-stock { border-left: 3px solid rgba(37, 99, 235, 0.30) !important; }
    table thead .col-stock {
        background: rgba(37, 99, 235, 0.07) !important;
    }
    table tbody td.col-stock {
        background: rgba(37, 99, 235, 0.04) !important;
    }
    table tbody tr:hover td.col-stock {
        background: rgba(37, 99, 235, 0.09) !important;
    }

    /* Verde — Precio Unitario */
    .col-precio { border-left: 3px solid rgba(5, 150, 105, 0.30) !important; }
    table thead .col-precio {
        background: rgba(5, 150, 105, 0.07) !important;
    }
    table tbody td.col-precio {
        background: rgba(5, 150, 105, 0.04) !important;
    }
    table tbody tr:hover td.col-precio {
        background: rgba(5, 150, 105, 0.09) !important;
    }

    /* Ámbar — Valor Total */
    .col-valor { border-left: 3px solid rgba(217, 119, 6, 0.30) !important; }
    table thead .col-valor {
        background: rgba(217, 119, 6, 0.07) !important;
    }
    table tbody td.col-valor {
        background: rgba(217, 119, 6, 0.04) !important;
    }
    table tbody tr:hover td.col-valor {
        background: rgba(217, 119, 6, 0.09) !important;
    }

    /* Violeta — Notas/Obs. */
    .col-notas { border-left: 3px solid rgba(168, 85, 247, 0.30) !important; }
    table thead .col-notas {
        background: rgba(168, 85, 247, 0.07) !important;
    }
    table tbody td.col-notas {
        background: rgba(168, 85, 247, 0.04) !important;
    }
    table tbody tr:hover td.col-notas {
        background: rgba(168, 85, 247, 0.09) !important;
    }

    /* Gris pizarra — Acciones */
    .col-acciones { border-left: 3px solid rgba(100, 116, 139, 0.28) !important; }
    table thead .col-acciones {
        background: rgba(100, 116, 139, 0.07) !important;
    }
    table tbody td.col-acciones {
        background: rgba(100, 116, 139, 0.04) !important;
    }
    table tbody tr:hover td.col-acciones {
        background: rgba(100, 116, 139, 0.09) !important;
    }
    /* ─────────────── FIN SEPARADORES ─────────────── */


    .table-container {
        background: var(--bg-card) !important;
        border-radius: 16px !important;
        border: 1.5px solid var(--border) !important;
        box-shadow: var(--shadow) !important;
        overflow: hidden !important;
    }
    table { width: 100%; border-collapse: collapse; }
    table th {
        background: var(--bg-card) !important;
        padding: 16px 12px !important;
        text-align: left;
        font-family: 'Outfit', 'Inter', system-ui, -apple-system, sans-serif !important;
        font-weight: 700 !important;
        color: var(--text-primary) !important;
        border-bottom: 2.5px solid var(--border) !important;
        font-size: 11.5px !important;
        text-transform: uppercase !important;
        letter-spacing: 1.2px !important;
        opacity: 0.95;
    }
    table td {
        padding: 16px 12px !important;
        border-bottom: 1px solid var(--border-light) !important;
        color: var(--text-primary) !important;
    }
    table tbody tr { transition: all 0.2s; background: transparent !important; }
    table tbody tr:hover { background: var(--bg-hover) !important; }

    /* Nombre del material destacado */
    .articulo-nombre {
        font-weight: 600;
        color: var(--text-primary) !important;
        font-size: 14.5px;
        letter-spacing: -0.1px;
    }

    /* Fila separadora de grupo */
    table tbody tr.grupo-separador-row td {
        background: var(--group-header-bg) !important;
        color: var(--group-color) !important;
        -webkit-text-fill-color: var(--group-color) !important;
        font-weight: 800 !important;
        font-size: 12.5px !important;
        text-transform: uppercase !important;
        letter-spacing: 1px !important;
        border-bottom: 2px solid var(--group-border) !important;
        border-top: none !important;
        border-left: 5px solid var(--group-color) !important;
        padding: 13px 18px !important;
    }
    table tbody tr.grupo-separador-row td i {
        color: var(--group-color) !important;
        margin-right: 8px !important;
        opacity: 0.8;
    }

    .badge-rotacion-diario {
        display: inline-block;
        background: rgba(71, 85, 105, 0.03) !important;
        color: #64748b !important;
        padding: 2px 6px !important;
        border-radius: 4px !important;
        font-size: 8.5px !important;
        font-weight: 600 !important;
        margin-left: 8px !important;
        text-transform: uppercase !important;
        border: 1px solid rgba(71, 85, 105, 0.1) !important;
        letter-spacing: 0.3px;
        opacity: 0.65;
    }
    [data-theme="dark"] .badge-rotacion-diario {
        color: #94a3b8 !important;
        background: rgba(148, 163, 184, 0.05) !important;
        border-color: rgba(148, 163, 184, 0.1) !important;
    }

    .badge-rotacion-ocasional {
        display: inline-block;
        background: rgba(71, 85, 105, 0.03) !important;
        color: #64748b !important;
        padding: 2px 6px !important;
        border-radius: 4px !important;
        font-size: 8.5px !important;
        font-weight: 600 !important;
        margin-left: 8px !important;
        text-transform: uppercase !important;
        border: 1px solid rgba(71, 85, 105, 0.1) !important;
        letter-spacing: 0.3px;
        opacity: 0.65;
    }
    [data-theme="dark"] .badge-rotacion-ocasional {
        color: #94a3b8 !important;
        background: rgba(148, 163, 184, 0.05) !important;
        border-color: rgba(148, 163, 184, 0.1) !important;
    }

    table tbody td .codigo {
        font-family: 'Courier New', monospace !important;
        font-weight: 600 !important;
        color: #475569 !important;
        -webkit-text-fill-color: #475569 !important;
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        font-size: 12.5px !important;
        letter-spacing: 0.5px !important;
        white-space: nowrap !important;
    }
    [data-theme="dark"] table tbody td .codigo {
        color: #94a3b8 !important;
        -webkit-text-fill-color: #94a3b8 !important;
        background: transparent !important;
        border: none !important;
    }
    .stock-badge {
        display: inline-block; padding: 4px 10px;
        border-radius: 20px; font-size: 10.5px; font-weight: 700; margin-left: 6px;
        letter-spacing: 0.4px; text-transform: uppercase;
    }
    /* Normal: azul eléctrico */
    .stock-ok {
        background: rgba(37, 99, 235, 0.10) !important;
        color: #1d4ed8 !important;
        border: 1.5px solid rgba(37, 99, 235, 0.25) !important;
    }
    [data-theme="dark"] .stock-ok {
        background: rgba(96, 165, 250, 0.12) !important;
        color: #60a5fa !important;
        border-color: rgba(96, 165, 250, 0.3) !important;
    }
    /* Medio: ámbar vibrante */
    .stock-medio {
        background: rgba(245, 158, 11, 0.12) !important;
        color: #b45309 !important;
        border: 1.5px solid rgba(245, 158, 11, 0.30) !important;
    }
    [data-theme="dark"] .stock-medio {
        background: rgba(251, 191, 36, 0.12) !important;
        color: #fbbf24 !important;
        border-color: rgba(251, 191, 36, 0.35) !important;
    }
    /* Bajo: rojo fuerte */
    .stock-bajo {
        background: rgba(220, 38, 38, 0.10) !important;
        color: #b91c1c !important;
        border: 1.5px solid rgba(220, 38, 38, 0.28) !important;
    }
    [data-theme="dark"] .stock-bajo {
        background: rgba(248, 113, 113, 0.12) !important;
        color: #f87171 !important;
        border-color: rgba(248, 113, 113, 0.35) !important;
    }
    /* Sin Stock: gris oscuro neutro */
    .stock-cero {
        background: rgba(71, 85, 105, 0.10) !important;
        color: #334155 !important;
        border: 1.5px solid rgba(71, 85, 105, 0.25) !important;
    }
    [data-theme="dark"] .stock-cero {
        background: rgba(148, 163, 184, 0.10) !important;
        color: #94a3b8 !important;
        border-color: rgba(148, 163, 184, 0.25) !important;
    }
    /* Precio unitario: azul zafiro */
    .precio { font-weight: 600; color: #2563eb; font-family: 'Courier New', monospace; }
    [data-theme="dark"] .precio { color: #60a5fa !important; }
    /* Precio múltiple: violeta amatista */
    .precio-multiple { font-weight: 700; color: #7c3aed !important; font-family: 'Courier New', monospace; }
    [data-theme="dark"] .precio-multiple { color: #a78bfa !important; }
    /* Valor total: índigo profundo (no verde) */
    .precio-total { font-weight: 700; color: #4338ca; font-family: 'Courier New', monospace; }
    [data-theme="dark"] .precio-total { color: #818cf8 !important; }

    .grupo-tag {
        display: inline-block !important;
        background: var(--group-bg, var(--bg-hover)) !important;
        color: var(--group-color, var(--text-muted)) !important;
        border: 1px solid var(--group-border, var(--border)) !important;
        padding: 3px 8px !important;
        border-radius: 6px !important;
        font-size: 11px !important;
        font-weight: 700 !important;
    }

    /* Paleta de colores para los grupos */
    .grupo-carmesi {
        --group-color: #9f1239;
        --group-bg: rgba(190, 18, 60, 0.06);
        --group-border: rgba(190, 18, 60, 0.28);
        --group-header-bg: rgba(190, 18, 60, 0.13);
    }
    .grupo-zafiro {
        --group-color: #1e40af;
        --group-bg: rgba(29, 78, 216, 0.06);
        --group-border: rgba(29, 78, 216, 0.28);
        --group-header-bg: rgba(29, 78, 216, 0.13);
    }
    .grupo-amatista {
        --group-color: #6d28d9;
        --group-bg: rgba(109, 40, 217, 0.06);
        --group-border: rgba(109, 40, 217, 0.28);
        --group-header-bg: rgba(109, 40, 217, 0.13);
    }
    .grupo-esmeralda {
        --group-color: #065f46;
        --group-bg: rgba(4, 120, 87, 0.06);
        --group-border: rgba(4, 120, 87, 0.28);
        --group-header-bg: rgba(4, 120, 87, 0.13);
    }
    .grupo-ambar {
        --group-color: #92400e;
        --group-bg: rgba(180, 83, 9, 0.06);
        --group-border: rgba(180, 83, 9, 0.28);
        --group-header-bg: rgba(180, 83, 9, 0.13);
    }
    .grupo-pizarra {
        --group-color: #1e293b;
        --group-bg: rgba(30, 41, 59, 0.06);
        --group-border: rgba(30, 41, 59, 0.28);
        --group-header-bg: rgba(30, 41, 59, 0.13);
    }

    [data-theme="dark"] .grupo-carmesi {
        --group-color: #fda4af;
        --group-bg: rgba(251, 113, 133, 0.07);
        --group-border: rgba(251, 113, 133, 0.25);
        --group-header-bg: rgba(251, 113, 133, 0.14);
    }
    [data-theme="dark"] .grupo-zafiro {
        --group-color: #93c5fd;
        --group-bg: rgba(96, 165, 250, 0.07);
        --group-border: rgba(96, 165, 250, 0.25);
        --group-header-bg: rgba(96, 165, 250, 0.14);
    }
    [data-theme="dark"] .grupo-amatista {
        --group-color: #c4b5fd;
        --group-bg: rgba(167, 139, 250, 0.07);
        --group-border: rgba(167, 139, 250, 0.25);
        --group-header-bg: rgba(167, 139, 250, 0.14);
    }
    [data-theme="dark"] .grupo-esmeralda {
        --group-color: #6ee7b7;
        --group-bg: rgba(52, 211, 153, 0.07);
        --group-border: rgba(52, 211, 153, 0.25);
        --group-header-bg: rgba(52, 211, 153, 0.14);
    }
    [data-theme="dark"] .grupo-ambar {
        --group-color: #fcd34d;
        --group-bg: rgba(251, 191, 36, 0.07);
        --group-border: rgba(251, 191, 36, 0.25);
        --group-header-bg: rgba(251, 191, 36, 0.14);
    }
    [data-theme="dark"] .grupo-pizarra {
        --group-color: #cbd5e1;
        --group-bg: rgba(148, 163, 184, 0.07);
        --group-border: rgba(148, 163, 184, 0.25);
        --group-header-bg: rgba(148, 163, 184, 0.14);
    }

    .empty { text-align: center; padding: 50px 20px; color: #999; }
    .empty i { font-size: 64px; opacity: 0.3; margin-bottom: 15px; }

    /* MODAL */
    .modal {
        display: none; position: fixed; top: 0; left: 0;
        width: 100%; height: 100%; background: rgba(0,0,0,0.5);
        z-index: 1000; align-items: center; justify-content: center;
    }
    .modal.active { display: flex; }
    .modal-content {
        background: white; border-radius: 15px; padding: 30px;
        max-width: 600px; width: 90%; max-height: 90vh;
        overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
    .modal-header {
        font-size: 20px; font-weight: bold; margin-bottom: 20px;
        display: flex; justify-content: space-between; align-items: center;
        color: #667eea;
    }
    .modal-close { background: none; border: none; font-size: 28px; cursor: pointer; color: #999; }
    .form-group { margin-bottom: 18px; }
    .form-group label {
        display: block; margin-bottom: 6px; font-weight: 600;
        color: #333; font-size: 14px;
    }
    .form-group input, .form-group select {
        width: 100%; padding: 12px;
        border: 2px solid #e0e0e0; border-radius: 8px;
        font-size: 14px; font-family: inherit; text-transform: uppercase;
    }
    .form-group input:focus, .form-group select:focus {
        outline: none; border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
    .modal-footer { display: flex; gap: 10px; margin-top: 25px; }
    .error-list {
        background: #ffe3e3; border-left: 4px solid #ff6b6b;
        padding: 12px 16px; border-radius: 8px; margin-bottom: 18px;
        color: #862e2e; font-size: 13px;
    }
    .help-text { font-size: 12px; color: #999; margin-top: 4px; font-style: italic; }

    /* LISTA DE GRUPOS */
    .grupo-item {
        display: flex; align-items: center; gap: 12px;
        padding: 14px; border: 1px solid #e9ecef;
        border-radius: 10px; margin-bottom: 10px;
        transition: all 0.2s;
    }
    .grupo-item:hover { background: #faf9ff; border-color: #667eea; }
    .grupo-item-id {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white; padding: 6px 12px; border-radius: 8px;
        font-family: 'Courier New', monospace; font-weight: bold;
        font-size: 13px; flex-shrink: 0;
    }
    .grupo-item-info { flex: 1; }
    .grupo-item-nombre { font-weight: 600; color: #2d3748; font-size: 14px; }
    .grupo-item-count { font-size: 12px; color: #868e96; margin-top: 2px; }
    .grupo-item-acciones { display: flex; gap: 6px; }
    .grupo-item-acciones button, .grupo-item-acciones a {
        padding: 7px 11px; border: none; border-radius: 7px;
        cursor: pointer; font-size: 12px; color: white;
        display: inline-flex; align-items: center; gap: 4px;
        text-decoration: none;
    }
    .btn-mini-edit { background: #ffa94d; }
    .btn-mini-delete { background: #ff6b6b; }
    .btn-mini-bloqueado {
        background: #e9ecef; color: #adb5bd;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .filters { grid-template-columns: 1fr; }
        .form-row { grid-template-columns: 1fr; }
    }

    .mensaje-flotante {
        position: fixed;
        top: 80px;
        right: 20px;
        padding: 14px 22px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        color: white;
        z-index: 9999;
        box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        gap: 10px;
        opacity: 1;
        transition: opacity 0.3s;
        animation: slideInRight 0.3s;
    }

    /* Notas / Observaciones */
    .notas-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 6px;
        background: rgba(168, 85, 247, 0.1);
        border: 1px solid rgba(168, 85, 247, 0.2);
        color: #a855f7;
        font-size: 11px;
        cursor: help;
        position: relative;
        transition: all 0.2s ease;
    }
    .notas-icon:hover {
        background: rgba(168, 85, 247, 0.18);
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(168, 85, 247, 0.2);
    }
    .notas-tooltip {
        display: none;
        position: absolute;
        bottom: 110%;
        left: 50%;
        transform: translateX(-50%);
        background: #1e1b4b;
        color: white;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 12px;
        white-space: pre-wrap;
        max-width: 220px;
        min-width: 120px;
        text-align: left;
        line-height: 1.5;
        z-index: 100;
        box-shadow: 0 8px 24px rgba(0,0,0,0.25);
    }
    .notas-tooltip::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border: 5px solid transparent;
        border-top-color: #1e1b4b;
    }
    .notas-icon:hover .notas-tooltip { display: block; }
    .sin-notas {
        color: var(--text-muted);
        font-size: 13px;
        opacity: 0.4;
    }

    .mensaje-error {
        background: linear-gradient(135deg, #c92a2a 0%, #e03131 100%);
    }

    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
</style>
@endpush

@section('contenido')

    <div class="page-header">
        <h2 class="page-title">
            <i class="fas fa-list"></i> Inventario
            <span class="page-counter" id="inventario-count">{{ $total }} artículos</span>
            <span class="valor-total" id="inventario-valor">
                <i class="fas fa-dollar-sign"></i>
                Valor: Bs. {{ number_format($valorInventario, 2) }}
            </span>
        </h2>
        @if(Auth::user()->puedeEditar())
            <div style="display:flex; gap:10px;">
                <button class="btn btn-primary" onclick="abrirModalNuevo()">
                    <i class="fas fa-plus"></i> Agregar Artículo
                </button>
                <button class="btn btn-secondary" onclick="abrirModalGrupo()">
                    <i class="fas fa-folder-plus"></i> Nuevo Grupo
                </button>
                @if(Auth::user()->esAdmin())
                    <button class="btn btn-grupos" onclick="abrirModalGestionarGrupos()">
                        <i class="fas fa-cog"></i> Gestionar Grupos
                    </button>
                @endif
            </div>
        @endif
    </div>

    <form method="GET" action="{{ route('inventario.index') }}" class="filters">
        <div class="form-field">
            <label><i class="fas fa-search"></i> Buscar</label>
            <input type="text" name="buscar" placeholder="Código o nombre..." value="{{ request('buscar') }}">
        </div>
        <div class="form-field">
            <label><i class="fas fa-folder"></i> Grupo</label>
            <select name="grupo">
                <option value="">Todos los grupos</option>
                @foreach($grupos as $grupo)
                    <option value="{{ $grupo->id }}" {{ request('grupo') == $grupo->id ? 'selected' : '' }}>
                        {{ $grupo->id }} — {{ $grupo->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-field">
            <label><i class="fas fa-warehouse"></i> Stock</label>
            <select name="stock">
                <option value="">Todos</option>
                <option value="con_stock" {{ request('stock') == 'con_stock' ? 'selected' : '' }}>Con stock</option>
                <option value="sin_stock" {{ request('stock') == 'sin_stock' ? 'selected' : '' }}>Sin stock</option>
            </select>
        </div>
        <div class="btn-group">
            <a href="{{ route('inventario.index') }}" class="btn btn-secondary" title="Limpiar filtros" style="display: inline-flex; align-items: center; gap: 6px;">
                <i class="fas fa-times"></i> Limpiar
            </a>
        </div>
    </form>

    <div id="inventario-table-wrapper" style="transition: opacity 0.15s ease-in-out;">
        @if($articulos->isEmpty())
            <div class="empty">
                <i class="fas fa-search"></i>
                <p>No se encontraron artículos.</p>
            </div>
        @else
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 105px;">Código</th>
                            <th>Descripción</th>
                            <th style="width: 70px;">Grupo</th>
                            <th style="width: 80px;">Unidad</th>
                            <th style="width: 140px;" class="col-stock">Stock</th>
                            <th style="width: 100px;" class="col-precio">Precio Unit.</th>
                            <th style="width: 110px;" class="col-valor">Valor Total</th>
                            <th style="width: 60px;" class="col-notas">Obs.</th>
                            @if(Auth::user()->puedeEditar() || Auth::user()->puedeReportes())
                                <th style="width: 130px;" class="col-acciones">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $grupoActual = null;
                            if (!function_exists('getGrupoColorClass')) {
                                function getGrupoColorClass($grupoId) {
                                    $num = intval(preg_replace('/[^0-9]/', '', $grupoId));
                                    switch ($num) {
                                        case 1: return 'grupo-carmesi';
                                        case 2: return 'grupo-zafiro';
                                        case 3: return 'grupo-amatista';
                                        case 4: return 'grupo-esmeralda';
                                        case 5: return 'grupo-ambar';
                                        default: return 'grupo-pizarra';
                                    }
                                }
                            }
                        @endphp
                        @foreach($articulos as $articulo)
                            @if($articulo->grupo_id !== $grupoActual)
                                @php $grupoActual = $articulo->grupo_id; @endphp
                                <tr class="grupo-separador-row {{ getGrupoColorClass($articulo->grupo_id) }}">
                                    <td colspan="{{ (Auth::user()->puedeEditar() || Auth::user()->puedeReportes()) ? 9 : 8 }}">
                                        <i class="fas fa-folder"></i>
                                        {{ $articulo->grupo_id }} — {{ $articulo->grupo->nombre ?? '' }}
                                    </td>
                                </tr>
                            @endif
                            <tr class="{{ getGrupoColorClass($articulo->grupo_id) }}">
                                <td><span class="codigo">{{ $articulo->codigo }}</span></td>
                                <td>
                                    <span class="articulo-nombre">{{ $articulo->nombre }}</span>
                                    @if($articulo->esConsumoDiario())
                                        <span class="badge-rotacion-diario" style="background: rgba(34,197,94,0.08) !important; color: #15803d !important; border: 1px solid rgba(34,197,94,0.25) !important;">CONSUMIBLES</span>
                                    @elseif($articulo->esPrestamo())
                                        <span class="badge-rotacion-diario" style="background: rgba(249,115,22,0.08) !important; color: #c2410c !important; border: 1px solid rgba(249,115,22,0.25) !important;">ACTIVO / EN USO</span>
                                    @else
                                        <span class="badge-rotacion-ocasional" style="background: rgba(99,102,241,0.08) !important; color: #4338ca !important; border: 1px solid rgba(99,102,241,0.25) !important;">RESERVA / EN ALMACÉN</span>
                                    @endif
                                </td>
                                <td><span class="grupo-tag">{{ $articulo->grupo_id }}</span></td>
                                <td>{{ $articulo->unidad }}</td>
                                <td class="col-stock">
                                    <strong>{{ number_format($articulo->cantidad, 3) }}</strong>
                                    @php
                                        $minimo = $articulo->stock_minimo > 0 ? $articulo->stock_minimo : 10;
                                        $medio = $minimo * 1.5;
                                    @endphp
                                    @if($articulo->cantidad <= 0)
                                        <span class="stock-badge stock-cero">Sin stock</span>
                                    @elseif($articulo->cantidad <= $minimo)
                                        <span class="stock-badge stock-bajo">Bajo</span>
                                    @elseif($articulo->cantidad <= $medio)
                                        <span class="stock-badge stock-medio">Medio</span>
                                    @else
                                        <span class="stock-badge stock-ok">Normal</span>
                                    @endif
                                </td>
                                <td class="col-precio">
                                    @if(isset($preciosPorArticulo[$articulo->id]) && count($preciosPorArticulo[$articulo->id]) > 1)
                                        <div style="display: flex; flex-direction: column; gap: 3px;">
                                            @foreach($preciosPorArticulo[$articulo->id] as $p)
                                                @php
                                                    $cant = floatval($p['cantidad']);
                                                    $cantFormated = ($cant == intval($cant)) ? number_format($cant, 0) : number_format($cant, 2);
                                                @endphp
                                                <div style="white-space: nowrap; font-size: 13px;">
                                                    <span class="precio-multiple">Bs. {{ number_format($p['precio'], 2) }}</span>
                                                    <span style="color: var(--text-muted); font-size: 11px; margin-left: 3px;">({{ $cantFormated }} {{ strtolower($articulo->unidad) }})</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif(isset($preciosPorArticulo[$articulo->id]) && count($preciosPorArticulo[$articulo->id]) === 1)
                                        <span class="precio">Bs. {{ number_format(data_get($preciosPorArticulo[$articulo->id], '0.precio'), 2) }}</span>
                                    @else
                                        <span class="precio">Bs. {{ number_format($articulo->precio, 2) }}</span>
                                    @endif
                                </td>
                                <td class="col-valor">
                                    @if(isset($valorPorArticulo[$articulo->id]))
                                        <strong class="precio-total">Bs. {{ number_format($valorPorArticulo[$articulo->id], 2) }}</strong>
                                    @else
                                        <strong class="precio-total">Bs. {{ number_format($articulo->precio * $articulo->cantidad, 2) }}</strong>
                                    @endif
                                </td>
                                <td class="col-notas" style="text-align: center;">
                                    @if($articulo->notas)
                                        <div class="notas-icon" title="{{ $articulo->notas }}">
                                            <i class="fas fa-sticky-note"></i>
                                            <div class="notas-tooltip">{{ $articulo->notas }}</div>
                                        </div>
                                    @else
                                        <span class="sin-notas">—</span>
                                    @endif
                                </td>
                                @if(Auth::user()->puedeEditar() || Auth::user()->puedeReportes())
                                    <td class="col-acciones">
                                        <div class="acciones-cell">
                                            @if(Auth::user()->puedeReportes() || Auth::user()->esAlmacenero() || Auth::user()->puedeEditar())
                                                <a href="{{ route('reportes.kardex', $articulo->id) }}?from=inventario"
                                                   class="btn-action btn-action-kardex"
                                                   title="Ver Kardex / Reportes">
                                                    <i class="fas fa-history"></i>
                                                </a>
                                            @endif
                                            @if(Auth::user()->puedeEditarMateriales())
                                                @php
                                                    $tieneMultiplesPrecios = (isset($preciosPorArticulo[$articulo->id]) && count($preciosPorArticulo[$articulo->id]) > 1) ? 'true' : 'false';
                                                @endphp
                                                <button class="btn-action btn-action-edit"
                                                         onclick="abrirModalEditar({{ $articulo->id }}, '{{ addslashes($articulo->codigo) }}', '{{ addslashes($articulo->nombre) }}', '{{ addslashes($articulo->unidad) }}', '{{ $articulo->grupo_id }}', {{ $articulo->cantidad }}, {{ $articulo->precio }}, {{ $tieneMultiplesPrecios }}, '{{ addslashes($articulo->notas ?? '') }}')"
                                                         title="Editar">
                                                     <i class="fas fa-edit"></i>
                                                </button>
                                            @endif
                                            @if(Auth::user()->esAdmin())
                                                <button type="button"
                                                        class="btn-action btn-action-delete"
                                                        title="Eliminar artículo"
                                                        onclick="abrirModalEliminarArticulo(
                                                            {{ $articulo->id }},
                                                            '{{ $articulo->codigo }}',
                                                            '{{ addslashes($articulo->nombre) }}',
                                                            '{{ $articulo->unidad }}',
                                                            {{ $articulo->cantidad }},
                                                            {{ $articulo->movimientos_count ?? 0 }}
                                                        )">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top:20px; display:flex; justify-content:center;">
                {{ $articulos->links() }}
            </div>
        @endif
    </div>

    @if(Auth::user()->puedeEditar())

        {{-- MODAL: NUEVO ARTÍCULO --}}
        <div class="modal {{ $errors->any() && old('_form') == 'nuevo' ? 'active' : '' }}" id="modalNuevo">
            <div class="modal-content">
                <div class="modal-header">
                    <span><i class="fas fa-plus-circle"></i> Agregar Nuevo Artículo</span>
                    <button class="modal-close" onclick="cerrarModales()">&times;</button>
                </div>
                @if($errors->any() && old('_form') == 'nuevo')
                    <div class="error-list">
                        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('inventario.store') }}">
                    @csrf
                    <input type="hidden" name="_form" value="nuevo">
                    <div class="form-group">
                        <label><i class="fas fa-folder"></i> Grupo</label>
                        <select name="grupo_id" id="select-grupo-nuevo" required>
                            <option value="">— Seleccionar grupo —</option>
                            @foreach($grupos as $g)
                                <option value="{{ $g->id }}" {{ old('grupo_id') == $g->id ? 'selected' : '' }}>
                                    {{ $g->id }} — {{ $g->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-barcode"></i> Código</label>
                            <input type="text" name="codigo" id="input-codigo-nuevo" value="{{ old('codigo') }}" placeholder="Se sugerirá al elegir grupo..." required>
                            <p class="help-text" id="codigo-sugerencia"><i class="fas fa-magic"></i> Selecciona un grupo y el código se sugerirá.</p>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-ruler"></i> Unidad</label>
                            <input type="text" name="unidad" value="{{ old('unidad') }}" placeholder="UNIDAD, KILOS..." required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-tag"></i> Nombre / Descripción</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Descripción del artículo" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-hashtag"></i> Cantidad inicial</label>
                            <input type="number" name="cantidad" value="{{ old('cantidad', 0) }}" min="0" step="any">
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-dollar-sign"></i> Precio unitario (Bs.)</label>
                            <input type="number" name="precio" value="{{ old('precio', 0) }}" min="0" step="0.01">
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-sticky-note"></i> Observaciones / Notas <span style="font-weight:400; opacity:0.6;">(opcional)</span></label>
                        <textarea name="notas" rows="2" style="resize:vertical; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 10px; font-size: 13px; background: var(--bg-input); color: var(--text-primary); width: 100%; font-family: inherit;" placeholder="Ej: Pieza con desgaste, revisar antes de usar...">{{ old('notas') }}</textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" style="flex:1; justify-content:center;">
                            <i class="fas fa-save"></i> Guardar Artículo
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="cerrarModales()" style="flex:1; justify-content:center;">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL: EDITAR ARTÍCULO --}}
        <div class="modal {{ $errors->any() && old('_form') == 'editar' ? 'active' : '' }}" id="modalEditar">
            <div class="modal-content">
                <div class="modal-header">
                    <span><i class="fas fa-edit"></i> Editar Artículo</span>
                    <button class="modal-close" onclick="cerrarModales()">&times;</button>
                </div>
                @if($errors->any() && old('_form') == 'editar')
                    <div class="error-list">
                        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                <form method="POST" action="" id="formEditar">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_form" value="editar">
                    <div class="form-group">
                        <label><i class="fas fa-folder"></i> Grupo</label>
                        <select name="grupo_id" id="edit_grupo_id" required>
                            @foreach($grupos as $g)
                                <option value="{{ $g->id }}">{{ $g->id }} — {{ $g->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-barcode"></i> Código</label>
                            <input type="text" name="codigo" id="edit_codigo" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-ruler"></i> Unidad</label>
                            <input type="text" name="unidad" id="edit_unidad" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-tag"></i> Nombre / Descripción</label>
                        <input type="text" name="nombre" id="edit_nombre" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-hashtag"></i> Cantidad</label>
                            <input type="number" name="cantidad" id="edit_cantidad" min="0" step="any">
                            <p id="edit_cantidad_warning" class="help-text" style="color: #ea580c; display: none; font-weight: 700; margin-top: 6px; font-style: normal; line-height: 1.3;">
                                <i class="fas fa-exclamation-triangle"></i> Solo administradores pueden cambiar la cantidad directamente. Para alterar el stock, registra una Entrada o Salida.
                            </p>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-dollar-sign"></i> Precio (Bs.)</label>
                            <input type="number" name="precio" id="edit_precio" min="0" step="0.01">
                            <p id="edit_precio_warning" class="help-text" style="color: var(--danger); display: none; font-weight: 700; margin-top: 6px; font-style: normal; line-height: 1.3;">
                                <i class="fas fa-exclamation-triangle"></i> Este artículo tiene múltiples lotes activos con diferentes precios. Los precios se gestionan mediante las entradas/lotes.
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-sticky-note"></i> Observaciones / Notas <span style="font-weight:400; opacity:0.6;">(opcional)</span></label>
                        <textarea name="notas" id="edit_notas" rows="2" style="resize:vertical; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 10px; font-size: 13px; background: var(--bg-input); color: var(--text-primary); width: 100%; font-family: inherit;" placeholder="Ej: Pieza con desgaste, revisar antes de usar..."></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" style="flex:1; justify-content:center;">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="cerrarModales()" style="flex:1; justify-content:center;">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL: NUEVO GRUPO --}}
        <div class="modal {{ $errors->any() && old('_form') == 'grupo' ? 'active' : '' }}" id="modalGrupo">
            <div class="modal-content">
                <div class="modal-header">
                    <span><i class="fas fa-folder-plus"></i> Crear Nuevo Grupo</span>
                    <button class="modal-close" onclick="cerrarModales()">&times;</button>
                </div>
                @if($errors->any() && old('_form') == 'grupo')
                    <div class="error-list">
                        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('inventario.grupos.store') }}">
                    @csrf
                    <input type="hidden" name="_form" value="grupo">
                    <div class="form-group">
                        <label><i class="fas fa-id-badge"></i> ID del Grupo</label>
                        <input type="text" name="id" value="{{ old('id') }}" placeholder="G-10" required>
                        <p class="help-text">Formato: G-XX (ej: G-10, G-11...)</p>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-tag"></i> Nombre del Grupo</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Nombre del grupo" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" style="flex:1; justify-content:center;">
                            <i class="fas fa-save"></i> Crear Grupo
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="cerrarModales()" style="flex:1; justify-content:center;">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL: EDITAR GRUPO --}}
        <div class="modal {{ $errors->any() && old('_form') == 'editar_grupo' ? 'active' : '' }}" id="modalEditarGrupo">
            <div class="modal-content">
                <div class="modal-header">
                    <span><i class="fas fa-edit"></i> Editar Nombre del Grupo</span>
                    <button class="modal-close" onclick="cerrarModales()">&times;</button>
                </div>
                @if($errors->any() && old('_form') == 'editar_grupo')
                    <div class="error-list">
                        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                <form method="POST" action="" id="formEditarGrupo">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_form" value="editar_grupo">
                    <div class="form-group">
                        <label><i class="fas fa-id-badge"></i> ID del Grupo</label>
                        <input type="text" id="edit_grupo_id_display" disabled
                               style="background:#f0f0f0; color:#666; cursor:not-allowed;">
                        <p class="help-text">El ID del grupo no se puede cambiar.</p>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-tag"></i> Nombre del Grupo</label>
                        <input type="text" name="nombre" id="edit_grupo_nombre" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" style="flex:1; justify-content:center;">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="cerrarModales()" style="flex:1; justify-content:center;">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL: GESTIONAR GRUPOS --}}
        @if(Auth::user()->esAdmin())
        <div class="modal" id="modalGestionarGrupos">
            <div class="modal-content">
                <div class="modal-header">
                    <span><i class="fas fa-cog"></i> Gestionar Grupos</span>
                    <button class="modal-close" onclick="cerrarModales()">&times;</button>
                </div>
                <p style="color:#666; font-size:13px; margin-bottom:18px;">
                    Edita el nombre o elimina grupos. Solo se pueden eliminar grupos <strong>sin artículos</strong>.
                </p>
                @foreach($grupos as $g)
                    @php $cantidad = $g->articulos()->count(); @endphp
                    <div class="grupo-item">
                        <div class="grupo-item-id">{{ $g->id }}</div>
                        <div class="grupo-item-info">
                            <div class="grupo-item-nombre">{{ $g->nombre }}</div>
                            <div class="grupo-item-count">
                                {{ $cantidad }} artículo{{ $cantidad != 1 ? 's' : '' }}
                            </div>
                        </div>
                        <div class="grupo-item-acciones">
                            <button class="btn-mini-edit"
                                    onclick="abrirModalEditarGrupo('{{ $g->id }}', '{{ addslashes($g->nombre) }}')">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            @if($cantidad == 0)
                                <form method="POST" action="{{ route('grupos.destroy', $g->id) }}"
                                      onsubmit="return confirm('¿Eliminar el grupo {{ $g->id }}? Esta acción no se puede deshacer.')"
                                      style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-mini-delete">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            @else
                                <button class="btn-mini-bloqueado" disabled
                                        title="No se puede eliminar: tiene artículos">
                                    <i class="fas fa-lock"></i> Eliminar
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

    @endif

    @if(Auth::user()->esAdmin())
    {{-- MODAL: ELIMINAR ARTÍCULO --}}
    <div class="modal" id="modalEliminarArticulo">
        <div class="modal-content" style="max-width:520px;">
            <div class="modal-header" style="color:#e03131;">
                <span><i class="fas fa-trash"></i> Eliminar Artículo</span>
                <button class="modal-close" onclick="cerrarModalEliminarArticulo()">&times;</button>
            </div>

            <p style="color:#495057; font-size:14px; margin-bottom:16px;">
                Estás por <strong>ELIMINAR PERMANENTEMENTE</strong> este artículo:
            </p>

            <div style="background:#fff5f5; border-left:4px solid #e03131; padding:14px 16px; border-radius:8px; margin-bottom:16px;">
                <div style="font-weight:700; color:#2d3748; font-size:16px;">
                    <span id="elim_art_codigo">—</span> — <span id="elim_art_nombre">—</span>
                </div>
                <div style="font-size:13px; color:#495057; margin-top:8px;">
                    <i class="fas fa-cubes" style="color:#1971c2;"></i>
                    Stock actual: <strong id="elim_art_stock">0</strong> <span id="elim_art_unidad">—</span>
                </div>
                <div style="font-size:13px; color:#495057; margin-top:4px;">
                    <i class="fas fa-exchange-alt" style="color:#e03131;"></i>
                    Movimientos: <strong id="elim_art_movimientos">0</strong>
                </div>
            </div>

            {{-- Aviso si tiene stock --}}
            <div id="aviso_art_con_stock" style="display:none;">
                <div style="background:#ffe3e3; border-left:4px solid #fa5252; padding:12px 14px; border-radius:8px; font-size:13px; color:#862e2e; line-height:1.6; margin-bottom:16px;">
                    <strong><i class="fas fa-ban"></i> NO SE PUEDE ELIMINAR:</strong>
                    Este artículo tiene <strong><span id="stock_actual_aviso">0</span> <span id="unidad_actual_aviso">—</span></strong> en stock. Para eliminar, primero deja el stock en cero (haz una salida de todo el stock).
                </div>
            </div>

            {{-- Aviso si tiene movimientos --}}
            <div id="aviso_art_con_movimientos" style="display:none;">
                <div style="background:#fff3bf; border-left:4px solid #f59f00; padding:12px 14px; border-radius:8px; font-size:13px; color:#7c2d12; line-height:1.6; margin-bottom:16px;">
                    <strong><i class="fas fa-exclamation-triangle"></i> IMPORTANTE:</strong>
                    Este artículo tiene movimientos registrados. <strong>Descarga el Kardex antes de eliminarlo</strong> para conservar evidencia.
                </div>
                <a href="#" id="btn_descargar_kardex" target="_blank" class="btn" style="background:#1971c2; color:white; width:100%; justify-content:center; margin-bottom:12px;">
                    <i class="fas fa-file-pdf"></i> Descargar Kardex PDF
                </a>
                <label style="display:flex; align-items:center; gap:8px; font-size:13px; color:#495057; margin-bottom:16px;">
                    <input type="checkbox" id="check_kardex_descargado">
                    Confirmo que descargué el Kardex
                </label>
            </div>

            <div style="background:#ffe3e3; border-left:4px solid #c92a2a; padding:12px 14px; border-radius:8px; font-size:12px; color:#862e2e; line-height:1.6; margin-bottom:20px;">
                <i class="fas fa-info-circle"></i>
                Esta acción <strong>no se puede deshacer</strong>.
            </div>

            <form method="POST" action="" id="formEliminarArticulo">
                @csrf
                @method('DELETE')
                <div style="display:flex; gap:10px;">
                    <button type="button" class="btn btn-secondary" onclick="cerrarModalEliminarArticulo()" style="flex:1; justify-content:center;">
                        Cancelar
                    </button>
                    <button type="submit" id="btn_confirmar_eliminar_art" class="btn" style="flex:1; justify-content:center; background:#e03131; color:white;">
                        <i class="fas fa-trash"></i> Sí, eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

@endsection

@push('scripts')
<script>
    function abrirModalNuevo() { document.getElementById('modalNuevo').classList.add('active'); }
    function abrirModalGrupo() { document.getElementById('modalGrupo').classList.add('active'); }
    function abrirModalGestionarGrupos() {
        const m = document.getElementById('modalGestionarGrupos');
        if (m) m.classList.add('active');
    }
    function abrirModalEditarGrupo(grupoId, nombreActual) {
        cerrarModales();
        document.getElementById('formEditarGrupo').action = '/inventario/grupos/' + grupoId;
        document.getElementById('edit_grupo_id_display').value = grupoId;
        document.getElementById('edit_grupo_nombre').value = nombreActual;
        document.getElementById('modalEditarGrupo').classList.add('active');
    }
    function abrirModalEditar(id, codigo, nombre, unidad, grupo_id, cantidad, precio, tieneMultiplesPrecios, notas) {
        document.getElementById('formEditar').action = '/inventario/' + id;
        document.getElementById('edit_codigo').value = codigo;
        document.getElementById('edit_nombre').value = nombre;
        document.getElementById('edit_unidad').value = unidad;
        document.getElementById('edit_grupo_id').value = grupo_id;
        document.getElementById('edit_cantidad').value = cantidad;
        document.getElementById('edit_notas').value = notas || '';

        const inputCantidad = document.getElementById('edit_cantidad');
        const warningCantidad = document.getElementById('edit_cantidad_warning');
        const esAdmin = {{ Auth::user()->esAdmin() ? 'true' : 'false' }};

        if (!esAdmin) {
            inputCantidad.readOnly = true;
            inputCantidad.classList.add('input-readonly');
            inputCantidad.style.background = 'rgba(0, 0, 0, 0.05)';
            inputCantidad.style.color = '#718096';
            inputCantidad.style.cursor = 'not-allowed';
            if (warningCantidad) warningCantidad.style.display = 'block';
        } else {
            inputCantidad.readOnly = false;
            inputCantidad.classList.remove('input-readonly');
            inputCantidad.style.background = '';
            inputCantidad.style.color = '';
            inputCantidad.style.cursor = '';
            if (warningCantidad) warningCantidad.style.display = 'none';
        }

        const inputPrecio = document.getElementById('edit_precio');
        const warningPrecio = document.getElementById('edit_precio_warning');

        if (tieneMultiplesPrecios) {
            inputPrecio.value = '';
            inputPrecio.readOnly = true;
            inputPrecio.classList.add('input-readonly');
            inputPrecio.placeholder = "Múltiples precios activos";
            if (warningPrecio) warningPrecio.style.display = 'block';
        } else {
            inputPrecio.value = precio;
            inputPrecio.readOnly = false;
            inputPrecio.classList.remove('input-readonly');
            inputPrecio.placeholder = "";
            if (warningPrecio) warningPrecio.style.display = 'none';
        }

        document.getElementById('modalEditar').classList.add('active');
    }
    function cerrarModales() {
        document.querySelectorAll('.modal').forEach(m => m.classList.remove('active'));
    }
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) cerrarModales();
        });
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') cerrarModales();
    });

    // Si hay un mensaje de éxito Y estábamos en un formulario, abrir el modal otra vez
    @if(session('success') && old('_form') == 'nuevo')
        document.addEventListener('DOMContentLoaded', function() {
            const formNuevo = document.querySelector('#modalNuevo form');
            if (formNuevo) formNuevo.reset();
            abrirModalNuevo();
        });
    @endif

    @if(session('success'))
        document.addEventListener('DOMContentLoaded', function() {
            mostrarMensajeFlotante('{{ session('success') }}', 'success');
        });
    @endif

    @if(session('error'))
        document.addEventListener('DOMContentLoaded', function() {
            mostrarMensajeFlotante('{{ session('error') }}', 'error');
        });
    @endif

    function mostrarMensajeFlotante(texto, tipo) {
        const div = document.createElement('div');
        div.className = 'mensaje-flotante mensaje-' + tipo;
        div.innerHTML = '<i class="fas fa-' + (tipo === 'success' ? 'check-circle' : 'exclamation-triangle') + '"></i> ' + texto;
        document.body.appendChild(div);

        setTimeout(() => {
            div.style.opacity = '0';
            setTimeout(() => div.remove(), 300);
        }, 3000);
    }

    // Auto-sugerir código según grupo
    const selectGrupoNuevo = document.getElementById('select-grupo-nuevo');
    const inputCodigoNuevo = document.getElementById('input-codigo-nuevo');
    const textoSugerencia = document.getElementById('codigo-sugerencia');
    if (selectGrupoNuevo && inputCodigoNuevo) {
        selectGrupoNuevo.addEventListener('change', async function() {
            const grupoId = this.value;
            if (!grupoId) {
                inputCodigoNuevo.value = '';
                textoSugerencia.innerHTML = '<i class="fas fa-magic"></i> Selecciona un grupo y el código se sugerirá.';
                return;
            }
            textoSugerencia.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Buscando código...';
            try {
                const response = await fetch(`/inventario/siguiente-codigo/${grupoId}`);
                const data = await response.json();
                if (data.codigo) {
                    inputCodigoNuevo.value = data.codigo;
                    textoSugerencia.innerHTML = '<i class="fas fa-check-circle" style="color:#2b8a3e;"></i> Código sugerido: <strong>' + data.codigo + '</strong>';
                } else {
                    textoSugerencia.innerHTML = '<i class="fas fa-exclamation-triangle" style="color:#f59f00;"></i> Escribe un código manualmente.';
                }
            } catch (error) {
                textoSugerencia.innerHTML = '<i class="fas fa-times-circle" style="color:#e03131;"></i> Error. Escribe un código manualmente.';
            }
        });
    }

    function abrirModalEliminarArticulo(id, codigo, nombre, unidad, stock, movimientos) {
        document.getElementById('formEliminarArticulo').action = '/inventario/' + id;
        document.getElementById('elim_art_codigo').textContent = codigo;
        document.getElementById('elim_art_nombre').textContent = nombre;
        document.getElementById('elim_art_unidad').textContent = unidad;
        document.getElementById('elim_art_stock').textContent = parseFloat(stock).toFixed(3);
        document.getElementById('elim_art_movimientos').textContent = movimientos;

        const avisoMov = document.getElementById('aviso_art_con_movimientos');
        const avisoStock = document.getElementById('aviso_art_con_stock');
        const btnConfirmar = document.getElementById('btn_confirmar_eliminar_art');
        const checkKardex = document.getElementById('check_kardex_descargado');
        const btnKardex = document.getElementById('btn_descargar_kardex');

        const tieneStock = parseFloat(stock) > 0;
        const tieneMovimientos = parseInt(movimientos) > 0;

        if (tieneStock) {
            avisoStock.style.display = 'block';
            document.getElementById('stock_actual_aviso').textContent = parseFloat(stock).toFixed(3);
            document.getElementById('unidad_actual_aviso').textContent = unidad;
            avisoMov.style.display = 'none';
            btnConfirmar.disabled = true;
            btnConfirmar.style.opacity = '0.4';
            btnConfirmar.style.cursor = 'not-allowed';
        } else if (tieneMovimientos) {
            avisoStock.style.display = 'none';
            avisoMov.style.display = 'block';
            btnConfirmar.disabled = true;
            btnConfirmar.style.opacity = '0.4';
            btnConfirmar.style.cursor = 'not-allowed';
            checkKardex.checked = false;
            btnKardex.href = '/reportes/kardex/' + id + '/pdf';

            checkKardex.onchange = function() {
                btnConfirmar.disabled = !this.checked;
                btnConfirmar.style.opacity = this.checked ? '1' : '0.4';
                btnConfirmar.style.cursor = this.checked ? 'pointer' : 'not-allowed';
            };
        } else {
            avisoStock.style.display = 'none';
            avisoMov.style.display = 'none';
            btnConfirmar.disabled = false;
            btnConfirmar.style.opacity = '1';
            btnConfirmar.style.cursor = 'pointer';
        }

        document.getElementById('modalEliminarArticulo').classList.add('active');
    }

    function cerrarModalEliminarArticulo() {
        document.getElementById('modalEliminarArticulo').classList.remove('active');
    }

    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.querySelector('input[name="buscar"]');
        const groupSelect = document.querySelector('select[name="grupo"]');
        const stockSelect = document.querySelector('select[name="stock"]');
        const searchForm = document.querySelector('.filters');
        
        let debounceTimer;
        
        function performSearch() {
            if (!searchForm) return;
            const formData = new FormData(searchForm);
            const params = new URLSearchParams(formData);
            const url = searchForm.action + '?' + params.toString();
            
            // Update browser URL
            window.history.replaceState({}, '', url);
            
            const wrapper = document.getElementById('inventario-table-wrapper');
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
                const newWrapper = doc.getElementById('inventario-table-wrapper');
                if (wrapper && newWrapper) {
                    wrapper.innerHTML = newWrapper.innerHTML;
                    wrapper.style.opacity = '1';
                }
                
                // Swap header stats
                ['inventario-count', 'inventario-valor'].forEach(id => {
                    const el = document.getElementById(id);
                    const newEl = doc.getElementById(id);
                    if (el && newEl) el.innerHTML = newEl.innerHTML;
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
        
        if (groupSelect) {
            groupSelect.addEventListener('change', performSearch);
        }
        if (stockSelect) {
            stockSelect.addEventListener('change', performSearch);
        }
    });
</script>
@endpush