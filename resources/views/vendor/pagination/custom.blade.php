@if ($paginator->hasPages())
    <nav class="pagination-mina">
        {{-- BOTÓN ANTERIOR --}}
        @if ($paginator->onFirstPage())
            <span class="pag-btn pag-disabled">
                <i class="fas fa-chevron-left"></i>
                <span>Anterior</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pag-btn">
                <i class="fas fa-chevron-left"></i>
                <span>Anterior</span>
            </a>
        @endif

        {{-- INFO DE PÁGINA --}}
        <div class="pag-info">
            <span class="pag-info-current">
                Página <strong>{{ $paginator->currentPage() }}</strong>
                de <strong>{{ $paginator->lastPage() }}</strong>
            </span>
            <span class="pag-info-total">
                {{ $paginator->total() }} registros en total
            </span>
        </div>

        {{-- BOTÓN SIGUIENTE --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pag-btn">
                <span>Siguiente</span>
                <i class="fas fa-chevron-right"></i>
            </a>
        @else
            <span class="pag-btn pag-disabled">
                <span>Siguiente</span>
                <i class="fas fa-chevron-right"></i>
            </span>
        @endif
    </nav>
@endif

<style>
.pagination-mina {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
    margin: 30px 0 10px;
}

.pag-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 24px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
    cursor: pointer;
}

.pag-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(102, 126, 234, 0.4);
    color: white;
    text-decoration: none;
}

.pag-btn i {
    font-size: 14px;
}

.pag-btn.pag-disabled {
    background: #e9ecef;
    color: #adb5bd;
    cursor: not-allowed;
    box-shadow: none;
}

.pag-btn.pag-disabled:hover {
    transform: none;
    box-shadow: none;
}

.pag-info {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.pag-info-current {
    font-size: 14px;
    color: #495057;
}

.pag-info-current strong {
    color: #667eea;
    font-size: 16px;
}

.pag-info-total {
    font-size: 12px;
    color: #868e96;
}

/* MODO OSCURO */
[data-theme="dark"] .pag-info-current {
    color: #cbd5e1;
}

[data-theme="dark"] .pag-info-current strong {
    color: #a5b4fc;
}

[data-theme="dark"] .pag-info-total {
    color: #94a3b8;
}

[data-theme="dark"] .pag-btn.pag-disabled {
    background: #334155;
    color: #64748b;
}
</style>
