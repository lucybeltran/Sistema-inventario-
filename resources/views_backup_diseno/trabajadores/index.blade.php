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

    .page-counter {
        background: #f0e9ff;
        color: #667eea;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .badge-activos {
        background: #d3f9d8;
        color: #2b8a3e;
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
    .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4); }
    .btn-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; }
    .btn-success:hover { transform: translateY(-2px); }
    .btn-secondary { background: #f0f0f0; color: #555; }
    .btn-edit { background: #ffa94d; color: white; }
    .btn-edit:hover { background: #ff922b; }
    .btn-toggle-on { background: #51cf66; color: white; }
    .btn-toggle-off { background: #ff6b6b; color: white; }

    .btn-historial {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        background: linear-gradient(135deg, #f59f00 0%, #f76707 100%);
        color: white;
        border-radius: 6px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-historial:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(245, 159, 0, 0.4);
        color: white;
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
        max-width: 550px; width: 90%;
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
    .form-group input {
        width: 100%; padding: 12px;
        border: 2px solid #e0e0e0; border-radius: 8px;
        font-size: 14px;
    }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
    .modal-footer { display: flex; gap: 10px; margin-top: 25px; }

    .error-list {
        background: #ffe3e3; border-left: 4px solid #ff6b6b;
        padding: 12px 16px; border-radius: 8px; margin-bottom: 18px;
        color: #862e2e; font-size: 13px;
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
            <i class="fas fa-hard-hat"></i> Trabajadores de la Mina
            <span class="page-counter">{{ $total }} en total</span>
            <span class="badge-activos">
                <i class="fas fa-check-circle"></i> {{ $activos }} activos
            </span>
        </h2>
        @if(Auth::user()->puedeEditar())
            <button class="btn btn-success" onclick="abrirModalNuevo()">
                <i class="fas fa-user-plus"></i> Agregar Trabajador
            </button>
        @endif
    </div>

    <form method="GET" action="{{ route('trabajadores.index') }}" class="filters">
        <div class="form-field">
            <label><i class="fas fa-search"></i> Buscar</label>
            <input type="text" name="buscar" placeholder="Nombre, CI o cargo..." value="{{ request('buscar') }}">
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
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Filtrar
            </button>
            <a href="{{ route('trabajadores.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </form>

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
                        <th>Nombre</th>
                        <th style="width: 130px;">CI</th>
                        <th style="width: 150px;">Cargo</th>
                        <th style="width: 130px;">Teléfono</th>
                        <th style="width: 100px;">Estado</th>
                        <th style="width: 120px;">Historial</th>
                        @if(Auth::user()->puedeEditar())
                            <th style="width: 140px;">Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($trabajadores as $t)
                        <tr>
                            <td><strong>{{ $t->nombre }}</strong></td>
                            <td><span class="ci-badge">{{ $t->ci }}</span></td>
                            <td>
                                @if($t->cargo)
                                    <span class="cargo-tag">{{ $t->cargo }}</span>
                                @else
                                    <span style="color:#999;">—</span>
                                @endif
                            </td>
                            <td>{{ $t->telefono ?? '—' }}</td>
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
                                @if(Auth::user()->puedeReportes())
                                    <a href="{{ route('reportes.trabajador', $t) }}"
                                       class="btn-historial"
                                       title="Ver historial completo de {{ $t->nombre }}">
                                        <i class="fas fa-eye"></i> Ver historial
                                    </a>
                                @else
                                    <span style="color:#bbb; font-size:11px;">—</span>
                                @endif
                            </td>
                            @if(Auth::user()->puedeEditar())
                                <td>
                                    <div class="acciones-cell">
                                        <button class="btn-edit"
                                                onclick="abrirModalEditar({{ $t->id }}, '{{ addslashes($t->nombre) }}', '{{ $t->ci }}', '{{ addslashes($t->cargo ?? '') }}', '{{ $t->telefono ?? '' }}')"
                                                title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @if(Auth::user()->esAdmin())
                                            <form method="POST" action="{{ route('trabajadores.toggle', $t) }}" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="{{ $t->activo ? 'btn-toggle-off' : 'btn-toggle-on' }}"
                                                        title="{{ $t->activo ? 'Desactivar' : 'Activar' }}"
                                                        onclick="return confirm('¿{{ $t->activo ? 'Desactivar' : 'Activar' }} a {{ $t->nombre }}?')">
                                                    <i class="fas fa-{{ $t->activo ? 'user-slash' : 'user-check' }}"></i>
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
            {{ $trabajadores->links() }}
        </div>
    @endif

    @if(Auth::user()->puedeEditar())

    {{-- MODAL: NUEVO TRABAJADOR --}}
    <div class="modal {{ $errors->any() && old('_form') == 'nuevo' ? 'active' : '' }}" id="modalNuevo">
        <div class="modal-content">
            <div class="modal-header">
                <span><i class="fas fa-user-plus"></i> Agregar Nuevo Trabajador</span>
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

                <div class="form-group">
                    <label><i class="fas fa-user"></i> Nombre completo</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Juan Pérez Mamani" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-id-card"></i> CI (Carnet de Identidad)</label>
                        <input type="text" name="ci" value="{{ old('ci') }}" placeholder="1234567 LP" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-phone"></i> Teléfono (opcional)</label>
                        <input type="text" name="telefono" value="{{ old('telefono') }}" placeholder="70123456">
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-briefcase"></i> Cargo (opcional)</label>
                    <input type="text" name="cargo" value="{{ old('cargo') }}" placeholder="Minero, Perforista, Capataz...">
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" style="flex:1; justify-content:center;">
                        <i class="fas fa-save"></i> Guardar Trabajador
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="cerrarModales()" style="flex:1; justify-content:center;">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: EDITAR TRABAJADOR --}}
    <div class="modal {{ $errors->any() && old('_form') == 'editar' ? 'active' : '' }}" id="modalEditar">
        <div class="modal-content">
            <div class="modal-header">
                <span><i class="fas fa-edit"></i> Editar Trabajador</span>
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
                    <label><i class="fas fa-user"></i> Nombre completo</label>
                    <input type="text" name="nombre" id="edit_nombre" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-id-card"></i> CI</label>
                        <input type="text" name="ci" id="edit_ci" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-phone"></i> Teléfono</label>
                        <input type="text" name="telefono" id="edit_telefono">
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-briefcase"></i> Cargo</label>
                    <input type="text" name="cargo" id="edit_cargo">
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
    function abrirModalEditar(id, nombre, ci, cargo, telefono) {
        document.getElementById('formEditar').action = '/trabajadores/' + id;
        document.getElementById('edit_nombre').value = nombre;
        document.getElementById('edit_ci').value = ci;
        document.getElementById('edit_cargo').value = cargo;
        document.getElementById('edit_telefono').value = telefono;
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
</script>
@endpush