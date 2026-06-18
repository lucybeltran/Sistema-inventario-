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
        color: #667eea;
        font-size: 24px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .upload-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .upload-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .gallery-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: all 0.3s;
        position: relative;
    }

    .gallery-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
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
    }

    .card-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 6px;
        font-size: 16px;
    }

    .card-code {
        color: #667eea;
        font-size: 13px;
        font-weight: 500;
    }

    .card-actions {
        display: flex;
        gap: 8px;
        padding: 0 16px 16px;
    }

    .card-actions button,
    .card-actions a {
        flex: 1;
        padding: 8px 12px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
        text-decoration: none;
        display: inline-block;
    }

    .btn-upload-image {
        background: #667eea;
        color: white;
    }

    .btn-upload-image:hover {
        background: #5568d3;
    }

    .btn-delete {
        background: #f44336;
        color: white;
    }

    .btn-delete:hover {
        background: #d32f2f;
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
        border-color: #667eea;
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
</style>
@endpush

@section('contenido')
    <div class="gallery-header">
        <h2 class="gallery-title">
            <i class="fas fa-images"></i> Imágenes de Artículos
        </h2>
        @if(Auth::user()->puedeEditar())
            <button class="upload-btn" onclick="openUploadModal()">
                <i class="fas fa-cloud-upload-alt"></i> Subir Imagen
            </button>
        @endif
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
                            <button class="btn-upload-image" onclick="openUploadModal({{ $articulo->id }})">
                                <i class="fas fa-upload"></i> {{ $articulo->imagen ? 'Cambiar' : 'Subir' }}
                            </button>
                        @endif
                        @if($articulo->imagen)
                            <form method="POST" action="{{ route('galeria.destroy', $articulo->id) }}" style="flex: 1;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('¿Eliminar imagen?')">
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
<script>
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
