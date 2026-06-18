@extends('layouts.mina')

@section('titulo', 'Movimientos')

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

    .filters {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto;
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
    .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4); }
    .btn-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; }
    .btn-success:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(17, 153, 142, 0.4); }
    .btn-secondary { background: #f0f0f0; color: #555; }

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

    .codigo {
        font-family: 'Courier New', monospace;
        font-weight: bold;
        color: #667eea;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .badge-success { background: #d3f9d8; color: #2b8a3e; }
    .badge-danger { background: #ffe3e3; color: #862e2e; }

    .trabajador-tag {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #fff3bf;
        color: #7f6b0d;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .empty { text-align: center; padding: 50px 20px; color: #999; }
    .empty i {
        font-size: 64px; opacity: 0.3; margin-bottom: 15px;
        animation: floatIcon 3s ease-in-out infinite;
    }
    @keyframes floatIcon {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    /* MODAL */
    .modal {
        display: none;
        position: fixed; top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        align-items: center; justify-content: center;
        animation: fadeIn 0.2s;
    }
    .modal.active { display: flex; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

    .modal-content {
        background: white; border-radius: 15px; padding: 30px;
        max-width: 600px; width: 90%;
        max-height: 90vh; overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        animation: slideDown 0.3s;
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-30px); }
        to { opacity: 1; transform: translateY(0); }
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
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%; padding: 12px;
        border: 2px solid #e0e0e0; border-radius: 8px;
        font-size: 14px; font-family: inherit;
    }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
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

    /* Campo de trabajador con animación */
    .field-trabajador {
        background: #fff8e1;
        padding: 15px;
        border-radius: 10px;
        border: 2px solid #ffd54f;
        margin-bottom: 18px;
        transition: all 0.3s;
    }

    .field-trabajador.oculto {
        display: none;
    }

    .field-trabajador label {
        color: #7f6b0d !important;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .alert-warning {
        background: #fff3bf;
        color: #7f6b0d;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 12px;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    @media (max-width: 768px) {
        .filters { grid-template-columns: 1fr; }
        .form-row { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('contenido')

    <div class="page-header">
        <h2 class="page-title">
            <i class="fas fa-exchange-alt"></i> Movimientos
            <span class="page-counter">{{ $movimientos->total() }} registros</span>
        </h2>
        @if(Auth::user()->puedeEditar())
            <button class="btn btn-success" onclick="abrirModal()">
                <i class="fas fa-plus"></i> Registrar Movimiento
            </button>
        @endif
    </div>

    <form method="GET" action="{{ route('movimientos.index') }}" class="filters">
        <div class="form-field">
            <label><i class="fas fa-search"></i> Buscar artículo</label>
            <input type="text" name="buscar" placeholder="Código o nombre..." value="{{ request('buscar') }}">
        </div>

        <div class="form-field">
            <label><i class="fas fa-tag"></i> Tipo</label>
            <select name="tipo">
                <option value="">Todos</option>
                <option value="entrada" {{ request('tipo') == 'entrada' ? 'selected' : '' }}>Entradas</option>
                <option value="salida" {{ request('tipo') == 'salida' ? 'selected' : '' }}>Salidas</option>
            </select>
        </div>

        <div class="form-field">
            <label><i class="fas fa-hard-hat"></i> Trabajador</label>
            <select name="trabajador_id">
                <option value="">Todos</option>
                @foreach($trabajadores as $t)
                    <option value="{{ $t->id }}" {{ request('trabajador_id') == $t->id ? 'selected' : '' }}>
                        {{ $t->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-field">
            <label><i class="fas fa-calendar"></i> Desde</label>
            <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}">
        </div>

        <div class="form-field">
            <label><i class="fas fa-calendar"></i> Hasta</label>
            <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}">
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i>
            </button>
            <a href="{{ route('movimientos.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </form>

    @if($movimientos->isEmpty())
        <div class="empty">
            <i class="fas fa-inbox"></i>
            <p>No hay movimientos registrados.</p>
        </div>
    @else
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 95px;">Fecha</th>
                        <th style="width: 105px;">Código</th>
                        <th>Artículo</th>
                        <th style="width: 95px;">Tipo</th>
                        <th style="width: 115px;">Cantidad</th>
                        <th style="width: 180px;">Entregado a</th>
                        <th>Notas</th>
                        <th style="width: 110px;">Registró</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movimientos as $mov)
                        <tr>
                            <td>{{ $mov->fecha->format('d/m/Y') }}</td>
                            <td><span class="codigo">{{ $mov->articulo->codigo }}</span></td>
                            <td>{{ $mov->articulo->nombre }}</td>
                            <td>
                                @if($mov->tipo === 'entrada')
                                    <span class="badge badge-success">
                                        <i class="fas fa-arrow-down"></i> Entrada
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        <i class="fas fa-arrow-up"></i> Salida
                                    </span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ number_format($mov->cantidad, 2) }}</strong>
                                <span style="color:#999; font-size:12px;">{{ $mov->articulo->unidad }}</span>
                            </td>
                            <td>
                                @if($mov->trabajador)
                                    <span class="trabajador-tag">
                                        <i class="fas fa-hard-hat"></i> {{ $mov->trabajador->nombre }}
                                    </span>
                                @else
                                    <span style="color:#bbb; font-size:13px;">—</span>
                                @endif
                            </td>
                            <td style="color:#666; font-size:13px;">{{ $mov->notas ?? '—' }}</td>
                            <td style="font-size:13px;">{{ $mov->user?->name ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top:20px; display:flex; justify-content:center;">
            {{ $movimientos->links() }}
        </div>
    @endif

    {{-- MODAL DE REGISTRAR MOVIMIENTO (solo si puede editar) --}}
    @if(Auth::user()->puedeEditar())
    <div class="modal {{ $errors->any() ? 'active' : '' }}" id="modalMovimiento">
        <div class="modal-content">
            <div class="modal-header">
                <span><i class="fas fa-plus-circle"></i> Registrar Movimiento</span>
                <button class="modal-close" onclick="cerrarModal()">&times;</button>
            </div>

            @if($errors->any())
                <div class="error-list">
                    <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('movimientos.store') }}">
                @csrf

                <div class="form-group">
                    <label><i class="fas fa-box"></i> Artículo</label>
                    <select name="articulo_id" required>
                        <option value="">— Seleccionar artículo —</option>
                        @foreach($articulos as $art)
                            <option value="{{ $art->id }}"
                                {{ old('articulo_id') == $art->id ? 'selected' : '' }}>
                                {{ $art->codigo }} — {{ $art->nombre }} (Stock: {{ number_format($art->cantidad, 2) }} {{ $art->unidad }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-exchange-alt"></i> Tipo de movimiento</label>
                    <select name="tipo" id="tipoMovimiento" required>
                        <option value="entrada" {{ old('tipo') == 'entrada' ? 'selected' : '' }}>
                            ⬇ Entrada (Compra / Recepción)
                        </option>
                        <option value="salida" {{ old('tipo') == 'salida' ? 'selected' : '' }}>
                            ⬆ Salida (Entrega a trabajador)
                        </option>
                    </select>
                </div>

                {{-- CAMPO DE TRABAJADOR (visible solo en SALIDA) --}}
                <div class="field-trabajador {{ old('tipo') == 'salida' ? '' : 'oculto' }}" id="campoTrabajador">
                    <div class="form-group" style="margin-bottom:0;">
                        <label>
                            <i class="fas fa-hard-hat"></i> Entregado a (Trabajador)
                            <span style="color:#e03131; font-size:12px;">* Obligatorio en salidas</span>
                        </label>
                        <select name="trabajador_id" id="selectTrabajador">
                            <option value="">— Seleccionar trabajador —</option>
                            @foreach($trabajadores as $t)
                                <option value="{{ $t->id }}" {{ old('trabajador_id') == $t->id ? 'selected' : '' }}>
                                    {{ $t->nombre }} {{ $t->cargo ? '(' . $t->cargo . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @if($trabajadores->isEmpty())
                            <div class="alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                No hay trabajadores activos registrados. Ve a <strong>Trabajadores</strong> para agregar uno.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-hashtag"></i> Cantidad</label>
                        <input type="number" name="cantidad" step="0.01" min="0.01" value="{{ old('cantidad') }}" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-calendar"></i> Fecha</label>
                        <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-sticky-note"></i> Notas (opcional)</label>
                    <textarea name="notas" rows="3" placeholder="Observaciones, proveedor, motivo de salida...">{{ old('notas') }}</textarea>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" style="flex: 1; justify-content: center;">
                        <i class="fas fa-save"></i> Guardar Movimiento
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="cerrarModal()" style="flex: 1; justify-content: center;">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

@endsection

@push('scripts')
@if(Auth::user()->puedeEditar())
<script>
    function abrirModal() {
        document.getElementById('modalMovimiento').classList.add('active');
        actualizarCampoTrabajador();
    }
    function cerrarModal() {
        document.getElementById('modalMovimiento').classList.remove('active');
    }
    document.getElementById('modalMovimiento').addEventListener('click', function(e) {
        if (e.target === this) cerrarModal();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') cerrarModal();
    });

    // ✨ MAGIA: Mostrar/ocultar campo de trabajador según el tipo
    const tipoSelect = document.getElementById('tipoMovimiento');
    const campoTrabajador = document.getElementById('campoTrabajador');
    const selectTrabajador = document.getElementById('selectTrabajador');

    function actualizarCampoTrabajador() {
        if (tipoSelect.value === 'salida') {
            campoTrabajador.classList.remove('oculto');
            selectTrabajador.required = true;
        } else {
            campoTrabajador.classList.add('oculto');
            selectTrabajador.required = false;
            selectTrabajador.value = '';
        }
    }

    tipoSelect.addEventListener('change', actualizarCampoTrabajador);

    // Si hay errores, ejecutar al cargar
    document.addEventListener('DOMContentLoaded', actualizarCampoTrabajador);
</script>
@endif
@endpush