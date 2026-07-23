@extends('layouts.mina')

@section('titulo', 'Galería')

@push('styles')
<style>
    .gallery-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .gallery-title {
        color: var(--text-primary);
        font-size: 24px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .gallery-title i {
        color: var(--primary);
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .gallery-card {
        background: var(--bg-card);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        transition: all 0.3s;
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .gallery-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }

    .image-placeholder {
        width: 100%;
        height: 180px;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .image-placeholder i {
        font-size: 50px;
        color: #999;
        opacity: 0.5;
    }

    .card-content {
        padding: 16px;
        text-align: center;
        background: var(--bg-card);
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 4px;
    }

    .card-title {
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 6px;
        font-size: 16px;
    }

    .card-code {
        color: #d97706;
        font-size: 13px;
        font-weight: 600;
    }

    .card-actions {
        display: flex;
        gap: 8px;
        padding: 0 16px 16px;
        align-items: stretch;
        margin-top: auto;
    }

    .card-actions button,
    .card-actions a,
    .card-actions form {
        flex: 1;
        display: flex;
    }

    .card-actions form button,
    .card-actions > button,
    .card-actions > a {
        width: 100% !important;
    }

    /* Colores suavizados (más bajitos) para botones en tarjetas */
    .card-actions .btn-upload-image {
        background: linear-gradient(135deg, #4f7bb0 0%, #6892cc 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 3px 8px rgba(79, 123, 176, 0.2) !important;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3) !important;
    }
    .card-actions .btn-upload-image:hover {
        background: linear-gradient(135deg, #5c8bbf 0%, #7aa2d6 100%) !important;
        box-shadow: 0 6px 14px rgba(79, 123, 176, 0.35) !important;
    }

    .card-actions .btn-delete {
        background: linear-gradient(135deg, #b85a54 0%, #cc726c 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 3px 8px rgba(184, 90, 84, 0.2) !important;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3) !important;
    }
    .card-actions .btn-delete:hover {
        background: linear-gradient(135deg, #c76a64 0%, #d98580 100%) !important;
        box-shadow: 0 6px 14px rgba(184, 90, 84, 0.35) !important;
    }

    /* Buscador y barra de filtros */
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

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }

    .modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: white;
        padding: 30px;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    }

    .modal-header {
        font-size: 20px;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: #999;
        cursor: pointer;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #555;
        font-size: 14px;
    }

    .form-group select,
    .form-group input[type="file"] {
        width: 100%;
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-group input[type="file"]:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--primary);
    }

    .modal-actions {
        display: flex;
        gap: 10px;
        margin-top: 24px;
    }

    .btn-cancel,
    .btn-submit {
        flex: 1;
        padding: 12px;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-cancel {
        background: #f0f0f0;
        color: #666;
    }

    .btn-cancel:hover {
        background: #e0e0e0;
    }

    .btn-submit {
        background: linear-gradient(135deg, #e65100 0%, #f57c00 100%);
        color: white;
    }

    .btn-submit:hover {
        transform: translateY(-1px);
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #999;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 16px;
        display: block;
        color: #ccc;
    }

    /* ===== MODO OSCURO - GALERÍA ===== */
    [data-theme="dark"] .gallery-card {
        background: #1e293b;
    }

    [data-theme="dark"] .card-content {
        background: #1e293b;
    }

    [data-theme="dark"] .card-title {
        color: #f1f5f9 !important;
    }

    [data-theme="dark"] .card-code {
        color: #a5b4fc !important;
    }

    [data-theme="dark"] .modal-content {
        background: #1e293b;
    }

    [data-theme="dark"] .modal-header {
        color: #f1f5f9;
    }

    [data-theme="dark"] .form-group label {
        color: #cbd5e1;
    }

    [data-theme="dark"] .empty-state {
        color: #94a3b8;
    }
</style>
@endpush

@section('contenido')
    <div class="gallery-header">
        <h2 class="gallery-title">
            <i class="fas fa-images"></i> Imágenes de Artículos
        </h2>
        @if(Auth::user()->puedeEditar())
            <button class="btn btn-success" onclick="openUploadModal()">
                <i class="fas fa-cloud-upload-alt"></i> Subir Imagen
            </button>
        @endif
    </div>

    {{-- Barra de filtros y búsqueda --}}
    <div class="filters-row">
        <form method="GET" action="{{ route('galeria.index') }}" class="search-box">
            @if(request()->filled('filtro'))
                <input type="hidden" name="filtro" value="{{ request('filtro') }}">
            @endif
            <input type="text" name="buscar" placeholder="Buscar por código o nombre..." value="{{ request('buscar') }}">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
            </button>
            @if(request()->filled('buscar'))
                <a href="{{ route('galeria.index', request()->only('filtro')) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>

        <div style="font-size: 13px; color: var(--text-muted); font-weight: 500;">
            Mostrando {{ $articulos->firstItem() ?? 0 }} - {{ $articulos->lastItem() ?? 0 }} de {{ $articulos->total() }} artículos
        </div>
    </div>

    @if($articulos->count() > 0)
        <div class="gallery-grid">
            @foreach($articulos as $articulo)
                <div class="gallery-card">
                    <div class="image-placeholder">
                        @if($articulo->imagen)
                            <img src="{{ asset('storage/' . $articulo->imagen) }}" 
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="fas fa-image"></i>
                        @endif
                    </div>
                    <div class="card-content">
                        <div class="card-title">{{ $articulo->nombre }}</div>
                        <div class="card-code">{{ $articulo->codigo }}</div>
                    </div>
                    <div class="card-actions">
                        @if(Auth::user()->puedeEditar())
                            <button class="btn-upload-image btn-sm" onclick="openUploadModal({{ $articulo->id }})">
                                <i class="fas fa-upload"></i> {{ $articulo->imagen ? 'Cambiar' : 'Subir' }}
                            </button>
                        @endif
                        @if($articulo->imagen)
                            <form method="POST" action="{{ route('galeria.destroy', $articulo->id) }}" style="flex: 1;" onsubmit="return confirmarEliminarImagen(this)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete btn-sm">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        @if($articulos->hasPages())
            <div style="display: flex; justify-content: center; margin-top: 30px;">
                {{ $articulos->links() }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <p>No hay artículos para mostrar</p>
        </div>
    @endif

    <!-- Modal para subir imagen -->
    @if(Auth::user()->puedeEditar())
    <div id="uploadModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span>Subir Imagen</span>
                <button class="modal-close" onclick="closeUploadModal()">×</button>
            </div>
            <form id="uploadForm" enctype="multipart/form-data" method="POST" action="{{ route('galeria.upload') }}">
                @csrf
                <div class="form-group">
                    <label for="articulo_id">Seleccionar Artículo</label>
                    <select id="articulo_id" name="articulo_id" required>
                        <option value="">-- Seleccionar --</option>
                        @foreach($articulos as $art)
                            <option value="{{ $art->id }}">{{ $art->codigo }} - {{ $art->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="imagen">Seleccionar Imagen</label>
                    <input type="file" id="imagen" name="imagen" accept="image/*" required>
                    <small style="color: #999; display: block; margin-top: 6px;">
                        Formatos: JPG, PNG, WEBP (máximo 5MB)
                    </small>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeUploadModal()">Cancelar</button>
                    <button type="submit" class="btn-submit">Subir Imagen</button>
                </div>
            </form>
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmarEliminarImagen(form) {
        Swal.fire({
            title: '¿Eliminar imagen?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e53e3e',
            cancelButtonColor: '#475569',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            background: document.documentElement.getAttribute('data-theme') === 'dark' ? '#1e293b' : '#ffffff',
            color: document.documentElement.getAttribute('data-theme') === 'dark' ? '#f1f5f9' : '#1e293b',
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }

    function openUploadModal(articuloId = null) {
        const modal = document.getElementById('uploadModal');
        const select = document.getElementById('articulo_id');
        
        if (articuloId) {
            select.value = articuloId;
        }
        
        modal.classList.add('active');
    }

    function closeUploadModal() {
        const modal = document.getElementById('uploadModal');
        modal.classList.remove('active');
        document.getElementById('uploadForm').reset();
    }

    // Cerrar modal al hacer click afuera
    window.onclick = function(event) {
        const modal = document.getElementById('uploadModal');
        if (event.target === modal) {
            closeUploadModal();
        }
    }
</script>
@endpush
