@extends('layouts.mina')

@section('titulo', 'Kardex por Producto')

@push('styles')
<style>
    .breadcrumb {
        display: flex;
        gap: 8px;
        font-size: 14px;
        color: #666;
        margin-bottom: 20px;
    }
    .breadcrumb a { color: #667eea; text-decoration: none; }
    .breadcrumb a:hover { text-decoration: underline; }

    .selector-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 25px;
    }

    .selector-card h2 {
        color: #667eea;
        font-size: 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr auto;
        gap: 15px;
        align-items: end;
    }

    .form-field { display: flex; flex-direction: column; gap: 6px; }
    .form-field label {
        font-size: 12px; font-weight: 600; color: #555;
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    .form-field input,
    .form-field select {
        padding: 10px 14px; border: 2px solid #e0e0e0;
        border-radius: 8px; font-size: 14px; background: white;
        font-family: inherit;
    }
    .form-field input:focus,
    .form-field select:focus {
        outline: none; border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .btn {
        padding: 11px 20px; border: none; border-radius: 8px;
        font-size: 14px; font-weight: 600; cursor: pointer;
        transition: all 0.3s; display: inline-flex; align-items: center;
        gap: 6px; text-decoration: none; color: inherit;
        font-family: inherit;
    }
    .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4); }
    .btn-secondary { background: #f0f0f0; color: #555; }
    .btn-excel { background: #1f7244; color: white; }
    .btn-excel:hover { background: #145a31; }
    .btn-pdf { background: #c0392b; color: white; }
    .btn-pdf:hover { background: #962e21; }

    /* ===== TARJETA DEL ARTÍCULO ===== */
    .articulo-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 14px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .articulo-info { flex: 1; }
    .articulo-info h3 {
        font-size: 22px;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .articulo-codigo {
        background: rgba(255,255,255,0.25);
        padding: 4px 10px;
        border-radius: 8px;
        font-family: 'Courier New', monospace;
        font-size: 14px;
        margin-right: 10px;
    }
    .articulo-meta { font-size: 13px; opacity: 0.95; line-height: 1.6; }

    /* ===== STATS DEL KARDEX ===== */
    .stats-kardex {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .stat-kardex {
        background: white;
        padding: 18px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        text-align: center;
        border-top: 3px solid;
    }

    .stat-kardex.entradas { border-color: #38a169; }
    .stat-kardex.salidas { border-color: #e53e3e; }
    .stat-kardex.saldo { border-color: #667eea; }
    .stat-kardex.valor { border-color: #f59f00; }

    .stat-kardex .icono { font-size: 24px; margin-bottom: 8px; }
    .stat-kardex.entradas .icono { color: #38a169; }
    .stat-kardex.salidas .icono { color: #e53e3e; }
    .stat-kardex.saldo .icono { color: #667eea; }
    .stat-kardex.valor .icono { color: #f59f00; }

    .stat-kardex .label {
        font-size: 11px;
        text-transform: uppercase;
        color: #718096;
        margin-bottom: 4px;
        font-weight: 600;
    }

    .stat-kardex .num {
        font-size: 22px;
        font-weight: bold;
        color: #2d3748;
    }

    .stat-kardex .subtitulo {
        font-size: 11px;
        color: #a0aec0;
        margin-top: 4px;
    }

    /* ===== BOTONES DE DESCARGA ===== */
    .download-buttons {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    /* ===== FILTROS DE FECHA ===== */
    .filtros-fecha {
        background: #f8f9fa;
        padding: 18px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: grid;
        grid-template-columns: 1fr 1fr auto auto;
        gap: 12px;
        align-items: end;
    }

    /* ===== TABLA KARDEX ===== */
    .table-kardex-container {
        background: white;
        border-radius: 12px;
        overflow-x: auto;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .table-kardex {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .table-kardex th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 14px 12px;
        text-align: left;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    .table-kardex td {
        padding: 12px;
        border-bottom: 1px solid #f0f0f0;
    }

    .table-kardex tbody tr:hover { background: #faf9ff; }

    .badge-tipo {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-entrada { background: #c6f6d5; color: #22543d; }
    .badge-salida { background: #fed7d7; color: #742a2a; }

    .numero {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        text-align: right;
    }

    .num-entrada { color: #38a169; }
    .num-salida { color: #e53e3e; }
    .num-saldo {
        color: #2d3748;
        font-weight: 700;
        background: #f7fafc;
        padding: 4px 10px;
        border-radius: 6px;
        display: inline-block;
    }

    .trabajador-mini {
        background: #fff3bf;
        color: #7f6b0d;
        padding: 3px 9px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 600;
    }

    .empty {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .empty i {
        font-size: 60px;
        opacity: 0.3;
        margin-bottom: 15px;
        animation: floatIcon 3s ease-in-out infinite;
    }
    @keyframes floatIcon {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    @media (max-width: 768px) {
        .form-row { grid-template-columns: 1fr; }
        .filtros-fecha { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('contenido')

    <div class="breadcrumb">
        <a href="{{ route('reportes.index') }}"><i class="fas fa-arrow-left"></i> Reportes</a>
        <span>/</span>
        <span>Kardex por Producto</span>
    </div>

    {{-- SELECTOR DE ARTÍCULO --}}
    <div class="selector-card">
        <h2><i class="fas fa-clipboard-list"></i> Kardex por Producto</h2>
        <p style="color:#666; font-size:14px; margin-bottom:18px;">
            Selecciona un artículo para ver su historial detallado de entradas y salidas, con saldo acumulado.
        </p>

        <form method="GET" action="{{ route('reportes.kardex') }}" id="formSelector">
            <div class="form-row">
                <div class="form-field">
                    <label><i class="fas fa-box"></i> Artículo</label>
                    <select name="articuloId" onchange="seleccionarArticulo(this.value)" required>
                        <option value="">— Seleccionar artículo —</option>
                        @foreach($articulos as $art)
                            <option value="{{ $art->id }}" {{ $articulo && $articulo->id == $art->id ? 'selected' : '' }}>
                                {{ $art->codigo }} — {{ $art->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label><i class="fas fa-calendar"></i> Desde</label>
                    <input type="date" name="desde" value="{{ request('desde') }}">
                </div>
                <div class="form-field">
                    <label><i class="fas fa-calendar"></i> Hasta</label>
                    <input type="date" name="hasta" value="{{ request('hasta') }}">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Ver Kardex
                </button>
            </div>
        </form>
    </div>

    @if($articulo)

        {{-- TARJETA DEL ARTÍCULO --}}
        <div class="articulo-card">
            <div class="articulo-info">
                <h3>
                    <i class="fas fa-cube"></i>
                    <span class="articulo-codigo">{{ $articulo->codigo }}</span>
                    {{ $articulo->nombre }}
                </h3>
                <div class="articulo-meta">
                    <strong>Grupo:</strong> {{ $articulo->grupo_id }} — {{ $articulo->grupo->nombre ?? '' }} &nbsp;|&nbsp;
                    <strong>Unidad:</strong> {{ $articulo->unidad }} &nbsp;|&nbsp;
                    <strong>Precio:</strong> Bs. {{ number_format($articulo->precio, 2) }}
                </div>
            </div>
        </div>

        {{-- ESTADÍSTICAS ===== --}}
        <div class="stats-kardex">
            <div class="stat-kardex entradas">
                <div class="icono"><i class="fas fa-arrow-down"></i></div>
                <div class="label">Total Entradas</div>
                <div class="num">{{ number_format($estadisticas['total_entradas'], 2) }}</div>
                <div class="subtitulo">{{ $estadisticas['entradas_count'] }} movimientos</div>
            </div>

            <div class="stat-kardex salidas">
                <div class="icono"><i class="fas fa-arrow-up"></i></div>
                <div class="label">Total Salidas</div>
                <div class="num">{{ number_format($estadisticas['total_salidas'], 2) }}</div>
                <div class="subtitulo">{{ $estadisticas['salidas_count'] }} movimientos</div>
            </div>

            <div class="stat-kardex saldo">
                <div class="icono"><i class="fas fa-warehouse"></i></div>
                <div class="label">Stock Actual</div>
                <div class="num">{{ number_format($estadisticas['stock_actual'], 2) }}</div>
                <div class="subtitulo">{{ $articulo->unidad }}</div>
            </div>

            <div class="stat-kardex valor">
                <div class="icono"><i class="fas fa-dollar-sign"></i></div>
                <div class="label">Valor Stock</div>
                <div class="num">Bs. {{ number_format($estadisticas['valor_actual'], 2) }}</div>
                <div class="subtitulo">{{ number_format($estadisticas['stock_actual'], 2) }} × Bs. {{ number_format($articulo->precio, 2) }}</div>
            </div>
        </div>

        {{-- BOTONES DE DESCARGA --}}
        <div class="download-buttons">
            <a href="{{ route('reportes.kardex.excel', $articulo->id) }}?{{ http_build_query(request()->only(['desde', 'hasta'])) }}" class="btn btn-excel">
                <i class="fas fa-file-excel"></i> Descargar Excel
            </a>
            <a href="{{ route('reportes.kardex.pdf', $articulo->id) }}?{{ http_build_query(request()->only(['desde', 'hasta'])) }}" class="btn btn-pdf">
                <i class="fas fa-file-pdf"></i> Descargar PDF
            </a>
        </div>

        {{-- TABLA KARDEX --}}
        @if($movimientos->isEmpty())
            <div class="empty">
                <i class="fas fa-clipboard-list"></i>
                <p>Este artículo no tiene movimientos registrados{{ request('desde') || request('hasta') ? ' en este rango de fechas' : '' }}.</p>
            </div>
        @else
            <div class="table-kardex-container">
                <table class="table-kardex">
                    <thead>
                        <tr>
                            <th style="width:85px;">N° Nota</th>
                            <th style="width:100px;">Fecha</th>
                            <th style="width:90px;">Tipo</th>
                            <th style="text-align:right; width:110px;">Entrada</th>
                            <th style="text-align:right; width:110px;">Salida</th>
                            <th style="text-align:right; width:100px;">Precio Bs.</th>
                            <th style="text-align:right; width:120px;">Saldo</th>
                            <th>Entregado a</th>
                            <th>Notas</th>
                            <th style="width:110px;">Registró</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movimientos as $mov)
                            <tr>
                                <td>
                                    <span style="font-family:monospace; font-weight:bold; color:#667eea;">
                                        {{ $mov->numero_nota ?? '—' }}
                                    </span>
                                </td>
                                <td>{{ $mov->fecha->format('d/m/Y') }}</td>
                                <td>
                                    @if($mov->tipo === 'entrada')
                                        <span class="badge-tipo badge-entrada">
                                            <i class="fas fa-arrow-down"></i> Entrada
                                        </span>
                                    @else
                                        <span class="badge-tipo badge-salida">
                                            <i class="fas fa-arrow-up"></i> Salida
                                        </span>
                                    @endif
                                </td>
                                <td class="numero num-entrada">
                                    @if($mov->tipo === 'entrada')
                                        + {{ number_format($mov->cantidad, 2) }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="numero num-salida">
                                    @if($mov->tipo === 'salida')
                                        − {{ number_format($mov->cantidad, 2) }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="numero" style="color:#2b8a3e; font-weight:600;">
                                    Bs. {{ number_format($mov->precio_unitario ?? 0, 2) }}
                                </td>
                                <td class="numero">
                                    <span class="num-saldo">{{ number_format($mov->saldo_acumulado, 2) }}</span>
                                </td>
                                <td>
                                    @if($mov->trabajador)
                                        <span class="trabajador-mini">
                                            <i class="fas fa-hard-hat"></i> {{ $mov->trabajador->nombre }}
                                        </span>
                                    @else
                                        <span style="color:#bbb;">—</span>
                                    @endif
                                </td>
                                <td style="color:#666; font-size:12px;">{{ $mov->notas ?? '—' }}</td>
                                <td style="font-size:12px;">{{ $mov->user?->name ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <p style="text-align:center; color:#a0aec0; font-size:12px; margin-top:15px;">
                <i class="fas fa-info-circle"></i>
                Los movimientos están ordenados del más reciente al más antiguo.
                El "Saldo" es el stock acumulado después de cada movimiento.
            </p>
        @endif

    @else
        <div class="empty">
            <i class="fas fa-search"></i>
            <p>Selecciona un artículo del dropdown para ver su Kardex.</p>
        </div>
    @endif

@endsection

@push('scripts')
<script>
    function seleccionarArticulo(id) {
        if (!id) return;
        const desde = document.querySelector('input[name="desde"]').value;
        const hasta = document.querySelector('input[name="hasta"]').value;
        let url = '/reportes/kardex/' + id;
        const params = [];
        if (desde) params.push('desde=' + desde);
        if (hasta) params.push('hasta=' + hasta);
        if (params.length) url += '?' + params.join('&');
        window.location.href = url;
    }
</script>
@endpush
