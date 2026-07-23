@extends('layouts.mina')

@section('titulo', request('from') === 'inventario' ? 'Historial de Artículo' : 'Kardex por Producto')

@push('styles')
<style>
    .breadcrumb {
        display: flex;
        gap: 8px;
        font-size: 14px;
        color: #666;
        margin-bottom: 20px;
    }
    .breadcrumb a { color: var(--primary); text-decoration: none; }
    .breadcrumb a:hover { text-decoration: underline; }

    .selector-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 25px;
    }

    .selector-card h2 {
        color: var(--primary);
        font-size: 20px;
        font-weight: 700;
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
        font-size: 12px; font-weight: 600; color: var(--text-secondary);
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    .form-field input,
    .form-field select {
        padding: 11px 14px; border: 2px solid var(--border);
        background: var(--bg-card);
        color: var(--text-primary);
        border-radius: 8px; font-size: 14px;
        font-family: inherit;
        transition: all 0.2s;
    }
    .form-field input:focus,
    .form-field select:focus {
        outline: none; border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.1);
    }

    .btn {
        padding: 12px 20px; border: none; border-radius: 10px;
        font-size: 14px; font-weight: 700; cursor: pointer;
        transition: all 0.25s ease; display: inline-flex; align-items: center;
        justify-content: center;
        gap: 8px; text-decoration: none; color: white;
        font-family: inherit;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .btn-primary { 
        background: var(--gradient); 
        color: white; 
    }
    .btn-primary:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 6px 20px rgba(217, 119, 6, 0.3); 
    }
    .btn-secondary { 
        background: var(--bg-hover); 
        color: var(--text-secondary); 
        border: 1px solid var(--border);
    }
    .btn-excel { 
        background: linear-gradient(135deg, #16a34a 0%, #10b981 100%); 
        color: white;
    }
    .btn-excel:hover { 
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(22, 163, 74, 0.3);
    }
    .btn-pdf { 
        background: linear-gradient(135deg, #dc2626 0%, #f43f5e 100%); 
        color: white;
    }
    .btn-pdf:hover { 
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.3);
    }

    /* ===== TARJETA DEL ARTÍCULO ===== */
    .articulo-card {
        background: var(--gradient);
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
    .stat-kardex.saldo { border-color: var(--primary); }
    .stat-kardex.valor { border-color: #f59f00; }

    .stat-kardex .icono { font-size: 24px; margin-bottom: 8px; }
    .stat-kardex.entradas .icono { color: #38a169; }
    .stat-kardex.salidas .icono { color: #e53e3e; }
    .stat-kardex.saldo .icono { color: var(--primary); }
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
        background: var(--gradient);
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
        @if(request('from') === 'inventario')
            <a href="{{ route('inventario.index') }}"><i class="fas fa-arrow-left"></i> Inventario</a>
            <span>/</span>
            <span>Historial de Artículo</span>
        @else
            <a href="{{ route('reportes.index') }}?tab=kardex&articuloId={{ $articulo ? $articulo->id : '' }}&desde={{ request('desde') }}&hasta={{ request('hasta') }}&tipo={{ request('tipo') }}"><i class="fas fa-arrow-left"></i> Reportes</a>
            <span>/</span>
            <span>Kardex por Producto</span>
        @endif
    </div>

    @if($articulo)
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; flex-wrap: wrap; gap: 15px;">
            <div>
                @if(request('from') === 'inventario')
                    <h1 style="font-size: 24px; font-weight: 800; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-history" style="color: var(--primary);"></i> Historial de Movimientos
                    </h1>
                    <p style="font-size: 14px; color: var(--text-muted); margin: 4px 0 0 0;">
                        Registro cronológico completo de entradas y salidas para este artículo.
                    </p>
                @else
                    <h1 style="font-size: 24px; font-weight: 800; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-clipboard-list" style="color: var(--primary);"></i> Kardex por Producto
                    </h1>
                    <p style="font-size: 14px; color: var(--text-muted); margin: 4px 0 0 0;">
                        Visualización del Kardex valorado en pantalla.
                    </p>
                @endif
            </div>
            <div>
                {{-- Siempre mostrar botón para ir a Reportes completos con filtros y exportación --}}
                <a href="{{ route('reportes.index') }}?tab=kardex&articuloId={{ $articulo->id }}"
                   style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px;
                          background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
                          color: white; border-radius: 10px; font-weight: 700; font-size: 14px;
                          text-decoration: none; box-shadow: 0 4px 14px rgba(79,70,229,0.3);
                          transition: all 0.2s ease;"
                   onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(79,70,229,0.4)'"
                   onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 14px rgba(79,70,229,0.3)'">
                    <i class="fas fa-chart-bar"></i>
                    Reportes &amp; Exportar
                </a>
            </div>
        </div>
    @else
        {{-- SELECTOR DE ARTÍCULO (Solo se muestra si no hay ningún artículo seleccionado) --}}
        <div class="selector-card">
            <h2><i class="fas fa-clipboard-list"></i> Kardex por Producto</h2>
            <p style="color:#666; font-size:14px; margin-bottom:18px;">
                Selecciona un artículo para ver su historial detallado de entradas y salidas, con saldo acumulado.
            </p>

            <form method="GET" action="{{ route('reportes.kardex') }}" id="formSelector">
                <div class="form-row">
                    <div class="form-field">
                        <!-- Search input using native datalist -->
                        <input list="articulos-datalist" id="kardex_articulo_search" placeholder="🔍 Escribe código o nombre del artículo..." autocomplete="off" style="width: 100% !important; padding: 10px 14px !important; border: 1.5px solid var(--border) !important; border-radius: 8px !important; font-size: 14px !important; background: var(--bg-input) !important; color: var(--text-primary) !important;" required>
                        <datalist id="articulos-datalist">
                            @foreach($articulos as $art)
                                <option value="{{ $art->codigo }} — {{ $art->nombre }}" data-id="{{ $art->id }}"></option>
                            @endforeach
                        </datalist>

                        <select name="articuloId" id="articuloIdSelect" onchange="seleccionarArticulo(this.value)" required style="display: none;">
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
    @endif

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
                    <strong>Precio:</strong> 
                    @php
                        $preciosActivos = \App\Models\Movimiento::where('articulo_id', $articulo->id)
                            ->where('tipo', 'entrada')
                            ->where('cantidad_restante', '>', 0)
                            ->whereNotNull('precio_unitario')
                            ->orderBy('created_at', 'asc')
                            ->pluck('precio_unitario')
                            ->unique()
                            ->values();
                    @endphp
                    @if($preciosActivos->isNotEmpty())
                        {{ $preciosActivos->map(fn($p) => 'Bs. ' . number_format($p, 2))->join(', ') }}
                    @else
                        Bs. {{ number_format($articulo->precio, 2) }}
                    @endif
                </div>
                @if($articulo->notas)
                    <div style="margin-top: 14px; background: rgba(255, 255, 255, 0.15); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4); padding: 10px 16px; border-radius: 10px; font-size: 13.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); letter-spacing: 0.3px;">
                        <i class="fas fa-exclamation-circle" style="font-size: 14px; color: #ffe066;"></i>
                        <span><strong>Nota/Observación del Material:</strong> {{ $articulo->notas }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- ESTADÍSTICAS ===== --}}
        @php
            $mesActual = now()->month;
            $anioActual = now()->year;
            $entradasMes = $movimientos->filter(fn($m) =>
                $m->tipo === 'entrada' &&
                \Carbon\Carbon::parse($m->created_at)->month == $mesActual &&
                \Carbon\Carbon::parse($m->created_at)->year == $anioActual
            );
            $salidasMes = $movimientos->filter(fn($m) =>
                $m->tipo === 'salida' &&
                \Carbon\Carbon::parse($m->created_at)->month == $mesActual &&
                \Carbon\Carbon::parse($m->created_at)->year == $anioActual
            );
            $nombreMes = now()->translatedFormat('F');
        @endphp
        <div class="stats-kardex">
            <div class="stat-kardex entradas">
                <div class="icono"><i class="fas fa-arrow-down"></i></div>
                <div class="label">Entradas — {{ $nombreMes }}</div>
                <div class="num">{{ number_format($entradasMes->sum('cantidad'), 2) }}</div>
                <div class="subtitulo">{{ $entradasMes->count() }} movimientos este mes</div>
            </div>

            <div class="stat-kardex salidas">
                <div class="icono"><i class="fas fa-arrow-up"></i></div>
                <div class="label">Salidas — {{ $nombreMes }}</div>
                <div class="num">{{ number_format($salidasMes->sum('cantidad'), 2) }}</div>
                <div class="subtitulo">{{ $salidasMes->count() }} movimientos este mes</div>
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
                <div class="subtitulo">Suma real por lotes activos</div>
            </div>
        </div>

        {{-- VALORACIÓN DETALLADA DEL STOCK POR LOTE / PRECIO --}}
        @if(isset($historialPrecios) && $historialPrecios->count() > 0)
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 25px;">
            
            {{-- Encabezado --}}
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span style="background: var(--gradient); color: white; width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                        <i class="fas fa-tags"></i>
                    </span>
                    <div>
                        <h3 style="font-weight: 700; font-size: 16px; color: #1e293b; margin: 0;">Valoración Detallada del Stock</h3>
                        <p style="font-size: 12px; color: #64748b; margin: 4px 0 0 0;">
                            Precios unitarios activos y stock remanente por lote (Método FIFO)
                        </p>
                    </div>
                </div>
                <div style="font-size: 12px; color: #475569; background: #f1f5f9; padding: 6px 12px; border-radius: 20px; font-weight: 600;">
                    {{ $historialPrecios->count() === 1 ? '1 precio activo' : $historialPrecios->count() . ' precios activos' }}
                </div>
            </div>

            {{-- Tabla de desglose --}}
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
                    <thead>
                        <tr style="border-bottom: 2px solid #cbd5e1; color: #475569; font-weight: 700;">
                            <th style="padding: 10px 12px; font-weight: 600;">Precio Unitario</th>
                            <th style="padding: 10px 12px; font-weight: 600; text-align: right;">Stock Disponible</th>
                            <th style="padding: 10px 12px; font-weight: 600; text-align: center;">Movimientos</th>
                            <th style="padding: 10px 12px; font-weight: 600;">Rango de Ingreso</th>
                            <th style="padding: 10px 12px; font-weight: 600; text-align: right;">Total Invertido</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $sumTotal = 0; @endphp
                        @foreach($historialPrecios as $lote)
                            @php
                                $totalLote = $lote['precio'] * $lote['cantidad'];
                                $sumTotal += $totalLote;
                            @endphp
                            <tr style="border-bottom: 1px solid #e2e8f0; color: #334155;">
                                <td style="padding: 12px; font-weight: 700; font-family: 'Courier New', monospace; font-size: 14px; color: #4f46e5;">
                                    Bs. {{ number_format($lote['precio'], 2) }}
                                </td>
                                <td style="padding: 12px; text-align: right; font-weight: 600; font-family: 'Courier New', monospace;">
                                    {{ number_format($lote['cantidad'], 3) }} {{ $articulo->unidad }}
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    <span style="background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 600;">
                                        {{ $lote['veces'] }} {{ $lote['veces'] === 1 ? 'entrada' : 'entradas' }}
                                    </span>
                                    @if($lote['notas']->count() > 0)
                                        <div style="font-size: 10px; color: #64748b; margin-top: 4px; font-family: monospace;">
                                            Notas: {{ $lote['notas']->map(fn($n) => '#'.$n)->implode(', ') }}
                                        </div>
                                    @endif
                                </td>
                                <td style="padding: 12px; color: #64748b; font-size: 12px;">
                                    @if($lote['primera'] === $lote['ultima'])
                                        {{ $lote['primera'] }}
                                    @else
                                        {{ $lote['primera'] }} al {{ $lote['ultima'] }}
                                    @endif
                                </td>
                                <td style="padding: 12px; text-align: right; font-weight: 700; font-family: 'Courier New', monospace; color: #0f766e;">
                                    Bs. {{ number_format($totalLote, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background: #f8fafc; font-size: 14px; font-weight: 800; border-top: 2px solid #94a3b8;">
                            <td style="padding: 14px 12px; color: #1e293b;">Suma Total:</td>
                            <td style="padding: 14px 12px; text-align: right; font-family: 'Courier New', monospace; color: #1e293b;">
                                {{ number_format($articulo->cantidad, 3) }} {{ $articulo->unidad }}
                            </td>
                            <td colspan="2"></td>
                            <td style="padding: 14px 12px; text-align: right; font-family: 'Courier New', monospace; color: #11998e; font-size: 16px;">
                                Bs. {{ number_format($sumTotal, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
        @endif



        {{-- TABLA KARDEX --}}
        @if($movimientos->isEmpty())
            <div class="empty">
                <i class="fas fa-clipboard-list"></i>
                <p>Este artículo no tiene movimientos registrados{{ request('desde') || request('hasta') ? ' en este rango de fechas' : '' }}.</p>
            </div>
        @else
            @php
                $sumaCantEntradas = 0;
                $sumaCantSalidas = 0;
                $sumaValorEntradas = 0;
                $sumaValorSalidas = 0;
                foreach($movimientos as $mov) {
                    $subtotal = ($mov->precio_unitario ?? 0) * $mov->cantidad;
                    if ($mov->tipo === 'entrada') {
                        $sumaCantEntradas += $mov->cantidad;
                        $sumaValorEntradas += $subtotal;
                    } else {
                        $sumaCantSalidas += $mov->cantidad;
                        $sumaValorSalidas += $subtotal;
                    }
                }
            @endphp
            <div class="table-kardex-container">
                <table class="table-kardex">
                    <thead>
                        <tr>
                            <th style="width:85px;">N° Nota</th>
                            <th style="width:130px;">Fecha y Hora</th>
                            <th style="width:90px;">Tipo</th>
                            <th style="text-align:right; width:100px;">Entrada</th>
                            <th style="text-align:right; width:100px;">Salida</th>
                            <th style="text-align:right; width:100px;">Precio Unit.</th>
                            <th style="text-align:right; width:110px;">Precio Total</th>
                            <th style="width:90px; text-align:center;">Turno</th>
                            <th style="text-align:right; width:100px;">Saldo</th>
                            <th style="width:130px;">Entregado A / Por</th>
                            <th>Notas</th>
                            <th style="width:110px;">Registró</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movimientos as $mov)
                            <tr>
                                <td>
                                    <span style="font-family:monospace; font-weight:bold; color:var(--primary);">
                                        {{ $mov->numero_nota ?? '—' }}
                                    </span>
                                </td>
                                <td>{{ $mov->created_at->format('d/m/Y H:i') }}</td>
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
                                <td class="numero" style="color:#1971c2; font-weight:600;">
                                    Bs. {{ number_format(($mov->precio_unitario ?? 0) * $mov->cantidad, 2) }}
                                </td>
                                <td style="text-align:center;">
                                    @if($mov->tipo === 'salida' && $mov->turno)
                                        @if($mov->turno === 'Primera')
                                            <span style="background:#fff9db; color:#b25e00; border:1px solid #ffe066; padding:4px 10px; border-radius:12px; font-size:11px; font-weight:700; display:inline-flex; align-items:center; gap:5px;">
                                                <i class="fas fa-sun"></i> Primera (Día)
                                            </span>
                                        @elseif($mov->turno === 'Segunda')
                                            <span style="background:#ffe8cc; color:#d9480f; border:1px solid #ffd8a8; padding:4px 10px; border-radius:12px; font-size:11px; font-weight:700; display:inline-flex; align-items:center; gap:5px;">
                                                <i class="fas fa-cloud-sun"></i> Segunda (Tarde)
                                            </span>
                                        @elseif($mov->turno === 'Tercera')
                                            <span style="background:#edf2ff; color:#364fc7; border:1px solid #bac8ff; padding:4px 10px; border-radius:12px; font-size:11px; font-weight:700; display:inline-flex; align-items:center; gap:5px;">
                                                <i class="fas fa-moon"></i> Tercera (Noche)
                                            </span>
                                        @else
                                            <span style="background:#f1f3f5; color:#495057; border:1px solid #dee2e6; padding:4px 10px; border-radius:12px; font-size:11px; font-weight:700; display:inline-flex; align-items:center; gap:5px;">
                                                {{ $mov->turno }}
                                            </span>
                                        @endif
                                    @else
                                        <span style="color:#bbb;">—</span>
                                    @endif
                                </td>
                                <td class="numero">
                                    <span class="num-saldo">{{ number_format($mov->saldo_acumulado, 2) }}</span>
                                </td>
                                <td>
                                    @if($mov->tipo === 'entrada')
                                        <span style="background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                                            <i class="fas fa-truck"></i> {{ $mov->entregado_por ?? '—' }} a {{ $mov->recibido_por ?? ($mov->user?->name ?? 'Almacén') }}
                                        </span>
                                    @else
                                        <div style="display: flex; flex-direction: column; gap: 4px; align-items: flex-start;">
                                            @if($mov->entregado_por)
                                                <span style="background: rgba(3,105,161,0.08); color: #0284c7; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                                                    <i class="fas fa-user-check"></i> {{ $mov->entregado_por }}
                                                </span>
                                            @endif
                                            @if($mov->trabajador)
                                                <span class="trabajador-mini">
                                                    <i class="fas fa-hard-hat"></i> {{ $mov->trabajador->nombre }}
                                                </span>
                                            @elseif($mov->trabajador_nombre)
                                                <span class="trabajador-mini">
                                                    <i class="fas fa-hard-hat"></i> {{ $mov->trabajador_nombre }}
                                                </span>
                                            @else
                                                <span style="color:#bbb;">—</span>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td style="color:#666; font-size:12px;">{{ $mov->notes ?? ($mov->notas ?? '—') }}</td>
                                <td style="font-size:12px;">{{ $mov->user?->name ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background: var(--bg-hover); font-weight: bold; border-top: 2px solid var(--border); border-bottom: 2px solid var(--border);">
                            <td colspan="3" style="text-align: right; padding: 12px; font-weight: 800; color: var(--text-primary);">TOTALES:</td>
                            <td style="text-align: right; padding: 12px; color: #16a34a; font-family: monospace; font-size: 14px; white-space: nowrap;">
                                @if(!request('tipo') || request('tipo') === 'entrada')
                                    + {{ number_format($sumaCantEntradas, 2) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td style="text-align: right; padding: 12px; color: #dc2626; font-family: monospace; font-size: 14px; white-space: nowrap;">
                                @if(!request('tipo') || request('tipo') === 'salida')
                                    − {{ number_format($sumaCantSalidas, 2) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td></td> <!-- 6: Precio Unit. -->
                            <td style="text-align: right; padding: 12px; font-family: monospace; font-size: 14px; white-space: nowrap;">
                                @if(!request('tipo') || request('tipo') === 'entrada')
                                    <div style="color: #16a34a; margin-bottom: 2px;" title="Total valor ingresos">
                                        Entrada: Bs. {{ number_format($sumaValorEntradas, 2) }}
                                    </div>
                                @endif
                                @if(!request('tipo') || request('tipo') === 'salida')
                                    <div style="color: #dc2626; font-weight: 800;" title="Total valor egresos (Gasto)">
                                        Salida: Bs. {{ number_format($sumaValorSalidas, 2) }}
                                    </div>
                                @endif
                            </td> <!-- 7: Precio Total -->
                            <td></td> <!-- 8: Turno -->
                            <td style="text-align: right; padding: 12px; font-family: monospace; font-weight: bold; font-size: 14px; white-space: nowrap; color: var(--text-primary);">
                                {{ number_format($articulo->cantidad, 2) }}
                            </td> <!-- 9: Saldo -->
                            <td colspan="3"></td> <!-- 10, 11, 12: Entregado, Notas, Registró -->
                        </tr>
                    </tfoot>
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

    // Buscador interactivo con datalist para Kardex
    const searchInput = document.getElementById('kardex_articulo_search');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
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
            
            if (selectedId) {
                seleccionarArticulo(selectedId);
            }
        });
    }
</script>
@endpush
