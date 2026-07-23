<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Sistema') - Sección Catalina - Empresa Minera Torrez S.R.L.</title>

    {{-- Google Fonts: Outfit & Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- Favicon (ícono de la pestaña) --}}
<link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <style>
    /* ============================================
       VARIABLES (Light + Dark mode)
       ============================================ */
    :root {
        --primary: #d97706;
        --primary-dark: #b45309;
        --primary-light: #fef3c7;
        --secondary: #eab308;
        --gradient: linear-gradient(135deg, #b45309 0%, #f59e0b 60%, #fbbf24 100%);
        --gradient-subtle: linear-gradient(135deg, rgba(217,119,6,0.12) 0%, rgba(245,158,11,0.05) 100%);

        --bg-body: #f5f0ea;
        --bg-image: linear-gradient(rgba(248, 244, 238, 0.78), rgba(248, 244, 238, 0.78)), url('/img/background_mine.png');
        --bg-sidebar: rgba(255, 253, 250, 0.92);
        --bg-header: rgba(255, 253, 250, 0.90);
        --bg-card: rgba(255, 253, 250, 0.80);
        --bg-hover: rgba(217, 119, 6, 0.07);
        --bg-active: rgba(254, 243, 199, 0.9);
        --bg-input: rgba(255, 255, 255, 0.92);

        --text-primary: #18140f;
        --text-secondary: #3d3328;
        --text-muted: #7a6a5a;
        --text-light: #b09b88;
        --text-on-card: #18140f;

        --border: rgba(180, 83, 9, 0.14);
        --border-light: rgba(180, 83, 9, 0.08);
        --border-strong: rgba(180, 83, 9, 0.25);

        --shadow-sm: 0 1px 4px rgba(120, 60, 0, 0.06), 0 1px 2px rgba(0,0,0,0.04);
        --shadow: 0 4px 20px rgba(120, 60, 0, 0.09), 0 1px 6px rgba(0,0,0,0.05);
        --shadow-lg: 0 12px 40px rgba(120, 60, 0, 0.14), 0 4px 12px rgba(0,0,0,0.08);
        --shadow-xl: 0 24px 60px rgba(120, 60, 0, 0.18), 0 8px 24px rgba(0,0,0,0.10);

        --success: #059669;
        --warning: #f59e0b;
        --danger: #e53e3e;
        --info: #3182ce;

        --sidebar-width: 258px;
        --header-height: 68px;
        --radius: 16px;
        --radius-sm: 12px;
        --radius-xs: 8px;
    }

    [data-theme="dark"] {
        --primary: #fbbf24;
        --primary-dark: #f59e0b;
        --primary-light: #2d1a00;
        --gradient: linear-gradient(135deg, #92400e 0%, #d97706 60%, #fbbf24 100%);
        --gradient-subtle: linear-gradient(135deg, rgba(251,191,36,0.12) 0%, rgba(245,158,11,0.04) 100%);

        --bg-body: #0e0c0a;
        --bg-image: linear-gradient(rgba(14, 12, 10, 0.85), rgba(14, 12, 10, 0.85)), url('/img/background_mine.png');
        --bg-sidebar: rgba(20, 17, 14, 0.95);
        --bg-header: rgba(20, 17, 14, 0.92);
        --bg-card: rgba(26, 22, 18, 0.88);
        --bg-hover: rgba(251, 191, 36, 0.08);
        --bg-active: rgba(42, 28, 4, 0.95);
        --bg-input: rgba(15, 13, 10, 0.90);

        --text-primary: #fdf8f2;
        --text-secondary: #e8dfd4;
        --text-muted: #9e8c7c;
        --text-light: #6e5e4e;
        --text-on-card: #fdf8f2;

        --border: rgba(251, 191, 36, 0.16);
        --border-light: rgba(251, 191, 36, 0.09);
        --border-strong: rgba(251, 191, 36, 0.28);

        --icon-neutral: #9e8c7c;
        --icon-accent: #fbbf24;

        --shadow-sm: 0 1px 4px rgba(0,0,0,0.6), 0 1px 2px rgba(0,0,0,0.4);
        --shadow: 0 4px 20px rgba(0,0,0,0.7), 0 2px 8px rgba(0,0,0,0.5);
        --shadow-lg: 0 12px 40px rgba(0,0,0,0.85), 0 4px 16px rgba(0,0,0,0.65);
        --shadow-xl: 0 24px 64px rgba(0,0,0,0.95), 0 8px 28px rgba(0,0,0,0.75);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    /* Premium scrollbar */
    ::-webkit-scrollbar { width: 7px; height: 7px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: var(--border-strong); border-radius: 8px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--primary); }

    ::selection { background: rgba(217,119,6,0.2); color: var(--primary); }

    a { color: inherit; }
    *:focus-visible {
        outline: 2px solid var(--primary);
        outline-offset: 2px;
        border-radius: 6px;
    }

    body {
        font-family: 'Outfit', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--bg-body);
        background-image: var(--bg-image);
        background-attachment: fixed;
        background-size: cover;
        background-position: center;
        color: var(--text-primary);
        min-height: 100vh;
        transition: background 0.3s, color 0.3s;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        font-feature-settings: "kern" 1, "liga" 1, "calt" 1;
        text-rendering: optimizeLegibility;
    /* Badge "X activos" verde - adaptativo y de alta legibilidad */
        & .badge-verde,
        & [class*="activos"],
        & .contador-activos {
            background: #d1fae5 !important;
            color: #065f46 !important;
            font-weight: 700 !important;
        }

    }

    [data-theme="dark"] .badge-verde,
    [data-theme="dark"] [class*="activos"],
    [data-theme="dark"] .contador-activos {
        background: rgba(16, 185, 129, 0.15) !important;
        color: #34d399 !important;
    }

    /* Page-level entrance animation */
    @keyframes pageEnter {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .main-content > * {
        animation: pageEnter 0.45s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    /* ============================================
       GLASSMORPHISM GLOBAL STYLES
       ============================================ */
    .table-container, .card, .filters-row, .filters, .modal-content, .empty, .rotacion-tabs {
        background: var(--bg-card) !important;
        backdrop-filter: blur(22px) saturate(160%) !important;
        -webkit-backdrop-filter: blur(22px) saturate(160%) !important;
        border: 1.5px solid var(--border) !important;
        box-shadow: var(--shadow) !important;
        border-radius: var(--radius) !important;
        transition: all 0.32s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Premium hover lift on glass cards */
    .card:hover, .filters-row:hover, .filters:hover, .table-container:hover {
        box-shadow: var(--shadow-lg) !important;
        border-color: var(--border-strong) !important;
        transform: translateY(-4px);
    }

    /* ═══════════════════════════════════════════════
       SISTEMA PREMIUM GLOBAL — Todas las vistas
       Normaliza colores hardcodeados al sistema
       ═══════════════════════════════════════════════ */

    /* ── Page Header ── */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 14px;
    }

    .page-title {
        font-size: 26px !important;
        font-weight: 800 !important;
        letter-spacing: -0.03em !important;
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        background: var(--gradient) !important;
        -webkit-background-clip: text !important;
        -webkit-text-fill-color: transparent !important;
        background-clip: text !important;
        color: transparent !important;
    }

    .page-counter {
        background: var(--gradient-subtle) !important;
        color: var(--primary) !important;
        border: 1.5px solid var(--border) !important;
        padding: 5px 14px !important;
        border-radius: 20px !important;
        font-size: 12.5px !important;
        font-weight: 700 !important;
        -webkit-text-fill-color: var(--primary) !important;
        letter-spacing: 0.2px !important;
    }

    .valor-total {
        background: rgba(5,150,105,0.1) !important;
        color: var(--success) !important;
        border: 1.5px solid rgba(5,150,105,0.2) !important;
        padding: 5px 14px !important;
        border-radius: 20px !important;
        font-size: 12.5px !important;
        font-weight: 700 !important;
        -webkit-text-fill-color: var(--success) !important;
    }

    .badge-activos {
        background: rgba(5,150,105,0.1) !important;
        color: var(--success) !important;
        border: 1.5px solid rgba(5,150,105,0.2) !important;
        padding: 5px 14px !important;
        border-radius: 20px !important;
        font-size: 12.5px !important;
        font-weight: 700 !important;
        -webkit-text-fill-color: var(--success) !important;
        box-shadow: none !important;
    }

    /* ── Filters / Search bar ── */
    .filters {
        background: var(--bg-card) !important;
        backdrop-filter: blur(20px) saturate(160%) !important;
        -webkit-backdrop-filter: blur(20px) saturate(160%) !important;
        border: 1.5px solid var(--border) !important;
        border-radius: var(--radius) !important;
        padding: 20px 22px !important;
        margin-bottom: 20px;
        box-shadow: var(--shadow-sm) !important;
        transition: all 0.3s ease;
    }

    .filters:hover {
        border-color: var(--border-strong) !important;
        box-shadow: var(--shadow) !important;
        transform: none !important;
    }

    .filters label {
        font-size: 11.5px !important;
        font-weight: 700 !important;
        color: var(--text-muted) !important;
        text-transform: uppercase !important;
        letter-spacing: 0.8px !important;
    }

    .filters .form-field { display: flex; flex-direction: column; gap: 6px; }

    .filters input,
    .filters select,
    .filters textarea {
        background: var(--bg-input) !important;
        color: var(--text-primary) !important;
        border: 1.5px solid var(--border) !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
        font-size: 14px !important;
        font-family: inherit !important;
        transition: all 0.2s ease !important;
    }

    .filters input:focus,
    .filters select:focus {
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 3px rgba(217,119,6,0.15) !important;
        outline: none !important;
        background: var(--bg-card) !important;
    }

    .filters input::placeholder {
        color: var(--text-muted) !important;
        opacity: 0.7 !important;
    }

    /* ── Buttons ── */
    .btn {
        padding: 10px 18px !important;
        border: none !important;
        border-radius: 10px !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        transition: all 0.25s cubic-bezier(0.16,1,0.3,1) !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 7px !important;
        text-decoration: none !important;
        font-family: inherit !important;
        letter-spacing: -0.01em !important;
    }

    .btn-primary {
        background: var(--gradient) !important;
        color: white !important;
        box-shadow: 0 4px 14px rgba(180,83,9,0.3) !important;
        -webkit-text-fill-color: white !important;
    }
    .btn-primary:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 22px rgba(180,83,9,0.42) !important;
        filter: brightness(1.06) !important;
    }

    .btn-success {
        background: linear-gradient(135deg, #065f46 0%, #059669 55%, #10b981 100%) !important;
        color: white !important;
        box-shadow: 0 4px 14px rgba(6,95,70,0.28) !important;
        -webkit-text-fill-color: white !important;
    }
    .btn-success:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 22px rgba(6,95,70,0.4) !important;
        filter: brightness(1.06) !important;
    }

    .btn-secondary {
        background: var(--bg-input) !important;
        color: var(--text-secondary) !important;
        border: 1.5px solid var(--border) !important;
        -webkit-text-fill-color: var(--text-secondary) !important;
    }
    .btn-secondary:hover {
        background: var(--bg-hover) !important;
        border-color: var(--primary) !important;
        color: var(--primary) !important;
        -webkit-text-fill-color: var(--primary) !important;
        transform: translateY(-1px) !important;
    }

    .btn-grupos {
        background: var(--gradient) !important;
        color: white !important;
        -webkit-text-fill-color: white !important;
        box-shadow: 0 4px 14px rgba(180,83,9,0.28) !important;
    }
    .btn-grupos:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 22px rgba(180,83,9,0.4) !important;
    }

    /* ── Table Container ── */
    .table-container {
        border-radius: var(--radius) !important;
        overflow-x: auto !important;
        background: var(--bg-card) !important;
        border: 1.5px solid var(--border) !important;
        box-shadow: var(--shadow) !important;
    }

    /* ── Tables ── */
    table { width: 100%; border-collapse: collapse; }

    table thead tr {
        background: var(--bg-hover) !important;
    }

    table th {
        background: transparent !important;
        background-image: none !important;
        padding: 13px 16px !important;
        text-align: left !important;
        font-weight: 700 !important;
        color: var(--text-muted) !important;
        border-bottom: 1.5px solid var(--border) !important;
        font-size: 11.5px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.8px !important;
        white-space: nowrap !important;
    }

    table td {
        padding: 13px 16px !important;
        border-bottom: 1px solid var(--border-light) !important;
        color: var(--text-secondary) !important;
        font-size: 14px !important;
        vertical-align: middle !important;
    }

    table tbody tr { transition: background 0.18s ease; }
    table tbody tr:hover { background: var(--bg-hover) !important; }
    table tbody tr:last-child td { border-bottom: none !important; }

    /* ── Badges global ── */
    .badge-success, .estado-activo {
        background: rgba(5,150,105,0.12) !important;
        color: #059669 !important;
        border: 1px solid rgba(5,150,105,0.2) !important;
        -webkit-text-fill-color: #059669 !important;
    }

    .badge-danger, .estado-inactivo {
        background: rgba(229,62,62,0.1) !important;
        color: var(--danger) !important;
        border: 1px solid rgba(229,62,62,0.18) !important;
        -webkit-text-fill-color: var(--danger) !important;
    }

    .codigo, .ci-badge {
        font-family: 'Outfit', monospace !important;
        font-weight: 700 !important;
        color: var(--primary) !important;
        -webkit-text-fill-color: var(--primary) !important;
        background: rgba(217,119,6,0.08) !important;
        padding: 2px 8px !important;
        border-radius: 6px !important;
    }

    .cargo-tag, .grupo-tag {
        background: var(--bg-hover) !important;
        color: var(--text-secondary) !important;
        border: 1px solid var(--border) !important;
        -webkit-text-fill-color: var(--text-secondary) !important;
        padding: 3px 10px !important;
        border-radius: 8px !important;
        font-size: 11.5px !important;
        font-weight: 600 !important;
    }

    .stock-ok   { background: rgba(5,150,105,0.1) !important;  color: #059669 !important; -webkit-text-fill-color: #059669 !important; }
    .stock-medio { background: rgba(245,158,11,0.1) !important; color: #d97706 !important; -webkit-text-fill-color: #d97706 !important; }
    .stock-bajo { background: rgba(217,119,6,0.12) !important;  color: #b45309 !important; -webkit-text-fill-color: #b45309 !important; }
    .stock-cero { background: rgba(229,62,62,0.1) !important;   color: var(--danger) !important; -webkit-text-fill-color: var(--danger) !important; }

    .precio {
        font-weight: 700 !important;
        color: #059669 !important;
        -webkit-text-fill-color: #059669 !important;
        font-family: 'Outfit', monospace !important;
    }

    /* ── Modals ── */
    .modal {
        display: none;
        position: fixed; top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.55);
        backdrop-filter: blur(8px);
        z-index: 1000;
        align-items: center; justify-content: center;
    }
    .modal.active { display: flex; animation: modalFadeIn 0.25s ease; }
    @keyframes modalFadeIn { from { opacity: 0; } to { opacity: 1; } }

    .modal-content {
        background: var(--bg-card) !important;
        backdrop-filter: blur(28px) saturate(180%) !important;
        -webkit-backdrop-filter: blur(28px) saturate(180%) !important;
        border: 1.5px solid var(--border) !important;
        border-radius: 20px !important;
        padding: 32px !important;
        max-width: 600px; width: 90%;
        max-height: 90vh; overflow-y: auto;
        box-shadow: var(--shadow-xl) !important;
        animation: modalSlideIn 0.3s cubic-bezier(0.16,1,0.3,1) !important;
        color: var(--text-primary) !important;
    }
    @keyframes modalSlideIn {
        from { opacity: 0; transform: translateY(-24px) scale(0.98); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .modal-header {
        font-size: 20px !important;
        font-weight: 800 !important;
        margin-bottom: 22px !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        background: var(--gradient) !important;
        -webkit-background-clip: text !important;
        -webkit-text-fill-color: transparent !important;
        letter-spacing: -0.02em !important;
        padding-bottom: 16px !important;
        border-bottom: 1px solid var(--border-light) !important;
    }

    .modal-close {
        background: var(--bg-hover) !important;
        border: 1px solid var(--border) !important;
        border-radius: 8px !important;
        width: 34px; height: 34px !important;
        font-size: 18px !important;
        cursor: pointer !important;
        color: var(--text-muted) !important;
        display: flex !important; align-items: center !important; justify-content: center !important;
        transition: all 0.2s ease !important;
        -webkit-text-fill-color: var(--text-muted) !important;
        flex-shrink: 0 !important;
    }
    .modal-close:hover {
        background: rgba(229,62,62,0.1) !important;
        color: var(--danger) !important;
        border-color: rgba(229,62,62,0.2) !important;
        -webkit-text-fill-color: var(--danger) !important;
    }

    /* ── Form groups inside modals ── */
    .form-group { margin-bottom: 18px; }

    .form-group label {
        display: flex !important;
        align-items: center !important;
        margin-bottom: 8px !important;
        font-weight: 700 !important;
        color: var(--text-primary) !important;
        font-size: 14px !important;
        letter-spacing: -0.01em !important;
        gap: 7px !important;
    }

    .form-group label i {
        color: var(--primary) !important;
        font-size: 15px !important;
        opacity: 0.85 !important;
        width: 18px !important;
        text-align: center !important;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100% !important;
        padding: 12px 14px !important;
        border: 1.5px solid var(--border) !important;
        border-radius: 11px !important;
        font-size: 14.5px !important;
        font-family: inherit !important;
        background: var(--bg-input) !important;
        color: var(--text-primary) !important;
        transition: all 0.2s ease !important;
    }

    .form-group input::placeholder {
        color: var(--text-muted) !important;
        opacity: 0.65 !important;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none !important;
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 3px rgba(217,119,6,0.18) !important;
        background: var(--bg-card) !important;
    }

    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; align-items: start; }
    .modal-footer { display: flex; gap: 10px; margin-top: 24px; }

    /* ── Error list ── */
    .error-list {
        background: rgba(229,62,62,0.08) !important;
        border-left: 4px solid var(--danger) !important;
        padding: 12px 16px !important;
        border-radius: 10px !important;
        margin-bottom: 18px !important;
        color: var(--danger) !important;
        font-size: 13px !important;
    }

    /* ── Empty state ── */
    .empty {
        text-align: center !important;
        padding: 60px 20px !important;
        color: var(--text-muted) !important;
    }
    .empty i {
        font-size: 52px !important;
        opacity: 0.25 !important;
        margin-bottom: 18px !important;
        display: block !important;
    }
    .empty p { font-size: 15px; font-weight: 500; }

    /* ── Grupo items ── */
    .grupo-item {
        background: var(--bg-input) !important;
        border: 1.5px solid var(--border) !important;
        border-radius: 12px !important;
        padding: 14px 16px !important;
        margin-bottom: 8px !important;
        transition: all 0.22s ease !important;
    }
    .grupo-item:hover {
        border-color: var(--primary) !important;
        background: var(--bg-hover) !important;
        transform: translateX(3px) !important;
    }
    .grupo-item-id {
        background: var(--gradient) !important;
        color: white !important;
        -webkit-text-fill-color: white !important;
        padding: 6px 12px !important;
        border-radius: 8px !important;
        font-family: 'Outfit', monospace !important;
        font-weight: 700 !important;
        font-size: 13px !important;
        box-shadow: 0 3px 10px rgba(180,83,9,0.25) !important;
    }
    .grupo-item-nombre { font-weight: 700; color: var(--text-primary); font-size: 14px; }
    .grupo-item-count { font-size: 12px; color: var(--text-muted); margin-top: 2px; }

    /* ── Separador de grupo en tabla ── */
    .grupo-separador-row td {
        background: var(--gradient) !important;
        color: white !important;
        -webkit-text-fill-color: white !important;
        font-weight: 700 !important;
        font-size: 13px !important;
        padding: 10px 16px !important;
        letter-spacing: 0.5px !important;
    }

    /* ── Toast notification ── */
    .mensaje-flotante {
        position: fixed !important;
        top: 82px !important;
        right: 22px !important;
        padding: 14px 22px !important;
        border-radius: 14px !important;
        font-weight: 700 !important;
        font-size: 14px !important;
        color: white !important;
        -webkit-text-fill-color: white !important;
        z-index: 9999 !important;
        box-shadow: 0 12px 32px rgba(0,0,0,0.22) !important;
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
        animation: toastIn 0.35s cubic-bezier(0.16,1,0.3,1) !important;
        backdrop-filter: blur(12px) !important;
    }
    @keyframes toastIn {
        from { transform: translateX(110%); opacity: 0; }
        to   { transform: translateX(0); opacity: 1; }
    }
    .mensaje-success { background: linear-gradient(135deg, #065f46, #10b981) !important; }
    .mensaje-error   { background: linear-gradient(135deg, #9b1c1c, #ef4444) !important; }

    /* ── Action buttons in table rows ── */
    .btn-action {
        width: 32px !important;
        height: 32px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        border: none !important;
        border-radius: 9px !important;
        cursor: pointer !important;
        color: white !important;
        -webkit-text-fill-color: white !important;
        font-size: 13px !important;
        transition: all 0.22s ease !important;
        text-decoration: none !important;
        padding: 0 !important;
    }
    .btn-action:hover {
        transform: translateY(-2.5px) scale(1.06) !important;
        box-shadow: 0 5px 14px rgba(0,0,0,0.22) !important;
    }
    .btn-action-kardex { background: linear-gradient(135deg, #1d4ed8, #3b82f6) !important; }
    .btn-action-edit   { background: linear-gradient(135deg, #b45309, #f59e0b) !important; }
    .btn-action-delete { background: linear-gradient(135deg, #9b1c1c, #ef4444) !important; }

    /* ── Trabajador tag (movimientos) ── */
    .trabajador-tag {
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
        background: rgba(217,119,6,0.1) !important;
        color: var(--primary) !important;
        -webkit-text-fill-color: var(--primary) !important;
        padding: 4px 10px !important;
        border-radius: 8px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        border: 1px solid rgba(217,119,6,0.18) !important;
    }

    /* ── Field working / worker highlight ── */
    .field-trabajador {
        background: rgba(217,119,6,0.06) !important;
        border: 1.5px solid rgba(217,119,6,0.2) !important;
        border-radius: 12px !important;
        padding: 14px 16px !important;
        margin-bottom: 18px !important;
    }
    .field-trabajador label { color: var(--primary) !important; -webkit-text-fill-color: var(--primary) !important; }

    /* ── Alert warning ── */
    .alert-warning {
        background: rgba(217,119,6,0.08) !important;
        color: var(--primary) !important;
        -webkit-text-fill-color: var(--primary) !important;
        border: 1px solid rgba(217,119,6,0.2) !important;
        padding: 10px 14px !important;
        border-radius: 10px !important;
        font-size: 12.5px !important;
        margin-top: 8px !important;
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
    }

    /* ── Help text ── */
    .help-text { font-size: 12px !important; color: var(--text-muted) !important; margin-top: 5px !important; font-style: italic; }

    /* ── Nota badge ── */
    .nota-badge {
        font-family: 'Outfit', monospace !important;
        font-weight: 700 !important;
        background: rgba(50,130,200,0.1) !important;
        color: #3182ce !important;
        -webkit-text-fill-color: #3182ce !important;
        padding: 4px 10px !important;
        border-radius: 6px !important;
        font-size: 12px !important;
        border: 1px solid rgba(50,130,200,0.18) !important;
    }

    /* ── Numero nota display ── */
    .numero-nota-display {
        background: rgba(50,130,200,0.08) !important;
        border: 2px dashed rgba(50,130,200,0.3) !important;
        border-radius: 10px !important;
        padding: 12px 16px !important;
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
        font-family: 'Outfit', monospace !important;
        font-weight: 700 !important;
        font-size: 18px !important;
        color: #3182ce !important;
        -webkit-text-fill-color: #3182ce !important;
    }

    @media (max-width: 768px) {
        .filters { grid-template-columns: 1fr !important; }
        .form-row { grid-template-columns: 1fr !important; }
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
        backdrop-filter: blur(28px) saturate(180%);
        -webkit-backdrop-filter: blur(28px) saturate(180%);
        border-right: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        z-index: 100;
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), background 0.3s, border 0.3s;
        box-shadow: 4px 0 24px rgba(0,0,0,0.04);
    }

    .sidebar-logo {
        padding: 20px 20px 18px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .sidebar-logo-img {
        width: 44px;
        height: 44px;
        background: var(--gradient);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 14px rgba(180, 83, 9, 0.35);
        padding: 4px;
        overflow: hidden;
        position: relative;
    }

    .sidebar-logo-img::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.25) 0%, transparent 60%);
        border-radius: inherit;
    }

    .sidebar-logo-img img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        position: relative;
        z-index: 1;
    }

    .sidebar-logo-text h1 {
        font-size: 15.5px;
        font-weight: 800;
        background: var(--gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        line-height: 1.2;
        margin: 0;
        letter-spacing: -0.02em;
    }

    .sidebar-logo-text p {
        font-size: 11px;
        color: var(--text-muted);
        margin: 3px 0 0 0;
        letter-spacing: 0.02em;
    }

    .sidebar-nav {
        flex: 1;
        overflow-y: auto;
        padding: 18px 10px;
        scrollbar-width: thin;
        scrollbar-color: var(--border) transparent;
    }

    .sidebar-nav::-webkit-scrollbar { width: 4px; }
    .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
    .sidebar-nav::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

    .sidebar-section-title {
        font-size: 10px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        padding: 0 12px;
        margin: 18px 0 6px;
        opacity: 0.7;
    }

    .sidebar-section-title:first-child { margin-top: 4px; }

    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 11px 13px;
        margin-bottom: 3px;
        color: var(--text-secondary);
        text-decoration: none;
        border-radius: var(--radius-sm);
        font-size: 14px;
        font-weight: 500;
        letter-spacing: -0.01em;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        border: 1px solid transparent;
    }

    .sidebar-link i { 
        width: 20px;
        font-size: 15px;
        text-align: center;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        opacity: 0.7;
    }

    .sidebar-link:hover {
        background: var(--bg-hover);
        color: var(--primary);
        transform: translateX(3px);
        border-color: var(--border-light);
    }

    .sidebar-link:hover i {
        opacity: 1;
        transform: scale(1.12);
    }

    .sidebar-link.active {
        background: var(--gradient-subtle) !important;
        border: 1px solid var(--border-strong) !important;
        color: var(--primary) !important;
        font-weight: 700 !important;
        box-shadow: var(--shadow-sm) !important;
    }

    .sidebar-link.active i {
        opacity: 1 !important;
        color: var(--primary);
    }

    .sidebar-link.active::before {
        content: '';
        position: absolute;
        left: 0; top: 50%;
        transform: translateY(-50%);
        width: 3px; height: 22px;
        background: var(--gradient);
        border-radius: 0 3px 3px 0;
    }

    .sidebar-footer {
        padding: 14px 12px 16px;
        border-top: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .sidebar-user {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 12px;
        border-radius: var(--radius-sm);
        transition: all 0.25s ease;
        text-decoration: none;
        border: 1px solid transparent;
    }

    .sidebar-user:hover {
        background: var(--bg-hover);
        border-color: var(--border-light);
    }

    .sidebar-user-avatar {
        width: 36px; height: 36px;
        background: var(--gradient);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 13px;
        flex-shrink: 0;
        box-shadow: 0 3px 10px rgba(180, 83, 9, 0.3);
    }

    .sidebar-user-info .name {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.01em;
    }

    .sidebar-user-info .role {
        font-size: 10.5px;
        color: var(--text-muted);
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.8px;
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
        backdrop-filter: blur(28px) saturate(180%);
        -webkit-backdrop-filter: blur(28px) saturate(180%);
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 30px;
        z-index: 99;
        transition: background 0.3s, border 0.3s;
        box-shadow: 0 1px 0 var(--border-light), var(--shadow-sm);
    }

    .header-title h2 {
        font-size: 21px;
        font-weight: 800;
        background: var(--gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
        letter-spacing: -0.03em;
    }

    .header-title .breadcrumb-mina {
        font-size: 11.5px;
        color: var(--text-muted);
        margin: 3px 0 0 0;
        display: flex;
        align-items: center;
        gap: 5px;
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
        font-size: 14px;
        color: var(--text-primary);
        padding: 8px 16px;
        background: var(--bg-card);
        border: 1.5px solid var(--border);
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
        font-family: 'Outfit', 'Inter', monospace;
        box-shadow: var(--shadow-sm);
        letter-spacing: 0.8px;
        transition: all 0.3s ease;
    }

    .header-time i {
        color: var(--primary);
        animation: pulseTimeIcon 2s infinite alternate ease-in-out;
    }

    @keyframes pulseTimeIcon {
        0% { transform: scale(1); opacity: 0.8; }
        100% { transform: scale(1.18); opacity: 1; }
    }

    /* Colon animado parpadeante */
    .time-colon {
        animation: blinkColon 1s infinite steps(1, start);
    }

    @keyframes blinkColon {
        0%, 100% { opacity: 1; }
        50% { opacity: 0; }
    }

    .theme-toggle {
        background: var(--bg-card);
        border: 1.5px solid var(--border);
        color: var(--text-secondary);
        width: 42px; height: 42px;
        border-radius: 12px;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: var(--shadow-sm);
    }

    .theme-toggle:hover {
        background: var(--gradient);
        color: white;
        border-color: var(--primary);
        transform: rotate(20deg) scale(1.08);
        box-shadow: 0 6px 18px rgba(180, 83, 9, 0.28);
    }

    .header-logout {
        background: transparent;
        color: var(--danger);
        border: 1.5px solid var(--border);
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: var(--shadow-sm);
    }

    .header-logout i {
        transition: transform 0.3s ease;
    }

    .header-logout:hover {
        background: var(--danger);
        color: white;
        border-color: var(--danger);
        transform: translateY(-1.5px) scale(1.02);
        box-shadow: 0 6px 15px rgba(229, 62, 62, 0.25);
    }

    .header-logout:hover i {
        transform: translateX(2px) scale(1.15);
    }

    .sidebar-logout {
        width: 100%;
        background: rgba(229, 62, 62, 0.05);
        color: var(--danger);
        border: 1.5px solid rgba(229, 62, 62, 0.18);
        padding: 11px 16px;
        border-radius: var(--radius-sm);
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        font-family: inherit;
        transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        letter-spacing: -0.01em;
    }

    .sidebar-logout i {
        transition: all 0.28s ease;
        font-size: 13px;
    }

    .sidebar-logout:hover {
        background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
        color: white;
        border-color: transparent;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(229, 62, 62, 0.3);
    }

    .sidebar-logout:hover i {
        transform: translateX(3px);
    }

    /* ============================================
       CONTENIDO PRINCIPAL
       ============================================ */
    .main-content {
        margin-left: var(--sidebar-width);
        margin-top: var(--header-height);
        padding: 28px 30px;
        min-height: calc(100vh - var(--header-height));
        transition: margin 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* ============================================
       FLASH MESSAGES
       ============================================ */
    .flash-message {
        padding: 15px 22px;
        border-radius: var(--radius-sm);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14.5px;
        font-weight: 600;
        animation: slideDown 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: var(--shadow-sm);
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

        /* =========================================
           🌙 REDUCIR NARANJA — Íconos y labels
           Solo activos/botones mantienen el naranja.
           Labels, panel-heads, stat icons → neutro.
           ========================================= */

        /* Íconos dentro de labels de formularios → slate */
        & .rpt-field label i,
        & .form-field label i,
        & .filters-grid label i,
        & .filters label i {
            color: var(--icon-neutral) !important;
        }

        /* Panel heads → slate en vez de naranja */
        & .rpt-panel-head i,
        & .panel-head i {
            color: var(--icon-neutral) !important;
        }

        /* Icono del page header (cuadrado gradiente naranja) → versión más suave */
        & .rpt-header-left h2 i {
            background: linear-gradient(135deg, #252d3d 0%, #30363d 100%) !important;
            color: var(--primary) !important;
            box-shadow: none !important;
        }

        /* Stat card icons → fondo muy suave sin color vivido */
        & .rpt-stat-icon.naranja {
            background: rgba(226,155,58,0.08) !important;
            color: #c47f25 !important;
        }
        & .rpt-stat-icon.verde {
            background: rgba(22,163,74,0.08) !important;
            color: #3d9e65 !important;
        }
        & .rpt-stat-icon.rojo {
            background: rgba(220,38,38,0.08) !important;
            color: #c05252 !important;
        }
        & .rpt-stat-icon.azul {
            background: rgba(37,99,235,0.08) !important;
            color: #4a90d9 !important;
        }

        /* Sidebar section titles → más discretos */
        & .sidebar-section-title {
            color: var(--icon-neutral) !important;
        }

        /* Stat mini bordes → menos vivos */
        & .stat-mini.morado { border-left-color: #4a4e8a !important; }
        & .stat-mini.verde  { border-left-color: #2d6b4a !important; }
        & .rpt-pstat.entradas { border-left-color: #2d6b4a !important; }
        & .rpt-pstat.salidas  { border-left-color: #7a3030 !important; }

        /* Shortcuts bar — más discreta */
        & .rpt-shortcuts {
            color: var(--text-light) !important;
            border-color: var(--border) !important;
        }
        & .rpt-shortcuts kbd {
            background: var(--bg-hover) !important;
            border-color: var(--border) !important;
            color: var(--text-muted) !important;
        }

        /* Tab shortcut badge no-activo → slate */
        & .rpt-tab-btn:not(.active) .shortcut {
            background: rgba(110,118,129,0.15) !important;
            color: var(--text-muted) !important;
        }

        /* Help box — fondo más discreto */
        & .rpt-help {
            background: rgba(110,118,129,0.1) !important;
            border-color: rgba(110,118,129,0.2) !important;
            color: var(--text-muted) !important;
        }
        & .rpt-help i { color: var(--text-muted) !important; }

        /* Preview bar — fondo muy suave */
        & .rpt-preview-bar {
            background: rgba(226,155,58,0.07) !important;
            border-color: rgba(226,155,58,0.15) !important;
            color: #b8934a !important;
        }
        & .rpt-preview-bar strong { color: #c9a05a !important; }
        & .rpt-preview-bar i { color: #b8934a !important; }

        /* Sidebar active link → más suave */
        & .sidebar-link.active {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.16) 0%, rgba(245, 158, 11, 0.04) 100%) !important;
            border: 1.5px solid rgba(245, 158, 11, 0.25) !important;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.1) !important;
        }

        /* Header icon activo de sidebar → suave */
        & .sidebar-link.active::before {
            background: linear-gradient(180deg, #fbbf24 0%, #d97706 100%) !important;
        }

        /* Badge live → más suave */
        & .badge-live {
            background: rgba(35,134,54,0.15) !important;
            color: #3fb950 !important;
        }

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
            -webkit-text-fill-color: white !important;
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

        /* =============================================
           🌙 DARK MODE — NUEVAS CLASES (Reportes v3)
           ============================================= */

        /* Panels y wrappers nuevos */
        & .rpt-tabs-wrapper,
        & .rpt-panel,
        & .rpt-action-panel,
        & .rpt-table-wrap,
        & .mes-card,
        & .rpt-stat,
        & .rpt-pstat,
        & .stat-card,
        & .table-wrap,
        & .filters-bar,
        & .worker-card-wrapper {
            background: var(--bg-card) !important;
            border-color: var(--border) !important;
            color: var(--text-primary) !important;
        }

        /* Nav de tabs */
        & .rpt-tabs-nav {
            background: var(--bg-hover) !important;
            border-color: var(--border) !important;
        }

        & .rpt-tab-btn {
            color: var(--text-muted) !important;
        }
        & .rpt-tab-btn:hover {
            color: var(--primary) !important;
            background: rgba(245,158,11,0.1) !important;
        }
        & .rpt-tab-btn.active {
            color: var(--primary) !important;
            background: var(--bg-card) !important;
            border-bottom-color: var(--primary) !important;
        }
        & .rpt-tab-btn .shortcut {
            background: rgba(245,158,11,0.15) !important;
            color: var(--primary) !important;
        }
        & .rpt-tab-btn.active .shortcut {
            background: var(--primary) !important;
            color: white !important;
        }

        /* Contenido de tabs */
        & .rpt-tab-content {
            background: transparent !important;
            color: var(--text-primary) !important;
        }

        /* Títulos de sección */
        & .rpt-section-title { color: var(--text-primary) !important; }
        & .rpt-section-title i { color: var(--primary) !important; }
        & .rpt-section-desc { color: var(--text-muted) !important; }

        /* Panel heads */
        & .rpt-panel-head { color: var(--text-secondary) !important; }
        & .rpt-panel-head i { color: var(--primary) !important; }

        /* Campos de formulario en reportes */
        & .rpt-field label { color: var(--text-secondary) !important; }
        & .rpt-field label i { color: var(--primary) !important; }
        & .rpt-field input,
        & .rpt-field select {
            background: var(--bg-input) !important;
            color: var(--text-primary) !important;
            border-color: var(--border) !important;
        }
        & .rpt-field input:focus,
        & .rpt-field select:focus {
            border-color: var(--primary) !important;
            background: var(--bg-card) !important;
        }

        /* Formulario de trabajador */
        & .form-field label { color: var(--text-secondary) !important; }
        & .form-field input,
        & .form-field select {
            background: var(--bg-input) !important;
            color: var(--text-primary) !important;
            border-color: var(--border) !important;
        }

        /* Preview bar amarilla */
        & .rpt-preview-bar {
            background: rgba(245,158,11,0.12) !important;
            border-color: rgba(245,158,11,0.25) !important;
            color: #fcd34d !important;
        }
        & .rpt-preview-bar strong { color: #fbbf24 !important; }
        & .rpt-preview-bar i { color: #f59e0b !important; }

        /* Info box azul */
        & .rpt-info {
            background: rgba(59,130,246,0.12) !important;
            border-color: rgba(59,130,246,0.3) !important;
            color: #93c5fd !important;
        }
        & .rpt-info i { color: #60a5fa !important; }

        /* Help box amarillo */
        & .rpt-help {
            background: rgba(245,158,11,0.12) !important;
            border-color: rgba(245,158,11,0.3) !important;
            color: #fcd34d !important;
        }
        & .rpt-help i { color: #f59e0b !important; }

        /* Checkbox estilizado */
        & .rpt-checkbox-label {
            background: var(--bg-input) !important;
            border-color: var(--border) !important;
            color: var(--text-secondary) !important;
        }
        & .rpt-checkbox-label:hover {
            border-color: var(--primary) !important;
            color: var(--primary) !important;
            background: rgba(245,158,11,0.1) !important;
        }

        /* Preview section */
        & .rpt-preview-section { border-color: var(--border) !important; }
        & .rpt-preview-header h4 { color: var(--text-primary) !important; }
        & .badge-live {
            background: rgba(22,163,74,0.2) !important;
            color: #4ade80 !important;
        }

        /* Stats preview cards */
        & .rpt-pstat {
            background: var(--bg-card) !important;
            border-color: var(--border) !important;
        }
        & .rpt-pstat .ps-label { color: var(--text-muted) !important; }
        & .rpt-pstat .ps-val { color: var(--text-primary) !important; }
        & .rpt-pstat.entradas { border-left-color: #059669 !important; }
        & .rpt-pstat.salidas  { border-left-color: #e53e3e !important; }

        /* Tabla de reportes */
        & .rpt-table thead th {
            background: var(--bg-hover) !important;
            color: var(--text-secondary) !important;
            border-color: var(--border) !important;
        }
        & .rpt-table tbody td {
            color: var(--text-secondary) !important;
            border-color: var(--border) !important;
        }
        & .rpt-table tbody tr:hover { background: var(--bg-hover) !important; }
        & .rpt-table tfoot td {
            background: var(--bg-hover) !important;
            color: var(--text-primary) !important;
            border-color: var(--border) !important;
        }

        /* Stats rapidas (reportes) */
        & .rpt-stat {
            background: var(--bg-card) !important;
            border-color: var(--border) !important;
        }
        & .rpt-stat-body .label { color: var(--text-muted) !important; }
        & .rpt-stat-body .val   { color: var(--text-primary) !important; }
        & .rpt-stat-icon.naranja { background: rgba(234,88,12,0.15) !important; }
        & .rpt-stat-icon.verde   { background: rgba(22,163,74,0.15) !important; }
        & .rpt-stat-icon.rojo    { background: rgba(220,38,38,0.15) !important; }
        & .rpt-stat-icon.azul    { background: rgba(37,99,235,0.15) !important; }

        /* Mes cards (mensual) */
        & .mes-card {
            background: var(--bg-card) !important;
            border-color: var(--border) !important;
        }
        & .mes-card-header {
            background: var(--bg-hover) !important;
            border-color: var(--border) !important;
        }
        & .mes-card-header h4 { color: var(--text-primary) !important; }
        & .mes-table th {
            color: var(--text-muted) !important;
            border-color: var(--border) !important;
        }
        & .mes-table td {
            color: var(--text-secondary) !important;
            border-color: var(--border) !important;
        }
        & .unidad-pill {
            background: var(--bg-hover) !important;
            border-color: var(--border) !important;
            color: var(--text-secondary) !important;
        }

        /* Stats card de trabajador */
        & .stat-card {
            background: var(--bg-card) !important;
            border-color: var(--border) !important;
        }
        & .stat-card .sc-label { color: var(--text-muted) !important; }
        & .stat-card .sc-sub   { color: var(--text-muted) !important; }

        /* Filters bar trabajador */
        & .filters-bar {
            background: var(--bg-card) !important;
            border-color: var(--border) !important;
        }

        /* Tabla data-table trabajador */
        & .table-wrap {
            background: var(--bg-card) !important;
            border-color: var(--border) !important;
        }
        & .data-table thead th {
            background: var(--bg-hover) !important;
            color: var(--text-secondary) !important;
            border-color: var(--border) !important;
        }
        & .data-table tbody td {
            color: var(--text-secondary) !important;
            border-color: var(--border) !important;
        }
        & .data-table tbody tr:hover { background: var(--bg-hover) !important; }
        & .data-table tfoot td {
            background: var(--bg-hover) !important;
            border-color: var(--border) !important;
            color: var(--text-primary) !important;
        }

        /* Breadcrumb */
        & .breadcrumb { color: var(--text-muted) !important; }
        & .breadcrumb span { color: var(--text-muted) !important; }

        /* Shortcuts bar */
        & .rpt-shortcuts { color: var(--text-light) !important; }
        & .rpt-shortcuts kbd {
            background: var(--bg-hover) !important;
            border-color: var(--border) !important;
            color: var(--text-secondary) !important;
        }

        /* Botones btn-rpt secundario */
        & .btn-rpt-secondary {
            border-color: var(--border) !important;
            color: var(--text-secondary) !important;
            background: transparent !important;
        }
        & .btn-rpt-secondary:hover {
            border-color: var(--primary) !important;
            color: var(--primary) !important;
            background: rgba(245,158,11,0.1) !important;
        }

        /* Btn filter secundario trabajador */
        & .btn-filter-secondary {
            border-color: var(--border) !important;
            color: var(--text-secondary) !important;
        }

        /* Colores hardcodeados claros en dark */
        & [style*="color:#2d3748"]   { color: var(--text-primary) !important; }
        & [style*="color:#4a5568"]   { color: var(--text-secondary) !important; }
        & [style*="color:#718096"]   { color: var(--text-muted) !important; }
        & [style*="color:#555"]      { color: var(--text-secondary) !important; }
        & [style*="color:#666"]      { color: var(--text-muted) !important; }
        & [style*="color:#333"]      { color: var(--text-primary) !important; }
        & [style*="color:#1e293b"]   { color: var(--text-primary) !important; }
        & [style*="color:#475569"]   { color: var(--text-secondary) !important; }
        & [style*="color:#64748b"]   { color: var(--text-muted) !important; }
        & [style*="color:#334155"]   { color: var(--text-secondary) !important; }

        /* Fondos blancos hardcodeados */
        & [style*="background:white"],
        & [style*="background: white"],
        & [style*="background:#ffffff"],
        & [style*="background: #ffffff"],
        & [style*="background:#fff"],
        & [style*="background: #fff"] {
            background: var(--bg-card) !important;
        }

        /* Fondos claros hardcodeados */
        & [style*="background:#f8f9fa"],
        & [style*="background: #f8f9fa"],
        & [style*="background:#f7fafc"],
        & [style*="background: #f7fafc"],
        & [style*="background:#f1f5f9"],
        & [style*="background: #f1f5f9"] {
            background: var(--bg-hover) !important;
        }

        /* Bordes claros */
        & [style*="border-color:#e2e8f0"],
        & [style*="border: 1px solid #e2e8f0"],
        & [style*="border:1px solid #e2e8f0"],
        & [style*="border-bottom: 1px solid #f0f0f0"],
        & [style*="border-bottom:1px solid #f0f0f0"] {
            border-color: var(--border) !important;
        }

        /* Kardex selector card */
        & .selector-card {
            background: var(--bg-card) !important;
            color: var(--text-primary) !important;
        }
        & .selector-card h2 { color: var(--primary) !important; }
        & .selector-card p  { color: var(--text-muted) !important; }

        /* Kardex articulo card (gradiente) — mantener ok */
        & .articulo-card { /* se mantiene con gradiente */ }

        /* Kardex tabla */
        & .table-kardex-container {
            background: var(--bg-card) !important;
        }
        & .table-kardex td {
            border-color: var(--border) !important;
            color: var(--text-secondary) !important;
        }
        & .table-kardex tbody tr:hover { background: var(--bg-hover) !important; }

        /* Valoracion detallada del stock */
        & [style*="background: white; border: 1px solid #e2e8f0"],
        & [style*="background:white;border:1px solid"] {
            background: var(--bg-card) !important;
            border-color: var(--border) !important;
        }

        /* Bordes top de stat-kardex en pantalla kardex.blade */
        & .stat-kardex {
            background: var(--bg-card) !important;
        }
        & .stat-kardex .label { color: var(--text-muted) !important; }
        & .stat-kardex .num   { color: var(--text-primary) !important; }
        & .stat-kardex .subtitulo { color: var(--text-light) !important; }

        /* Historial precio en kdx */
        & [style*="color: #1e293b"],
        & [style*="color:#1e293b"] { color: var(--text-primary) !important; }
        & [style*="color: #475569"],
        & [style*="color:#475569"] { color: var(--text-secondary) !important; }
        & [style*="color: #64748b"],
        & [style*="color:#64748b"] { color: var(--text-muted) !important; }

        /* Fila de totales en tablas */
        & [style*="background:#f8fafc"],
        & [style*="background: #f8fafc"] {
            background: var(--bg-hover) !important;
            color: var(--text-primary) !important;
        }

        /* Input type date placeholder en dark */
        & input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1) opacity(0.6);
        }

        /* Select option en dark (Chrome) */
        & select option {
            background: var(--bg-card);
            color: var(--text-primary);
        }

    } /* fin [data-theme="dark"] */
</style>

    @stack('styles')

    <style>
        /* ==========================================================================
           💎 SYSTEM-WIDE PREMIUM BUTTON STYLE & ANIMATIONS OVERRIDES (Light & Dark)
           ========================================================================== */
        
        /* Universal transition, font weight, border-radius, and base styles for buttons */
        .btn,
        .btn-primary,
        .btn-secondary,
        .btn-success,
        .btn-danger,
        .btn-warning,
        .btn-grupos,
        .btn-edit,
        .btn-eliminar-trab,
        .btn-historial,
        .btn-upload-image,
        .btn-delete,
        .btn-cancel,
        .btn-submit,
        .btn-mes,
        .btn-mes-excel,
        .btn-mes-pdf,
        .btn-toggle-on,
        .btn-toggle-off,
        .btn-mini-edit,
        .btn-mini-delete,
        .accion-btn,
        button[type="submit"]:not(.btn-pass-toggle):not(.btn-ver-pass-inline):not(.menu-toggle):not(.theme-toggle):not(.header-logout):not(.btn-mover-pill) {
            position: relative !important;
            overflow: hidden !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            font-weight: 700 !important;
            font-size: 13.5px !important;
            letter-spacing: 0.5px !important;
            border-radius: 10px !important;
            padding: 11px 22px !important;
            transition: transform 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275), 
                        box-shadow 0.25s ease, 
                        background 0.3s ease, 
                        border-color 0.3s ease !important;
            cursor: pointer !important;
            text-decoration: none !important;
            border: none !important;
            outline: none !important;
            -webkit-font-smoothing: antialiased !important;
            -moz-osx-font-smoothing: grayscale !important;
            text-transform: none !important; /* Keep original casing */
        }

        /* Adjust sizing for smaller/mini buttons so they fit in tables */
        .btn-mini-edit,
        .btn-mini-delete,
        .btn-toggle-on,
        .btn-toggle-off,
        .btn-eliminar-trab,
        .btn-historial,
        .btn-sm {
            padding: 6px 12px !important;
            font-size: 12px !important;
            border-radius: 6px !important;
            gap: 5px !important;
        }

        /* Text positioning over the sweep animation */
        .btn span,
        .btn-primary span,
        .btn-success span,
        .btn-danger span,
        .btn-warning span,
        .btn-submit span {
            position: relative !important;
            z-index: 2 !important;
        }

        /* Transition for button icons */
        .btn i,
        .btn-primary i,
        .btn-secondary i,
        .btn-success i,
        .btn-danger i,
        .btn-warning i,
        .btn-grupos i,
        .btn-edit i,
        .btn-eliminar-trab i,
        .btn-historial i,
        .btn-upload-image i,
        .btn-delete i,
        .btn-cancel i,
        .btn-submit i,
        .btn-mes i,
        .btn-mes-excel i,
        .btn-mes-pdf i,
        .btn-toggle-on i,
        .btn-toggle-off i,
        .btn-mini-edit i,
        .btn-mini-delete i,
        .accion-btn i,
        button[type="submit"]:not(.btn-pass-toggle):not(.btn-ver-pass-inline):not(.menu-toggle):not(.theme-toggle):not(.header-logout) i {
            transition: transform 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
            position: relative !important;
            z-index: 2 !important;
        }

        /* Micro-animation of icons on hover (slight rotate and scale) */
        .btn:hover i,
        .btn-primary:hover i,
        .btn-secondary:hover i,
        .btn-success:hover i,
        .btn-danger:hover i,
        .btn-warning:hover i,
        .btn-grupos:hover i,
        .btn-edit:hover i,
        .btn-eliminar-trab:hover i,
        .btn-historial:hover i,
        .btn-upload-image:hover i,
        .btn-delete:hover i,
        .btn-cancel:hover i,
        .btn-submit:hover i,
        .btn-mes:hover i,
        .btn-mes-excel:hover i,
        .btn-mes-pdf:hover i,
        .btn-toggle-on:hover i,
        .btn-toggle-off:hover i,
        .btn-mini-edit:hover i,
        .btn-mini-delete:hover i,
        .accion-btn:hover i,
        button[type="submit"]:not(.btn-pass-toggle):not(.btn-ver-pass-inline):not(.menu-toggle):not(.theme-toggle):not(.header-logout):hover i {
            transform: scale(1.15) rotate(8deg) !important;
        }

        /* Sweep Shine Sweep effect overlay */
        .btn::after,
        .btn-primary::after,
        .btn-secondary::after,
        .btn-success::after,
        .btn-danger::after,
        .btn-warning::after,
        .btn-grupos::after,
        .btn-edit::after,
        .btn-eliminar-trab::after,
        .btn-historial::after,
        .btn-upload-image::after,
        .btn-delete::after,
        .btn-cancel::after,
        .btn-submit::after,
        .btn-mes::after,
        .btn-mes-excel::after,
        .btn-mes-pdf::after,
        .btn-toggle-on::after,
        .btn-toggle-off::after,
        .btn-mini-edit::after,
        .btn-mini-delete::after,
        .accion-btn::after,
        button[type="submit"]:not(.btn-pass-toggle):not(.btn-ver-pass-inline):not(.menu-toggle):not(.theme-toggle):not(.header-logout)::after {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: -50% !important;
            width: 200% !important;
            height: 100% !important;
            background: linear-gradient(
                to right,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.4) 30%,
                rgba(255, 255, 255, 0) 100%
            ) !important;
            transform: skewX(-25deg) translateX(-100%) !important;
            transition: none !important;
            pointer-events: none !important;
            z-index: 1 !important;
        }

        .btn:hover::after,
        .btn-primary:hover::after,
        .btn-secondary:hover::after,
        .btn-success:hover::after,
        .btn-danger:hover::after,
        .btn-warning:hover::after,
        .btn-grupos:hover::after,
        .btn-edit:hover::after,
        .btn-eliminar-trab:hover::after,
        .btn-historial:hover::after,
        .btn-upload-image:hover::after,
        .btn-delete:hover::after,
        .btn-cancel:hover::after,
        .btn-submit:hover::after,
        .btn-mes:hover::after,
        .btn-mes-excel:hover::after,
        .btn-mes-pdf:hover::after,
        .btn-toggle-on:hover::after,
        .btn-toggle-off:hover::after,
        .btn-mini-edit:hover::after,
        .btn-mini-delete:hover::after,
        .accion-btn:hover::after,
        button[type="submit"]:not(.btn-pass-toggle):not(.btn-ver-pass-inline):not(.menu-toggle):not(.theme-toggle):not(.header-logout):hover::after {
            transform: skewX(-25deg) translateX(100%) !important;
            transition: transform 0.8s ease-in-out !important;
        }

        /* Scale and translate on hover */
        .btn:hover,
        .btn-primary:hover,
        .btn-secondary:hover,
        .btn-success:hover,
        .btn-danger:hover,
        .btn-warning:hover,
        .btn-grupos:hover,
        .btn-edit:hover,
        .btn-eliminar-trab:hover,
        .btn-historial:hover,
        .btn-upload-image:hover,
        .btn-delete:hover,
        .btn-cancel:hover,
        .btn-submit:hover,
        .btn-mes:hover,
        .btn-mes-excel:hover,
        .btn-mes-pdf:hover,
        .btn-toggle-on:hover,
        .btn-toggle-off:hover,
        .btn-mini-edit:hover,
        .btn-mini-delete:hover,
        .accion-btn:hover,
        button[type="submit"]:not(.btn-pass-toggle):not(.btn-ver-pass-inline):not(.menu-toggle):not(.theme-toggle):not(.header-logout):hover {
            transform: translateY(-2.5px) scale(1.025) !important;
        }

        /* Press animation when active/clicked */
        .btn:active,
        .btn-primary:active,
        .btn-secondary:active,
        .btn-success:active,
        .btn-danger:active,
        .btn-warning:active,
        .btn-grupos:active,
        .btn-edit:active,
        .btn-eliminar-trab:active,
        .btn-historial:active,
        .btn-upload-image:active,
        .btn-delete:active,
        .btn-cancel:active,
        .btn-submit:active,
        .btn-mes:active,
        .btn-mes-excel:active,
        .btn-mes-pdf:active,
        .btn-toggle-on:active,
        .btn-toggle-off:active,
        .btn-mini-edit:active,
        .btn-mini-delete:active,
        .accion-btn:active,
        button[type="submit"]:not(.btn-pass-toggle):not(.btn-ver-pass-inline):not(.menu-toggle):not(.theme-toggle):not(.header-logout):active {
            transform: translateY(1px) scale(0.97) !important;
        }


        /* ==========================================
           COLOR SCHEMES & HIGH LEGIBILITY CONTRASTS
           ========================================== */

        /* 🔵 PRIMARY ACTION / SEARCH BUTTONS (Cobalt Blue Gradient) */
        .btn-primary,
        .btn-submit,
        .btn-upload-image,
        button[type="submit"].btn-primary,
        .btn-filtrar {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%) !important;
            color: #ffffff !important;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.4) !important;
            box-shadow: 0 4px 10px rgba(30, 58, 138, 0.25) !important;
        }

        .btn-primary:hover,
        .btn-submit:hover,
        .btn-upload-image:hover,
        button[type="submit"].btn-primary:hover,
        .btn-filtrar:hover {
            background: linear-gradient(135deg, #2563eb 0%, #60a5fa 100%) !important;
            box-shadow: 0 8px 20px rgba(30, 58, 138, 0.45) !important;
        }

        .btn-primary:active,
        .btn-submit:active,
        .btn-upload-image:active,
        button[type="submit"].btn-primary:active,
        .btn-filtrar:active {
            box-shadow: 0 2px 5px rgba(30, 58, 138, 0.2) !important;
        }

        /* 🟢 SUCCESS / CREATION BUTTONS (Emerald Green Gradient - Agregar etc.) */
        .btn-success {
            background: linear-gradient(135deg, #065f46 0%, #10b981 100%) !important;
            color: #ffffff !important;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.4) !important;
            box-shadow: 0 4px 10px rgba(6, 95, 70, 0.25) !important;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%) !important;
            box-shadow: 0 8px 20px rgba(6, 95, 70, 0.45) !important;
        }

        .btn-success:active {
            box-shadow: 0 2px 5px rgba(6, 95, 70, 0.2) !important;
        }

        /* 🔴 DANGER BUTTONS (Crimson Red Gradient) */
        .btn-danger,
        .btn-delete,
        .btn-eliminar-trab,
        #btn_confirmar_eliminar_art {
            background: linear-gradient(135deg, #991b1b 0%, #ef4444 100%) !important;
            color: #ffffff !important;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.4) !important;
            box-shadow: 0 4px 10px rgba(153, 27, 27, 0.25) !important;
        }

        .btn-danger:hover,
        .btn-delete:hover,
        .btn-eliminar-trab:hover,
        #btn_confirmar_eliminar_art:hover {
            background: linear-gradient(135deg, #b91c1c 0%, #f87171 100%) !important;
            box-shadow: 0 8px 20px rgba(153, 27, 27, 0.45) !important;
        }

        .btn-danger:active,
        .btn-delete:active,
        .btn-eliminar-trab:active,
        #btn_confirmar_eliminar_art:active {
            box-shadow: 0 2px 5px rgba(153, 27, 27, 0.2) !important;
        }

        /* 🟡 WARNING / EDIT BUTTONS (Warm Gold/Amber Gradient) */
        .btn-warning,
        .btn-edit,
        .btn-mini-edit {
            background: linear-gradient(135deg, #b45309 0%, #f59e0b 100%) !important;
            color: #ffffff !important;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.4) !important;
            box-shadow: 0 4px 10px rgba(180, 83, 9, 0.25) !important;
        }

        .btn-warning:hover,
        .btn-edit:hover,
        .btn-mini-edit:hover {
            background: linear-gradient(135deg, #d97706 0%, #fbbf24 100%) !important;
            box-shadow: 0 8px 20px rgba(180, 83, 9, 0.45) !important;
        }

        .btn-warning:active,
        .btn-edit:active,
        .btn-mini-edit:active {
            box-shadow: 0 2px 5px rgba(180, 83, 9, 0.2) !important;
        }

        /* ⚫ HISTORIAL / DETAIL / KARDEX BUTTONS (Charcoal Slate Gradient) */
        .btn-historial {
            background: linear-gradient(135deg, #1e293b 0%, #475569 100%) !important;
            color: #ffffff !important;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.35) !important;
            box-shadow: 0 4px 10px rgba(30, 41, 59, 0.25) !important;
        }

        .btn-historial:hover {
            background: linear-gradient(135deg, #334155 0%, #64748b 100%) !important;
            box-shadow: 0 8px 20px rgba(30, 41, 59, 0.45) !important;
        }

        .btn-historial:active {
            box-shadow: 0 2px 5px rgba(30, 41, 59, 0.2) !important;
        }

        /* 🔘 MODULE GROUPS BUTTONS (Steel Slate Gradient) */
        .btn-grupos {
            background: linear-gradient(135deg, #334155 0%, #475569 100%) !important;
            color: #ffffff !important;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.35) !important;
            box-shadow: 0 4px 10px rgba(51, 65, 85, 0.25) !important;
        }

        .btn-grupos:hover {
            background: linear-gradient(135deg, #475569 0%, #64748b 100%) !important;
            box-shadow: 0 8px 20px rgba(51, 65, 85, 0.45) !important;
        }
        .btn-grupos:active {
            box-shadow: 0 2px 5px rgba(51, 65, 85, 0.2) !important;
        }

        /* ⚪ SECONDARY / CANCEL BUTTONS (Adaptive colors: Day & Night) */
        .btn-secondary,
        .btn-cancel,
        .btn-mini-bloqueado {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%) !important;
            border: 1.5px solid #cbd5e1 !important;
            color: #334155 !important;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05) !important;
        }

        .btn-secondary:hover,
        .btn-cancel:hover {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
            border-color: #94a3b8 !important;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1) !important;
        }

        .btn-secondary:active,
        .btn-cancel:active {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05) !important;
        }

        /* Dark mode overrides for secondary/cancel buttons */
        [data-theme="dark"] .btn-secondary,
        [data-theme="dark"] .btn-cancel,
        [data-theme="dark"] .btn-mini-bloqueado {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
            border: 1.5px solid #475569 !important;
            color: #f8fafc !important;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2) !important;
        }

        [data-theme="dark"] .btn-secondary:hover,
        [data-theme="dark"] .btn-cancel:hover {
            background: linear-gradient(135deg, #334155 0%, #1e293b 100%) !important;
            border-color: #64748b !important;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4) !important;
        }

        /* 🔘 TOGGLE ON/OFF BUTTONS (Workers status toggle) */
        .btn-toggle-on {
            background: linear-gradient(135deg, #1b5e20 0%, #4caf50 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(27, 94, 32, 0.25) !important;
        }

        .btn-toggle-on:hover {
            background: linear-gradient(135deg, #2e7d32 0%, #81c784 100%) !important;
            box-shadow: 0 8px 20px rgba(27, 94, 32, 0.45) !important;
        }

        .btn-toggle-off {
            background: linear-gradient(135deg, #b71c1c 0%, #ef5350 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(183, 28, 28, 0.25) !important;
        }

        .btn-toggle-off:hover {
            background: linear-gradient(135deg, #c62828 0%, #e57373 100%) !important;
            box-shadow: 0 8px 20px rgba(183, 28, 28, 0.45) !important;
        }

        /* 📊 REPORT MONTH BUTTONS */
        .btn-mes {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(30, 41, 59, 0.2) !important;
        }

        .btn-mes:hover {
            background: linear-gradient(135deg, #334155 0%, #475569 100%) !important;
            box-shadow: 0 8px 20px rgba(30, 41, 59, 0.35) !important;
        }

        [data-theme="dark"] .btn-mes {
            background: linear-gradient(135deg, #334155 0%, #475569 100%) !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3) !important;
        }

        [data-theme="dark"] .btn-mes:hover {
            background: linear-gradient(135deg, #475569 0%, #64748b 100%) !important;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.45) !important;
        }

        .btn-mes-excel {
            background: linear-gradient(135deg, #15803d 0%, #22c55e 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(21, 128, 61, 0.25) !important;
        }

        .btn-mes-excel:hover {
            background: linear-gradient(135deg, #16a34a 0%, #4ade80 100%) !important;
            box-shadow: 0 8px 20px rgba(21, 128, 61, 0.45) !important;
        }

        .btn-mes-pdf {
            background: linear-gradient(135deg, #b91c1c 0%, #ef4444 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(185, 28, 28, 0.25) !important;
        }

        .btn-mes-pdf:hover {
            background: linear-gradient(135deg, #dc2626 0%, #f87171 100%) !important;
            box-shadow: 0 8px 20px rgba(185, 28, 28, 0.45) !important;
        }

        /* ==========================================================================
           🎨 GENERAL FRONTEND ENHANCEMENTS & LIGHT/DARK ADAPTABILITY
           ========================================================================== */

        /* Force inputs focus states globally to orange */
        input:focus, 
        select:focus, 
        textarea:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15) !important;
        }

        /* Clean up any hardcoded purple links or text color */
        a[style*="color:#667eea"], 
        span[style*="color:#667eea"], 
        div[style*="color:#667eea"],
        a[style*="color: #667eea"], 
        span[style*="color: #667eea"], 
        div[style*="color: #667eea"],
        td a[style*="color:#667eea"] {
            color: var(--primary) !important;
        }

        /* Clean up inline backgrounds with purple */
        [style*="background:#667eea"],
        [style*="background: #667eea"],
        [style*="background:rgba(102, 126, 234"],
        [style*="background: rgba(102, 126, 234"] {
            background: var(--primary) !important;
        }

        /* Clasificación: Activo Corriente — azul-teal (no naranja) */
        .badge-rotacion-diario {
            font-size: 10px !important;
            padding: 3px 9px !important;
            background: #dbeafe !important;        /* azul claro */
            color: #1d4ed8 !important;             /* azul oscuro */
            border-radius: 6px !important;
            font-weight: 700 !important;
            margin-left: 5px !important;
            display: inline-block !important;
            letter-spacing: 0.2px !important;
        }
        [data-theme="dark"] .badge-rotacion-diario {
            background: rgba(88,166,255,0.12) !important;
            color: #58a6ff !important;             /* azul GitHub dark */
        }

        /* Clasificación: Activo Fijo — gris slate neutro */
        .badge-rotacion-ocasional {
            font-size: 10px !important;
            padding: 3px 9px !important;
            background: #f1f5f9 !important;        /* gris muy claro */
            color: #475569 !important;             /* slate */
            border-radius: 6px !important;
            font-weight: 700 !important;
            margin-left: 5px !important;
            display: inline-block !important;
            letter-spacing: 0.2px !important;
        }
        [data-theme="dark"] .badge-rotacion-ocasional {
            background: rgba(110,118,129,0.12) !important;
            color: #8b949e !important;             /* slate GitHub dark */
        }

        /* Table headers and cells contrast legibility */
        table th {
            color: var(--text-primary) !important;
            font-weight: 700 !important;
        }
        table td {
            color: var(--text-secondary) !important;
        }

        /* Labels contrast */
        .filters label,
        .form-field label,
        .filters-grid label {
            color: #475569 !important;
            font-weight: 700 !important;
        }
        [data-theme="dark"] .filters label,
        [data-theme="dark"] .form-field label,
        [data-theme="dark"] .filters-grid label {
            color: var(--text-secondary) !important;
        }
    </style>
</head>
<body>

    {{-- ====== SIDEBAR ====== --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
    <div class="sidebar-logo-img">
        <img src="{{ asset('img/logo.png') }}" alt="Logo Empresa Minera Torrez">
    </div>
    <div class="sidebar-logo-text">
        <h1>Minera Torrez</h1>
        <p>Sección Catalina</p>
    </div>
</div>

        <nav class="sidebar-nav">
            <div class="sidebar-section-title">Principal</div>

            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>

            <div class="sidebar-section-title">Gestión</div>

            <a href="{{ route('inventario.index') }}" class="sidebar-link {{ (request()->routeIs('inventario.*') && !request()->routeIs('inventario.rotacion.*')) ? 'active' : '' }}">
                <i class="fas fa-boxes-stacked"></i>
                <span>Inventario</span>
            </a>

            @if(Auth::user()->puedeEditar())
                <a href="{{ route('inventario.rotacion.index') }}" class="sidebar-link {{ request()->routeIs('inventario.rotacion.*') ? 'active' : '' }}">
                    <i class="fas fa-arrows-spin"></i>
                    <span>Clasificación</span>
                </a>
            @endif

            <a href="{{ route('movimientos.index', ['tipo' => 'entrada']) }}" class="sidebar-link {{ (request()->routeIs('movimientos.*') && request('tipo') === 'entrada') ? 'active' : '' }}">
                <i class="fas fa-arrow-down-long" style="color: #2b8a3e;"></i>
                <span>Movimiento de Entrada</span>
            </a>

            <a href="{{ route('movimientos.index', ['tipo' => 'salida']) }}" class="sidebar-link {{ (request()->routeIs('movimientos.*') && request('tipo') === 'salida') ? 'active' : '' }}">
                <i class="fas fa-arrow-up-long" style="color: #c92a2a;"></i>
                <span>Movimiento de Salida</span>
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

            @if(Auth::user()->esAdmin())
                <div class="sidebar-section-title">Administración</div>
                <a href="{{ route('usuarios.index') }}" class="sidebar-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                    <i class="fas fa-users-cog"></i>
                    <span>Gestión de Usuarios</span>
                </a>
                <a href="{{ route('backups.index') }}" class="sidebar-link {{ request()->routeIs('backups.*') ? 'active' : '' }}">
                    <i class="fas fa-database"></i>
                    <span>Copia de Seguridad</span>
                </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('profile.edit') }}" class="sidebar-user" title="Mi Perfil" style="margin-bottom: 8px;">
                <div class="sidebar-user-avatar" style="overflow: hidden; display: flex; align-items: center; justify-content: center; background: var(--border-light);">
                    <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="sidebar-user-info">
                    <div class="name">{{ Auth::user()->name }}</div>
                    <div class="role">{{ Auth::user()->nombreRol() }}</div>
                </div>
            </a>
            <form method="POST" action="{{ route('logout') }}" style="display:block; width: 100%;">
                @csrf
                <button type="submit" class="sidebar-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    Salir
                </button>
            </form>
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
                <span id="header-hora">{{ now()->format('H:i:s') }}</span>
            </div>

            <!-- Campana de Notificaciones -->
            <div class="notif-wrapper" style="position: relative; display: inline-block;">
                <button class="notif-btn" id="btn-notif-bell" onclick="toggleNotifDropdown(event)" title="Notificaciones" style="background: var(--bg-hover); border: 1.5px solid var(--border); color: var(--text-primary); width: 42px; height: 42px; border-radius: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; position: relative; transition: all 0.2s ease;">
                    <i class="fas fa-bell"></i>
                    @php
                        $countUnread = Auth::user()->notificacionesNoLeidasCount();
                    @endphp
                    <span id="notif-badge" class="notif-badge" style="position: absolute; top: -5px; right: -5px; background: #ef4444; color: white; font-size: 10px; font-weight: 800; width: 18px; height: 18px; border-radius: 50%; display: {{ $countUnread > 0 ? 'flex' : 'none' }}; align-items: center; justify-content: center; border: 2px solid var(--bg-card); transition: all 0.3s ease;">
                        {{ $countUnread }}
                    </span>
                </button>
                
                <div id="notif-dropdown" class="notif-dropdown" style="display: none; position: absolute; top: 52px; right: 0; width: 320px; background: var(--bg-card); border: 1.5px solid var(--border); border-radius: 16px; box-shadow: var(--shadow-lg); z-index: 1000; padding: 15px 0; overflow: hidden; backdrop-filter: blur(20px);">
                    <div style="padding: 0 16px 12px 16px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; gap: 8px;">
                        <span style="font-weight: 800; font-size: 14px; color: var(--text-primary);">Notificaciones</span>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <button onclick="marcarTodasLeidas(event)" style="background: none; border: none; color: var(--primary); font-size: 11.5px; font-weight: 700; cursor: pointer; padding: 0;">Leídas</button>
                            <span style="color: var(--border); font-size: 10px;">|</span>
                            <button onclick="limpiarTodasNotificaciones(event)" style="background: none; border: none; color: #ef4444; font-size: 11.5px; font-weight: 700; cursor: pointer; padding: 0;">Limpiar todo</button>
                        </div>
                    </div>
                    <div id="notif-list-container" style="max-height: 250px; overflow-y: auto;">
                        @php
                            $notifList = Auth::user()->notificaciones()->orderBy('created_at', 'desc')->take(5)->get();
                        @endphp
                        @if($notifList->isEmpty())
                            <div id="notif-empty" style="padding: 20px; text-align: center; color: var(--text-muted); font-size: 12.5px;">
                                No tienes notificaciones.
                            </div>
                        @else
                            @foreach($notifList as $n)
                                <div class="notif-item" id="notif-item-{{ $n->id }}" 
                                     @if(!$n->leido)
                                         onmouseenter="iniciarLecturaNotif(this, {{ $n->id }})" 
                                         onmouseleave="cancelarLecturaNotif(this)"
                                     @endif
                                     style="position: relative; padding: 12px 16px; border-bottom: 1px solid var(--border); border-left: 3.5px solid {{ $n->leido ? 'transparent' : 'var(--primary)' }}; font-size: 12px; line-height: 1.4; transition: all 0.4s ease; background: {{ $n->leido ? 'transparent' : 'rgba(249, 115, 22, 0.06)' }}; display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
                                    <div style="flex: 1; min-width: 0;">
                                        <div style="font-weight: {{ $n->leido ? '600' : '800' }}; color: var(--text-primary); margin-bottom: 3px; display: flex; align-items: center; gap: 6px;">
                                            @if(!$n->leido)
                                                <span class="unread-dot" style="width: 6px; height: 6px; background: #ef4444; border-radius: 50%; display: inline-block; transition: opacity 0.3s ease;"></span>
                                            @endif
                                            {{ $n->titulo }}
                                        </div>
                                        <div style="color: var(--text-muted); margin-bottom: 4px;">{{ $n->mensaje }}</div>
                                        <div style="font-size: 10px; color: var(--text-muted); opacity: 0.7;">{{ $n->created_at->diffForHumans() }}</div>
                                    </div>
                                    <button onclick="eliminarNotificacion(event, {{ $n->id }})" style="background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 13px; padding: 0 4px; line-height: 1; transition: color 0.2s;" title="Eliminar" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='var(--text-muted)'">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <button class="theme-toggle" onclick="toggleTheme()" title="Cambiar tema">
                <i class="fas fa-moon" id="theme-icon"></i>
            </button>
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
            const segundos = String(ahora.getSeconds()).padStart(2, '0');
            const reloj = document.getElementById('header-hora');
            if (reloj) {
                reloj.innerHTML = `${horas}<span class="time-colon">:</span>${minutos}<span class="time-colon">:</span>${segundos}`;
            }
        }
        actualizarReloj();
        setInterval(actualizarReloj, 1000);

        // ============================================
        // SISTEMA DE NOTIFICACIONES Y TOASTS EN VIVO
        // ============================================
        function toggleNotifDropdown(event) {
            if (event) event.stopPropagation();
            const dropdown = document.getElementById('notif-dropdown');
            if (dropdown.style.display === 'none' || dropdown.style.display === '') {
                dropdown.style.display = 'block';
                // Al abrir, marcamos como leídas automáticamente tras 3.5 segundos
                setTimeout(marcarTodasLeidas, 3500);
            } else {
                dropdown.style.display = 'none';
            }
        }

        // Cerrar dropdown al hacer clic fuera
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('notif-dropdown');
            const bellBtn = document.getElementById('btn-notif-bell');
            if (dropdown && dropdown.style.display === 'block') {
                if (!dropdown.contains(e.target) && !bellBtn.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            }
        });

        function sonarNotificacion() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioCtx.createOscillator();
                const gainNode = audioCtx.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioCtx.destination);

                oscillator.type = 'sine';
                oscillator.frequency.setValueAtTime(587.33, audioCtx.currentTime); // D5
                oscillator.frequency.exponentialRampToValueAtTime(880, audioCtx.currentTime + 0.12); // A5

                gainNode.gain.setValueAtTime(0.05, audioCtx.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.12);

                oscillator.start();
                oscillator.stop(audioCtx.currentTime + 0.12);
            } catch (e) {
                console.log("Audio no permitido aún por interacción del usuario:", e);
            }
        }

        function marcarTodasLeidas(event) {
            if (event) event.stopPropagation();
            
            fetch("{{ route('notificaciones.marcar-leidas') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const badge = document.getElementById('notif-badge');
                    if (badge) badge.style.display = 'none';
                    
                    // Quitar los puntos rojos de la lista visual y cambiar estilos con transiciones
                    document.querySelectorAll('.unread-dot').forEach(el => {
                        el.style.opacity = '0';
                        setTimeout(() => el.style.display = 'none', 300);
                    });
                    document.querySelectorAll('.notif-item').forEach(el => {
                        el.style.background = 'transparent';
                        el.style.borderLeftColor = 'transparent';
                        const titulo = el.querySelector('div[style*="font-weight"]');
                        if (titulo) {
                            titulo.style.fontWeight = '600';
                        }
                    });
                }
            })
            .catch(err => console.error("Error al marcar leídas:", err));
        }

        // ===== LECTURA DE NOTIFICACIONES AL PASAR EL MOUSE (800ms) =====
        let timeoutsLectura = {};

        function iniciarLecturaNotif(el, id) {
            // Si ya está leída o tiene un timer corriendo, no hacer nada
            if (timeoutsLectura[id]) return;

            timeoutsLectura[id] = setTimeout(() => {
                marcarNotifLeidaIndividual(el, id);
            }, 800); // 800ms de visualización flotante
        }

        function cancelarLecturaNotif(el) {
            // Extraer ID de la notificación
            const idMatch = el.id.match(/notif-item-(\d+)/);
            if (idMatch && idMatch[1]) {
                const id = idMatch[1];
                if (timeoutsLectura[id]) {
                    clearTimeout(timeoutsLectura[id]);
                    delete timeoutsLectura[id];
                }
            }
        }

        function marcarNotifLeidaIndividual(el, id) {
            // Evitar llamadas duplicadas
            if (timeoutsLectura[id]) {
                clearTimeout(timeoutsLectura[id]);
                delete timeoutsLectura[id];
            }

            // Cambiar estilos en vivo con animaciones suaves
            el.style.background = 'transparent';
            el.style.borderLeftColor = 'transparent';
            
            const dot = el.querySelector('.unread-dot');
            if (dot) {
                dot.style.opacity = '0';
                setTimeout(() => dot.style.display = 'none', 300);
            }

            const titulo = el.querySelector('div[style*="font-weight"]');
            if (titulo) {
                titulo.style.fontWeight = '600';
            }

            // Quitar los listeners de hover una vez leída
            el.removeAttribute('onmouseenter');
            el.removeAttribute('onmouseleave');

            // Enviar petición AJAX al servidor
            fetch(`/notificaciones/${id}/leer`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Actualizar el contador del badge
                    const badge = document.getElementById('notif-badge');
                    if (badge) {
                        if (data.total_no_leidas > 0) {
                            badge.textContent = data.total_no_leidas;
                            badge.style.display = 'flex';
                        } else {
                            badge.style.display = 'none';
                        }
                    }
                }
            })
            .catch(err => console.error("Error al marcar como leída individualmente:", err));
        }

        // ===== ELIMINACIÓN INDIVIDUAL DE NOTIFICACIÓN (TIPO CELULAR) =====
        function eliminarNotificacion(event, id) {
            if (event) event.stopPropagation();
            
            const item = document.getElementById(`notif-item-${id}`);
            if (!item) return;

            // Animación lateral de deslizamiento (swipe)
            item.style.transform = 'translateX(-105%)';
            item.style.opacity = '0';

            fetch(`/notificaciones/${id}`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    setTimeout(() => {
                        item.remove();
                        
                        // Si ya no quedan notificaciones, pintar aviso de vacío
                        const listContainer = document.getElementById('notif-list-container');
                        if (listContainer && listContainer.querySelectorAll('.notif-item').length === 0) {
                            listContainer.innerHTML = `<div id="notif-empty" style="padding: 20px; text-align: center; color: var(--text-muted); font-size: 12.5px;">No tienes notificaciones.</div>`;
                        }

                        // Actualizar contador del badge
                        const badge = document.getElementById('notif-badge');
                        if (badge) {
                            let total = parseInt(badge.textContent || 0) - 1;
                            if (total > 0) {
                                badge.textContent = total;
                            } else {
                                badge.style.display = 'none';
                            }
                        }
                    }, 300);
                } else {
                    // Si falla el servidor, restauramos el item
                    item.style.transform = 'translateX(0)';
                    item.style.opacity = '1';
                }
            })
            .catch(err => {
                console.error("Error al eliminar notificación:", err);
                item.style.transform = 'translateX(0)';
                item.style.opacity = '1';
            });
        }

        // ===== LIMPIAR TODAS LAS NOTIFICACIONES =====
        function limpiarTodasNotificaciones(event) {
            if (event) event.stopPropagation();

            const listContainer = document.getElementById('notif-list-container');
            if (listContainer) {
                // Animación de deslizamiento de todos los elementos
                listContainer.querySelectorAll('.notif-item').forEach(item => {
                    item.style.transform = 'translateX(-105%)';
                    item.style.opacity = '0';
                });
            }

            fetch("{{ route('notificaciones.limpiar-todas') }}", {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    setTimeout(() => {
                        const badge = document.getElementById('notif-badge');
                        if (badge) badge.style.display = 'none';
                        
                        if (listContainer) {
                            listContainer.innerHTML = `<div id="notif-empty" style="padding: 20px; text-align: center; color: var(--text-muted); font-size: 12.5px;">No tienes notificaciones.</div>`;
                        }
                    }, 300);
                }
            })
            .catch(err => console.error("Error al limpiar notificaciones:", err));
        }

        // Guardar el último ID procesado para evitar repetir Toasts en el polling
        let ultimaNotificacionId = null;

        function mostrarToast(notificacion) {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            toast.style.cssText = `
                pointer-events: auto;
                background: var(--bg-card);
                border: 1.5px solid var(--border);
                border-left: 4px solid var(--primary);
                border-radius: 14px;
                padding: 15px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.15);
                display: flex;
                flex-direction: column;
                gap: 5px;
                transform: translateX(120%);
                transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.3s ease;
                opacity: 0;
            `;

            toast.innerHTML = `
                <div style="display:flex; justify-content:space-between; align-items:center; width:100%;">
                    <strong style="font-size:13px; color:var(--text-primary); display:flex; align-items:center; gap:6px;">
                        <i class="fas fa-bell" style="color:var(--primary);"></i> ${notificacion.titulo}
                    </strong>
                    <button onclick="this.parentElement.parentElement.remove()" style="background:none; border:none; color:var(--text-muted); cursor:pointer; font-size:14px; padding:0 5px;">&times;</button>
                </div>
                <div style="font-size:12px; color:var(--text-muted); line-height:1.4;">${notificacion.mensaje}</div>
            `;

            container.appendChild(toast);

            // Reflujo para activar transición
            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
                toast.style.opacity = '1';
            }, 50);

            // Sonar chirp
            sonarNotificacion();

            // Autoeliminar en 6 segundos
            setTimeout(() => {
                toast.style.transform = 'translateX(120%)';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 6000);
        }

        function checkNuevasNotificaciones() {
            fetch("{{ route('notificaciones.get-nuevas') }}")
            .then(res => res.json())
            .then(data => {
                const badge = document.getElementById('notif-badge');
                if (badge) {
                    if (data.total_no_leidas > 0) {
                        badge.textContent = data.total_no_leidas;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                }

                if (data.notificaciones && data.notificaciones.length > 0) {
                    // Si es la primera consulta, solo memorizamos la más reciente para evitar llenarnos de Toasts
                    if (ultimaNotificacionId === null) {
                        ultimaNotificacionId = data.notificaciones[0].id;
                        return;
                    }

                    // Procesar solo notificaciones más nuevas que la última procesada
                    let nuevas = data.notificaciones.filter(n => n.id > ultimaNotificacionId);
                    
                    if (nuevas.length > 0) {
                        // Actualizar ID más reciente
                        ultimaNotificacionId = data.notificaciones[0].id;
                        
                        // Lanzar Toasts en orden cronológico inverso
                        nuevas.reverse().forEach(n => {
                            mostrarToast(n);
                        });

                        // Recargar lista del dropdown si está visible
                        actualizarListaDropdown(data.notificaciones);
                    }
                }
            })
            .catch(err => console.log("Error al consultar notificaciones:", err));
        }

        function actualizarListaDropdown(lista) {
            const listContainer = document.getElementById('notif-list-container');
            if (!listContainer) return;

            if (lista.length === 0) {
                listContainer.innerHTML = `<div id="notif-empty" style="padding: 20px; text-align: center; color: var(--text-muted); font-size: 12.5px;">No tienes notificaciones.</div>`;
                return;
            }

            let html = '';
            lista.slice(0, 5).forEach(n => {
                html += `
                    <div class="notif-item" id="notif-item-${n.id}" 
                         ${!n.leido ? `onmouseenter="iniciarLecturaNotif(this, ${n.id})"` : ''} 
                         ${!n.leido ? `onmouseleave="cancelarLecturaNotif(this)"` : ''} 
                         style="position: relative; padding: 12px 16px; border-bottom: 1px solid var(--border); border-left: 3.5px solid ${n.leido ? 'transparent' : 'var(--primary)'}; font-size: 12px; line-height: 1.4; transition: all 0.4s ease; background: ${n.leido ? 'transparent' : 'rgba(249, 115, 22, 0.06)'}; display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-weight: ${n.leido ? '600' : '800'}; color: var(--text-primary); margin-bottom: 3px; display: flex; align-items: center; gap: 6px;">
                                ${!n.leido ? '<span class="unread-dot" style="width: 6px; height: 6px; background: #ef4444; border-radius: 50%; display: inline-block; transition: opacity 0.3s ease;"></span>' : ''}
                                ${n.titulo}
                            </div>
                            <div style="color: var(--text-muted); margin-bottom: 4px;">${n.mensaje}</div>
                            <div style="font-size: 10px; color: var(--text-muted); opacity: 0.7;">Hace un momento</div>
                        </div>
                        <button onclick="eliminarNotificacion(event, ${n.id})" style="background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 13px; padding: 0 4px; line-height: 1; transition: color 0.2s;" title="Eliminar" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='var(--text-muted)'">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            });
            listContainer.innerHTML = html;
        }

        // Consultar cada 15 segundos
        setInterval(checkNuevasNotificaciones, 15000);
        // Primera consulta al cargar
        setTimeout(checkNuevasNotificaciones, 2000);

        // ===== CUADRO DE DIÁLOGO MODAL DE CONFIRMACIÓN PERSONALIZADO PREMIUM =====
        function mostrarConfirmacionPersonalizada(titulo, mensaje, textoBotonConfirmar = 'Aceptar', textoBotonCancelar = 'Cancelar') {
            return new Promise((resolve) => {
                // Crear el overlay
                const overlay = document.createElement('div');
                overlay.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.4);
                    backdrop-filter: blur(5px);
                    z-index: 100000;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    opacity: 0;
                    transition: opacity 0.2s ease;
                `;

                // Crear el contenedor
                const card = document.createElement('div');
                card.style.cssText = `
                    background: var(--bg-card);
                    border: 1.5px solid var(--border);
                    border-radius: 18px;
                    padding: 24px;
                    width: 90%;
                    max-width: 400px;
                    box-shadow: var(--shadow-lg);
                    transform: scale(0.9);
                    transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                    text-align: center;
                `;

                card.innerHTML = `
                    <div style="font-size: 32px; color: #f59e0b; margin-bottom: 14px;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 style="margin: 0 0 10px 0; font-size: 16px; font-weight: 800; color: var(--text-primary);">${titulo}</h3>
                    <p style="margin: 0 0 24px 0; font-size: 12.5px; color: var(--text-muted); line-height: 1.5; padding: 0 10px;">${mensaje}</p>
                    <div style="display: flex; gap: 10px; justify-content: center;">
                        <button id="custom-confirm-cancel" style="flex: 1; justify-content: center; background: none; border: 1.5px solid var(--border); color: var(--text-secondary); font-size: 13px; font-weight: 700; padding: 10px; border-radius: 10px; cursor: pointer; transition: all 0.2s;">
                            ${textoBotonCancelar}
                        </button>
                        <button id="custom-confirm-ok" style="flex: 1; justify-content: center; background: #ef4444; color: white; border: none; font-size: 13px; font-weight: 800; padding: 10px; border-radius: 10px; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);">
                            ${textoBotonConfirmar}
                        </button>
                    </div>
                `;

                overlay.appendChild(card);
                document.body.appendChild(overlay);

                // Forzar reflujo
                setTimeout(() => {
                    overlay.style.opacity = '1';
                    card.style.transform = 'scale(1)';
                }, 20);

                const btnCancel = card.querySelector('#custom-confirm-cancel');
                const btnOk = card.querySelector('#custom-confirm-ok');

                // Hover effects
                btnCancel.onmouseover = () => { btnCancel.style.background = 'var(--bg-hover)'; };
                btnCancel.onmouseout = () => { btnCancel.style.background = 'none'; };
                btnOk.onmouseover = () => { btnOk.style.background = '#dc2626'; };
                btnOk.onmouseout = () => { btnOk.style.background = '#ef4444'; };

                function cerrar(resultado) {
                    overlay.style.opacity = '0';
                    card.style.transform = 'scale(0.9)';
                    setTimeout(() => {
                        overlay.remove();
                        resolve(resultado);
                    }, 200);
                }

                btnCancel.addEventListener('click', () => cerrar(false));
                btnOk.addEventListener('click', () => cerrar(true));
                overlay.addEventListener('click', (e) => {
                    if (e.target === overlay) cerrar(false);
                });
            });
        }
    </script>

    <!-- Contenedor para notificaciones Toast flotantes en vivo -->
    <div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; width: 320px; pointer-events: none;"></div>

    @stack('scripts')
</body>
</html>