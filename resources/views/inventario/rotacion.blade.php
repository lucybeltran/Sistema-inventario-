@extends('layouts.mina')

@section('titulo', 'Clasificación de Uso')

@push('styles')
<style>
    .rotacion-tabs {
        display: flex;
        gap: 24px;
        margin-bottom: 20px;
        border-bottom: 2px solid var(--border);
        padding-bottom: 0;
    }

    .tab-link {
        padding: 12px 4px;
        text-decoration: none;
        color: var(--text-muted);
        font-weight: 600;
        font-size: 15px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
    }

    .tab-link:hover {
        color: var(--text-primary);
    }

    .tab-link.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
        font-weight: 700;
    }

    .tab-badge {
        background: var(--border);
        color: var(--text-secondary);
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        transition: all 0.2s;
    }

    .tab-link.active .tab-badge {
        background: var(--primary);
        color: white;
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
        color: var(--text-primary);
        font-size: 24px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .page-title i {
        color: var(--primary);
    }

    .filters-row {
        background: var(--bg-card);
        padding: 16px;
        border-radius: var(--radius);
        border: 1px solid var(--border);
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .search-box {
        display: flex;
        gap: 8px;
        flex-grow: 1;
        max-width: 450px;
    }

    .search-box input {
        flex-grow: 1;
        padding: 10px 14px;
        border: 2px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 14px;
        background: var(--bg-input);
        color: var(--text-primary);
        outline: none;
    }

    .search-box input:focus {
        border-color: var(--primary);
    }

    .btn {
        padding: 10px 18px;
        border: none;
        border-radius: var(--radius-sm);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .btn-primary { background: var(--gradient); color: white; }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: var(--shadow); }
    .btn-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; }
    .btn-success:hover { transform: translateY(-1px); box-shadow: 0 4px 10px rgba(17, 153, 142, 0.3); }
    .btn-danger { background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%); color: white; }
    .btn-danger:hover { transform: translateY(-1px); box-shadow: 0 4px 10px rgba(255, 75, 43, 0.3); }
    .btn-secondary { background: var(--border-light); color: var(--text-secondary); border: 1px solid var(--border); }
    .btn-secondary:hover { background: var(--border); }

    .btn-mover {
        background: linear-gradient(135deg, #5a6d82 0%, #70849c 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(90, 109, 130, 0.15) !important;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3) !important;
        font-weight: 700;
    }
    .btn-mover:hover {
        background: linear-gradient(135deg, #677b93 0%, #7f95ad 100%) !important;
        box-shadow: 0 4px 10px rgba(90, 109, 130, 0.3) !important;
        transform: translateY(-1.5px) !important;
    }

    .btn-group { display: flex; gap: 8px; }

    .table-container {
        background: var(--bg-card);
        border-radius: var(--radius);
        overflow-x: auto;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
    }

    table { width: 100%; border-collapse: collapse; }
    table th {
        background: var(--bg-hover);
        padding: 14px 12px;
        text-align: left;
        font-weight: 600;
        color: var(--text-secondary);
        border-bottom: 2px solid var(--border);
        font-size: 13px;
        text-transform: uppercase;
    }
    table td { padding: 14px 12px; border-bottom: 1px solid var(--border-light); }
    table tbody tr:hover { background: var(--bg-hover); }

    .codigo {
        font-family: 'Courier New', monospace;
        font-weight: bold;
        color: var(--primary);
    }

    .badge-grupo {
        background: var(--bg-hover) !important;
        color: var(--text-secondary) !important;
        border: 1px solid var(--border) !important;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }

    .empty-state i {
        font-size: 50px;
        opacity: 0.3;
        margin-bottom: 15px;
    }

    .pagination-wrapper {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }
    table tbody td {
        vertical-align: middle !important;
        padding: 8px 10px !important;
    }
    .btn-mover-pill {
        width: auto !important;
        min-width: 90px !important;
        padding: 4.5px 12px !important;
        font-size: 9.5px !important;
        border-radius: 5px !important;
        cursor: pointer !important;
        font-weight: 700 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-transform: uppercase !important;
        letter-spacing: 0.3px !important;
        white-space: nowrap !important;
        transition: all 0.15s ease !important;
        height: auto !important;
        line-height: 1.2 !important;
        box-shadow: none !important;
        gap: 4px !important;
    }
    .btn-mover-pill:hover {
        transform: translateY(-0.5px) !important;
        filter: brightness(0.96) !important;
    }
    .btn-mover-pill:active {
        transform: translateY(0) !important;
    }
    .btn-to-consumibles {
        background: #eafbf1 !important;
        color: #0d5c3a !important;
        border: 1px solid #d3f4e2 !important;
    }
    .btn-to-reserva {
        background: #f0f3ff !important;
        color: #1a237e !important;
        border: 1px solid #d0d7fe !important;
    }
    .btn-to-activo {
        background: #fdf3eb !important;
        color: #9c4221 !important;
        border: 1px solid #fae2d1 !important;
    }
    th.col-precio-soft, td.col-precio-soft {
        background-color: #f4fbf7 !important;
        border-left: 1px solid #e2f4eb !important;
        border-right: 1px solid #e2f4eb !important;
    }
    th.col-stock-soft, td.col-stock-soft {
        background-color: #fdf8f4 !important;
        border-left: 1px solid #fbf0e8 !important;
        border-right: 1px solid #fbf0e8 !important;
    }
</style>
@endpush

@section('contenido')

    <div class="page-header">
        <h2 class="page-title">
            <i class="fas fa-arrows-spin"></i> Clasificación de Uso
        </h2>
        
        <div class="btn-group" style="display: flex; gap: 8px; align-items: center;">
            <select id="export_rotacion_select" style="padding: 8px 12px; border: 2px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; background: var(--bg-input); color: var(--text-primary); outline: none; font-weight: 600; cursor: pointer;">
                <option value="todos">Exportar: Todos</option>
                <option value="diario">Exportar: Solo CONSUMIBLES</option>
                <option value="ocasional">Exportar: Solo RESERVA/ALMACÉN</option>
                <option value="prestamo">Exportar: Solo ACTIVO/USO</option>
            </select>
            
            <a href="{{ route('reportes.rotacion.excel', ['buscar' => request('buscar'), 'grupo' => request('grupo'), 'rotacion' => 'todos']) }}" id="btn-export-excel" class="btn btn-success" title="Exportar a Excel">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </a>
            <a href="{{ route('reportes.rotacion.pdf', ['buscar' => request('buscar'), 'grupo' => request('grupo'), 'rotacion' => 'todos']) }}" id="btn-export-pdf" class="btn btn-danger" title="Exportar a PDF">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </a>
        </div>
    </div>

    {{-- Pestañas superiores --}}
    <div class="rotacion-tabs" style="display: flex; gap: 8px; border-bottom: 2px solid var(--border); margin-bottom: 20px;">
        <a href="{{ route('inventario.rotacion.index', ['tab' => 'diario', 'buscar' => request('buscar'), 'grupo' => request('grupo')]) }}" 
           class="tab-link {{ $tab === 'diario' ? 'active' : '' }}" style="padding: 10px 16px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; border-bottom: 3px solid {{ $tab === 'diario' ? '#22c55e' : 'transparent' }}; color: {{ $tab === 'diario' ? '#15803d' : 'var(--text-muted)' }}; text-decoration: none;">
            <i class="fas fa-fire-flame-simple" style="color: #22c55e;"></i>
            <span>CONSUMIBLES</span>
            <span class="tab-badge" id="count-diario" style="background: rgba(34,197,94,0.1); color: #15803d; padding: 2px 8px; border-radius: 20px; font-size: 11px;">{{ $totalDiario }}</span>
        </a>
        <a href="{{ route('inventario.rotacion.index', ['tab' => 'ocasional', 'buscar' => request('buscar'), 'grupo' => request('grupo')]) }}" 
           class="tab-link {{ $tab === 'ocasional' ? 'active' : '' }}" style="padding: 10px 16px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; border-bottom: 3px solid {{ $tab === 'ocasional' ? '#6366f1' : 'transparent' }}; color: {{ $tab === 'ocasional' ? '#4338ca' : 'var(--text-muted)' }}; text-decoration: none;">
            <i class="fas fa-warehouse" style="color: #6366f1;"></i>
            <span>RESERVA / EN ALMACÉN</span>
            <span class="tab-badge" id="count-ocasional" style="background: rgba(99,102,241,0.1); color: #4338ca; padding: 2px 8px; border-radius: 20px; font-size: 11px;">{{ $totalOcasional }}</span>
        </a>
        <a href="{{ route('inventario.rotacion.index', ['tab' => 'prestamo', 'buscar' => request('buscar'), 'grupo' => request('grupo')]) }}" 
           class="tab-link {{ $tab === 'prestamo' ? 'active' : '' }}" style="padding: 10px 16px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; border-bottom: 3px solid {{ $tab === 'prestamo' ? '#f97316' : 'transparent' }}; color: {{ $tab === 'prestamo' ? '#c2410c' : 'var(--text-muted)' }}; text-decoration: none;">
            <i class="fas fa-screwdriver-wrench" style="color: #f97316;"></i>
            <span>ACTIVO / EN USO</span>
            <span class="tab-badge" id="count-prestamo" style="background: rgba(249,115,22,0.1); color: #c2410c; padding: 2px 8px; border-radius: 20px; font-size: 11px;">{{ $totalPrestamo }}</span>
        </a>
    </div>

    {{-- Barra de filtros --}}
    <div class="filters-row">
        <form method="GET" action="{{ route('inventario.rotacion.index') }}" class="search-box" style="max-width: 750px;">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <input type="text" name="buscar" placeholder="Buscar por código o nombre..." value="{{ request('buscar') }}" style="flex-grow: 2;">
            
            <select name="grupo" style="padding: 10px 14px; border: 2px solid var(--border); border-radius: var(--radius-sm); font-size: 14px; background: var(--bg-input); color: var(--text-primary); outline: none; flex-grow: 1; min-width: 180px;">
                <option value="">Todos los grupos</option>
                @foreach($grupos as $grupo)
                    <option value="{{ $grupo->id }}" {{ request('grupo') == $grupo->id ? 'selected' : '' }}>
                        {{ $grupo->id }} — {{ $grupo->nombre }}
                    </option>
                @endforeach
            </select>

            @if(request()->filled('buscar') || request()->filled('grupo'))
                <a href="{{ route('inventario.rotacion.index', ['tab' => $tab]) }}" class="btn btn-secondary" title="Limpiar filtros" style="display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fas fa-times"></i> Limpiar
                </a>
            @endif
        </form>

        <div id="showing-text" style="font-size: 13px; color: var(--text-muted); font-weight: 500;">
            Mostrando {{ $articulos->firstItem() ?? 0 }} - {{ $articulos->lastItem() ?? 0 }} de {{ $articulos->total() }} artículos
        </div>
    </div>

    <div id="rotacion-table-wrapper" style="transition: opacity 0.15s ease-in-out;">
        {{-- Tabla de artículos --}}
        @if($articulos->isEmpty())
            <div class="table-container">
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <p>No se encontraron artículos clasificados en esta sección.</p>
                </div>
            </div>
        @else
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 130px;">Código</th>
                            <th>Material / Artículo</th>
                            <th>Grupo</th>
                            <th style="width: 100px; text-align: center;">Unidad</th>
                            <th class="col-precio-soft" style="width: 150px; text-align: right;">Precio Unit.</th>
                            <th class="col-stock-soft" style="width: 120px; text-align: right;">Stock Actual</th>
                            <th style="width: 200px; text-align: center;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($articulos as $art)
                            <tr>
                                <td class="codigo">{{ $art->codigo }}</td>
                                <td style="font-weight: 500; color: var(--text-primary);">{{ $art->nombre }}</td>
                                <td>
                                    <span class="badge-grupo">{{ $art->grupo?->nombre ?? 'Sin Grupo' }}</span>
                                </td>
                                <td style="text-align: center; color: var(--text-muted);">{{ $art->unidad }}</td>
                                <td class="col-precio-soft" style="text-align: right; font-weight: 600; font-size: 11.5px; color: #0d5c3a !important;">
                                    @if(isset($preciosPorArticulo[$art->id]) && count($preciosPorArticulo[$art->id]) > 1)
                                        @foreach($preciosPorArticulo[$art->id] as $p)
                                            <div style="line-height: 1.2;">Bs. {{ number_format($p['precio'], 2) }}</div>
                                        @endforeach
                                    @else
                                        Bs. {{ number_format($art->precio, 2) }}
                                    @endif
                                </td>
                                <td class="col-stock-soft" style="text-align: right; font-weight: 600; font-size: 11.5px; color: {{ $art->cantidad <= 0 ? '#b91c1c' : '#9c4221' }} !important;">
                                    @if(isset($preciosPorArticulo[$art->id]) && count($preciosPorArticulo[$art->id]) > 1)
                                        @foreach($preciosPorArticulo[$art->id] as $p)
                                            <div style="line-height: 1.2; color: {{ $p['cantidad'] <= 0 ? '#b91c1c' : '#9c4221' }} !important;">
                                                {{ number_format($p['cantidad'], 3) }}
                                            </div>
                                        @endforeach
                                    @else
                                        {{ number_format($art->cantidad, 3) }}
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <div style="display: flex; gap: 6px; justify-content: center; align-items: center; height: 100%;">
                                        @if($tab !== 'diario')
                                            <form method="POST" action="{{ route('inventario.rotacion.cambiar', $art->id) }}" style="display:inline-flex; align-items: center; margin: 0;">
                                                @csrf
                                                <input type="hidden" name="rotacion" value="diario">
                                                <button type="submit" class="btn-mover-pill btn-to-consumibles">🟢 CONSUMO</button>
                                             </form>
                                        @endif
                                        @if($tab !== 'ocasional')
                                            <form method="POST" action="{{ route('inventario.rotacion.cambiar', $art->id) }}" style="display:inline-flex; align-items: center; margin: 0;">
                                                @csrf
                                                <input type="hidden" name="rotacion" value="ocasional">
                                                <button type="submit" class="btn-mover-pill btn-to-reserva">🔵 RESERVA</button>
                                             </form>
                                        @endif
                                        @if($tab !== 'prestamo')
                                            <form method="POST" action="{{ route('inventario.rotacion.cambiar', $art->id) }}" style="display:inline-flex; align-items: center; margin: 0;">
                                                @csrf
                                                <input type="hidden" name="rotacion" value="prestamo">
                                                <button type="submit" class="btn-mover-pill btn-to-activo">🟠 ACTIVO</button>
                                             </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper">
                {{ $articulos->links() }}
            </div>
        @endif
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.querySelector('input[name="buscar"]');
        const groupSelect = document.querySelector('select[name="grupo"]');
        const searchForm = document.querySelector('.search-box');
        
        let debounceTimer;
        
        function performSearch() {
            const formData = new FormData(searchForm);
            const params = new URLSearchParams(formData);
            const url = searchForm.action + '?' + params.toString();
            
            // Update browser URL
            window.history.replaceState({}, '', url);
            
            const wrapper = document.getElementById('rotacion-table-wrapper');
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
                const newWrapper = doc.getElementById('rotacion-table-wrapper');
                if (wrapper && newWrapper) {
                    wrapper.innerHTML = newWrapper.innerHTML;
                    wrapper.style.opacity = '1';
                }
                
                // Swap showing text
                const showingText = document.getElementById('showing-text');
                const newShowingText = doc.getElementById('showing-text');
                if (showingText && newShowingText) {
                    showingText.innerHTML = newShowingText.innerHTML;
                }
                
                // Swap tab counts
                ['count-diario', 'count-ocasional'].forEach(id => {
                    const el = document.getElementById(id);
                    const newEl = doc.getElementById(id);
                    if (el && newEl) el.textContent = newEl.textContent;
                });
                
                // Swap tab hrefs
                const tabs = document.querySelectorAll('.tab-link');
                const newTabs = doc.querySelectorAll('.tab-link');
                tabs.forEach((tab, index) => {
                    if (newTabs[index]) {
                        tab.setAttribute('href', newTabs[index].getAttribute('href'));
                    }
                });
                
                // Swap export buttons
                ['btn-export-excel', 'btn-export-pdf'].forEach(id => {
                    const btn = document.getElementById(id);
                    const newBtn = doc.getElementById(id);
                    if (btn && newBtn) btn.setAttribute('href', newBtn.getAttribute('href'));
                });
                
                // Recalculate export URLs based on selected dropdown option
                actualizarUrlsExportacion();
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
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                performSearch();
            });
        }
        
        if (groupSelect) {
            groupSelect.addEventListener('change', performSearch);
        }

        // --- Export URL logic ---
        const exportSelect = document.getElementById('export_rotacion_select');
        const btnExcel = document.getElementById('btn-export-excel');
        const btnPdf = document.getElementById('btn-export-pdf');

        function actualizarUrlsExportacion() {
            if (!exportSelect || !btnExcel || !btnPdf) return;
            const rot = exportSelect.value;
            
            // Excel
            let excelUrl = new URL(btnExcel.href, window.location.origin);
            excelUrl.searchParams.set('rotacion', rot);
            btnExcel.href = excelUrl.pathname + excelUrl.search;

            // PDF
            let pdfUrl = new URL(btnPdf.href, window.location.origin);
            pdfUrl.searchParams.set('rotacion', rot);
            btnPdf.href = pdfUrl.pathname + pdfUrl.search;
        }

        if (exportSelect) {
            exportSelect.addEventListener('change', actualizarUrlsExportacion);
        }
        
        // Initial setup
        actualizarUrlsExportacion();
    });
</script>
@endpush
