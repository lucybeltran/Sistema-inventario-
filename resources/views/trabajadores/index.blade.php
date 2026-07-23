@extends('layouts.mina')

@section('titulo', 'Trabajadores')

@push('styles')
<style>
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

    /* ===== CAMBIO 1: page-counter más vibrante ===== */
    .page-counter {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }

    /* ===== CAMBIO 2: badge-activos más vibrante ===== */
    .badge-activos {
        background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
        color: white;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(56, 161, 105, 0.3);
    }

    .filters {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: grid;
        grid-template-columns: 2fr 1fr auto;
        gap: 12px;
        align-items: end;
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

    /* ===== CAMBIO 3: btn-primary más vivo ===== */
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 3px 10px rgba(102, 126, 234, 0.35);
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(102, 126, 234, 0.5);
    }

    .btn-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; }
    .btn-success:hover { transform: translateY(-2px); }
    .btn-secondary { background: #f0f0f0; color: #555; }


    .btn-historial-mini {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: #f1f3f5;
        color: #495057;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid #dee2e6;
    }

    .btn-historial-mini:hover {
        background: #6366f1;
        color: white;
        border-color: #6366f1;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(99, 102, 241, 0.25);
    }

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

    .estado-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }
    .estado-activo { background: #d3f9d8; color: #2b8a3e; }
    .estado-inactivo { background: #ffe3e3; color: #862e2e; }

    .ci-badge {
        font-family: 'Courier New', monospace;
        font-weight: bold;
        color: #667eea;
    }

    .cargo-tag {
        background: #e9ecef; color: #495057;
        padding: 3px 10px; border-radius: 10px;
        font-size: 11px; font-weight: 600;
    }

    .acciones-cell {
        display: flex !important;
        gap: 6px !important;
        align-items: center !important;
        justify-content: center !important;
    }
    .acciones-cell button,
    .acciones-cell form button {
        width: 32px !important;
        height: 32px !important;
        padding: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        border: 1px solid transparent !important;
        border-radius: 8px !important;
        cursor: pointer !important;
        font-size: 13px !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
        flex-shrink: 0 !important;
        margin: 0 !important;
        position: relative !important;
    }
    .acciones-cell button:hover,
    .acciones-cell form button:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15) !important;
    }
    .acciones-cell button:active,
    .acciones-cell form button:active {
        transform: translateY(0) !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
    }
    
    /* Colores personalizados para los botones de acción del personal */
    .acciones-cell .btn-edit {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
        color: #ffffff !important;
        border-color: rgba(217, 119, 6, 0.2) !important;
    }
    .acciones-cell .btn-edit:hover {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%) !important;
    }
    
    .acciones-cell .btn-eliminar-trab {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        color: #ffffff !important;
        border-color: rgba(220, 38, 38, 0.2) !important;
    }
    .acciones-cell .btn-eliminar-trab:hover {
        background: linear-gradient(135deg, #f87171 0%, #ef4444 100%) !important;
    }
    
    .acciones-cell .btn-toggle-off {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%) !important;
        color: #ffffff !important;
        border-color: rgba(71, 85, 105, 0.2) !important;
    }
    .acciones-cell .btn-toggle-off:hover {
        background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%) !important;
    }
    
    .acciones-cell .btn-toggle-on {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        color: #ffffff !important;
        border-color: rgba(5, 150, 105, 0.2) !important;
    }
    .acciones-cell .btn-toggle-on:hover {
        background: linear-gradient(135deg, #34d399 0%, #10b981 100%) !important;
    }

    table th.col-acciones {
        text-align: center !important;
    }
    table td.col-acciones-td {
        text-align: center !important;
    }

    .empty { text-align: center; padding: 50px 20px; color: #999; }
    .empty i { font-size: 64px; opacity: 0.3; margin-bottom: 15px; }

    /* MODAL */
    .modal {
        display: none;
        position: fixed; top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(4px);
        z-index: 1000;
        align-items: center; justify-content: center;
        transition: all 0.25s ease-in-out;
    }
    .modal.active { display: flex; }
    .modal-content {
        background: white; border-radius: 20px; padding: 32px;
        max-width: 680px; width: 95%;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: 1px solid rgba(226, 232, 240, 0.8);
        animation: modalFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes modalFadeIn {
        from { transform: translateY(15px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .modal-header {
        font-size: 20px; font-weight: 700; margin-bottom: 24px;
        display: flex; justify-content: space-between; align-items: center;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 16px;
    }
    .modal-close {
        background: #f1f5f9; border: none; font-size: 20px;
        cursor: pointer; color: #64748b;
        width: 32px; height: 32px; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        transition: all 0.2s;
    }
    .modal-close:hover {
        background: #ffe3e3; color: #e03131;
    }
    
    .modal-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    /* DYNAMIC WORKPLACES SECTIONS */
    .lugares-seccion {
        grid-column: span 2;
        border-top: 1px solid #e2e8f0;
        padding-top: 20px;
        margin-top: 10px;
    }
    .lugares-seccion-titulo {
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .lugar-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
        position: relative;
        display: grid;
        grid-template-columns: 1fr 1.2fr 1.5fr;
        gap: 12px;
        align-items: end;
    }
    .btn-remove-lugar {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #ef4444;
        color: white;
        border: none;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 11px;
        box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
        transition: all 0.2s;
        z-index: 10;
    }
    .btn-remove-lugar:hover {
        background: #dc2626;
        transform: scale(1.1);
    }
    .btn-add-lugar {
        background: #6366f1;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    .btn-add-lugar:hover {
        background: #4f46e5;
    }
    
    .modal-header span {
        color: #0f172a;
        font-weight: 700;
        font-size: 18px;
    }
    .modal-header span i {
        color: #6366f1 !important; /* Premium Indigo Icon instead of Orange */
        margin-right: 8px;
    }
    .btn-success {
        background: #6366f1 !important; /* Premium Indigo theme button */
        border-color: #6366f1 !important;
        color: white !important;
    }
    .btn-success:hover {
        background: #4f46e5 !important;
        border-color: #4f46e5 !important;
    }
    
    .form-group { margin-bottom: 0px; }
    .form-group label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        font-weight: 600;
        color: #475569;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .form-group input {
        width: 100%; padding: 12px 14px;
        border: 2px solid #e2e8f0; border-radius: 10px;
        font-size: 14px;
        background: #f8fafc;
        color: #1e293b;
        transition: all 0.15s ease-in-out;
    }
    .form-group input:focus {
        border-color: #667eea;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
        outline: none;
    }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
    .modal-footer { display: flex; gap: 12px; margin-top: 28px; grid-column: span 2; }

    .error-list {
        background: #ffe3e3; border-left: 4px solid #ff6b6b;
        padding: 12px 16px; border-radius: 8px; margin-bottom: 18px;
        color: #862e2e; font-size: 13px;
    }

    @media (max-width: 640px) {
        .filters { grid-template-columns: 1fr; }
        .modal-form-grid { grid-template-columns: 1fr; gap: 16px; }
        .modal-footer { grid-column: span 1; }
    }
</style>
@endpush

@section('contenido')

    <div class="page-header">
        <h2 class="page-title">
            <i class="fas fa-hard-hat"></i> Contratistas / Personal de Mina
            <span class="page-counter">{{ $total }} en total</span>
            <span class="badge-activos">
                <i class="fas fa-check-circle"></i> {{ $activos }} activos
            </span>
        </h2>
        @if(Auth::user()->puedeEditar())
            <button class="btn btn-success" onclick="abrirModalNuevo()">
                <i class="fas fa-user-plus"></i> Agregar Contratista
            </button>
        @endif
    </div>

    <form method="GET" action="{{ route('trabajadores.index') }}" class="filters">
        <div class="form-field">
            <label><i class="fas fa-search"></i> Buscar</label>
            <input type="text" name="buscar" placeholder="Contratista, ayudante, cargo..." value="{{ request('buscar') }}">
        </div>
        <div class="form-field">
            <label><i class="fas fa-filter"></i> Estado</label>
            <select name="estado">
                <option value="">Todos</option>
                <option value="activos" {{ request('estado') == 'activos' ? 'selected' : '' }}>Solo activos</option>
                <option value="inactivos" {{ request('estado') == 'inactivos' ? 'selected' : '' }}>Solo inactivos</option>
            </select>
        </div>
        <div class="btn-group">
            <a href="{{ route('trabajadores.index') }}" class="btn btn-secondary" title="Limpiar filtros" style="display: inline-flex; align-items: center; gap: 6px;">
                <i class="fas fa-times"></i> Limpiar
            </a>
        </div>
    </form>

    <div id="trabajadores-table-wrapper" style="transition: opacity 0.15s ease-in-out;">
        @if($trabajadores->isEmpty())
            <div class="empty">
                <i class="fas fa-hard-hat"></i>
                <p>No hay trabajadores registrados.</p>
                <p style="font-size:13px; margin-top:8px;">
                    Click en "Agregar Trabajador" para empezar.
                </p>
            </div>
        @else
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 100px;">Código</th>
                            <th>Contratista</th>
                            <th style="width: 160px;">Ayudante</th>
                            <th style="width: 120px;">Cargo</th>
                            <th style="width: 130px;">Nivel</th>
                            <th style="width: 160px;">Labor / Trabajo</th>
                            <th style="width: 180px;">Ubicación</th>
                            <th style="width: 90px;">Estado</th>
                            <th style="width: 120px;">Historial</th>
                            @if(Auth::user()->puedeEditar())
                                <th style="width: 160px; min-width: 160px;" class="col-acciones">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trabajadores as $t)
                            <tr>
                                <td>
                                    @if($t->codigo)
                                        <span style="display: inline-block; white-space: nowrap; font-weight: 700; color: #6366f1; font-family: monospace; font-size: 13px; background: rgba(99, 102, 241, 0.08); padding: 3px 8px; border-radius: 6px; border: 1px solid rgba(99, 102, 241, 0.15);">{{ $t->codigo }}</span>
                                    @else
                                        <span style="color: #bbb; font-style: italic; font-size: 12px;">Sin código</span>
                                    @endif
                                </td>
                                <td><strong>{{ $t->nombre }}</strong></td>
                                <td>
                                    @if($t->ayudante)
                                        <span style="color:#495057; font-weight: 500;">
                                            <i class="fas fa-user-friends" style="color:#667eea; margin-right:4px;"></i>{{ $t->ayudante }}
                                        </span>
                                    @else
                                        <span style="color:#bbb; font-style:italic; font-size:12px;">Sin ayudante</span>
                                    @endif
                                </td>
                                <td>
                                    @if($t->cargo)
                                        <span class="cargo-tag">{{ $t->cargo }}</span>
                                    @else
                                        <span style="color:#999;">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($t->nivel)
                                        <div style="display: flex; flex-direction: column; gap: 4px; align-items: flex-start;">
                                            @foreach(explode(',', $t->nivel) as $lvl)
                                                @if(trim($lvl) !== '')
                                                    <span style="background: rgba(245, 159, 0, 0.08); color: #b45309; border: 1px solid rgba(245, 159, 0, 0.2); padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;">
                                                        <i class="fas fa-layer-group"></i> {{ trim($lvl) }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <span style="color:#bbb; font-style:italic; font-size:12px;">No especificado</span>
                                    @endif
                                </td>
                                <td>
                                    @if($t->labor)
                                        <div style="display: flex; flex-direction: column; gap: 4px; align-items: flex-start;">
                                            @foreach(explode(',', $t->labor) as $lab)
                                                @if(trim($lab) !== '')
                                                    <span style="background: rgba(59, 91, 219, 0.08); color: #3b5bdb; border: 1px solid rgba(59, 91, 219, 0.2); padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;">
                                                        <i class="fas fa-hammer"></i> {{ trim($lab) }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <span style="color:#bbb; font-style:italic; font-size:12px;">No especificado</span>
                                    @endif
                                </td>
                                <td>
                                    @if($t->area_trabajo)
                                        <div style="display: flex; flex-direction: column; gap: 4px; align-items: flex-start;">
                                            @foreach(explode(',', $t->area_trabajo) as $loc)
                                                @if(trim($loc) !== '')
                                                    <span style="background: rgba(224, 49, 49, 0.08); color: #e03131; border: 1px solid rgba(224, 49, 49, 0.2); padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;">
                                                        <i class="fas fa-map-marker-alt"></i> {{ trim($loc) }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <span style="color:#bbb; font-style:italic; font-size:12px;">No especificado</span>
                                    @endif
                                </td>
                                <td>
                                    @if($t->activo)
                                        <span class="estado-badge estado-activo">
                                            <i class="fas fa-check"></i> Activo
                                        </span>
                                    @else
                                        <span class="estado-badge estado-inactivo">
                                            <i class="fas fa-times"></i> Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('reportes.trabajador', $t) }}"
                                       class="btn-historial-mini"
                                       title="Ver historial de {{ $t->nombre }}">
                                        <i class="fas fa-history"></i>
                                    </a>
                                </td>
                                @if(Auth::user()->puedeEditar())
                                    <td class="col-acciones-td">
                                        <div class="acciones-cell">
                                            <button class="btn-edit"
                                                    onclick="abrirModalEditar({{ $t->id }}, '{{ addslashes($t->nombre) }}', '{{ addslashes($t->codigo ?? '') }}', '{{ addslashes($t->cargo ?? '') }}', '{{ addslashes($t->nivel ?? '') }}', '{{ addslashes($t->labor ?? '') }}', '{{ addslashes($t->area_trabajo ?? '') }}', '{{ addslashes($t->ayudante ?? '') }}')"
                                                    title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @if(Auth::user()->esAdmin())
                                                {{-- ELIMINAR (solo admin) --}}
                                                <button type="button"
                                                        class="btn-eliminar-trab"
                                                        title="Eliminar contratista"
                                                        onclick="abrirModalEliminar({{ $t->id }}, '{{ addslashes($t->nombre) }}', '{{ $t->ci }}', {{ $t->movimientos_count }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                            @if(Auth::user()->esAdmin())
                                                @if($t->activo)
                                                    <button type="button"
                                                            class="btn-toggle-off"
                                                            title="Desactivar"
                                                            onclick="abrirModalDesactivar({{ $t->id }}, '{{ addslashes($t->nombre) }}', '{{ $t->ci }}', {{ $t->movimientos_count }})">
                                                        <i class="fas fa-user-slash"></i>
                                                    </button>
                                                @else
                                                    <form method="POST" action="{{ route('trabajadores.toggle', $t) }}" style="display:inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                                class="btn-toggle-on"
                                                                title="Activar"
                                                                onclick="return confirm('¿Activar a {{ $t->nombre }}?')">
                                                            <i class="fas fa-user-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
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
                {{ $trabajadores->links() }}
            </div>
        @endif
    </div>

    @if(Auth::user()->puedeEditar())

    {{-- MODAL: NUEVO TRABAJADOR --}}
    <div class="modal {{ $errors->any() && old('_form') == 'nuevo' ? 'active' : '' }}" id="modalNuevo">
        <div class="modal-content">
            <div class="modal-header">
                <span><i class="fas fa-user-plus"></i> Agregar Nuevo Contratista / Personal</span>
                <button class="modal-close" onclick="cerrarModales()">&times;</button>
            </div>

            @if($errors->any() && old('_form') == 'nuevo')
                <div class="error-list">
                    <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('trabajadores.store') }}">
                @csrf
                <input type="hidden" name="_form" value="nuevo">

                <div class="modal-form-grid">
                    <div class="form-group">
                        <label style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 4px; width: 100%;">
                            <span><i class="fas fa-id-card" style="color: #6366f1;"></i> Código de Contratista</span>
                            <span style="font-size: 11px; text-transform: none; color: #4f46e5; font-weight: 500; background: rgba(99, 102, 241, 0.08); padding: 2px 8px; border-radius: 6px; border: 1px solid rgba(99, 102, 241, 0.12); display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;">
                                Sugerido: <strong style="color: #4f46e5; font-family: monospace; font-weight: 700;">{{ $siguienteCodigo }}</strong>
                            </span>
                        </label>
                        <input type="text" name="codigo" value="{{ old('codigo', $siguienteCodigo) }}" placeholder="Ej: {{ $siguienteCodigo }}" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-briefcase" style="color: #fab005;"></i> Cargo</label>
                        <input type="text" name="cargo" value="{{ old('cargo') }}" placeholder="Contratista, Perforista, Capataz...">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-user" style="color: #6366f1;"></i> Nombre completo del contratista</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Juan Pérez Mamani" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-user-friends" style="color: #4c6ef5;"></i> Nombre del ayudante</label>
                        <input type="text" name="ayudante" value="{{ old('ayudante') }}" placeholder="Ej: Pedro Colque">
                    </div>

                    <div class="lugares-seccion">
                        <div class="lugares-seccion-titulo">
                            <span><i class="fas fa-map-marked-alt" style="color:#6366f1; margin-right:6px;"></i> Lugares de Trabajo Asignados</span>
                            <button type="button" class="btn-add-lugar" onclick="agregarLugarNuevo()">
                                <i class="fas fa-plus"></i> Añadir Lugar
                            </button>
                        </div>
                        <div id="nuevo_lugares_container">
                            {{-- Se rellenará dinámicamente --}}
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" style="flex:1; justify-content:center;">
                            <i class="fas fa-save"></i> Guardar Contratista
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="cerrarModales()" style="flex:1; justify-content:center;">
                            Cancelar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: EDITAR TRABAJADOR --}}
    <div class="modal {{ $errors->any() && old('_form') == 'editar' ? 'active' : '' }}" id="modalEditar">
        <div class="modal-content">
            <div class="modal-header">
                <span><i class="fas fa-edit"></i> Editar Contratista</span>
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

                <div class="modal-form-grid">
                    <div class="form-group">
                        <label><i class="fas fa-id-card" style="color: #6366f1;"></i> Código de Contratista</label>
                        <input type="text" name="codigo" id="edit_codigo" placeholder="Ej: CON-01" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-briefcase" style="color: #fab005;"></i> Cargo</label>
                        <input type="text" name="cargo" id="edit_cargo">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-user" style="color: #6366f1;"></i> Nombre completo del contratista</label>
                        <input type="text" name="nombre" id="edit_nombre" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-user-friends" style="color: #4c6ef5;"></i> Nombre del ayudante</label>
                        <input type="text" name="ayudante" id="edit_ayudante">
                    </div>

                    <div class="lugares-seccion">
                        <div class="lugares-seccion-titulo">
                            <span><i class="fas fa-map-marked-alt" style="color:#6366f1; margin-right:6px;"></i> Lugares de Trabajo Asignados</span>
                            <button type="button" class="btn-add-lugar" onclick="agregarLugarEditar()">
                                <i class="fas fa-plus"></i> Añadir Lugar
                            </button>
                        </div>
                        <div id="editar_lugares_container">
                            {{-- Se rellenará dinámicamente --}}
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" style="flex:1; justify-content:center;">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="cerrarModales()" style="flex:1; justify-content:center;">
                            Cancelar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(Auth::user()->esAdmin())
    {{-- MODAL: CONFIRMAR DESACTIVAR TRABAJADOR --}}
    <div class="modal" id="modalDesactivar">
        <div class="modal-content" style="max-width:480px;">
            <div class="modal-header" style="color:#e03131;">
                <span><i class="fas fa-exclamation-triangle"></i> Desactivar Trabajador</span>
                <button class="modal-close" onclick="cerrarModales()">&times;</button>
            </div>

            <p style="color:#495057; font-size:14px; margin-bottom:16px;">
                Estás por desactivar a:
            </p>

            <div style="background:#fff5f5; border-left:4px solid #e03131; padding:14px 16px; border-radius:8px; margin-bottom:16px;">
                <div style="font-weight:700; color:#2d3748; font-size:16px;" id="desac_nombre">—</div>
                <div style="font-size:12px; color:#868e96; margin-top:4px;">
                    CI: <span id="desac_ci">—</span>
                </div>
                <div style="font-size:13px; color:#495057; margin-top:8px;">
                    <i class="fas fa-exchange-alt" style="color:#e03131;"></i>
                    Movimientos registrados: <strong id="desac_movimientos">0</strong>
                </div>
            </div>

            <div style="background:#fff8e1; border-left:4px solid #f59f00; padding:12px 14px; border-radius:8px; font-size:12px; color:#7c2d12; line-height:1.6; margin-bottom:20px;">
                <i class="fas fa-info-circle" style="color:#f59f00;"></i>
                El trabajador <strong>no se borra</strong>, solo se desactiva.
                No podrá recibir nuevas entregas hasta que lo reactives.
                Su historial de movimientos se conserva.
            </div>

            <form method="POST" action="" id="formDesactivar">
                @csrf
                @method('PATCH')
                <div style="display:flex; gap:10px;">
                    <button type="button" class="btn btn-secondary" onclick="cerrarModales()" style="flex:1; justify-content:center;">
                        Cancelar
                    </button>
                    <button type="submit" class="btn" style="flex:1; justify-content:center; background:#e03131; color:white;">
                        <i class="fas fa-user-slash"></i> Sí, desactivar
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

@endif

@if(Auth::user()->esAdmin())
{{-- MODAL: ELIMINAR TRABAJADOR --}}
<div class="modal" id="modalEliminar">
    <div class="modal-content" style="max-width:520px;">
        <div class="modal-header" style="color:#e03131;">
            <span><i class="fas fa-trash"></i> Eliminar Trabajador</span>
            <button class="modal-close" onclick="cerrarModales()">&times;</button>
        </div>

        <p style="color:#495057; font-size:14px; margin-bottom:16px;">
            Estás por <strong>ELIMINAR PERMANENTEMENTE</strong> a:
        </p>

        <div style="background:#fff5f5; border-left:4px solid #e03131; padding:14px 16px; border-radius:8px; margin-bottom:16px;">
            <div style="font-weight:700; color:#2d3748; font-size:16px;" id="elim_nombre">—</div>
            <div style="font-size:12px; color:#868e96; margin-top:4px;">
                CI: <span id="elim_ci">—</span>
            </div>
            <div style="font-size:13px; color:#495057; margin-top:8px;">
                <i class="fas fa-exchange-alt" style="color:#e03131;"></i>
                Movimientos: <strong id="elim_movimientos">0</strong>
            </div>
        </div>

        {{-- Aviso si tiene movimientos --}}
        <div id="aviso_con_movimientos" style="display:none;">
            <div style="background:#fff3bf; border-left:4px solid #f59f00; padding:12px 14px; border-radius:8px; font-size:13px; color:#7c2d12; line-height:1.6; margin-bottom:16px;">
                <strong><i class="fas fa-exclamation-triangle"></i> IMPORTANTE:</strong>
                Este trabajador tiene movimientos registrados. Antes de eliminarlo, <strong>descarga su reporte PDF</strong> para conservar evidencia (control de explosivos).
            </div>
            <a href="#" id="btn_descargar_reporte" target="_blank" class="btn" style="background:#1971c2; color:white; width:100%; justify-content:center; margin-bottom:12px;">
                <i class="fas fa-file-pdf"></i> Descargar Reporte PDF
            </a>
            <label style="display:flex; align-items:center; gap:8px; font-size:13px; color:#495057; margin-bottom:16px;">
                <input type="checkbox" id="check_descargado">
                Confirmo que descargué el reporte
            </label>
        </div>

        <div style="background:#ffe3e3; border-left:4px solid #c92a2a; padding:12px 14px; border-radius:8px; font-size:12px; color:#862e2e; line-height:1.6; margin-bottom:20px;">
            <i class="fas fa-info-circle"></i>
            El trabajador se elimina, pero <strong>su nombre se conserva</strong> en los movimientos pasados (para trazabilidad legal). Esta acción <strong>no se puede deshacer</strong>.
        </div>

        <form method="POST" action="" id="formEliminar">
            @csrf
            @method('DELETE')
            <div style="display:flex; gap:10px;">
                <button type="button" class="btn btn-secondary" onclick="cerrarModales()" style="flex:1; justify-content:center;">
                    Cancelar
                </button>
                <button type="submit" id="btn_confirmar_eliminar" class="btn" style="flex:1; justify-content:center; background:#e03131; color:white;" disabled>
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
    function abrirModalNuevo() {
        // Limpiar contenedor y añadir primer lugar por defecto
        const container = document.getElementById('nuevo_lugares_container');
        container.innerHTML = '';
        agregarLugarNuevo();
        document.getElementById('modalNuevo').classList.add('active');
    }
    
    function crearLugarCardHTML(nivel = '', labor = '', ubicacion = '', isFirst = false) {
        return `
            <div class="lugar-card">
                ${!isFirst ? `
                <button type="button" class="btn-remove-lugar" onclick="this.parentElement.remove()" title="Eliminar este lugar de trabajo">
                    <i class="fas fa-times"></i>
                </button>
                ` : ''}
                <div class="form-group">
                    <label><i class="fas fa-layer-group" style="color: #e64980;"></i> Nivel</label>
                    <input type="text" name="nivel[]" value="${nivel}" placeholder="Ej: Nivel -160" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-hammer" style="color: #12b886;"></i> Labor</label>
                    <input type="text" name="labor[]" value="${labor}" placeholder="Ej: Desarrollos Primarios" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-map-marker-alt" style="color: #fa5252;"></i> Ubicación</label>
                    <input type="text" name="area_trabajo[]" value="${ubicacion}" placeholder="Ej: Profundización Cuadro" required>
                </div>
            </div>
        `;
    }
    
    function agregarLugarNuevo(nivel = '', labor = '', ubicacion = '') {
        const container = document.getElementById('nuevo_lugares_container');
        const isFirst = container.children.length === 0;
        const html = crearLugarCardHTML(nivel, labor, ubicacion, isFirst);
        container.insertAdjacentHTML('beforeend', html);
    }

    function agregarLugarEditar(nivel = '', labor = '', ubicacion = '') {
        const container = document.getElementById('editar_lugares_container');
        const isFirst = container.children.length === 0;
        const html = crearLugarCardHTML(nivel, labor, ubicacion, isFirst);
        container.insertAdjacentHTML('beforeend', html);
    }

    function abrirModalEditar(id, nombre, codigo, cargo, nivel, labor, area_trabajo, ayudante) {
        document.getElementById('formEditar').action = '/trabajadores/' + id;
        document.getElementById('edit_nombre').value = nombre;
        document.getElementById('edit_codigo').value = codigo;
        document.getElementById('edit_cargo').value = cargo;
        document.getElementById('edit_ayudante').value = ayudante;
        
        // Limpiar contenedor de edición
        const container = document.getElementById('editar_lugares_container');
        container.innerHTML = '';
        
        // Separar cadenas e insertar cada lugar
        const niveles = nivel ? nivel.split(',').map(s => s.trim()) : [];
        const labores = labor ? labor.split(',').map(s => s.trim()) : [];
        const ubicaciones = area_trabajo ? area_trabajo.split(',').map(s => s.trim()) : [];
        
        const count = Math.max(niveles.length, labores.length, ubicaciones.length, 1);
        for (let i = 0; i < count; i++) {
            agregarLugarEditar(niveles[i] || '', labores[i] || '', ubicaciones[i] || '');
        }
        
        document.getElementById('modalEditar').classList.add('active');
    }
    function abrirModalDesactivar(id, nombre, ci, movimientos) {
        document.getElementById('formDesactivar').action = '/trabajadores/' + id + '/toggle';
        document.getElementById('desac_nombre').textContent = nombre;
        document.getElementById('desac_ci').textContent = ci;
        document.getElementById('desac_movimientos').textContent = movimientos;
        document.getElementById('modalDesactivar').classList.add('active');
    }
    function abrirModalEliminar(id, nombre, ci, movimientos) {
        document.getElementById('formEliminar').action = '/trabajadores/' + id;
        document.getElementById('elim_nombre').textContent = nombre;
        document.getElementById('elim_ci').textContent = ci;
        document.getElementById('elim_movimientos').textContent = movimientos;

        const avisoMov = document.getElementById('aviso_con_movimientos');
        const btnConfirmar = document.getElementById('btn_confirmar_eliminar');
        const checkDescargado = document.getElementById('check_descargado');
        const btnReporte = document.getElementById('btn_descargar_reporte');

        if (movimientos > 0) {
            avisoMov.style.display = 'block';
            btnConfirmar.disabled = true;
            checkDescargado.checked = false;
            btnReporte.href = '/reportes/trabajador/' + id + '/pdf';

            checkDescargado.onchange = function() {
                btnConfirmar.disabled = !this.checked;
            };
        } else {
            avisoMov.style.display = 'none';
            btnConfirmar.disabled = false;
        }

        document.getElementById('modalEliminar').classList.add('active');
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

    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.querySelector('input[name="buscar"]');
        const statusSelect = document.querySelector('select[name="estado"]');
        const searchForm = document.querySelector('.filters');
        
        let debounceTimer;
        
        function performSearch() {
            if (!searchForm) return;
            const formData = new FormData(searchForm);
            const params = new URLSearchParams(formData);
            const url = searchForm.action + '?' + params.toString();
            
            // Update browser URL
            window.history.replaceState({}, '', url);
            
            const wrapper = document.getElementById('trabajadores-table-wrapper');
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
                const newWrapper = doc.getElementById('trabajadores-table-wrapper');
                if (wrapper && newWrapper) {
                    wrapper.innerHTML = newWrapper.innerHTML;
                    wrapper.style.opacity = '1';
                }
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
        
        if (statusSelect) {
            statusSelect.addEventListener('change', performSearch);
        }
    });
</script>
@endpush