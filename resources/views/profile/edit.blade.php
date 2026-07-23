@extends('layouts.mina')

@section('titulo', 'Mi Perfil')

@push('styles')
<style>
    .profile-container {
        max-width: 850px;
        margin: 0 auto;
        padding: 15px 0;
    }

    .perfil-header {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        color: white;
        padding: 35px 30px;
        border-radius: 20px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 25px;
        flex-wrap: wrap;
        box-shadow: 0 10px 25px rgba(234, 88, 12, 0.25);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .avatar {
        width: 90px;
        height: 90px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        color: white;
        border: 3.5px solid rgba(255, 255, 255, 0.35);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(8px);
    }

    .perfil-info { flex: 1; min-width: 200px; }
    .perfil-info h2 {
        font-size: 26px;
        margin: 0 0 6px 0;
        font-weight: 800;
        letter-spacing: -0.5px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .perfil-info p {
        opacity: 0.9;
        font-size: 14.5px;
        margin: 0 0 14px 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .badge-rol-perfil {
        background: rgba(255, 255, 255, 0.22);
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid rgba(255, 255, 255, 0.25);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .card {
        background: var(--bg-card, #fff);
        border-radius: 18px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        margin-bottom: 25px;
        border: 1.5px solid var(--border);
        border-left: 5px solid #f97316;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card.password {
        border-left-color: #f59f00;
    }

    .card-title {
        color: var(--text-primary);
        font-size: 19px;
        font-weight: 800;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: -0.3px;
    }

    .card-title i { color: #f97316; }
    .card.password .card-title i { color: #f59f00; }

    .card-subtitle {
        color: var(--text-muted);
        font-size: 13.5px;
        margin-bottom: 24px;
        opacity: 0.85;
    }

    .form-group { margin-bottom: 20px; }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 700;
        color: var(--text-secondary);
        font-size: 12.5px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }

    .input-wrapper {
        position: relative;
    }

    /* Padding-left reforzado con !important para solucionar el solapamiento del icono */
    .profile-container .form-group input {
        width: 100% !important;
        padding: 12px 16px 12px 46px !important;
        border: 1.5px solid var(--border) !important;
        border-radius: 11px !important;
        font-size: 14.5px !important;
        background: var(--bg-input) !important;
        color: var(--text-primary) !important;
        transition: all 0.25s ease !important;
        font-family: inherit !important;
        box-sizing: border-box !important;
    }

    .profile-container .form-group input:focus {
        outline: none !important;
        border-color: #f97316 !important;
        background: var(--bg-card) !important;
        box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.12) !important;
    }

    .profile-container .card.password .form-group input:focus {
        border-color: #f59f00 !important;
        box-shadow: 0 0 0 4px rgba(245, 159, 0, 0.12) !important;
    }

    .input-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 16px;
        pointer-events: none;
        opacity: 0.7;
        z-index: 5;
    }

    .btn-ver-pass-inline {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        font-size: 16px;
        padding: 5px;
        opacity: 0.7;
        transition: color 0.2s, opacity 0.2s;
        z-index: 5;
    }
    .btn-ver-pass-inline:hover {
        color: #f97316;
        opacity: 1;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 11px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        color: white;
        font-family: inherit;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .btn-primary {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(234, 88, 12, 0.35);
    }

    .btn-warning {
        background: linear-gradient(135deg, #f59f00 0%, #e67e22 100%);
    }
    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(245, 159, 0, 0.35);
    }

    .success-msg {
        background: rgba(56, 161, 105, 0.1);
        border: 1px solid rgba(56, 161, 105, 0.25);
        border-left: 5px solid #38a169;
        color: #2f855a;
        padding: 14px 20px;
        border-radius: 12px;
        font-size: 14.5px;
        margin-bottom: 25px;
        animation: slideDown 0.4s;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .error-list {
        background: rgba(229, 62, 98, 0.1);
        border: 1px solid rgba(229, 62, 98, 0.25);
        border-left: 5px solid #e53e3e;
        color: #c53030;
        padding: 14px 20px;
        border-radius: 12px;
        font-size: 13.5px;
        margin-bottom: 20px;
    }

    .error-list ul { margin: 0; padding-left: 20px; }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .password-strength {
        margin-top: 10px;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text-secondary);
    }

    .strength-bar {
        flex: 1;
        height: 6px;
        background: var(--border);
        border-radius: 3px;
        overflow: hidden;
    }

    .strength-fill {
        height: 100%;
        width: 0%;
        transition: all 0.3s;
        border-radius: 3px;
    }

    .strength-debil { background: #e53e3e; }
    .strength-media { background: #f59f00; }
    .strength-fuerte { background: #38a169; }

    .tips {
        background: rgba(245, 159, 0, 0.08);
        border: 1px solid rgba(245, 159, 0, 0.2);
        border-left: 4px solid #f59f00;
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 22px;
        font-size: 13px;
        color: var(--text-primary);
        line-height: 1.6;
    }

    .tips strong { font-weight: 700; color: #d97706; }
</style>
@endpush

@section('contenido')

<div class="profile-container">

    {{-- HEADER --}}
    <div class="perfil-header">
        <div class="avatar" style="overflow: hidden; display: flex; align-items: center; justify-content: center;">
            <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
        </div>
        <div class="perfil-info">
            <h2>{{ Auth::user()->name }}</h2>
            <p><i class="fas fa-envelope"></i> {{ Auth::user()->email }}</p>
            <span class="badge-rol-perfil">
                <i class="fas fa-shield-alt"></i>
                {{ strtoupper(Auth::user()->nombreRol()) }}
            </span>
        </div>
    </div>

    {{-- MENSAJE DE ÉXITO --}}
    @if (session('status') === 'profile-updated')
        <div class="success-msg">
            <i class="fas fa-check-circle"></i>
            <strong>¡Perfil actualizado!</strong> Tus datos se guardaron correctamente.
        </div>
    @endif

    @if (session('status') === 'password-updated')
        <div class="success-msg">
            <i class="fas fa-check-circle"></i>
            <strong>¡Contraseña cambiada!</strong> Tu nueva contraseña ya está activa.
        </div>
    @endif

    {{-- CARD 1: INFORMACIÓN DEL PERFIL --}}
    <div class="card">
        <div class="card-title">
            <i class="fas fa-user-edit"></i> Información Personal
        </div>
        <p class="card-subtitle">
            Actualiza tu foto de perfil, nombre y correo electrónico.
        </p>

        @if($errors->updateProfileInformation->any())
            <div class="error-list">
                <ul>
                    @foreach($errors->updateProfileInformation->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div style="display: flex; align-items: center; gap: 24px; margin-bottom: 24px; flex-wrap: wrap;">
                <div style="position: relative; width: 100px; height: 100px; border-radius: 50%; border: 3px solid var(--primary); padding: 2px; overflow: hidden; background: var(--bg-hover); flex-shrink: 0;">
                    <img id="avatar-preview" src="{{ Auth::user()->avatar_url }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label for="foto_perfil" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border: 1.5px solid var(--border); background: var(--bg-card); color: var(--text-primary); border-radius: 10px; cursor: pointer; font-size: 13px; font-weight: 700; transition: all 0.2s ease; box-shadow: var(--shadow);">
                        <i class="fas fa-camera"></i> Seleccionar Imagen
                    </label>
                    <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*" style="display: none;" onchange="previewAvatar(this)">
                    <p style="margin: 6px 0 0 0; font-size: 11.5px; color: var(--text-muted);">Formatos: JPG, PNG o WEBP. Máx: 2 MB.</p>
                </div>
            </div>

            <div class="form-group">
                <label for="name">Nombre completo</label>
                <div class="input-wrapper">
                    <i class="fas fa-user input-icon"></i>
                    <input id="name" name="name" type="text" value="{{ old('name', Auth::user()->name) }}" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input id="email" name="email" type="email" value="{{ old('email', Auth::user()->email) }}" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar Cambios
            </button>
        </form>
    </div>

    {{-- CARD 2: CAMBIAR CONTRASEÑA --}}
    <div class="card password">
        <div class="card-title">
            <i class="fas fa-lock"></i> Cambiar Contraseña
        </div>
        <p class="card-subtitle">
            Por seguridad, ingresa tu contraseña actual y luego la nueva (mínimo 8 caracteres).
        </p>

        @if(Auth::user()->esAdmin() || Auth::user()->permitir_cambio_password)
            <div class="tips">
                <strong>💡 Consejos para una contraseña segura:</strong><br>
                • Mínimo 8 caracteres (recomendado 12 o más)<br>
                • Combina letras MAYÚSCULAS y minúsculas<br>
                • Incluye al menos un número<br>
                • Agrega un símbolo (!@#$%)<br>
                • Evita palabras comunes y datos personales
            </div>

            @if($errors->updatePassword->any())
                <div class="error-list">
                    <ul>
                        @foreach($errors->updatePassword->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="current_password">Contraseña actual</label>
                    <div class="input-wrapper">
                        <i class="fas fa-key input-icon"></i>
                        <input id="current_password" name="current_password" type="password" autocomplete="current-password" required>
                        <button type="button" class="btn-ver-pass-inline" onclick="togglePass('current_password', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Nueva contraseña</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input id="password" name="password" type="password" autocomplete="new-password" required oninput="checkPasswordStrength(this.value)">
                        <button type="button" class="btn-ver-pass-inline" onclick="togglePass('password', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <div class="password-strength" id="strengthBox" style="display:none; flex-direction:column; gap:8px; margin-top:8px;">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <span style="font-size:12px; font-weight:700; color: var(--text-primary);">Fortaleza: <span id="strengthText">Débil</span></span>
                        </div>
                        <div class="strength-bar" style="height:6px; background:var(--border); border-radius:3px; overflow:hidden;">
                            <div class="strength-fill" id="strengthFill" style="height:100%; width:0%; transition:all 0.3s ease;"></div>
                        </div>
                        <div style="font-size:11.5px; color:var(--text-muted); display:flex; flex-direction:column; gap:4px; margin-top:4px;" id="passwordRequirements">
                            <div id="req-length"><i class="fas fa-times" style="color:#e53e3e; margin-right:5px;"></i> Mínimo 8 caracteres</div>
                            <div id="req-upper"><i class="fas fa-times" style="color:#e53e3e; margin-right:5px;"></i> Al menos una letra MAYÚSCULA</div>
                            <div id="req-lower"><i class="fas fa-times" style="color:#e53e3e; margin-right:5px;"></i> Al menos una letra minúscula</div>
                            <div id="req-number"><i class="fas fa-times" style="color:#e53e3e; margin-right:5px;"></i> Al menos un número</div>
                            <div id="req-symbol"><i class="fas fa-times" style="color:#e53e3e; margin-right:5px;"></i> Al menos un carácter especial (!@#$%)</div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar nueva contraseña</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
                        <button type="button" class="btn-ver-pass-inline" onclick="togglePass('password_confirmation', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-lock"></i> Cambiar Contraseña
                </button>
            </form>
        @else
            <div style="text-align: center; padding: 40px 20px; border-radius: 12px; background: rgba(239, 68, 68, 0.03); border: 1.5px dashed rgba(239, 68, 68, 0.2); margin-top: 15px;">
                <div style="font-size: 38px; color: #ef4444; margin-bottom: 15px;">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h4 style="font-size: 16px; font-weight: 800; color: var(--text-primary); margin: 0 0 8px 0;">Sección Bloqueada por Seguridad</h4>
                <p style="font-size: 13px; color: var(--text-muted); max-width: 480px; margin: 0 auto; line-height: 1.5;">
                    Por políticas de control de la mina, no tienes permitido cambiar tu contraseña en este momento. 
                    <strong>Solicita autorización a tu Administrador</strong> para habilitar temporalmente esta sección.
                </p>
            </div>
        @endif
    </div>

    {{-- CARD 3: HISTORIAL DE ACCESOS Y DISPOSITIVOS --}}
    @php
        $logs = Auth::user()->iniciosSesion()->orderBy('created_at', 'desc')->take(5)->get();
    @endphp
    <div class="card" style="margin-top: 30px;">
        <div class="card-title" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div>
                <i class="fas fa-history"></i> Historial de Accesos y Dispositivos
            </div>
            @if(!$logs->isEmpty())
                <form id="form-limpiar-accesos" method="POST" action="{{ route('profile.limpiar-accesos') }}" style="margin: 0;">
                    @csrf
                    <button type="button" onclick="confirmarLimpiezaAccesos()" class="btn" style="background: rgba(239, 68, 68, 0.05); border: 1.5px solid rgba(239, 68, 68, 0.25); color: #ef4444; padding: 6px 14px; font-size: 12px; font-weight: 700; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s; box-shadow: none;" title="Limpiar historial de inicios de sesión">
                        <i class="fas fa-trash-alt"></i> Limpiar Historial
                    </button>
                </form>
            @endif
        </div>
        <p class="card-subtitle">
            Audita los últimos inicios de sesión registrados en tu cuenta para detectar accesos no autorizados.
        </p>

        @if($logs->isEmpty())
            <div style="text-align: center; padding: 25px; color: var(--text-muted); font-size: 13px;">
                No se registran accesos previos.
            </div>
        @else
            <div class="table-responsive" style="margin-top: 15px; overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 13px; color: var(--text-primary);">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border); text-align: left;">
                            <th style="padding: 12px 10px; color: var(--text-muted); font-weight: 700; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Dispositivo / Navegador</th>
                            <th style="padding: 12px 10px; color: var(--text-muted); font-weight: 700; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Dirección IP</th>
                            <th style="padding: 12px 10px; color: var(--text-muted); font-weight: 700; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Fecha y Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            @php
                                $ua = $log->user_agent;
                                $icon = 'desktop';
                                $devName = 'PC / Computadora';
                                if (preg_match('/mobile/i', $ua) || preg_match('/phone/i', $ua) || preg_match('/android/i', $ua)) {
                                    $icon = 'mobile-alt';
                                    $devName = 'Dispositivo Móvil';
                                } elseif (preg_match('/tablet/i', $ua) || preg_match('/ipad/i', $ua)) {
                                    $icon = 'tablet-alt';
                                    $devName = 'Tablet';
                                }

                                // Detectar navegador simplificado
                                $browser = 'Navegador';
                                if (preg_match('/edg/i', $ua)) {
                                    $browser = 'Microsoft Edge';
                                } elseif (preg_match('/chrome/i', $ua)) {
                                    $browser = 'Google Chrome';
                                } elseif (preg_match('/firefox/i', $ua)) {
                                    $browser = 'Mozilla Firefox';
                                } elseif (preg_match('/safari/i', $ua)) {
                                    $browser = 'Apple Safari';
                                }
                            @endphp
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 12px 10px; font-weight: 600;">
                                    <i class="fas fa-{{ $icon }}" style="margin-right: 8px; opacity: 0.7; color: var(--primary);"></i>
                                    {{ $devName }} <span style="font-size: 11px; color: var(--text-muted); font-weight: normal; margin-left: 4px;">({{ $browser }})</span>
                                </td>
                                <td style="padding: 12px 10px; font-family: monospace; font-size: 12px;">
                                    {{ $log->ip_address }}
                                </td>
                                <td style="padding: 12px 10px; color: var(--text-muted);">
                                    {{ $log->created_at->setTimezone('America/La_Paz')->format('d/m/Y H:i:s') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script>
    // ===== PREVISUALIZAR IMAGEN DEL AVATAR =====
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // ===== VER/OCULTAR CONTRASEÑA =====
    function togglePass(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // ===== INDICADOR DE FUERZA DE CONTRASEÑA Y REQUISITOS EN VIVO =====
    function checkPasswordStrength(pass) {
        const box = document.getElementById('strengthBox');
        const text = document.getElementById('strengthText');
        const fill = document.getElementById('strengthFill');

        if (!pass) {
            box.style.display = 'none';
            return;
        }

        box.style.display = 'flex';
        
        // Requisitos individuales
        const isLength = pass.length >= 8;
        const isUpper = /[A-Z]/.test(pass);
        const isLower = /[a-z]/.test(pass);
        const isNumber = /[0-9]/.test(pass);
        const isSymbol = /[^a-zA-Z0-9]/.test(pass);

        // Actualizar UI de requisitos
        updateRequirement('req-length', isLength);
        updateRequirement('req-upper', isUpper);
        updateRequirement('req-lower', isLower);
        updateRequirement('req-number', isNumber);
        updateRequirement('req-symbol', isSymbol);

        let puntos = 0;
        if (isLength) puntos++;
        if (pass.length >= 12) puntos++;
        if (isUpper) puntos++;
        if (isLower) puntos++;
        if (isNumber) puntos++;
        if (isSymbol) puntos++;

        if (puntos <= 2) {
            fill.style.width = '33%';
            fill.style.background = '#e53e3e';
            text.textContent = 'Débil';
            text.style.color = '#e53e3e';
        } else if (puntos <= 4) {
            fill.style.width = '66%';
            fill.style.background = '#f59f00';
            text.textContent = 'Media';
            text.style.color = '#f59f00';
        } else {
            fill.style.width = '100%';
            fill.style.background = '#38a169';
            text.textContent = 'Fuerte';
            text.style.color = '#38a169';
        }
    }

    function updateRequirement(id, met) {
        const reqEl = document.getElementById(id);
        if (reqEl) {
            const icon = reqEl.querySelector('i');
            if (met) {
                icon.className = 'fas fa-check';
                icon.style.color = '#38a169';
                reqEl.style.color = 'var(--text-primary)';
            } else {
                icon.className = 'fas fa-times';
                icon.style.color = '#e53e3e';
                reqEl.style.color = 'var(--text-muted)';
            }
        }
    }

    function confirmarLimpiezaAccesos() {
        mostrarConfirmacionPersonalizada(
            '¿Limpiar Historial de Accesos?',
            'Se conservará únicamente el registro de tu dispositivo actual y se eliminarán todas las sesiones anteriores.',
            'Sí, limpiar',
            'Cancelar'
        ).then(confirmado => {
            if (confirmado) {
                document.getElementById('form-limpiar-accesos').submit();
            }
        });
    }
</script>
@endpush