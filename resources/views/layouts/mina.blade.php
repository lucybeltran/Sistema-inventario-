<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Sistema') - Inventario Mina Tres Amigos</title>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- Favicon (ícono de la pestaña) --}}
<link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <style>
    /* ============================================
       VARIABLES (Light + Dark mode)
       ============================================ */
    :root {
        --primary: #667eea;
        --primary-dark: #5568d3;
        --primary-light: #f0e9ff;
        --secondary: #764ba2;
        --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

        --bg-body: #f5f7fa;
        --bg-sidebar: #ffffff;
        --bg-header: #ffffff;
        --bg-card: #ffffff;
        --bg-hover: #f7fafc;
        --bg-active: #f0e9ff;
        --bg-input: #f7fafc;

        --text-primary: #2d3748;
        --text-secondary: #4a5568;
        --text-muted: #718096;
        --text-light: #a0aec0;
        --text-on-card: #2d3748;

        --border: #e2e8f0;
        --border-light: #edf2f7;

        --shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
        --shadow: 0 4px 12px rgba(0,0,0,0.08);
        --shadow-lg: 0 10px 30px rgba(0,0,0,0.12);

        --success: #38a169;
        --warning: #f59f00;
        --danger: #e53e3e;
        --info: #3182ce;

        --sidebar-width: 250px;
        --header-height: 65px;
        --radius: 12px;
        --radius-sm: 8px;
    }

    [data-theme="dark"] {
        --primary: #818cf8;
        --primary-dark: #6366f1;
        --primary-light: #312e81;

        --bg-body: #0f172a;
        --bg-sidebar: #1e293b;
        --bg-header: #1e293b;
        --bg-card: #1e293b;
        --bg-hover: #334155;
        --bg-active: #312e81;
        --bg-input: #334155;

        --text-primary: #f1f5f9;
        --text-secondary: #e2e8f0;
        --text-muted: #94a3b8;
        --text-light: #64748b;
        --text-on-card: #f1f5f9;

        --border: #334155;
        --border-light: #475569;

        --shadow-sm: 0 1px 3px rgba(0,0,0,0.3);
        --shadow: 0 4px 12px rgba(0,0,0,0.4);
        --shadow-lg: 0 10px 30px rgba(0,0,0,0.5);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--bg-body);
        color: var(--text-primary);
        min-height: 100vh;
        transition: background 0.3s, color 0.3s;
    /* Badge "X activos" verde - más vibrante */
        & .badge-verde,
        & [class*="activos"],
        & .contador-activos {
            background: rgba(56, 161, 105, 0.25) !important;
            color: #9ae6b4 !important;
        }

        /* Botón filtrar - más vibrante */
        & .btn-filtrar,
        & button[type="submit"].btn-primary {
            background: linear-gradient(135deg, #818cf8 0%, #a78bfa 100%) !important;
            color: white !important;
            opacity: 1 !important;
        }
    }

    /* ============================================
       SIDEBAR
       ============================================ */
    .sidebar {
        position: fixed;
        top: 0; left: 0;
        width: var(--sidebar-width);
        height: 100vh;
        background: var(--bg-sidebar);
        border-right: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        z-index: 100;
        transition: transform 0.3s, background 0.3s, border 0.3s;
    }

    .sidebar-logo {
        padding: 22px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 12px;
    }

.sidebar-logo-img {
    width: 46px;
    height: 46px;
    background: white;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    padding: 4px;
    overflow: hidden;
}

.sidebar-logo-img img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

    .sidebar-logo-text h1 {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
        margin: 0;
    }

    .sidebar-logo-text p {
        font-size: 11px;
        color: var(--text-muted);
        margin: 2px 0 0 0;
    }

    .sidebar-nav {
        flex: 1;
        overflow-y: auto;
        padding: 20px 12px;
    }

    .sidebar-section-title {
        font-size: 10px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1.2px;
        padding: 0 14px;
        margin: 15px 0 8px;
    }

    .sidebar-section-title:first-child { margin-top: 0; }

    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 14px;
        margin-bottom: 4px;
        color: var(--text-secondary);
        text-decoration: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s;
        position: relative;
    }

    .sidebar-link i { width: 20px; font-size: 16px; text-align: center; }

    .sidebar-link:hover {
        background: var(--bg-hover);
        color: var(--primary);
    }

    .sidebar-link.active {
        background: var(--bg-active);
        color: var(--primary);
        font-weight: 600;
    }

    .sidebar-link.active::before {
        content: '';
        position: absolute;
        left: 0; top: 50%;
        transform: translateY(-50%);
        width: 3px; height: 24px;
        background: var(--primary);
        border-radius: 0 3px 3px 0;
    }

    .sidebar-footer {
        padding: 16px;
        border-top: 1px solid var(--border);
    }

    .sidebar-user {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        border-radius: 10px;
        transition: background 0.2s;
        text-decoration: none;
    }

    .sidebar-user:hover { background: var(--bg-hover); }

    .sidebar-user-avatar {
        width: 38px; height: 38px;
        background: var(--gradient);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
        flex-shrink: 0;
    }

    .sidebar-user-info .name {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
    }

    .sidebar-user-info .role {
        font-size: 11px;
        color: var(--text-muted);
        text-transform: uppercase;
        font-weight: 500;
    }

    /* ============================================
       HEADER SUPERIOR
       ============================================ */
    .header-top {
        position: fixed;
        top: 0;
        left: var(--sidebar-width);
        right: 0;
        height: var(--header-height);
        background: var(--bg-header);
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 28px;
        z-index: 99;
        transition: background 0.3s, border 0.3s;
    }

    .header-title h2 {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .header-title .breadcrumb-mina {
        font-size: 12px;
        color: var(--text-muted);
        margin: 2px 0 0 0;
    }

    .menu-toggle {
        display: none;
        background: transparent;
        border: none;
        color: var(--text-primary);
        font-size: 20px;
        cursor: pointer;
        padding: 8px;
        border-radius: 8px;
    }

    .menu-toggle:hover { background: var(--bg-hover); }

    .header-title { display: flex; align-items: center; gap: 14px; }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .header-time {
        font-size: 12px;
        color: var(--text-secondary);
        padding: 8px 14px;
        background: var(--bg-hover);
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
    }

    .theme-toggle {
        background: var(--bg-hover);
        border: 1px solid var(--border);
        color: var(--text-secondary);
        width: 40px; height: 40px;
        border-radius: 10px;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .theme-toggle:hover {
        background: var(--primary);
        color: white;
        transform: rotate(180deg);
    }

    .header-logout {
        background: transparent;
        color: var(--danger);
        border: 1px solid var(--border);
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        font-family: inherit;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .header-logout:hover {
        background: var(--danger);
        color: white;
        border-color: var(--danger);
    }

    /* ============================================
       CONTENIDO PRINCIPAL
       ============================================ */
    .main-content {
        margin-left: var(--sidebar-width);
        margin-top: var(--header-height);
        padding: 28px;
        min-height: calc(100vh - var(--header-height));
        transition: margin 0.3s;
    }

    /* ============================================
       FLASH MESSAGES
       ============================================ */
    .flash-message {
        padding: 14px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
        font-weight: 500;
        animation: slideDown 0.4s;
    }

    .flash-success { background: #d3f9d8; color: #22543d; border-left: 4px solid var(--success); }
    .flash-error { background: #fed7d7; color: #742a2a; border-left: 4px solid var(--danger); }

    [data-theme="dark"] .flash-success {
        background: rgba(56, 161, 105, 0.15);
        color: #9ae6b4;
    }

    [data-theme="dark"] .flash-error {
        background: rgba(229, 62, 62, 0.15);
        color: #feb2b2;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ============================================
       RESPONSIVE
       ============================================ */
    @media (max-width: 1024px) {
        .sidebar { transform: translateX(-100%); box-shadow: var(--shadow-lg); }
        .sidebar.open { transform: translateX(0); }
        .header-top { left: 0; }
        .main-content { margin-left: 0; }
        .menu-toggle { display: flex; }
        .header-time { display: none; }
    }

    @media (max-width: 600px) {
        .header-top { padding: 0 14px; }
        .header-title h2 { font-size: 15px; }
        .main-content { padding: 16px; }
    }

    .sidebar-overlay {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 99;
    }

    .sidebar-overlay.active { display: block; }

    /* ============================================
       💎 OVERRIDES PARA MODO OSCURO
       (Estos arreglan los colores hardcoded de las
        vistas internas para que respeten el dark mode)
       ============================================ */

    [data-theme="dark"] {

        /* Backgrounds blancos → oscuros */
        & .table-container,
        & .panel,
        & .stat-card,
        & .stat-mini,
        & .stat-kardex,
        & .mes-card,
        & .report-card,
        & .selector-card,
        & .filters,
        & .filters-grid,
        & .modal-content,
        & .articulo-card,
        & .tabs-container,
        & .tab-content {
            background: var(--bg-card) !important;
            color: var(--text-primary) !important;
        }

        /* Filtros con fondo claro → oscuro */
        & .filters {
            background: var(--bg-card) !important;
            border: 1px solid var(--border);
        }

        /* Tablas */
        & table { background: var(--bg-card); color: var(--text-primary); }

        & table th {
            background: var(--bg-hover) !important;
            color: var(--text-primary) !important;
            border-color: var(--border) !important;
            background-image: none !important;
        }

        & table td {
            color: var(--text-secondary) !important;
            border-color: var(--border) !important;
        }

        & table tbody tr {
            background: var(--bg-card);
        }

        & table tbody tr:hover {
            background: var(--bg-hover) !important;
        }

        /* Inputs y selects */
        & input[type="text"],
        & input[type="email"],
        & input[type="password"],
        & input[type="number"],
        & input[type="date"],
        & input[type="search"],
        & input[type="tel"],
        & select,
        & textarea {
            background: var(--bg-input) !important;
            color: var(--text-primary) !important;
            border-color: var(--border) !important;
        }

        & input::placeholder,
        & textarea::placeholder {
            color: var(--text-muted) !important;
        }

        & input:focus,
        & select:focus,
        & textarea:focus {
            border-color: var(--primary) !important;
            background: var(--bg-card) !important;
        }

        /* Page titles morados → claros */
        & .page-title {
            color: var(--primary) !important;
        }

        /* Counter y badge activos: mantener su gradiente en dark */
        & .page-counter {
            background: linear-gradient(135deg, #818cf8 0%, #a78bfa 100%) !important;
            color: white !important;
        }

        & .badge-activos {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%) !important;
            color: white !important;
        }

        /* Encabezados de tabla con gradiente gris */
        & table th[style*="gradient"] {
            background: var(--bg-hover) !important;
            background-image: none !important;
        }

        /* Textos secundarios */
        & p, & span, & label, & h1, & h2, & h3, & h4, & h5, & h6 {
            color: var(--text-primary);
        }

        /* Labels en filtros */
        & .filters label,
        & .form-field label,
        & .filters-grid label {
            color: var(--text-secondary) !important;
        }

        /* Códigos monospace (G-1/0001) */
        & .codigo,
        & .codigo-mini {
            color: var(--primary) !important;
        }

        /* Stats cards - mejorar contraste */
        & .stat-card .stat-numero,
        & .stat-mini .num,
        & .stat-kardex .num {
            color: var(--text-primary) !important;
        }

        & .stat-card .stat-label,
        & .stat-mini .label {
            color: var(--text-muted) !important;
        }

        /* Tarjetas de bienvenida y articulo (que ya son gradiente) - quedan iguales */

        /* Help text amarillo */
        & .help-text {
            background: rgba(245, 159, 0, 0.15) !important;
            color: #fbd38d !important;
        }

        /* Preview boxes */
        & .preview-box {
            background: var(--bg-hover) !important;
            color: var(--text-primary) !important;
        }

        & .preview-box strong { color: var(--primary) !important; }

        /* Empty state */
        & .empty,
        & .empty p { color: var(--text-muted) !important; }

        /* Grupos tags */
        & .grupo-tag,
        & .unidad-badge,
        & .cargo-tag {
            background: var(--bg-hover) !important;
            color: var(--text-primary) !important;
        }

        /* Badges - mantener sus colores pero ajustar contraste */
        & .badge-success,
        & .estado-activo {
            background: rgba(56, 161, 105, 0.2) !important;
            color: #9ae6b4 !important;
        }

        & .badge-danger,
        & .estado-inactivo {
            background: rgba(229, 62, 62, 0.2) !important;
            color: #feb2b2 !important;
        }

        & .stock-ok {
            background: rgba(56, 161, 105, 0.2) !important;
            color: #9ae6b4 !important;
        }

        & .stock-bajo {
            background: rgba(245, 159, 0, 0.2) !important;
            color: #fbd38d !important;
        }

        & .stock-cero {
            background: rgba(229, 62, 62, 0.2) !important;
            color: #feb2b2 !important;
        }

        /* Trabajador tag */
        & .trabajador-tag,
        & .trabajador-mini {
            background: rgba(245, 159, 0, 0.2) !important;
            color: #fbd38d !important;
        }

        /* Página headers de fondo */
        & .page-header,
        & .filters {
            background: transparent;
        }

        /* Tabs */
        & .tabs-nav {
            background: var(--bg-hover) !important;
            background-image: none !important;
            border-bottom-color: var(--border) !important;
        }

        & .tab-btn {
            color: var(--text-secondary) !important;
        }

        & .tab-btn:hover {
            color: var(--primary) !important;
            background: rgba(102, 126, 234, 0.1) !important;
        }

        & .tab-btn.active {
            color: var(--primary) !important;
            background: var(--bg-card) !important;
            border-bottom-color: var(--primary) !important;
        }

        /* Modales con bordes */
        & .modal { background: rgba(0, 0, 0, 0.7) !important; }
        & .modal-content { box-shadow: var(--shadow-lg) !important; }
        & .modal-close { color: var(--text-muted) !important; }
        & .modal-header { color: var(--primary) !important; }

        /* Error list */
        & .error-list {
            background: rgba(229, 62, 62, 0.15) !important;
            color: #feb2b2 !important;
        }

        /* Notas en gris */
        & [style*="color:#666"],
        & [style*="color:#999"],
        & [style*="color:#bbb"],
        & [style*="color:#a0aec0"] {
            color: var(--text-muted) !important;
        }

        /* Subtítulos */
        & .page-subtitle,
        & .card-subtitle,
        & .tab-description {
            color: var(--text-muted) !important;
        }

        /* Links morados */
        & a[style*="color:#667eea"] {
            color: var(--primary) !important;
        }

        /* Botones secundarios */
        & .btn-secondary {
            background: var(--bg-hover) !important;
            color: var(--text-primary) !important;
        }

        & .btn-secondary:hover {
            background: var(--border) !important;
        }

        /* Top articulo (ranking) */
        & .top-articulo {
            background: var(--bg-hover) !important;
        }

        & .top-articulo:hover {
            background: var(--bg-active) !important;
        }

        & .top-articulo-nombre {
            color: var(--text-primary) !important;
        }

        & .top-articulo-count {
            background: var(--bg-card) !important;
            color: var(--primary) !important;
        }

        /* Resumen unidades table */
        & .resumen-unidades th {
            background: var(--bg-hover) !important;
            color: var(--text-secondary) !important;
        }

        /* Stock crítico mini-tabla */
        & .mini-table th {
            background: var(--bg-hover) !important;
            color: var(--text-secondary) !important;
        }

        /* Acciones rapidas botones */
        & .accion-btn {
            background: var(--bg-hover) !important;
            color: var(--text-primary) !important;
            border-color: var(--border) !important;
            background-image: none !important;
        }

        & .accion-btn:hover {
            background: var(--gradient) !important;
            color: white !important;
        }

        & .accion-btn i {
            color: var(--primary) !important;
        }

        & .accion-btn:hover i {
            color: white !important;
        }
        /* ============================================
           ✨ ARREGLOS FINALES DE MODO OSCURO
           ============================================ */

        /* 🔴 KARDEX: El badge del SALDO se ve blanco gigante */
        & .num-saldo {
            background: rgba(102, 126, 234, 0.2) !important;
            color: #c7d2fe !important;
            padding: 6px 14px !important;
        }

        /* 🔴 Badges entrada/salida con mejor contraste */
        & .badge-entrada,
        & .badge-tipo.badge-entrada {
            background: rgba(56, 161, 105, 0.25) !important;
            color: #9ae6b4 !important;
        }

        & .badge-salida,
        & .badge-tipo.badge-salida {
            background: rgba(229, 62, 62, 0.25) !important;
            color: #feb2b2 !important;
        }

        /* 🟣 STAT CARDS del Dashboard - darles gradientes coloridos */
        & .stat-card.morado {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.25) 0%, rgba(118, 75, 162, 0.15) 100%) !important;
            border-left-color: #818cf8 !important;
        }

        & .stat-card.verde {
            background: linear-gradient(135deg, rgba(56, 161, 105, 0.25) 0%, rgba(56, 161, 105, 0.08) 100%) !important;
            border-left-color: #68d391 !important;
        }

        & .stat-card.rojo {
            background: linear-gradient(135deg, rgba(229, 62, 62, 0.25) 0%, rgba(229, 62, 62, 0.08) 100%) !important;
            border-left-color: #fc8181 !important;
        }

        & .stat-card.naranja {
            background: linear-gradient(135deg, rgba(245, 159, 0, 0.25) 0%, rgba(245, 159, 0, 0.08) 100%) !important;
            border-left-color: #fbd38d !important;
        }

        & .stat-card .stat-icon {
            opacity: 0.4 !important;
        }

        & .stat-card.morado .stat-icon { color: #818cf8 !important; }
        & .stat-card.verde .stat-icon { color: #68d391 !important; }
        & .stat-card.rojo .stat-icon { color: #fc8181 !important; }
        & .stat-card.naranja .stat-icon { color: #fbd38d !important; }

        /* Stats mini de Reportes */
        & .stat-mini.morado { border-left-color: #818cf8 !important; }
        & .stat-mini.verde { border-left-color: #68d391 !important; }
        & .stat-mini.rojo { border-left-color: #fc8181 !important; }
        & .stat-mini.naranja { border-left-color: #fbd38d !important; }

        /* 🟣 Cards con borde izquierdo morado en modo claro */
        & .report-card { border-left-color: #818cf8 !important; }
        & .report-card.naranja { border-left-color: #fbd38d !important; }
        & .report-card.azul { border-left-color: #63b3ed !important; }
        & .report-card.verde { border-left-color: #68d391 !important; }

        & .report-card-title i { color: #818cf8 !important; }
        & .report-card.naranja .report-card-title i { color: #fbd38d !important; }
        & .report-card.azul .report-card-title i { color: #63b3ed !important; }
        & .report-card.verde .report-card-title i { color: #68d391 !important; }

        /* Tarjeta articulo del Kardex - gradiente más sutil */
        & .articulo-card {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.3) 0%, rgba(118, 75, 162, 0.2) 100%) !important;
            border: 1px solid rgba(102, 126, 234, 0.3);
        }

        /* Stat kardex bordes ajustados */
        & .stat-kardex.entradas { border-top-color: #68d391 !important; }
        & .stat-kardex.salidas { border-top-color: #fc8181 !important; }
        & .stat-kardex.saldo { border-top-color: #818cf8 !important; }
        & .stat-kardex.valor { border-top-color: #fbd38d !important; }

        & .stat-kardex.entradas .icono { color: #68d391 !important; }
        & .stat-kardex.salidas .icono { color: #fc8181 !important; }
        & .stat-kardex.saldo .icono { color: #818cf8 !important; }
        & .stat-kardex.valor .icono { color: #fbd38d !important; }

        /* Colores de números entrada/salida */
        & .num-entrada,
        & [style*="color:#2b8a3e"],
        & [style*="color:#38a169"] {
            color: #68d391 !important;
        }

        & .num-salida,
        & [style*="color:#862e2e"],
        & [style*="color:#e53e3e"] {
            color: #fc8181 !important;
        }

        /* Botón "Ver historial" naranja */
        & .btn-historial {
            background: linear-gradient(135deg, #f59f00 0%, #f76707 100%) !important;
        }

        /* Botón filtrar azul (en filtros) */
        & .btn-primary {
            background: linear-gradient(135deg, #818cf8 0%, #a78bfa 100%) !important;
        }

        /* Botón success */
        & .btn-success {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%) !important;
        }

        /* Resumen del Kardex (resumen items en PDF/web) */
        & .resumen,
        & .resumen-item {
            background: var(--bg-hover) !important;
            color: var(--text-primary) !important;
        }

        /* Mes cards en Reportes */
        & .mes-card {
            background: var(--bg-card) !important;
            border-left-color: #818cf8 !important;
        }

        & .mes-header h4 { color: #c7d2fe !important; }

        /* Banderas verde/naranja del reportes (alertas) */
        & .alerta-rojo {
            background: rgba(229, 62, 62, 0.15) !important;
            color: #feb2b2 !important;
        }

        & .alerta-naranja {
            background: rgba(245, 159, 0, 0.15) !important;
            color: #fbd38d !important;
        }

        & .alerta-verde {
            background: rgba(56, 161, 105, 0.15) !important;
            color: #9ae6b4 !important;
        }

        & .alerta-azul {
            background: rgba(49, 130, 206, 0.15) !important;
            color: #90cdf4 !important;
        }

        /* Bienvenida del Dashboard - mantener visible */
        & .bienvenida {
            background: linear-gradient(135deg, #4f46e5 0%, #6d28d9 100%) !important;
        }

        /* Empty state */
        & .empty {
            background: transparent !important;
        }

        /* Panel del dashboard */
        & .panel {
            background: var(--bg-card) !important;
            color: var(--text-primary) !important;
        }

        & .panel-header { border-bottom-color: var(--border) !important; }

        /* Top articulos rankings */
        & .top-articulo:nth-child(1) .ranking {
            background: linear-gradient(135deg, #fbbf24, #f59e0b) !important;
        }
        & .top-articulo:nth-child(2) .ranking {
            background: linear-gradient(135deg, #cbd5e1, #94a3b8) !important;
        }
        & .top-articulo:nth-child(3) .ranking {
            background: linear-gradient(135deg, #d97706, #92400e) !important;
        }

        /* Barras del gráfico */
        & .barra-entrada {
            background: linear-gradient(180deg, #38a169 0%, #2f855a 100%) !important;
        }

        & .barra-salida {
            background: linear-gradient(180deg, #e53e3e 0%, #c53030 100%) !important;
        }

        /* Acciones rápidas */
        & .accion-btn {
            background: var(--bg-hover) !important;
            background-image: none !important;
        }

        /* Help text con ícono amarillo */
        & .help-text i {
            color: #fbd38d !important;
        }

        /* Tabla resumen unidades */
        & .resumen-unidades th {
            background: var(--bg-hover) !important;
            color: var(--text-secondary) !important;
        }

        & .resumen-unidades td {
            color: var(--text-secondary) !important;
            border-color: var(--border) !important;
        }

        /* Stock crítico - bordes */
        & .panel[style*="border-left"] {
            border-left-color: #818cf8 !important;
        }

        /* Stock-num colores en modo oscuro */
        & .stock-num.ok { color: #68d391 !important; }
        & .stock-num.bajo { color: #fbd38d !important; }
        & .stock-num.cero { color: #fc8181 !important; }

        /* Tips amarillos */
        & .tips {
            background: rgba(245, 159, 0, 0.1) !important;
            color: #fbd38d !important;
        }

        /* Card de perfil */
        & .card {
            background: var(--bg-card) !important;
            color: var(--text-primary) !important;
        }

        & .card-title { color: var(--text-primary) !important; }
        & .card-subtitle { color: var(--text-muted) !important; }

        /* Indicador de fuerza de password */
        & .strength-bar {
            background: var(--bg-hover) !important;
        }

        /* N° Nota badge - visible en modo oscuro */
        & .nota-badge {
            background: rgba(102, 126, 234, 0.25) !important;
            color: #c7d2fe !important;
        }

    }
</style>

    @stack('styles')
</head>
<body>

    {{-- ====== SIDEBAR ====== --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
    <div class="sidebar-logo-img">
        <img src="{{ asset('img/logo.png') }}" alt="Logo Empresa Minera Torrez">
    </div>
    <div class="sidebar-logo-text">
        <h1>Mina Tres Amigos</h1>
        <p>Santa Catalina</p>
    </div>
</div>

        <nav class="sidebar-nav">
            <div class="sidebar-section-title">Principal</div>

            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>

            <div class="sidebar-section-title">Gestión</div>

            <a href="{{ route('inventario.index') }}" class="sidebar-link {{ request()->routeIs('inventario.*') ? 'active' : '' }}">
                <i class="fas fa-boxes-stacked"></i>
                <span>Inventario</span>
            </a>

            <a href="{{ route('movimientos.index') }}" class="sidebar-link {{ request()->routeIs('movimientos.*') ? 'active' : '' }}">
                <i class="fas fa-exchange-alt"></i>
                <span>Movimientos</span>
            </a>

            <a href="{{ route('trabajadores.index') }}" class="sidebar-link {{ request()->routeIs('trabajadores.*') ? 'active' : '' }}">
                <i class="fas fa-hard-hat"></i>
                <span>Trabajadores</span>
            </a>

            <a href="{{ route('galeria.index') }}" class="sidebar-link {{ request()->routeIs('galeria.*') ? 'active' : '' }}">
                <i class="fas fa-images"></i>
                <span>Galería</span>
            </a>

            @if(Auth::user()->puedeReportes())
                <div class="sidebar-section-title">Reportes</div>

                <a href="{{ route('reportes.index') }}" class="sidebar-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                    <i class="fas fa-file-export"></i>
                    <span>Reportes</span>
                </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('profile.edit') }}" class="sidebar-user" title="Mi Perfil">
                <div class="sidebar-user-avatar">
                    <i class="fas fa-{{ Auth::user()->iconoRol() }}"></i>
                </div>
                <div class="sidebar-user-info">
                    <div class="name">{{ Auth::user()->name }}</div>
                    <div class="role">{{ Auth::user()->nombreRol() }}</div>
                </div>
            </a>
        </div>
    </aside>

    {{-- Overlay para móvil --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- ====== HEADER SUPERIOR ====== --}}
    <header class="header-top">
        <div class="header-title">
            <button class="menu-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div>
                <h2>@yield('titulo', 'Sistema')</h2>
                <p class="breadcrumb-mina">
                    <i class="fas fa-clock"></i>
                    <span id="header-fecha">{{ now()->translatedFormat('l, d \d\e F Y') }}</span>
                </p>
            </div>
        </div>

        <div class="header-actions">
            <div class="header-time">
                <i class="fas fa-clock"></i>
                <span id="header-hora">{{ now()->format('H:i') }}</span>
            </div>

            <button class="theme-toggle" onclick="toggleTheme()" title="Cambiar tema">
                <i class="fas fa-moon" id="theme-icon"></i>
            </button>

            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="header-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    Salir
                </button>
            </form>
        </div>
    </header>

    {{-- ====== CONTENIDO ====== --}}
    <main class="main-content">

        {{-- Mensajes flash --}}
        @if (session('success'))
            <div class="flash-message flash-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="flash-message flash-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        @yield('contenido')
    </main>

    <script>
        // ============================================
        // MODO OSCURO
        // ============================================
        function toggleTheme() {
            const html = document.documentElement;
            const icon = document.getElementById('theme-icon');
            const current = html.getAttribute('data-theme');
            const newTheme = current === 'light' ? 'dark' : 'light';

            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            if (newTheme === 'dark') {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            }
        }

        // Cargar tema guardado al iniciar
        (function() {
            const saved = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', saved);
            const icon = document.getElementById('theme-icon');
            if (icon && saved === 'dark') {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            }
        })();

        // ============================================
        // SIDEBAR MÓVIL
        // ============================================
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        }

        // ============================================
        // RELOJ EN VIVO
        // ============================================
        function actualizarReloj() {
            const ahora = new Date();
            const horas = String(ahora.getHours()).padStart(2, '0');
            const minutos = String(ahora.getMinutes()).padStart(2, '0');
            const reloj = document.getElementById('header-hora');
            if (reloj) reloj.textContent = `${horas}:${minutos}`;
        }
        setInterval(actualizarReloj, 30000);
    </script>

    @stack('scripts')
</body>
</html>