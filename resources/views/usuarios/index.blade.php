@extends('layouts.mina')

@section('titulo', 'Gestión de Usuarios')

@push('styles')
<style>
/* ── Grid de tarjetas de usuario ── */
.usuarios-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
    margin-top: 15px;
}

/* ── Tarjeta de usuario premium ── */
.user-card {
    background: var(--bg-card);
    backdrop-filter: blur(22px) saturate(160%);
    -webkit-backdrop-filter: blur(22px) saturate(160%);
    border: 1.5px solid var(--border);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    transition: all 0.3s cubic-bezier(0.16,1,0.3,1);
    animation: cardEnter 0.5s cubic-bezier(0.16,1,0.3,1) backwards;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.user-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    border-radius: 20px 20px 0 0;
    background: var(--gradient);
}

.user-card:hover {
    box-shadow: var(--shadow-lg);
    border-color: var(--border-strong);
    transform: translateY(-4px);
}

@keyframes cardEnter {
    from { opacity: 0; transform: translateY(20px) scale(0.97); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}

/* ── Avatar + info ── */
.user-card-top {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
}

.user-avatar {
    width: 56px; height: 56px;
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    font-size: 24px;
    color: white;
    flex-shrink: 0;
    position: relative;
    background: var(--gradient);
    box-shadow: 0 4px 14px rgba(180,83,9,0.3);
}

.user-avatar::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.22) 0%, transparent 60%);
    border-radius: inherit;
}

.user-name {
    font-size: 17px;
    font-weight: 800;
    color: var(--text-primary);
    letter-spacing: -0.02em;
    line-height: 1.2;
    margin-bottom: 4px;
}

.user-email {
    font-size: 13px;
    color: var(--text-muted);
    word-break: break-all;
}

/* ── Permission status pills ── */
.perms-row {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 20px;
}

.perm-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    border: 1px solid var(--border);
    transition: all 0.2s ease;
}

.perm-pill.activo-almacen {
    background: rgba(180,83,9,0.06);
    color: var(--primary);
    border-color: rgba(180,83,9,0.2);
}

.perm-pill.activo-reportes {
    background: rgba(29,78,216,0.06);
    color: #3b82f6;
    border-color: rgba(29,78,216,0.2);
}

.perm-pill.activo-movimientos {
    background: rgba(99, 102, 241, 0.06);
    color: #6366f1;
    border-color: rgba(99, 102, 241, 0.2);
}

.perm-pill.activo-materiales {
    background: rgba(16, 185, 129, 0.06);
    color: #10b981;
    border-color: rgba(16, 185, 129, 0.2);
}

.perm-check-icon.indigo { background: rgba(99, 102, 241, 0.1) !important; color: #6366f1 !important; }
.perm-check-icon.green  { background: rgba(16, 185, 129, 0.1) !important; color: #10b981 !important; }

.perm-pill.inactivo {
    background: var(--bg-hover);
    color: var(--text-muted);
    border-color: var(--border-light);
    opacity: 0.5;
}

/* ── Action buttons in card ── */
.user-actions {
    display: flex;
    gap: 8px;
    margin-top: 10px;
}

.btn-edit-user {
    flex: 1;
    padding: 10px 16px;
    background: var(--gradient-subtle);
    color: var(--primary);
    border: 1.5px solid var(--border);
    border-radius: 12px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.25s ease;
    text-decoration: none;
    letter-spacing: -0.01em;
}

.btn-edit-user:hover {
    background: var(--gradient);
    color: white;
    border-color: transparent;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(180,83,9,0.25);
    -webkit-text-fill-color: white !important;
}

/* ── Checkbox permisos custom ── */
.perms-check-group {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin: 14px 0 8px;
}

.perm-check-item {
    display: flex !important;
    align-items: center !important;
    justify-content: flex-start !important;
    gap: 14px !important;
    padding: 12px 16px !important;
    border-radius: 12px !important;
    border: 1.5px solid var(--border) !important;
    background: var(--bg-input) !important;
    cursor: pointer !important;
    transition: all 0.22s ease !important;
    user-select: none !important;
    width: 100% !important;
}

.perm-check-item:hover {
    border-color: var(--primary) !important;
    background: var(--bg-hover) !important;
}

.perm-check-item input[type="checkbox"] {
    width: 20px !important;
    height: 20px !important;
    min-width: 20px !important;
    min-height: 20px !important;
    max-width: 20px !important;
    max-height: 20px !important;
    padding: 0 !important;
    margin: 0 !important;
    accent-color: var(--primary) !important;
    cursor: pointer !important;
    flex-shrink: 0 !important;
    -webkit-appearance: checkbox !important;
    appearance: checkbox !important;
    border: 1px solid var(--border-strong) !important;
    background: white !important;
}

.perm-check-item.selected {
    border-color: var(--primary) !important;
    background: var(--gradient-subtle) !important;
}

.perm-check-icon {
    width: 36px !important;
    height: 36px !important;
    border-radius: 8px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 16px !important;
    flex-shrink: 0 !important;
}

.perm-check-icon.amber { background: rgba(180,83,9,0.1) !important; color: var(--primary) !important; }
.perm-check-icon.blue  { background: rgba(29,78,216,0.1) !important; color: #3b82f6 !important; }

.perm-check-info { flex: 1; }
.perm-check-name { font-weight: 750; font-size: 14px; color: var(--text-primary); }
.perm-check-desc { font-size: 12px; color: var(--text-muted); margin-top: 2px; }

/* ── Modal overrides specific ── */
.modal-user-content {
    max-width: 540px !important;
}

.pass-hint {
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.input-readonly {
    background: var(--bg-hover) !important;
    color: var(--text-muted) !important;
    cursor: not-allowed;
    border-color: var(--border-light) !important;
}

/* ── Empty state ── */
.usuarios-empty {
    text-align: center;
    padding: 70px 20px;
    color: var(--text-muted);
}

.usuarios-empty i {
    font-size: 58px;
    opacity: 0.2;
    margin-bottom: 20px;
    display: block;
}
</style>
@endpush

@section('contenido')

    {{-- ══ HEADER ══ --}}
    <div class="page-header">
        <h2 class="page-title">
            <i class="fas fa-users-cog"></i>
            Gestión de Usuarios
            <span class="page-counter">{{ $usuarios->count() }} usuario{{ $usuarios->count() != 1 ? 's' : '' }}</span>
        </h2>
        <button class="btn btn-primary" onclick="abrirModalNuevo()">
            <i class="fas fa-user-plus"></i> Nuevo Usuario
        </button>
    </div>

    {{-- ══ GRID DE USUARIOS ══ --}}
    @if($usuarios->isEmpty())
        <div class="usuarios-empty" style="background: var(--bg-card); border: 1.5px solid var(--border); border-radius: 20px; backdrop-filter: blur(20px);">
            <i class="fas fa-users"></i>
            <p>No hay usuarios registrados todavía.</p>
            <span>Haz clic en <strong>Nuevo Usuario</strong> para crear accesos.</span>
        </div>
    @else
        <div class="usuarios-grid">
            @foreach($usuarios as $u)
                <div class="user-card" id="card-usuario-{{ $u->id }}" style="opacity: {{ $u->activo ? '1' : '0.85' }}">
                    <div>
                        <div class="user-card-top">
                            <div class="user-avatar" style="overflow: hidden; display: flex; align-items: center; justify-content: center; background: var(--bg-hover);">
                                <img src="{{ $u->avatar_url }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div style="flex:1; min-width:0;">
                                <div class="user-name" style="display: flex; align-items: center; gap: 8px;">
                                    {{ $u->name }}
                                    @if(!$u->activo)
                                        <span style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); font-size: 10px; padding: 2px 6px; border-radius: 6px; font-weight: 800; letter-spacing: 0.5px;">INACTIVO</span>
                                    @endif
                                </div>
                                <div class="user-email">
                                    <i class="fas fa-envelope" style="font-size:11px; margin-right:5px; opacity:0.6;"></i>
                                    {{ $u->email }}
                                </div>
                            </div>
                        </div>

                        {{-- Permisos del usuario --}}
                        <div class="perms-row">
                            <span class="perm-pill {{ $u->permiso_almacen ? 'activo-almacen' : 'inactivo' }}">
                                <i class="fas fa-{{ $u->permiso_almacen ? 'check' : 'times' }}"></i>
                                Gestión de Almacén
                            </span>
                            <span class="perm-pill {{ $u->permiso_editar_movimientos ? 'activo-movimientos' : 'inactivo' }}">
                                <i class="fas fa-{{ $u->permiso_editar_movimientos ? 'check' : 'times' }}"></i>
                                Editar Movimientos
                            </span>
                            <span class="perm-pill {{ $u->permiso_editar_materiales ? 'activo-materiales' : 'inactivo' }}">
                                <i class="fas fa-{{ $u->permiso_editar_materiales ? 'check' : 'times' }}"></i>
                                Editar Materiales
                            </span>
                            <span class="perm-pill {{ $u->permiso_reportes ? 'activo-reportes' : 'inactivo' }}">
                                <i class="fas fa-{{ $u->permiso_reportes ? 'check' : 'times' }}"></i>
                                Contabilidad / Reportes
                            </span>
                            @if($u->permitir_cambio_password)
                                <span class="perm-pill" style="background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1.5px solid rgba(16, 185, 129, 0.25);">
                                    <i class="fas fa-key"></i> Autorizado Cambio Clave
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="user-actions" style="display: flex; gap: 10px; width: 100%; margin-top: 15px;">
                        <button class="btn-edit-user" style="flex: 2; padding: 10px 14px;" onclick="abrirModalEditar({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ addslashes($u->email) }}', {{ $u->permiso_almacen ? 'true' : 'false' }}, {{ $u->permiso_reportes ? 'true' : 'false' }}, {{ $u->permiso_editar_movimientos ? 'true' : 'false' }}, {{ $u->permiso_editar_materiales ? 'true' : 'false' }}, {{ $u->permitir_cambio_password ? 'true' : 'false' }})">
                            <i class="fas fa-pen"></i> Editar
                        </button>
                        <form method="POST" action="{{ route('usuarios.toggle', $u->id) }}" style="display: block; flex: 1.2; margin: 0;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn" style="width: 100%; height: 100%; padding: 10px 14px; border: 1.5px solid {{ $u->activo ? 'rgba(239, 68, 68, 0.3)' : 'rgba(16, 185, 129, 0.3)' }}; background: {{ $u->activo ? 'rgba(239, 68, 68, 0.05)' : 'rgba(16, 185, 129, 0.05)' }}; color: {{ $u->activo ? '#ef4444' : '#10b981' }}; border-radius: 12px; font-size: 13px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 6px; transition: all 0.2s ease; box-shadow: none;" title="{{ $u->activo ? 'Desactivar cuenta' : 'Activar cuenta' }}">
                                <i class="fas fa-{{ $u->activo ? 'user-slash' : 'user-check' }}"></i>
                                {{ $u->activo ? 'Desactivar' : 'Activar' }}
                            </button>
                        </form>
                    </div>

                    @if($u->bloqueado_hasta && $u->bloqueado_hasta->isFuture())
                        <div class="user-block-warning" style="width: 100%; margin-top: 10px; padding: 10px; background: rgba(245, 158, 11, 0.05); border: 1px solid rgba(245, 158, 11, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                            <span style="font-size: 11.5px; color: #f59e0b; font-weight: 700;">
                                <i class="fas fa-lock" style="margin-right: 5px;"></i> Bloqueado temporalmente
                            </span>
                            <form method="POST" action="{{ route('usuarios.desbloquear', $u->id) }}" style="margin: 0; display: inline-block;">
                                @csrf
                                <button type="submit" class="btn" style="padding: 6px 12px; background: #f59e0b; color: white; border: none; border-radius: 8px; font-size: 11.5px; font-weight: 800; cursor: pointer; display: flex; align-items: center; gap: 5px; transition: all 0.2s;" title="Desbloquear de inmediato">
                                    <i class="fas fa-lock-open"></i> Desbloquear
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- ══════════════════════════════════════════
         MODAL — NUEVO USUARIO
    ══════════════════════════════════════════ --}}
    <div class="modal" id="modalNuevo">
        <div class="modal-content modal-user-content">
            <div class="modal-header">
                <span><i class="fas fa-user-plus" style="font-size:16px;"></i> Nuevo Usuario del Sistema</span>
                <button class="modal-close" onclick="cerrarModal('modalNuevo')" type="button">&#x2715;</button>
            </div>

            <form method="POST" action="{{ route('usuarios.store') }}">
                @csrf

                <div class="form-group">
                    <label><i class="fas fa-user"></i> Nombre Completo</label>
                    <input type="text" name="name" placeholder="Ej: Juan Mamani" required maxlength="255"
                           value="{{ old('name') }}" autocomplete="off">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Correo Electrónico</label>
                    <input type="email" name="email" placeholder="correo@empresa.com" required maxlength="255"
                           value="{{ old('email') }}" autocomplete="off">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Contraseña</label>
                        <input type="password" name="password" placeholder="Mínimo 8 caracteres" required minlength="8">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" placeholder="Repite la contraseña" required minlength="8">
                    </div>
                </div>

                {{-- CHECKBOXES DE PERMISOS --}}
                <div class="form-group" style="margin-bottom:0;">
                    <label><i class="fas fa-shield-alt"></i> Permisos del Sistema</label>
                    <div class="perms-check-group">
                        <label class="perm-check-item" id="lbl-nuevo-almacen">
                            <input type="checkbox" name="permiso_almacen" id="chk-nuevo-almacen" value="1" checked onchange="toggleCheckboxStyle('nuevo', 'almacen')">
                            <div class="perm-check-icon amber"><i class="fas fa-boxes"></i></div>
                            <div class="perm-check-info">
                                <div class="perm-check-name">Almacenero / Gestión de Almacén</div>
                                <div class="perm-check-desc">Registrar entradas, salidas, artículos y trabajadores</div>
                            </div>
                        </label>
                        <label class="perm-check-item" id="lbl-nuevo-movimientos">
                            <input type="checkbox" name="permiso_editar_movimientos" id="chk-nuevo-movimientos" value="1" onchange="toggleCheckboxStyle('nuevo', 'movimientos')">
                            <div class="perm-check-icon indigo"><i class="fas fa-edit"></i></div>
                            <div class="perm-check-info">
                                <div class="perm-check-name">Editar Movimientos</div>
                                <div class="perm-check-desc">Editar registros de movimientos de entrada y salida guardados</div>
                            </div>
                        </label>
                        <label class="perm-check-item" id="lbl-nuevo-materiales">
                            <input type="checkbox" name="permiso_editar_materiales" id="chk-nuevo-materiales" value="1" onchange="toggleCheckboxStyle('nuevo', 'materiales')">
                            <div class="perm-check-icon green"><i class="fas fa-cubes"></i></div>
                            <div class="perm-check-info">
                                <div class="perm-check-name">Editar Materiales</div>
                                <div class="perm-check-desc">Modificar datos de materiales registrados en el inventario</div>
                            </div>
                        </label>
                        <label class="perm-check-item" id="lbl-nuevo-reportes">
                            <input type="checkbox" name="permiso_reportes" id="chk-nuevo-reportes" value="1" onchange="toggleCheckboxStyle('nuevo', 'reportes')">
                            <div class="perm-check-icon blue"><i class="fas fa-file-invoice"></i></div>
                            <div class="perm-check-info">
                                <div class="perm-check-name">Contabilidad / Reportes</div>
                                <div class="perm-check-desc">Ver y descargar reportes, kardex y bitácora de auditoría</div>
                            </div>
                        </label>
                        <label class="perm-check-item" id="lbl-nuevo-password-auth">
                            <input type="checkbox" name="permitir_cambio_password" id="chk-nuevo-password-auth" value="1" onchange="toggleCheckboxStyle('nuevo', 'password-auth')">
                            <div class="perm-check-icon red" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;"><i class="fas fa-key"></i></div>
                            <div class="perm-check-info">
                                <div class="perm-check-name">Habilitar Cambio de Contraseña</div>
                                <div class="perm-check-desc">Permitir temporalmente que este usuario cambie su propia contraseña en su perfil</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" style="flex:1;">
                        <i class="fas fa-user-plus"></i> Crear Usuario
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalNuevo')">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         MODAL — EDITAR USUARIO
    ══════════════════════════════════════════ --}}
    <div class="modal" id="modalEditar">
        <div class="modal-content modal-user-content">
            <div class="modal-header">
                <span><i class="fas fa-user-edit" style="font-size:16px;"></i> Editar Usuario</span>
                <button class="modal-close" onclick="cerrarModal('modalEditar')" type="button">&#x2715;</button>
            </div>

            <form method="POST" action="" id="formEditar">
                @csrf @method('PUT')

                <div class="form-group">
                    <label><i class="fas fa-user"></i> Nombre Completo (No editable)</label>
                    <input type="text" id="editar-name" class="input-readonly" readonly autocomplete="off">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Correo Electrónico</label>
                    <input type="email" name="email" id="editar-email" placeholder="correo@empresa.com" required maxlength="255" autocomplete="off">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Nueva Contraseña <span style="font-weight:400; font-size:11px; color:var(--text-muted);">(opcional)</span></label>
                        <input type="password" name="password" id="editar-password" placeholder="Vacío para no cambiar" minlength="8">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" id="editar-password-confirmation" placeholder="Repite la contraseña" minlength="8">
                    </div>
                </div>
                <p class="pass-hint" style="margin-top:-10px; margin-bottom:15px;"><i class="fas fa-info-circle"></i> Si dejas estos campos vacíos, la contraseña no cambiará.</p>

                {{-- CHECKBOXES DE PERMISOS AL EDITAR --}}
                <div class="form-group" style="margin-bottom:0;">
                    <label><i class="fas fa-shield-alt"></i> Permisos del Sistema</label>
                    <div class="perms-check-group">
                        <label class="perm-check-item" id="lbl-editar-almacen">
                            <input type="checkbox" name="permiso_almacen" id="chk-editar-almacen" value="1" onchange="toggleCheckboxStyle('editar', 'almacen')">
                            <div class="perm-check-icon amber"><i class="fas fa-boxes"></i></div>
                            <div class="perm-check-info">
                                <div class="perm-check-name">Almacenero / Gestión de Almacén</div>
                                <div class="perm-check-desc">Registrar entradas, salidas, artículos y trabajadores</div>
                            </div>
                        </label>
                        <label class="perm-check-item" id="lbl-editar-movimientos">
                            <input type="checkbox" name="permiso_editar_movimientos" id="chk-editar-movimientos" value="1" onchange="toggleCheckboxStyle('editar', 'movimientos')">
                            <div class="perm-check-icon indigo"><i class="fas fa-edit"></i></div>
                            <div class="perm-check-info">
                                <div class="perm-check-name">Editar Movimientos</div>
                                <div class="perm-check-desc">Editar registros de movimientos de entrada y salida guardados</div>
                            </div>
                        </label>
                        <label class="perm-check-item" id="lbl-editar-materiales">
                            <input type="checkbox" name="permiso_editar_materiales" id="chk-editar-materiales" value="1" onchange="toggleCheckboxStyle('editar', 'materiales')">
                            <div class="perm-check-icon green"><i class="fas fa-cubes"></i></div>
                            <div class="perm-check-info">
                                <div class="perm-check-name">Editar Materiales</div>
                                <div class="perm-check-desc">Modificar datos de materiales registrados en el inventario</div>
                            </div>
                        </label>
                        <label class="perm-check-item" id="lbl-editar-reportes">
                            <input type="checkbox" name="permiso_reportes" id="chk-editar-reportes" value="1" onchange="toggleCheckboxStyle('editar', 'reportes')">
                            <div class="perm-check-icon blue"><i class="fas fa-file-invoice"></i></div>
                            <div class="perm-check-info">
                                <div class="perm-check-name">Contabilidad / Reportes</div>
                                <div class="perm-check-desc">Ver y descargar reportes, kardex y bitácora de auditoría</div>
                            </div>
                        </label>
                        <label class="perm-check-item" id="lbl-editar-password-auth">
                            <input type="checkbox" name="permitir_cambio_password" id="chk-editar-password-auth" value="1" onchange="toggleCheckboxStyle('editar', 'password-auth')">
                            <div class="perm-check-icon red" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;"><i class="fas fa-key"></i></div>
                            <div class="perm-check-info">
                                <div class="perm-check-name">Habilitar Cambio de Contraseña</div>
                                <div class="perm-check-desc">Permitir temporalmente que este usuario cambie su propia contraseña en su perfil</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" style="flex:1;">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalEditar')">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
function abrirModalNuevo() {
    document.getElementById('modalNuevo').classList.add('active');
    document.body.style.overflow = 'hidden';
    
    // Inicializar clases 'selected' basadas en el estado del checkbox
    syncCheckboxClasses('nuevo');
}

function abrirModalEditar(id, nombre, email, permAlmacen, permReportes, permMovimientos, permMateriales, permPassword) {
    const form = document.getElementById('formEditar');
    form.action = `/usuarios/${id}`;

    document.getElementById('editar-name').value = nombre;
    document.getElementById('editar-email').value = email;
    document.getElementById('editar-password').value = '';
    document.getElementById('editar-password-confirmation').value = '';

    // Asignar los estados de los checkboxes
    document.getElementById('chk-editar-almacen').checked = permAlmacen;
    document.getElementById('chk-editar-reportes').checked = permReportes;
    document.getElementById('chk-editar-movimientos').checked = permMovimientos;
    document.getElementById('chk-editar-materiales').checked = permMateriales;
    document.getElementById('chk-editar-password-auth').checked = permPassword;

    syncCheckboxClasses('editar');

    document.getElementById('modalEditar').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function cerrarModal(id) {
    document.getElementById(id).classList.remove('active');
    document.body.style.overflow = '';
}

// Alternar clase 'selected' según el checkbox cambie
function toggleCheckboxStyle(prefix, target) {
    const chk = document.getElementById(`chk-${prefix}-${target}`);
    const lbl = document.getElementById(`lbl-${prefix}-${target}`);
    if (chk && lbl) {
        if (chk.checked) {
            lbl.classList.add('selected');
        } else {
            lbl.classList.remove('selected');
        }
    }
}

// Sincronizar clases 'selected' al abrir el modal
function syncCheckboxClasses(prefix) {
    toggleCheckboxStyle(prefix, 'almacen');
    toggleCheckboxStyle(prefix, 'reportes');
    toggleCheckboxStyle(prefix, 'movimientos');
    toggleCheckboxStyle(prefix, 'materiales');
    toggleCheckboxStyle(prefix, 'password-auth');
}

// Cerrar al pulsar fuera del modal
document.querySelectorAll('.modal').forEach(m => {
    m.addEventListener('click', function(e) {
        if (e.target === this) cerrarModal(this.id);
    });
});

// ESC para cerrar
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal.active').forEach(m => cerrarModal(m.id));
    }
});

document.addEventListener('DOMContentLoaded', () => {
    syncCheckboxClasses('nuevo');
});
</script>
@endpush
