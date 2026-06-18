@extends('layouts.mina')

@section('titulo', 'Mi Perfil')

@push('styles')
<style>
    .profile-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .perfil-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 16px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 25px;
        flex-wrap: wrap;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .avatar {
        width: 100px;
        height: 100px;
        background: rgba(255,255,255,0.25);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        border: 4px solid rgba(255,255,255,0.4);
        backdrop-filter: blur(10px);
    }

    .perfil-info { flex: 1; min-width: 200px; }
    .perfil-info h2 {
        font-size: 26px;
        margin-bottom: 6px;
        font-weight: 700;
    }
    .perfil-info p {
        opacity: 0.95;
        font-size: 14px;
        margin-bottom: 12px;
    }

    .badge-rol-perfil {
        background: rgba(255,255,255,0.25);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        backdrop-filter: blur(10px);
    }

    .card {
        background: white;
        border-radius: 14px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin-bottom: 20px;
        border-left: 4px solid #667eea;
    }

    .card.password {
        border-left-color: #f59f00;
    }

    .card-title {
        color: #2d3748;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-title i { color: #667eea; }
    .card.password .card-title i { color: #f59f00; }

    .card-subtitle {
        color: #718096;
        font-size: 13px;
        margin-bottom: 20px;
    }

    .form-group { margin-bottom: 18px; }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: #4a5568;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .input-wrapper {
        position: relative;
    }

    .form-group input {
        width: 100%;
        padding: 12px 16px 12px 44px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        background: #f7fafc;
        transition: all 0.3s;
        font-family: inherit;
    }

    .form-group input:focus {
        outline: none;
        border-color: #667eea;
        background: white;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #a0aec0;
        font-size: 15px;
        pointer-events: none;
    }

    .btn-ver-pass-inline {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #a0aec0;
        cursor: pointer;
        font-size: 16px;
        padding: 5px;
    }
    .btn-ver-pass-inline:hover { color: #667eea; }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        color: white;
        font-family: inherit;
    }

    .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4); }

    .btn-warning { background: linear-gradient(135deg, #f59f00 0%, #f76707 100%); }
    .btn-warning:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(245, 159, 0, 0.4); }

    .success-msg {
        background: #c6f6d5;
        border-left: 4px solid #38a169;
        color: #22543d;
        padding: 12px 16px;
        border-radius: 10px;
        font-size: 14px;
        margin-bottom: 20px;
        animation: slideDown 0.4s;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .error-list {
        background: #fed7d7;
        border-left: 4px solid #e53e3e;
        color: #742a2a;
        padding: 12px 16px;
        border-radius: 10px;
        font-size: 13px;
        margin-bottom: 18px;
    }

    .error-list ul { margin: 0; padding-left: 20px; }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .password-strength {
        margin-top: 8px;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
        color: #718096;
    }

    .strength-bar {
        flex: 1;
        height: 6px;
        background: #e2e8f0;
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
        background: #fff8e1;
        border-left: 4px solid #f59f00;
        padding: 14px 18px;
        border-radius: 8px;
        margin-bottom: 18px;
        font-size: 12px;
        color: #7c2d12;
        line-height: 1.6;
    }

    .tips strong { font-weight: 700; }
</style>
@endpush

@section('contenido')

<div class="profile-container">

    {{-- HEADER --}}
    <div class="perfil-header">
        <div class="avatar">
            <i class="fas fa-{{ Auth::user()->iconoRol() }}"></i>
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
            Actualiza tu nombre y correo electrónico.
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

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

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

                <div class="password-strength" id="strengthBox" style="display:none;">
                    <span id="strengthText">Débil</span>
                    <div class="strength-bar">
                        <div class="strength-fill" id="strengthFill"></div>
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
    </div>

</div>

@endsection

@push('scripts')
<script>
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

    // ===== INDICADOR DE FUERZA DE CONTRASEÑA =====
    function checkPasswordStrength(pass) {
        const box = document.getElementById('strengthBox');
        const text = document.getElementById('strengthText');
        const fill = document.getElementById('strengthFill');

        if (!pass) {
            box.style.display = 'none';
            return;
        }

        box.style.display = 'flex';
        let puntos = 0;

        if (pass.length >= 8) puntos++;
        if (pass.length >= 12) puntos++;
        if (/[a-z]/.test(pass)) puntos++;
        if (/[A-Z]/.test(pass)) puntos++;
        if (/[0-9]/.test(pass)) puntos++;
        if (/[^a-zA-Z0-9]/.test(pass)) puntos++;

        fill.className = 'strength-fill';

        if (puntos <= 2) {
            fill.style.width = '33%';
            fill.classList.add('strength-debil');
            text.textContent = 'Débil';
            text.style.color = '#e53e3e';
        } else if (puntos <= 4) {
            fill.style.width = '66%';
            fill.classList.add('strength-media');
            text.textContent = 'Media';
            text.style.color = '#f59f00';
        } else {
            fill.style.width = '100%';
            fill.classList.add('strength-fuerte');
            text.textContent = 'Fuerte';
            text.style.color = '#38a169';
        }
    }
</script>
@endpush