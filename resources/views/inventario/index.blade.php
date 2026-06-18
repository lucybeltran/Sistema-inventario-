@extends('layouts.mina')

@section('titulo', 'Inventario')

@push('styles')
<style>
    .page-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 25px; flex-wrap: wrap; gap: 15px;
    }
    .page-title {
        color: #667eea; font-size: 24px; font-weight: 600;
        display: flex; align-items: center; gap: 10px;
    }
    .page-counter {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white; padding: 6px 14px; border-radius: 20px;
        font-size: 13px; font-weight: 600;
    }
    .valor-total {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white; padding: 8px 16px; border-radius: 20px;
        font-size: 13px; font-weight: 600;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .filters {
        background: #f8f9fa; padding: 20px; border-radius: 10px;
        margin-bottom: 20px; display: grid;
        grid-template-columns: 2fr 1fr 1fr auto; gap: 12px; align-items: end;
    }
    .filters .form-field { display: flex; flex-direction: column; gap: 5px; }
    .filters label {
        font-size: 12px; font-weight: 600; color: #555;
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    .filters input, .filters select {
        padding: 10px 14px; border: 2px solid #e0e0e0;
        border-radius: 8px; font-size: 14px; background: white;
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
    .btn-secondary:hover { background: #e0e0e0; }
    .btn-grupos { background: linear-gradient(135deg, #f59f00 0%, #f76707 100%); color: white; }
    .btn-grupos:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(245, 159, 0, 0.4); }
    .btn-danger { background: #ff6b6b; color: white; }
    .btn-edit { background: #ffa94d; color: white; }
    .btn-edit:hover { background: #ff922b; }
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
    table tbody tr { transition: all 0.2s; }
    table tbody tr:hover { background: #faf9ff; }

    /* Fila separadora de grupo */
    .grupo-separador-row td {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white; font-weight: 700; font-size: 14px;
        padding: 12px 14px;
    }

    .codigo { font-family: 'Courier New', monospace; font-weight: bold; color: #667eea; }
    .stock-badge {
        display: inline-block; padding: 4px 10px;
        border-radius: 12px; font-size: 12px; font-weight: 600; margin-left: 6px;
    }
    .stock-ok { background: #d3f9d8; color: #2b8a3e; }
    .stock-bajo { background: #fff3bf; color: #7f6b0d; }
    .stock-cero { background: #ffe3e3; color: #862e2e; }
    .precio { font-weight: 600; color: #2b8a3e; font-family: 'Courier New', monospace; }
    .grupo-tag {
        display: inline-block; background: #e9ecef; color: #495057;
        padding: 3px 10px; border-radius: 10px;
        font-size: 11px; font-weight: 600; font-family: 'Courier New', monospace;
    }
    .acciones-cell { display: flex; gap: 5px; }
    .acciones-cell button {
        padding: 6px 10px; font-size: 12px;
        border: none; border-radius: 6px; cursor: pointer;
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

    .mensaje-success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
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
            <span class="page-counter">{{ $total }} artículos</span>
            <span class="valor-total">
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
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Buscar
            </button>
            <a href="{{ route('inventario.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </form>

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
                        <th style="width: 140px;">Stock</th>
                        <th style="width: 100px;">Precio Unit.</th>
                        <th style="width: 110px;">Valor Total</th>
                        @if(Auth::user()->puedeEditar() || Auth::user()->puedeReportes())
                            <th style="width: 130px;">Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @php $grupoActual = null; @endphp
                    @foreach($articulos as $articulo)
                        @if($articulo->grupo_id !== $grupoActual)
                            @php $grupoActual = $articulo->grupo_id; @endphp
                            <tr class="grupo-separador-row">
                                <td colspan="{{ (Auth::user()->puedeEditar() || Auth::user()->puedeReportes()) ? 8 : 7 }}">
                                    <i class="fas fa-folder"></i>
                                    {{ $articulo->grupo_id }} — {{ $articulo->grupo->nombre ?? '' }}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td><span class="codigo">{{ $articulo->codigo }}</span></td>
                            <td>{{ $articulo->nombre }}</td>
                            <td><span class="grupo-tag">{{ $articulo->grupo_id }}</span></td>
                            <td>{{ $articulo->unidad }}</td>
                            <td>
                                <strong>{{ number_format($articulo->cantidad, 3) }}</strong>
                                @if($articulo->cantidad <= 0)
                                    <span class="stock-badge stock-cero">Sin stock</span>
                                @elseif($articulo->cantidad < 10)
                                    <span class="stock-badge stock-bajo">Bajo</span>
                                @else
                                    <span class="stock-badge stock-ok">OK</span>
                                @endif
                            </td>
                            <td><span class="precio">Bs. {{ number_format($articulo->precio, 2) }}</span></td>
                            <td><strong style="color:#11998e;">Bs. {{ number_format($articulo->precio * $articulo->cantidad, 2) }}</strong></td>
                            @if(Auth::user()->puedeEditar() || Auth::user()->puedeReportes())
                                <td>
                                    <div class="acciones-cell">
                                        @if(Auth::user()->puedeReportes())
                                            <a href="{{ route('reportes.kardex', $articulo->id) }}"
                                               title="Ver Kardex"
                                               style="padding:6px 10px; font-size:12px; background:#667eea; color:white; border-radius:6px; text-decoration:none; display:inline-flex; align-items:center; gap:4px;">
                                                <i class="fas fa-clipboard-list"></i>
                                            </a>
                                        @endif
                                        @if(Auth::user()->puedeEditar())
                                            <button class="btn-edit"
                                                    onclick="abrirModalEditar({{ $articulo->id }}, '{{ addslashes($articulo->codigo) }}', '{{ addslashes($articulo->nombre) }}', '{{ addslashes($articulo->unidad) }}', '{{ $articulo->grupo_id }}', {{ $articulo->cantidad }}, {{ $articulo->precio }})"
                                                    title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @endif
                                        @if(Auth::user()->esAdmin())
                                            <button type="button"
                                                    class="btn-danger"
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
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-dollar-sign"></i> Precio (Bs.)</label>
                            <input type="number" name="precio" id="edit_precio" min="0" step="0.01">
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
    function abrirModalEditar(id, codigo, nombre, unidad, grupo_id, cantidad, precio) {
        document.getElementById('formEditar').action = '/inventario/' + id;
        document.getElementById('edit_codigo').value = codigo;
        document.getElementById('edit_nombre').value = nombre;
        document.getElementById('edit_unidad').value = unidad;
        document.getElementById('edit_grupo_id').value = grupo_id;
        document.getElementById('edit_cantidad').value = cantidad;
        document.getElementById('edit_precio').value = precio;
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
</script>
@endpush