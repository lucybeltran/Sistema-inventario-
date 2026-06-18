@extends('layouts.mina')

@section('titulo', 'Reportes')

@push('styles')
<style>
    /* ===== HEADER ===== */
    .page-header {
        margin-bottom: 25px;
    }

    .page-title {
        color: #667eea;
        font-size: 26px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 5px;
    }

    .page-subtitle {
        color: #666;
        font-size: 14px;
    }

    /* ===== STATS RÁPIDAS ===== */
    .stats-mini {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .stat-mini {
        background: white;
        padding: 18px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border-left: 4px solid #667eea;
        transition: all 0.3s;
    }

    .stat-mini:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }

    .stat-mini.morado { border-left-color: #667eea; }
    .stat-mini.verde { border-left-color: #38a169; }
    .stat-mini.rojo { border-left-color: #e53e3e; }
    .stat-mini.naranja { border-left-color: #f59f00; }

    .stat-mini .label {
        font-size: 11px;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .stat-mini .num {
        font-size: 26px;
        font-weight: 700;
        color: #2d3748;
        line-height: 1.2;
    }

    /* ===== PESTAÑAS (TABS) ===== */
    .tabs-container {
        background: white;
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .tabs-nav {
        display: flex;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 2px solid #e0e0e0;
        overflow-x: auto;
    }

    .tab-btn {
        flex: 1;
        min-width: 150px;
        padding: 18px 22px;
        background: transparent;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        color: #718096;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-family: inherit;
        border-bottom: 3px solid transparent;
        position: relative;
    }

    .tab-btn:hover {
        color: #667eea;
        background: rgba(102, 126, 234, 0.05);
    }

    .tab-btn.active {
        color: #667eea;
        background: white;
        border-bottom-color: #667eea;
    }

    .tab-btn i { font-size: 18px; }

    .tab-btn .shortcut {
        position: absolute;
        top: 6px;
        right: 8px;
        font-size: 9px;
        background: rgba(102, 126, 234, 0.15);
        color: #667eea;
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: 700;
    }

    .tab-btn.active .shortcut {
        background: #667eea;
        color: white;
    }

    .tab-content {
        padding: 30px;
        animation: fadeIn 0.35s ease;
        display: none;
    }

    .tab-content.active { display: block; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ===== CONTENIDO DENTRO DE TABS ===== */
    .tab-title {
        font-size: 20px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .tab-title i { color: #667eea; }

    .tab-description {
        color: #718096;
        font-size: 14px;
        margin-bottom: 22px;
        line-height: 1.5;
    }

    /* ===== FILTROS ===== */
    .filters-grid {
        display: grid;
        gap: 14px;
        margin-bottom: 22px;
    }

    .filters-grid.cols-2 { grid-template-columns: 1fr 1fr; }
    .filters-grid.cols-3 { grid-template-columns: 2fr 1fr 1fr; }
    .filters-grid.cols-4 { grid-template-columns: 1fr 1fr 1fr 1fr; }

    .form-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-field label {
        font-size: 12px;
        font-weight: 600;
        color: #555;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-field input,
    .form-field select {
        padding: 11px 14px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        background: white;
        font-family: inherit;
        transition: all 0.2s;
    }

    .form-field input:focus,
    .form-field select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* ===== PREVIEW BOX ===== */
    .preview-box {
        background: linear-gradient(135deg, #f0e9ff 0%, #e0d5fa 100%);
        padding: 14px 18px;
        border-radius: 10px;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 13px;
        color: #5e3c8c;
    }

    .preview-box i {
        color: #667eea;
        font-size: 18px;
    }

    .preview-box strong { color: #4c2d8c; }

    /* ===== BOTONES DE DESCARGA ===== */
    .download-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        font-family: inherit;
        color: white;
    }

    .btn:disabled,
    .btn.disabled {
        opacity: 0.4;
        cursor: not-allowed;
        pointer-events: none;
    }

    .btn-excel { background: #1f7244; }
    .btn-excel:hover { background: #145a31; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(31, 114, 68, 0.3); }

    .btn-pdf { background: #c0392b; }
    .btn-pdf:hover { background: #962e21; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(192, 57, 43, 0.3); }

    /* ===== AYUDA / INFO ===== */
    .help-text {
        background: #fff8e1;
        border-left: 3px solid #f59f00;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 13px;
        color: #7c2d12;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .help-text i { color: #f59f00; font-size: 16px; }

    /* ===== RESUMEN MENSUAL ===== */
    .mes-card {
        background: white;
        border-radius: 12px;
        padding: 18px 20px;
        margin-bottom: 15px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        border: 1px solid #f0f0f0;
        border-left: 4px solid #667eea;
        transition: all 0.2s;
    }

    .mes-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }

    .mes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f0f0f0;
        flex-wrap: wrap;
        gap: 10px;
    }

    .mes-header h4 {
        color: #667eea;
        font-size: 16px;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .mes-actions { display: flex; gap: 8px; }

    .btn-mes {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s;
        color: white;
    }

    .btn-mes-excel { background: #1f7244; }
    .btn-mes-excel:hover { background: #145a31; transform: translateY(-1px); }
    .btn-mes-pdf { background: #c0392b; }
    .btn-mes-pdf:hover { background: #962e21; transform: translateY(-1px); }

    .resumen-unidades {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .resumen-unidades th {
        background: #f8f9fa;
        color: #555;
        padding: 9px 12px;
        text-align: left;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e0e0e0;
    }

    .resumen-unidades td {
        padding: 10px 12px;
        border-bottom: 1px solid #f5f5f5;
    }

    .unidad-badge {
        background: #e9ecef;
        color: #495057;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    /* ===== ATAJOS INFO ===== */
    .keyboard-hint {
        text-align: center;
        margin-top: 18px;
        color: #a0aec0;
        font-size: 11px;
        padding: 10px;
    }

    .keyboard-hint kbd {
        background: #f7fafc;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        padding: 2px 7px;
        font-family: 'Courier New', monospace;
        font-size: 10px;
        font-weight: 600;
        color: #4a5568;
        margin: 0 2px;
    }

    @media (max-width: 768px) {
        .filters-grid.cols-2,
        .filters-grid.cols-3,
        .filters-grid.cols-4 { grid-template-columns: 1fr; }
        .tab-btn { min-width: 120px; padding: 14px 12px; font-size: 13px; }
        .tab-btn .shortcut { display: none; }
        .tab-content { padding: 20px; }
    }
</style>
@endpush

@section('contenido')

    {{-- HEADER --}}
    <div class="page-header">
        <h2 class="page-title">
            <i class="fas fa-file-export"></i> Reportes y Exportaciones
        </h2>
        <p class="page-subtitle">
            Selecciona el tipo de reporte que deseas generar.
        </p>
    </div>

    {{-- STATS RÁPIDAS --}}
    <div class="stats-mini">
        <div class="stat-mini morado">
            <div class="label"><i class="fas fa-boxes-stacked"></i> Total Artículos</div>
            <div class="num">{{ $totalArticulos }}</div>
        </div>
        <div class="stat-mini verde">
            <div class="label"><i class="fas fa-exchange-alt"></i> Movimientos</div>
            <div class="num">{{ $totalMovimientos }}</div>
        </div>
        <div class="stat-mini rojo">
            <div class="label"><i class="fas fa-exclamation-circle"></i> Sin Stock</div>
            <div class="num">{{ $articulosSinStock }}</div>
        </div>
        <div class="stat-mini naranja">
            <div class="label"><i class="fas fa-hard-hat"></i> Trabajadores</div>
            <div class="num">{{ $trabajadores->count() }}</div>
        </div>
    </div>

    {{-- PESTAÑAS --}}
    <div class="tabs-container">
        <div class="tabs-nav">
            <button class="tab-btn active" data-tab="inventario">
                <i class="fas fa-boxes-stacked"></i>
                <span>Inventario</span>
                <span class="shortcut">Ctrl+1</span>
            </button>
            <button class="tab-btn" data-tab="movimientos">
                <i class="fas fa-exchange-alt"></i>
                <span>Movimientos</span>
                <span class="shortcut">Ctrl+2</span>
            </button>
            <button class="tab-btn" data-tab="kardex">
                <i class="fas fa-clipboard-list"></i>
                <span>Kardex</span>
                <span class="shortcut">Ctrl+3</span>
            </button>
            <button class="tab-btn" data-tab="mensual">
                <i class="fas fa-calendar-alt"></i>
                <span>Mensual</span>
                <span class="shortcut">Ctrl+4</span>
            </button>
        </div>

        {{-- ====== PESTAÑA 1: INVENTARIO ====== --}}
        <div class="tab-content active" id="tab-inventario">
            <h3 class="tab-title">
                <i class="fas fa-boxes-stacked"></i> Reporte de Inventario
            </h3>
            <p class="tab-description">
                Lista completa de todos los artículos con stock actual, precio unitario y valor total del inventario.
            </p>

            <div class="preview-box">
                <i class="fas fa-info-circle"></i>
                <div>
                    Se incluirán los <strong>{{ $totalArticulos }} artículos</strong> del sistema con su stock, precio y valor.
                </div>
            </div>

            <div class="download-buttons">
                <a href="{{ route('reportes.inventario.excel') }}" class="btn btn-excel">
                    <i class="fas fa-file-excel"></i> Descargar Excel
                </a>
                <a href="{{ route('reportes.inventario.pdf') }}" class="btn btn-pdf">
                    <i class="fas fa-file-pdf"></i> Descargar PDF
                </a>
            </div>
        </div>

        {{-- ====== PESTAÑA 2: MOVIMIENTOS ====== --}}
        <div class="tab-content" id="tab-movimientos">
            <h3 class="tab-title">
                <i class="fas fa-exchange-alt"></i> Reporte de Movimientos
            </h3>
            <p class="tab-description">
                Historial de entradas y salidas. Filtra por trabajador, fechas o ambos para obtener reportes específicos.
                <strong>Dejar todo vacío incluye todos los movimientos.</strong>
            </p>

            <div class="filters-grid cols-3">
                <div class="form-field">
                    <label><i class="fas fa-hard-hat"></i> Trabajador (opcional)</label>
                    <select id="filtro_trabajador_mov">
                        <option value="">Todos los trabajadores</option>
                        @foreach($trabajadores as $t)
                            <option value="{{ $t->id }}">
                                {{ $t->nombre }} {{ $t->cargo ? '(' . $t->cargo . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label><i class="fas fa-calendar"></i> Desde (opcional)</label>
                    <input type="date" id="mov_desde">
                </div>
                <div class="form-field">
                    <label><i class="fas fa-calendar"></i> Hasta (opcional)</label>
                    <input type="date" id="mov_hasta">
                </div>
            </div>

            <div class="preview-box" id="preview-movimientos">
                <i class="fas fa-info-circle"></i>
                <div id="preview-movimientos-text">
                    Se incluirán <strong>todos los movimientos</strong>. Puedes filtrar por trabajador y/o fechas.
                </div>
            </div>

            <div class="download-buttons">
                <a href="#" onclick="descargarMovimientos('excel'); return false;" class="btn btn-excel">
                    <i class="fas fa-file-excel"></i> Descargar Excel
                </a>
                <a href="#" onclick="descargarMovimientos('pdf'); return false;" class="btn btn-pdf">
                    <i class="fas fa-file-pdf"></i> Descargar PDF
                </a>
            </div>

            <div class="help-text" style="margin-top:20px;">
                <i class="fas fa-lightbulb"></i>
                <div>
                    <strong>Tip:</strong> Para ver el historial completo de un trabajador específico,
                    ve a la pestaña <strong>Trabajadores</strong> y haz click en <em>"Ver historial"</em>.
                </div>
            </div>
        </div>

        {{-- ====== PESTAÑA 3: KARDEX ====== --}}
        <div class="tab-content" id="tab-kardex">
            <h3 class="tab-title">
                <i class="fas fa-clipboard-list"></i> Kardex por Producto
            </h3>
            <p class="tab-description">
                Historial detallado de un producto específico con entradas, salidas y saldo acumulado después de cada movimiento.
                Útil para auditorías y seguimiento individual.
            </p>

            <div class="filters-grid cols-3">
                <div class="form-field">
                    <label><i class="fas fa-box"></i> Producto *</label>
                    <select id="kardex_producto" onchange="actualizarBotonesKardex()">
                        <option value="">— Seleccionar producto —</option>
                        @foreach(\App\Models\Articulo::orderBy('codigo')->get() as $art)
                            <option value="{{ $art->id }}">
                                {{ $art->codigo }} — {{ $art->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label><i class="fas fa-calendar"></i> Desde (opcional)</label>
                    <input type="date" id="kardex_desde">
                </div>
                <div class="form-field">
                    <label><i class="fas fa-calendar"></i> Hasta (opcional)</label>
                    <input type="date" id="kardex_hasta">
                </div>
            </div>

            <div class="help-text" id="kardex_help">
                <i class="fas fa-info-circle"></i>
                Selecciona un producto para habilitar los botones de descarga.
            </div>

            <div class="preview-box" id="preview-kardex" style="display:none;">
                <i class="fas fa-info-circle"></i>
                <div id="preview-kardex-text"></div>
            </div>

            <div class="download-buttons">
                <a href="#" onclick="descargarKardex('excel'); return false;" class="btn btn-excel disabled" id="btn_kardex_excel">
                    <i class="fas fa-file-excel"></i> Descargar Excel
                </a>
                <a href="#" onclick="descargarKardex('pdf'); return false;" class="btn btn-pdf disabled" id="btn_kardex_pdf">
                    <i class="fas fa-file-pdf"></i> Descargar PDF
                </a>
            </div>
        </div>

        {{-- ====== PESTAÑA 4: RESUMEN MENSUAL ====== --}}
        <div class="tab-content" id="tab-mensual">
            <h3 class="tab-title">
                <i class="fas fa-calendar-alt"></i> Resumen Mensual
            </h3>
            <p class="tab-description">
                Movimientos agrupados por mes y unidad de medida (últimos 6 meses).
                Puedes descargar el reporte de cada mes por separado.
            </p>

            @forelse($resumenMensual as $periodo => $unidades)
                @php $fechaMes = \Carbon\Carbon::parse($periodo . '-01'); @endphp
                <div class="mes-card">
                    <div class="mes-header">
                        <h4>
                            <i class="fas fa-calendar"></i>
                            {{ strtoupper($fechaMes->translatedFormat('F Y')) }}
                        </h4>
                        <div class="mes-actions">
                            <a href="{{ route('reportes.mes.excel', $periodo) }}" class="btn-mes btn-mes-excel">
                                <i class="fas fa-file-excel"></i> Excel
                            </a>
                            <a href="{{ route('reportes.mes.pdf', $periodo) }}" class="btn-mes btn-mes-pdf">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                        </div>
                    </div>

                    <table class="resumen-unidades">
                        <thead>
                            <tr>
                                <th>Unidad</th>
                                <th style="text-align:right;">Entradas</th>
                                <th style="text-align:right;">Salidas</th>
                                <th style="text-align:right;">Neto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($unidades as $row)
                                <tr>
                                    <td><span class="unidad-badge">{{ $row->unidad }}</span></td>
                                    <td style="text-align:right; color:#2b8a3e;">
                                        <i class="fas fa-arrow-down"></i> {{ number_format($row->entradas, 2) }}
                                    </td>
                                    <td style="text-align:right; color:#862e2e;">
                                        <i class="fas fa-arrow-up"></i> {{ number_format($row->salidas, 2) }}
                                    </td>
                                    <td style="text-align:right;">
                                        <strong style="color: {{ ($row->entradas - $row->salidas) >= 0 ? '#2b8a3e' : '#862e2e' }};">
                                            {{ number_format($row->entradas - $row->salidas, 2) }}
                                        </strong>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @empty
                <div style="text-align:center; padding:40px; color:#999;">
                    <i class="fas fa-inbox" style="font-size:48px; opacity:0.3;"></i>
                    <p style="margin-top:15px;">Sin movimientos registrados aún.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ATAJOS --}}
    <div class="keyboard-hint">
        <i class="fas fa-keyboard"></i>
        Atajos de teclado:
        <kbd>Ctrl</kbd>+<kbd>1</kbd> Inventario ·
        <kbd>Ctrl</kbd>+<kbd>2</kbd> Movimientos ·
        <kbd>Ctrl</kbd>+<kbd>3</kbd> Kardex ·
        <kbd>Ctrl</kbd>+<kbd>4</kbd> Mensual
    </div>

@endsection

@push('scripts')
<script>
    // ============================================
    // SISTEMA DE PESTAÑAS
    // ============================================
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    function activarTab(tabName) {
        tabBtns.forEach(b => b.classList.remove('active'));
        tabContents.forEach(c => c.classList.remove('active'));

        const btn = document.querySelector(`.tab-btn[data-tab="${tabName}"]`);
        const content = document.getElementById('tab-' + tabName);

        if (btn && content) {
            btn.classList.add('active');
            content.classList.add('active');
            // Guardar última pestaña visitada
            try { localStorage.setItem('ultima_tab_reportes', tabName); } catch(e) {}
        }
    }

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => activarTab(btn.dataset.tab));
    });

    // Recordar última pestaña al recargar
    try {
        const ultima = localStorage.getItem('ultima_tab_reportes');
        if (ultima) activarTab(ultima);
    } catch(e) {}

    // ============================================
    // ATAJOS DE TECLADO (Ctrl+1, Ctrl+2, etc.)
    // ============================================
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && !e.shiftKey && !e.altKey) {
            const tecla = e.key;
            const mapa = { '1': 'inventario', '2': 'movimientos', '3': 'kardex', '4': 'mensual' };
            if (mapa[tecla]) {
                e.preventDefault();
                activarTab(mapa[tecla]);
            }
        }
    });

    // ============================================
    // REPORTE DE MOVIMIENTOS
    // ============================================
    function actualizarPreviewMovimientos() {
        const trab = document.getElementById('filtro_trabajador_mov');
        const desde = document.getElementById('mov_desde').value;
        const hasta = document.getElementById('mov_hasta').value;
        const text = document.getElementById('preview-movimientos-text');

        let mensaje = '';
        const trabajadorNombre = trab.options[trab.selectedIndex].text;

        if (trab.value) {
            mensaje += `Movimientos de <strong>${trabajadorNombre}</strong>`;
        } else {
            mensaje += `<strong>Todos los movimientos</strong>`;
        }

        if (desde || hasta) {
            mensaje += ` · Período: <strong>${desde || '—'}</strong> al <strong>${hasta || 'hoy'}</strong>`;
        }

        text.innerHTML = mensaje + '.';
    }

    document.getElementById('filtro_trabajador_mov').addEventListener('change', actualizarPreviewMovimientos);
    document.getElementById('mov_desde').addEventListener('change', actualizarPreviewMovimientos);
    document.getElementById('mov_hasta').addEventListener('change', actualizarPreviewMovimientos);

    function descargarMovimientos(formato) {
        const desde = document.getElementById('mov_desde').value;
        const hasta = document.getElementById('mov_hasta').value;
        const trabajadorId = document.getElementById('filtro_trabajador_mov').value;

        let url = formato === 'excel'
            ? "{{ route('reportes.movimientos.excel') }}"
            : "{{ route('reportes.movimientos.pdf') }}";

        const params = [];
        if (desde) params.push('desde=' + desde);
        if (hasta) params.push('hasta=' + hasta);
        if (trabajadorId) params.push('trabajador_id=' + trabajadorId);

        if (params.length) url += '?' + params.join('&');
        window.location.href = url;
    }

    // ============================================
    // KARDEX POR PRODUCTO
    // ============================================
    function actualizarBotonesKardex() {
        const productoSelect = document.getElementById('kardex_producto');
        const productoId = productoSelect.value;
        const btnExcel = document.getElementById('btn_kardex_excel');
        const btnPdf = document.getElementById('btn_kardex_pdf');
        const help = document.getElementById('kardex_help');
        const preview = document.getElementById('preview-kardex');
        const previewText = document.getElementById('preview-kardex-text');

        if (productoId) {
            btnExcel.classList.remove('disabled');
            btnPdf.classList.remove('disabled');
            help.style.display = 'none';
            preview.style.display = 'flex';
            const productoNombre = productoSelect.options[productoSelect.selectedIndex].text;
            previewText.innerHTML = `Kardex de <strong>${productoNombre}</strong> con entradas, salidas y saldo acumulado.`;
        } else {
            btnExcel.classList.add('disabled');
            btnPdf.classList.add('disabled');
            help.style.display = 'flex';
            preview.style.display = 'none';
        }
    }

    function descargarKardex(formato) {
        const productoId = document.getElementById('kardex_producto').value;
        if (!productoId) {
            alert('Por favor selecciona un producto.');
            return;
        }

        const desde = document.getElementById('kardex_desde').value;
        const hasta = document.getElementById('kardex_hasta').value;

        let url = formato === 'excel'
            ? '/reportes/kardex/' + productoId + '/excel'
            : '/reportes/kardex/' + productoId + '/pdf';

        const params = [];
        if (desde) params.push('desde=' + desde);
        if (hasta) params.push('hasta=' + hasta);

        if (params.length) url += '?' + params.join('&');
        window.location.href = url;
    }
</script>
@endpush