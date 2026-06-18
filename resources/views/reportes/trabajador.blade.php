@extends('layouts.mina')

@section('titulo', 'Historial: ' . $trabajador->nombre)

@push('styles')
<style>
    .page-title {
        color: #667eea;
        font-size: 24px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .breadcrumb {
        display: flex;
        gap: 8px;
        font-size: 14px;
        color: #666;
        margin-bottom: 20px;
    }
    .breadcrumb a {
        color: #667eea;
        text-decoration: none;
    }
    .breadcrumb a:hover { text-decoration: underline; }

    .trabajador-card {
        background: linear-gradient(135deg, #f5af19 0%, #f12711 100%);
        color: white;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }
    .trabajador-info h3 {
        font-size: 24px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .trabajador-meta {
        font-size: 13px;
        opacity: 0.95;
        line-height: 1.6;
    }
    .trabajador-meta strong { font-weight: 700; }
    .estado-trabajador {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        margin-left: 8px;
    }
    .estado-activo { background: #d3f9d8; color: #2b8a3e; }
    .estado-inactivo { background: rgba(255,255,255,0.25); }

    .stats-mini {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }
    .stat-mini {
        background: white;
        padding: 18px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border-top: 3px solid #667eea;
    }
    .stat-mini h4 {
        font-size: 12px;
        color: #666;
        margin-bottom: 6px;
        text-transform: uppercase;
    }
    .stat-mini .num {
        font-size: 28px;
        font-weight: bold;
        color: #667eea;
    }

    .filters {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        gap: 15px;
        align-items: end;
        flex-wrap: wrap;
    }

    .form-field { display: flex; flex-direction: column; gap: 5px; }
    .form-field label {
        font-size: 12px; font-weight: 600; color: #555;
        text-transform: uppercase;
    }
    .form-field input {
        padding: 9px 14px; border: 2px solid #e0e0e0;
        border-radius: 8px; font-size: 14px;
    }

    .btn {
        padding: 10px 18px; border: none; border-radius: 8px;
        font-size: 14px; font-weight: 500; cursor: pointer;
        transition: all 0.3s; display: inline-flex; align-items: center;
        gap: 6px; text-decoration: none; color: inherit;
    }
    .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .btn-secondary { background: #f0f0f0; color: #555; }
    .btn-excel { background: #1f7244; color: white; }
    .btn-pdf { background: #c0392b; color: white; }

    .download-buttons {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .table-container {
        background: white; border-radius: 10px;
        overflow-x: auto;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    table { width: 100%; border-collapse: collapse; }
    table th {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 14px 12px;
        text-align: left;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #e0e0e0;
        font-size: 13px;
        text-transform: uppercase;
    }
    table td { padding: 14px 12px; border-bottom: 1px solid #f0f0f0; }
    table tbody tr:hover { background: #faf9ff; }

    .codigo {
        font-family: 'Courier New', monospace;
        font-weight: bold;
        color: #667eea;
    }

    .empty {
        text-align: center; padding: 50px 20px; color: #999;
    }
    .empty i { font-size: 64px; opacity: 0.3; margin-bottom: 15px; }
</style>
@endpush

@section('contenido')

    <div class="breadcrumb">
        <a href="{{ route('trabajadores.index') }}"><i class="fas fa-arrow-left"></i> Volver a Trabajadores</a>
        <span>/</span>
        <span>Historial del Trabajador</span>
    </div>

    {{-- TARJETA DEL TRABAJADOR --}}
    <div class="trabajador-card">
        <div class="trabajador-info">
            <h3>
                <i class="fas fa-hard-hat"></i>
                {{ $trabajador->nombre }}
                @if($trabajador->activo)
                    <span class="estado-trabajador estado-activo">ACTIVO</span>
                @else
                    <span class="estado-trabajador estado-inactivo">INACTIVO</span>
                @endif
            </h3>
            <div class="trabajador-meta">
                <strong>CI:</strong> {{ $trabajador->ci }}<br>
                @if($trabajador->cargo)
                    <strong>Cargo:</strong> {{ $trabajador->cargo }}<br>
                @endif
                @if($trabajador->telefono)
                    <strong>Teléfono:</strong> {{ $trabajador->telefono }}<br>
                @endif
                <strong>Registrado:</strong> {{ $trabajador->created_at->format('d/m/Y') }}
            </div>
        </div>
    </div>

    {{-- ESTADÍSTICAS --}}
    <div class="stats-mini">
        <div class="stat-mini">
            <h4>Total Salidas</h4>
            <div class="num">{{ $totalSalidas }}</div>
        </div>
        <div class="stat-mini">
            <h4>Artículos Únicos</h4>
            <div class="num">{{ $articulosUnicos }}</div>
        </div>
    </div>

    {{-- FILTROS DE FECHA --}}
    <form method="GET" action="{{ route('reportes.trabajador', $trabajador) }}" class="filters">
        <div class="form-field">
            <label><i class="fas fa-calendar"></i> Desde</label>
            <input type="date" name="desde" value="{{ request('desde') }}">
        </div>
        <div class="form-field">
            <label><i class="fas fa-calendar"></i> Hasta</label>
            <input type="date" name="hasta" value="{{ request('hasta') }}">
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-filter"></i> Filtrar
        </button>
        <a href="{{ route('reportes.trabajador', $trabajador) }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> Limpiar
        </a>
    </form>

    {{-- DESCARGAR REPORTES --}}
    <div class="download-buttons">
        <a href="{{ route('reportes.trabajador.excel', $trabajador) }}?{{ http_build_query(request()->only(['desde', 'hasta'])) }}" class="btn btn-excel">
            <i class="fas fa-file-excel"></i> Descargar Excel
        </a>
        <a href="{{ route('reportes.trabajador.pdf', $trabajador) }}?{{ http_build_query(request()->only(['desde', 'hasta'])) }}" class="btn btn-pdf">
            <i class="fas fa-file-pdf"></i> Descargar PDF
        </a>
    </div>

    {{-- TABLA DE SALIDAS --}}
    @if($movimientos->isEmpty())
        <div class="empty">
            <i class="fas fa-inbox"></i>
            <p>Este trabajador no tiene salidas registradas{{ request('desde') || request('hasta') ? ' en este rango de fechas' : '' }}.</p>
        </div>
    @else
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width:100px;">Fecha</th>
                        <th style="width:110px;">Código</th>
                        <th>Artículo</th>
                        <th style="width:130px;">Cantidad</th>
                        <th>Notas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movimientos as $mov)
                        <tr>
                            <td>{{ $mov->fecha->format('d/m/Y') }}</td>
                            <td><span class="codigo">{{ $mov->articulo->codigo }}</span></td>
                            <td>{{ $mov->articulo->nombre }}</td>
                            <td>
                                <strong>{{ number_format($mov->cantidad, 2) }}</strong>
                                <span style="color:#999; font-size:12px;">{{ $mov->articulo->unidad }}</span>
                            </td>
                            <td style="color:#666; font-size:13px;">{{ $mov->notas ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top:20px; display:flex; justify-content:center;">
            {{ $movimientos->links() }}
        </div>
    @endif

@endsection