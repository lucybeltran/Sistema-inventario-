@extends('layouts.mina')

@section('titulo', 'Copia de Seguridad')

@push('styles')
<style>
    .backup-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .action-card {
        background: var(--bg-card, #fff);
        border: 1.5px solid var(--border);
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
        box-shadow: var(--shadow);
    }

    .action-info h3 {
        margin: 0 0 6px 0;
        font-size: 18px;
        color: var(--text-primary);
        font-weight: 700;
    }

    .action-info p {
        margin: 0;
        font-size: 13.5px;
        color: var(--text-muted);
        line-height: 1.5;
    }

    .btn-create-backup {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.25s ease;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.2);
    }

    .btn-create-backup:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(5, 150, 105, 0.35);
    }

    .btn-create-backup:disabled {
        background: #a0aec0;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .backup-table-wrapper {
        background: var(--bg-card, #fff);
        border: 1.5px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow);
    }

    .backup-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .backup-table th {
        background: var(--bg-hover, #f7fafc);
        padding: 14px 18px;
        font-size: 12.5px;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1.5px solid var(--border);
    }

    .backup-table td {
        padding: 14px 18px;
        font-size: 13.5px;
        color: var(--text-primary);
        border-bottom: 1.5px solid var(--border);
    }

    .backup-table tr:last-child td {
        border-bottom: none;
    }

    .backup-actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .backup-btn-action {
        border: 1.5px solid transparent !important;
        padding: 8px 14px !important;
        border-radius: 9px !important;
        font-size: 12.5px !important;
        font-weight: 700 !important;
        cursor: pointer !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        transition: all 0.2s ease !important;
        white-space: nowrap !important;
        text-decoration: none !important;
        box-sizing: border-box !important;
    }

    .backup-btn-action.download {
        border-color: rgba(5, 150, 105, 0.25) !important;
        color: #059669 !important;
        background: rgba(5, 150, 105, 0.06) !important;
    }
    .backup-btn-action.download:hover {
        background: #059669 !important;
        color: white !important;
        border-color: #059669 !important;
        box-shadow: 0 4px 10px rgba(5, 150, 105, 0.2) !important;
    }

    .backup-btn-action.restore {
        border-color: rgba(245, 159, 0, 0.25) !important;
        color: #d97706 !important;
        background: rgba(245, 159, 0, 0.06) !important;
    }
    .backup-btn-action.restore:hover {
        background: #f59f00 !important;
        color: white !important;
        border-color: #f59f00 !important;
        box-shadow: 0 4px 10px rgba(245, 159, 0, 0.2) !important;
    }

    .backup-btn-action.delete {
        border-color: rgba(239, 68, 68, 0.25) !important;
        color: #ef4444 !important;
        background: rgba(239, 68, 68, 0.06) !important;
    }
    .backup-btn-action.delete:hover {
        background: #ef4444 !important;
        color: white !important;
        border-color: #ef4444 !important;
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.2) !important;
    }

    .alert-banner {
        background: rgba(245, 159, 0, 0.06);
        border: 1px solid rgba(245, 159, 0, 0.2);
        border-left: 5px solid #f59f00;
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        font-size: 13.5px;
        color: var(--text-primary);
        line-height: 1.6;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .alert-banner i {
        font-size: 18px;
        color: #f59f00;
        margin-top: 2px;
    }

    .badge-sql {
        background: rgba(99, 102, 241, 0.08);
        color: #4f46e5;
        border: 1.5px solid rgba(99, 102, 241, 0.2);
        padding: 3px 8px;
        border-radius: 6px;
        font-family: monospace;
        font-size: 11px;
        font-weight: 700;
    }

    /* Modal de carga para restauración */
    .loader-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        z-index: 999999;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        color: white;
        font-family: inherit;
    }

    .loader-spinner {
        border: 4px solid rgba(255, 255, 255, 0.1);
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border-left-color: #f97316;
        animation: spin 1s linear infinite;
        margin-bottom: 16px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    /* Estilos del panel de programador de backup */
    .backup-schedule-card {
        background: var(--bg-card, #fff);
        border: 1.5px solid var(--border);
        border-radius: 20px;
        padding: 24px;
        margin-bottom: 25px;
        box-shadow: var(--shadow-sm);
    }
    
    .backup-schedule-card h3 {
        margin: 0 0 16px 0;
        font-size: 15.5px;
        color: var(--text-primary);
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .backup-schedule-grid {
        display: grid;
        grid-template-columns: 1.2fr 1.2fr 1.5fr;
        gap: 25px;
        align-items: stretch;
    }
    
    @media (max-width: 900px) {
        .backup-schedule-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .backup-schedule-col {
            border-right: none !important;
            padding-right: 0 !important;
            border-bottom: 1.5px dashed var(--border);
            padding-bottom: 20px;
        }
        
        .backup-schedule-col:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
    }

    .backup-form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .backup-form-group label {
        font-size: 12.5px;
        font-weight: 700;
        color: var(--text-secondary);
    }

    .backup-input {
        height: 42px;
        padding: 0 14px;
        border-radius: 10px;
        border: 1.5px solid var(--border);
        background: var(--bg-main, #f7fafc);
        color: var(--text-primary);
        font-size: 13.5px;
        font-weight: 600;
        outline: none;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }

    .backup-input:focus {
        border-color: #d97706;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15);
    }

    select.backup-input {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%234b5563'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px;
        padding-right: 36px;
        cursor: pointer;
    }
    
    [data-theme="dark"] select.backup-input {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239ca3af'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
    }

    .btn-save-schedule {
        background: #d97706;
        color: white;
        border: none;
        padding: 0 24px;
        height: 42px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 13.5px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        box-shadow: 0 4px 10px rgba(217, 119, 6, 0.2);
    }

    .btn-save-schedule:hover {
        background: #f59e0b;
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(217, 119, 6, 0.3);
    }

    /* Switch Premium Toggle */
    .switch-container {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 26px;
        flex-shrink: 0;
    }
    .switch-container input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .switch-slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #cbd5e1;
        transition: .3s;
        border-radius: 34px;
    }
    .switch-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.15);
    }
    input:checked + .switch-slider {
        background-color: #d97706;
    }
    input:checked + .switch-slider:before {
        transform: translateX(22px);
    }

    /* Indicador de Estado Pulso */
    .glow-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }
    .glow-dot.active {
        background-color: #10b981;
        box-shadow: 0 0 10px #10b981;
        animation: pulse 1.8s infinite;
    }
    .glow-dot.disabled {
        background-color: #94a3b8;
    }
    
    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
</style>
@endpush

@section('contenido')
<div class="backup-container">

    {{-- HEADER CON BOTONES PRINCIPALES DE ACCIÓN --}}
    <div class="page-header" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px; margin-bottom:25px; border-bottom:1px solid var(--border); padding-bottom:18px;">
        <div>
            <h2 class="page-title" style="margin:0 0 4px 0; font-size:22px; font-weight:800; color:var(--text-primary); display:flex; align-items:center; gap:8px;">
                <i class="fas fa-database" style="color:var(--primary);"></i> Copias de Seguridad (Backups)
            </h2>
            <p class="page-subtitle" style="margin:0; font-size:13.5px; color:var(--text-secondary);">
                Gestión integral de respaldos ZIP, imágenes de storage y restauración inteligente sin duplicados
            </p>
        </div>
        
        <div style="display:flex; align-items:center; gap:12px;">
            <!-- Input oculto para carga de archivos de respaldo -->
            <input type="file" id="backup-file-input" style="display:none;" onchange="subirRestaurarZip(this)" accept=".zip,.sql">
            
            <button type="button" class="btn-create-backup" onclick="document.getElementById('backup-file-input').click()" style="background:#1e293b; color:#cbd5e1; border:1px solid #334155; padding:10px 20px; border-radius:10px; font-weight:700; font-size:13.5px; cursor:pointer; display:inline-flex; align-items:center; gap:8px; transition:all 0.25s ease;" onmouseover="this.style.background='#334155'; this.style.color='#f1f5f9';" onmouseout="this.style.background='#1e293b'; this.style.color='#cbd5e1';">
                <i class="fas fa-cloud-upload-alt" style="color:#eab308; font-size:15px;"></i> Subir & Restaurar ZIP
            </button>
            
            <button type="button" class="btn-create-backup" id="btnGenerarBackup" onclick="generarBackup()" style="background:linear-gradient(135deg, #d97706 0%, #f59e0b 100%); color:white; border:none; padding:10px 22px; border-radius:10px; font-weight:700; font-size:13.5px; cursor:pointer; display:inline-flex; align-items:center; gap:8px; transition:all 0.25s ease; box-shadow:0 4px 12px rgba(217, 119, 6, 0.2);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 18px rgba(217, 119, 6, 0.35)';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 12px rgba(217, 119, 6, 0.2)';">
                <i class="fas fa-cloud-download-alt"></i> Generar Respaldo Ahora
            </button>
        </div>
    </div>

    {{-- ADVERTENCIA / RECOMENDACIÓN DE DESCARGA MENSUAL --}}
    <div class="alert-banner" style="border-left-color: #eab308; background: rgba(234, 179, 8, 0.05); border-color: rgba(234, 179, 8, 0.2); display: flex; align-items: flex-start; gap: 12px; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
        <i class="fas fa-exclamation-triangle" style="color: #eab308; font-size: 20px; margin-top: 2px;"></i>
        <div>
            <strong style="color: #a16207; font-size: 13.5px;">💡 Recomendación importante de Seguridad:</strong>
            <p style="margin: 4px 0 0; font-size: 12.5px; color: #854d0e; line-height: 1.5;">
                Para evitar la pérdida total de datos por fallas de hardware en este equipo, te sugerimos 
                <strong>descargar el archivo ZIP de tus respaldos al menos una vez al mes</strong> y guardarlo en un disco externo, pendrive o en la nube. Así tendrás tu información y fotos a salvo.
            </p>
        </div>
    </div>

    {{-- CARD DE CONFIGURACIÓN AUTOMÁTICA PREMIUM --}}
    <div class="backup-schedule-card">
        <h3 style="margin: 0 0 20px 0; font-size: 16px; color: var(--text-primary); font-weight: 800; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--border); padding-bottom: 12px;">
            <i class="fas fa-clock" style="color: #d97706; font-size: 18px;"></i> Programación de Respaldos Automáticos
        </h3>
        
        <form id="form-config-backup" style="margin: 0;">
            <div class="backup-schedule-grid">
                
                {{-- Columna 1: Interruptor y Estado General --}}
                <div class="backup-schedule-col" style="border-right: 1.5px solid var(--border); padding-right: 20px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                        <div style="width: 38px; height: 38px; border-radius: 50%; background: rgba(217, 119, 6, 0.08); display: flex; align-items: center; justify-content: center; color: #d97706; font-size: 16px; flex-shrink: 0;">
                            <i class="fas fa-power-off"></i>
                        </div>
                        <h4 style="margin: 0; font-size: 14px; font-weight: 700; color: var(--text-primary);">Estado del Servicio</h4>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <label class="switch-container">
                            <input type="checkbox" id="backup_auto_habilitado" name="habilitado" value="1" {{ ($config['habilitado'] ?? '0') === '1' ? 'checked' : '' }}>
                            <span class="switch-slider"></span>
                        </label>
                        <span style="font-size: 14px; font-weight: 700; color: var(--text-primary);">Activar respaldos automáticos</span>
                    </div>
                    <p style="margin: 0; font-size: 12.5px; color: var(--text-muted); line-height: 1.5;">
                        Cuando está activo, el servidor ejecutará y guardará copias de seguridad de la base de datos e imágenes en segundo plano.
                    </p>
                </div>

                {{-- Columna 2: Frecuencia y Hora --}}
                <div class="backup-schedule-col" style="border-right: 1.5px solid var(--border); padding-right: 20px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 15px;">
                        <div style="width: 38px; height: 38px; border-radius: 50%; background: rgba(217, 119, 6, 0.08); display: flex; align-items: center; justify-content: center; color: #d97706; font-size: 16px; flex-shrink: 0;">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h4 style="margin: 0; font-size: 14px; font-weight: 700; color: var(--text-primary);">Configurar Cronograma</h4>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 14px;">
                        <div class="backup-form-group">
                            <label>Frecuencia de Ejecución</label>
                            <select id="backup_auto_frecuencia" name="frecuencia" class="backup-input" onchange="mostrarOpcionesFrecuencia(this.value)">
                                <option value="ultimo_dia_mes" {{ ($config['frecuencia'] ?? 'ultimo_dia_mes') === 'ultimo_dia_mes' ? 'selected' : '' }}>Mensual (Último día del mes)</option>
                                <option value="fecha_unica" {{ ($config['frecuencia'] ?? '') === 'fecha_unica' ? 'selected' : '' }}>Fecha Exacta (Una sola vez)</option>
                            </select>
                        </div>

                        {{-- Inputs ocultos de retrocompatibilidad --}}
                        <input type="hidden" id="backup_auto_dia_semana" value="1">
                        <input type="hidden" id="backup_auto_dia_mes" value="ultimo">

                        <div class="backup-form-group">
                            <label>Hora del Respaldo</label>
                            <input type="time" id="backup_auto_hora" name="hora" class="backup-input" value="{{ $config['hora'] ?? '23:00' }}">
                        </div>

                        {{-- Detalles Dinámicos: Fecha Única --}}
                        <div id="wrapper-fecha-unica" class="backup-form-group" style="display:none;">
                            <label>Fecha de Ejecución</label>
                            <input type="date" id="backup_auto_fecha_unica" name="fecha_unica" class="backup-input" value="{{ $config['fecha_unica'] ?? '' }}">
                        </div>
                    </div>
                </div>

                {{-- Columna 3: Próxima Ejecución y Confirmación --}}
                <div class="backup-schedule-col" style="display: flex; flex-direction: column; justify-content: space-between; min-height: 180px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="width: 38px; height: 38px; border-radius: 50%; background: rgba(217, 119, 6, 0.08); display: flex; align-items: center; justify-content: center; color: #d97706; font-size: 16px; flex-shrink: 0;">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <h4 style="margin: 0; font-size: 14px; font-weight: 700; color: var(--text-primary);">Próxima Ejecución</h4>
                    </div>

                    {{-- Card/Badge del próximo respaldo --}}
                    <div id="info-proximo-backup" style="flex-grow: 1; margin-bottom: 12px; border-radius: 12px; padding: 14px; display: flex; align-items: center; justify-content: center; text-align: center; box-sizing: border-box; transition: all 0.3s ease; border: 1.5px dashed var(--border); background: var(--bg-main);">
                        <!-- Se calcula dinámicamente con JS -->
                    </div>

                    {{-- Botón Guardar --}}
                    <button type="button" onclick="guardarConfiguracionBackup()" class="btn-save-schedule" style="width: 100%; justify-content: center; height: 42px; font-size: 14px;">
                        <i class="fas fa-save"></i> Guardar Programación
                    </button>
                </div>

            </div>
        </form>
    </div>

    {{-- LISTADO DE RESPALDOS --}}
    <div class="backup-table-wrapper">
        @if(count($backups) === 0)
            <div style="padding: 40px; text-align: center; color: var(--text-muted);">
                <i class="fas fa-folder-open" style="font-size: 40px; opacity: 0.5; margin-bottom: 12px; display: block;"></i>
                <p style="margin: 0; font-size: 14.5px;">Aún no se han generado copias de seguridad de la base de datos.</p>
            </div>
        @else
            <table class="backup-table">
                <thead>
                    <tr>
                        <th>Archivo</th>
                        <th>Fecha de Creación</th>
                        <th>Tamaño</th>
                        <th style="text-align: right; width: 220px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($backups as $b)
                        <tr>
                            <td>
                                @if(Str::endsWith($b['filename'], '.zip'))
                                    <span class="badge-sql" style="background: rgba(249, 115, 22, 0.08); color: #f97316; border-color: rgba(249, 115, 22, 0.2);"><i class="fas fa-file-archive"></i> ZIP</span>
                                @else
                                    <span class="badge-sql"><i class="fas fa-file-code"></i> SQL</span>
                                @endif
                                <strong style="margin-left: 6px; font-size: 13.5px; color: var(--text-primary);">{{ $b['filename'] }}</strong>
                            </td>
                            <td style="color: var(--text-secondary);">
                                {{ $b['created_at']->format('d/m/Y H:i:s') }}
                                <span style="font-size: 11px; opacity: 0.7; margin-left: 4px;">({{ $b['created_at']->diffForHumans() }})</span>
                            </td>
                            <td style="font-weight: 600; color: var(--text-secondary);">
                                {{ $b['size'] }}
                            </td>
                            <td style="text-align: right;">
                                <div class="backup-actions" style="justify-content: flex-end;">
                                    <a href="{{ route('backups.download', $b['filename']) }}" class="backup-btn-action download" title="Descargar archivo en local">
                                        <i class="fas fa-download"></i> Descargar
                                    </a>
                                    <button type="button" class="backup-btn-action delete" onclick="confirmarEliminacion('{{ $b['filename'] }}')" title="Eliminar este archivo de respaldo">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>

{{-- Pantalla de carga animada durante las acciones AJAX --}}
<div class="loader-overlay" id="loaderOverlay">
    <div class="loader-spinner"></div>
    <h3 style="margin: 0 0 8px 0; font-weight: 700; color: white;" id="loaderTitle">Procesando...</h3>
    <p style="margin: 0; font-size: 13.5px; opacity: 0.8;" id="loaderSubtitle">Por favor, no cierres esta ventana.</p>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showLoader(title, subtitle) {
        document.getElementById('loaderTitle').textContent = title;
        document.getElementById('loaderSubtitle').textContent = subtitle;
        document.getElementById('loaderOverlay').style.display = 'flex';
    }

    function hideLoader() {
        document.getElementById('loaderOverlay').style.display = 'none';
    }

    // Generar copia de seguridad por AJAX
    function generarBackup() {
        const btn = document.getElementById('btnGenerarBackup');
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando...';
        showLoader('Generando Copia de Seguridad', 'Leyendo base de datos y preparando archivo SQL...');

        fetch("{{ route('backups.create') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-cloud-download-alt"></i> Crear Respaldo Ahora';
            hideLoader();

            if (data.success) {
                Swal.fire({
                    title: '¡Copia Creada!',
                    text: 'El respaldo se generó correctamente en el servidor.',
                    icon: 'success',
                    confirmButtonColor: '#10b981',
                    background: isDark ? '#1e293b' : '#ffffff',
                    color: isDark ? '#f1f5f9' : '#1e293b',
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.error || 'Ocurrió un error al intentar crear el respaldo.',
                    icon: 'error',
                    confirmButtonColor: '#6366f1',
                    background: isDark ? '#1e293b' : '#ffffff',
                    color: isDark ? '#f1f5f9' : '#1e293b',
                });
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-cloud-download-alt"></i> Crear Respaldo Ahora';
            hideLoader();
            console.error(err);
            Swal.fire({
                title: 'Error de conexión',
                text: 'No se pudo comunicar con el servidor.',
                icon: 'error',
                confirmButtonColor: '#6366f1',
                background: isDark ? '#1e293b' : '#ffffff',
                color: isDark ? '#f1f5f9' : '#1e293b',
            });
        });
    }

    // Confirmar y eliminar archivo de respaldo
    function confirmarEliminacion(filename) {
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';

        Swal.fire({
            title: '¿Eliminar copia de seguridad?',
            text: `El archivo "${filename}" se eliminará permanentemente. Esta acción no se puede deshacer.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#475569',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            background: isDark ? '#1e293b' : '#ffffff',
            color: isDark ? '#f1f5f9' : '#1e293b',
        }).then((result) => {
            if (result.isConfirmed) {
                showLoader('Eliminando Respaldo', 'Eliminando archivo físico del servidor...');
                
                fetch(`/backups/eliminar/${filename}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    hideLoader();
                    if (data.success) {
                        Swal.fire({
                            title: '¡Eliminado!',
                            text: 'El archivo de respaldo fue eliminado correctamente.',
                            icon: 'success',
                            confirmButtonColor: '#10b981',
                            background: isDark ? '#1e293b' : '#ffffff',
                            color: isDark ? '#f1f5f9' : '#1e293b',
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.error || 'Error al eliminar el archivo.',
                            icon: 'error',
                            confirmButtonColor: '#6366f1',
                            background: isDark ? '#1e293b' : '#ffffff',
                            color: isDark ? '#f1f5f9' : '#1e293b',
                        });
                    }
                })
                .catch(err => {
                    hideLoader();
                    console.error(err);
                    Swal.fire({
                        title: 'Error de conexión',
                        text: 'No se pudo comunicar con el servidor.',
                        icon: 'error',
                        confirmButtonColor: '#6366f1',
                        background: isDark ? '#1e293b' : '#ffffff',
                        color: isDark ? '#f1f5f9' : '#1e293b',
                    });
                });
            }
        });
    }

    // Confirmar y restaurar la base de datos
    function confirmarRestauracion(filename) {
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';

        Swal.fire({
            title: '⚠️ ADVERTENCIA CRÍTICA ⚠️',
            html: `¿Estás seguro de que deseas restaurar el sistema al punto del archivo <strong>${filename}</strong>?<br><br>Toda la información ingresada después de este respaldo se perderá permanentemente.<br><br>Escribe <strong>RESTAURAR</strong> abajo para confirmar:`,
            icon: 'warning',
            input: 'text',
            inputPlaceholder: 'Escribe RESTAURAR aquí...',
            showCancelButton: true,
            confirmButtonColor: '#f59f00',
            cancelButtonColor: '#475569',
            confirmButtonText: 'Confirmar Restauración',
            cancelButtonText: 'Cancelar',
            background: isDark ? '#1e293b' : '#ffffff',
            color: isDark ? '#f1f5f9' : '#1e293b',
            inputValidator: (value) => {
                if (value !== 'RESTAURAR') {
                    return 'Debes escribir la palabra exacta RESTAURAR para proceder.';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showLoader('Restaurando Base de Datos', 'Limpiando tablas y ejecutando script SQL de respaldo...');
                
                fetch(`/backups/restaurar/${filename}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    hideLoader();
                    if (data.success) {
                        Swal.fire({
                            title: '¡Sistema Restaurado!',
                            text: 'El sistema fue restaurado correctamente al punto seleccionado.',
                            icon: 'success',
                            confirmButtonColor: '#10b981',
                            background: isDark ? '#1e293b' : '#ffffff',
                            color: isDark ? '#f1f5f9' : '#1e293b',
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.error || 'Error durante la restauración.',
                            icon: 'error',
                            confirmButtonColor: '#6366f1',
                            background: isDark ? '#1e293b' : '#ffffff',
                            color: isDark ? '#f1f5f9' : '#1e293b',
                        });
                    }
                })
                .catch(err => {
                    hideLoader();
                    console.error(err);
                    Swal.fire({
                        title: 'Error de comunicación',
                        text: 'Ocurrió un error al procesar el script de respaldo en el servidor.',
                        icon: 'error',
                        confirmButtonColor: '#6366f1',
                        background: isDark ? '#1e293b' : '#ffffff',
                        color: isDark ? '#f1f5f9' : '#1e293b',
                    });
                });
            }
        });
    }

    // Subir y restaurar archivo ZIP/SQL
    function subirRestaurarZip(input) {
        if (!input.files || input.files.length === 0) return;

        const file = input.files[0];
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';

        Swal.fire({
            title: '⚠️ ADVERTENCIA CRÍTICA ⚠️',
            html: `¿Estás seguro de que deseas subir e importar el archivo de respaldo <strong>${file.name}</strong>?<br><br>Se sobrescribirá toda la base de datos actual y se integrarán las imágenes del sistema.<br><br>Escribe <strong>RESTAURAR</strong> abajo para confirmar:`,
            icon: 'warning',
            input: 'text',
            inputPlaceholder: 'Escribe RESTAURAR aquí...',
            showCancelButton: true,
            confirmButtonColor: '#d97706',
            cancelButtonColor: '#475569',
            confirmButtonText: 'Subir e Importar Respaldo',
            cancelButtonText: 'Cancelar',
            background: isDark ? '#1e293b' : '#ffffff',
            color: isDark ? '#f1f5f9' : '#1e293b',
            inputValidator: (value) => {
                if (value !== 'RESTAURAR') {
                    return 'Debes escribir la palabra exacta RESTAURAR para proceder.';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showLoader('Subiendo e Importando Respaldo', 'Subiendo archivo ZIP/SQL y procesando restauración en el servidor...');

                const formData = new FormData();
                formData.append('backup_file', file);

                fetch("{{ route('backups.uploadRestore') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    hideLoader();
                    input.value = '';

                    if (data.success) {
                        Swal.fire({
                            title: '¡Importación Completa!',
                            text: 'El sistema fue restaurado correctamente con el archivo subido.',
                            icon: 'success',
                            confirmButtonColor: '#10b981',
                            background: isDark ? '#1e293b' : '#ffffff',
                            color: isDark ? '#f1f5f9' : '#1e293b',
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error de importación',
                            text: data.error || 'Ocurrió un error al procesar el archivo subido.',
                            icon: 'error',
                            confirmButtonColor: '#6366f1',
                            background: isDark ? '#1e293b' : '#ffffff',
                            color: isDark ? '#f1f5f9' : '#1e293b',
                        });
                    }
                })
                .catch(err => {
                    hideLoader();
                    input.value = '';
                    console.error(err);
                    Swal.fire({
                        title: 'Error de comunicación',
                        text: 'No se pudo subir el archivo o procesarlo en el servidor.',
                        icon: 'error',
                        confirmButtonColor: '#6366f1',
                        background: isDark ? '#1e293b' : '#ffffff',
                        color: isDark ? '#f1f5f9' : '#1e293b',
                    });
                });
            } else {
                input.value = '';
            }
        });
    }
    function mostrarOpcionesFrecuencia(frecuencia) {
        const wrapperFechaUnica = document.getElementById('wrapper-fecha-unica');
        if (wrapperFechaUnica) {
            wrapperFechaUnica.style.display = (frecuencia === 'fecha_unica') ? 'block' : 'none';
        }
        calcularProximaEjecucion();
    }

    function calcularProximaEjecucion() {
        const habilitado = document.getElementById('backup_auto_habilitado').checked;
        const infoProximo = document.getElementById('info-proximo-backup');
        
        if (!infoProximo) return;
        
        if (!habilitado) {
            infoProximo.innerHTML = '<div style="padding: 10px 0;"><span class="glow-dot disabled" style="margin-bottom: 6px;"></span><br><span style="color: var(--text-muted); font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">Desactivado</span><br><span style="font-size:11.5px; color:var(--text-secondary); opacity:0.8; display:block; margin-top:2px;">Respaldos automáticos apagados</span></div>';
            infoProximo.style.borderColor = 'var(--border)';
            infoProximo.style.background = 'var(--bg-main)';
            return;
        }

        const horaInput = document.getElementById('backup_auto_hora').value || '23:00';
        const frecuencia = document.getElementById('backup_auto_frecuencia').value;
        const fechaUnica = document.getElementById('backup_auto_fecha_unica').value;

        if (frecuencia === 'fecha_unica') {
            if (!fechaUnica) {
                infoProximo.innerHTML = '<div style="padding: 10px 0;"><span style="color: #ef4444; font-size:12px; font-weight:700;"><i class="fas fa-exclamation-circle" style="font-size:16px; margin-bottom:4px; display:block;"></i> SELECCIONA FECHA</span></div>';
                infoProximo.style.borderColor = 'rgba(239, 68, 68, 0.3)';
                infoProximo.style.background = 'rgba(239, 68, 68, 0.03)';
                return;
            }
            const partes = fechaUnica.split('-');
            const fechaFormateada = `${partes[2]}/${partes[1]}/${partes[0]}`;
            infoProximo.innerHTML = `<div style="padding: 6px 0;"><span class="glow-dot active" style="margin-bottom: 6px; background-color:#ea580c; box-shadow:0 0 10px #ea580c;"></span><br><span style="color: #ea580c; font-size:12px; font-weight:800; text-transform:uppercase; letter-spacing:0.5px;">Fecha Única</span><br><span style="font-size:13px; color:var(--text-primary); font-weight:700; margin-top:3px; display:block;">${fechaFormateada} a las ${horaInput}</span></div>`;
            infoProximo.style.borderColor = 'rgba(234, 88, 12, 0.3)';
            infoProximo.style.background = 'rgba(234, 88, 12, 0.04)';
        } else if (frecuencia === 'ultimo_dia_mes') {
            const hoy = new Date();
            const [hora, min] = horaInput.split(':').map(Number);
            
            const ultimoDiaActual = new Date(hoy.getFullYear(), hoy.getMonth() + 1, 0);
            ultimoDiaActual.setHours(hora, min, 0, 0);

            let fechaProxima;
            if (hoy.getTime() > ultimoDiaActual.getTime()) {
                fechaProxima = new Date(hoy.getFullYear(), hoy.getMonth() + 2, 0);
            } else {
                fechaProxima = ultimoDiaActual;
            }

            const dia = String(fechaProxima.getDate()).padStart(2, '0');
            const mes = String(fechaProxima.getMonth() + 1).padStart(2, '0');
            const anio = fechaProxima.getFullYear();
            const fechaFormateada = `${dia}/${mes}/${anio}`;

            infoProximo.innerHTML = `<div style="padding: 6px 0;"><span class="glow-dot active" style="margin-bottom: 6px;"></span><br><span style="color: #10b981; font-size:12px; font-weight:800; text-transform:uppercase; letter-spacing:0.5px;">Fin de Mes</span><br><span style="font-size:13px; color:var(--text-primary); font-weight:700; margin-top:3px; display:block;">${fechaFormateada} a las ${horaInput}</span></div>`;
            infoProximo.style.borderColor = 'rgba(16, 185, 129, 0.3)';
            infoProximo.style.background = 'rgba(16, 185, 129, 0.04)';
        }
    }

    // Inicializar visualización de campos al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        const selectFrecuencia = document.getElementById('backup_auto_frecuencia');
        if (selectFrecuencia) {
            mostrarOpcionesFrecuencia(selectFrecuencia.value);
        }

        // Registrar oyentes para recálculo dinámico en tiempo real
        const inputs = [
            'backup_auto_habilitado',
            'backup_auto_hora',
            'backup_auto_frecuencia',
            'backup_auto_fecha_unica'
        ];
        inputs.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', calcularProximaEjecucion);
                el.addEventListener('change', calcularProximaEjecucion);
            }
        });

        calcularProximaEjecucion();
    });

    // Guardar configuración de respaldo automático programado
    function guardarConfiguracionBackup() {
        const habilitado = document.getElementById('backup_auto_habilitado').checked ? '1' : '0';
        const hora = document.getElementById('backup_auto_hora').value;
        const frecuencia = document.getElementById('backup_auto_frecuencia').value;
        const dia_semana = document.getElementById('backup_auto_dia_semana').value;
        const dia_mes = document.getElementById('backup_auto_dia_mes').value;
        const fecha_unica = document.getElementById('backup_auto_fecha_unica').value;
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';

        showLoader('Guardando Configuración', 'Actualizando base de datos de configuraciones...');

        fetch("{{ route('backups.saveSettings') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                habilitado: habilitado,
                hora: hora,
                frecuencia: frecuencia,
                dia_semana: dia_semana,
                dia_mes: dia_mes,
                fecha_unica: fecha_unica
            })
        })
        .then(res => res.json())
        .then(data => {
            hideLoader();
            if (data.success) {
                Swal.fire({
                    title: '¡Configuración Guardada!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonColor: '#10b981',
                    background: isDark ? '#1e293b' : '#ffffff',
                    color: isDark ? '#f1f5f9' : '#1e293b',
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.error || 'Ocurrió un error al guardar la configuración.',
                    icon: 'error',
                    confirmButtonColor: '#ef4444',
                    background: isDark ? '#1e293b' : '#ffffff',
                    color: isDark ? '#f1f5f9' : '#1e293b',
                });
            }
        })
        .catch(err => {
            hideLoader();
            console.error(err);
            Swal.fire({
                title: 'Error de comunicación',
                text: 'No se pudo comunicar con el servidor para guardar las configuraciones.',
                icon: 'error',
                confirmButtonColor: '#ef4444',
                background: isDark ? '#1e293b' : '#ffffff',
                color: isDark ? '#f1f5f9' : '#1e293b',
            });
        });
    }
</script>
@endpush
