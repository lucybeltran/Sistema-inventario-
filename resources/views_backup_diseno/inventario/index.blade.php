@extends('layouts.mina')

@section('titulo', 'Inventario')

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

    .page-counter {
        background: #f0e9ff;
        color: #667eea;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .valor-total {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .filters {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: grid;
        grid-template-columns: 2fr 1fr 1fr auto;
        gap: 12px;
        align-items: end;
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

    .codigo {
        font-family: 'Courier New', monospace;
        font-weight: bold;
        color: #667eea;
    }

    .stock-badge {
        display: inline-block; padding: 4px 10px;
        border-radius: 12px; font-size: 12px; font-weight: 600;
        margin-left: 6px;
    }
    .stock-ok { background: #d3f9d8; color: #2b8a3e; }
    .stock-bajo { background: #fff3bf; color: #7f6b0d; }
    .stock-cero { background: #ffe3e3; color: #862e2e; }

    .precio {
        font-weight: 600;
        color: #2b8a3e;
        font-family: 'Courier New', monospace;
    }

    .grupo-tag {
        display: inline-block; background: #e9ecef; color: #495057;
        padding: 3px 10px; border-radius: 10px;
        font-size: 11px; font-weight: 600;
        font-family: 'Courier New', monospace;
        transition: all 0.2s;
    }

    .grupo-tag-editable {
        cursor: pointer;
        border: 1px solid transparent;
    }

    .grupo-tag-editable:hover {
        background: #667eea;
        color: white;
        transform: scale(1.1);
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
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
        display: none;
        position: fixed; top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        align-items: center; justify-content: center;
    }
    .modal.active { display: flex; }

    .modal-content {
        background: white; border-radius: 15px; padding: 30px;
        max-width: 600px; width: 90%; max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }

    .modal-header {
        font-size: 20px; font-weight: bold; margin-bottom: 20px;
        display: flex; justify-content: space-between; align-items: center;
        color: #667eea;
    }
    .modal-close {
        background: none; border: none; font-size: 28px;
        cursor: pointer; color: #999;
    }

    .form-group { margin-bottom: 18px; }
    .form-group label {
        display: block; margin-bottom: 6px; font-weight: 600;
        color: #333; font-size: 14px;
    }
    .form-group input, .form-group select {
        width: 100%; padding: 12px;
        border: 2px solid #e0e0e0; border-radius: 8px;
        font-size: 14px; font-family: inherit;
        text-transform: uppercase;
    }
    .form-group input:focus, .form-group select:focus {
        outline: none; border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
    .form-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; }
    .modal-footer { display: flex; gap: 10px; margin-top: 25px; }

    .error-list {
        background: #ffe3e3; border-left: 4px solid #ff6b6b;
        padding: 12px 16px; border-radius: 8px; margin-bottom: 18px;
        color: #862e2e; font-size: 13px;
    }
    .help-text {
        font-size: 12px; color: #999;
        margin-top: 4px; font-style: italic;
    }

    @media (max-width: 768px) {
        .filters { grid-template-columns: 1fr; }
        .form-row, .form-row-3 { grid-template-columns: 1fr; }
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
                    @foreach($articulos as $articulo)
                        <tr>
                            <td><span class="codigo">{{ $articulo->codigo }}</span></td>
                            <td>{{ $articulo->nombre }}</td>
                            <td>
                                @if(Auth::user()->puedeEditar())
                                    <span class="grupo-tag grupo-tag-editable"
                                          onclick="abrirModalEditarGrupo('{{ $articulo->grupo_id }}', '{{ addslashes($articulo->grupo->nombre ?? '') }}')"
                                          title="Click para editar el nombre de este grupo">
                                        {{ $articulo->grupo_id }}
                                    </span>
                                @else
                                    <span class="grupo-tag">{{ $articulo->grupo_id }}</span>
                                @endif
                            </td>
                            <td>{{ $articulo->unidad }}</td>
                            <td>
                                <strong>{{ number_format($articulo->cantidad, 2) }}</strong>
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
            {{-- KARDEX: visible para admin y reportes --}}
            @if(Auth::user()->puedeReportes())
                <a href="{{ route('reportes.kardex', $articulo->id) }}"
                   class="btn-kardex"
                   title="Ver Kardex de este producto"
                   style="padding:6px 10px; font-size:12px; background:#667eea; color:white; border-radius:6px; text-decoration:none; display:inline-flex; align-items:center; gap:4px;">
                    <i class="fas fa-clipboard-list"></i>
                </a>
            @endif

            {{-- EDITAR: visible para admin y almacenero --}}
            @if(Auth::user()->puedeEditar())
                <button class="btn-edit"
                        onclick="abrirModalEditar({{ $articulo->id }}, '{{ addslashes($articulo->codigo) }}', '{{ addslashes($articulo->nombre) }}', '{{ addslashes($articulo->unidad) }}', '{{ $articulo->grupo_id }}', {{ $articulo->cantidad }}, {{ $articulo->precio }})"
                        title="Editar">
                    <i class="fas fa-edit"></i>
                </button>
            @endif

            {{-- ELIMINAR: solo admin --}}
            @if(Auth::user()->esAdmin())
                <form method="POST" action="{{ route('inventario.destroy', $articulo) }}"
                      onsubmit="return confirm('¿Eliminar el artículo {{ $articulo->codigo }}?')"
                      style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
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
                            <p class="help-text" id="codigo-sugerencia"><i class="fas fa-magic"></i> Selecciona un grupo y el código se sugerirá automáticamente.</p>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-ruler"></i> Unidad</label>
                            <input type="text" name="unidad" value="{{ old('unidad') }}" placeholder="UNIDAD, KILOS, METROS..." required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-tag"></i> Nombre / Descripción</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Descripción del artículo" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-hashtag"></i> Cantidad inicial</label>
                            <input type="number" name="cantidad" value="{{ old('cantidad', 0) }}" min="0" step="0.01">
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-dollar-sign"></i> Precio unitario (Bs.)</label>
                            <input type="number" name="precio" value="{{ old('precio', 0) }}" min="0" step="0.01">
                            <p class="help-text">Precio por unidad en bolivianos</p>
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
                            <input type="number" name="cantidad" id="edit_cantidad" min="0" step="0.01">
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

    @endif

@endsection

@push('scripts')
<script>
    function abrirModalNuevo() {
        document.getElementById('modalNuevo').classList.add('active');
    }
    function abrirModalGrupo() {
        document.getElementById('modalGrupo').classList.add('active');
    }
    function abrirModalEditarGrupo(grupoId, nombreActual) {
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

    // ✨ MAGIA: Auto-sugerir código según el grupo
    const selectGrupoNuevo = document.getElementById('select-grupo-nuevo');
    const inputCodigoNuevo = document.getElementById('input-codigo-nuevo');
    const textoSugerencia = document.getElementById('codigo-sugerencia');

    if (selectGrupoNuevo && inputCodigoNuevo) {
        selectGrupoNuevo.addEventListener('change', async function() {
            const grupoId = this.value;

            if (!grupoId) {
                inputCodigoNuevo.value = '';
                textoSugerencia.innerHTML = '<i class="fas fa-magic"></i> Selecciona un grupo y el código se sugerirá automáticamente.';
                return;
            }

            // Mostrar mensaje de "cargando"
            textoSugerencia.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Buscando próximo código disponible...';

            try {
                const response = await fetch(`/inventario/siguiente-codigo/${grupoId}`);
                const data = await response.json();

                if (data.codigo) {
                    inputCodigoNuevo.value = data.codigo;
                    textoSugerencia.innerHTML = '<i class="fas fa-check-circle" style="color:#2b8a3e;"></i> Código sugerido: <strong>' + data.codigo + '</strong>. Puedes modificarlo si lo deseas.';
                } else {
                    textoSugerencia.innerHTML = '<i class="fas fa-exclamation-triangle" style="color:#f59f00;"></i> No se pudo sugerir un código. Escribe uno manualmente.';
                }
            } catch (error) {
                console.error('Error:', error);
                textoSugerencia.innerHTML = '<i class="fas fa-times-circle" style="color:#e03131;"></i> Error al sugerir el código. Escribe uno manualmente.';
            }
        });
    }
</script>
@endpush