<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar Sesión - Sección Catalina - Empresa Minera Torrez S.R.L.</title>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary: #d97706;
            --primary-dark: #b45309;
            --secondary: #eab308;
            --gradient: linear-gradient(135deg, #d97706 0%, #eab308 100%);
            --gradient-radial: radial-gradient(circle at 10% 20%, #111827 0%, #030712 90%);

            --bg-body: #050508;
            --text-primary: #f9fafb;
            --text-secondary: #cbd5e1;
            --text-muted: #9ca3af;
            --border: rgba(255, 255, 255, 0.08);
            --input-bg: rgba(10, 10, 12, 0.8);
            --card-bg: rgba(13, 13, 17, 0.75);

            --shadow-card: 0 30px 60px rgba(0, 0, 0, 0.6);
            --shadow-input: 0 4px 10px rgba(0, 0, 0, 0.2);

            --success: #10b981;
            --danger: #ef4444;

            --blob-1: rgba(249, 115, 22, 0.25);
            --blob-2: rgba(234, 179, 8, 0.2);
            --blob-3: rgba(239, 68, 68, 0.12);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            background: var(--bg-body);
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 20px;
            perspective: 1200px;
        }

        /* ========== IMAGEN DE FONDO DINÁMICA (ATARDECER MINERO) ========== */
        .background-image-container {
            position: absolute;
            inset: -5%;
            z-index: 1;
            background-image: linear-gradient(
                to right,
                rgba(5, 5, 8, 0.88) 0%,
                rgba(5, 5, 8, 0.65) 45%,
                rgba(5, 5, 8, 0.88) 100%
            ), url('/img/background_mine.png');
            background-size: cover;
            background-position: center;
            filter: saturate(1.15) brightness(0.8);
            animation: zoomBackground 50s infinite alternate ease-in-out;
            pointer-events: none;
        }

        @keyframes zoomBackground {
            0% { transform: scale(1) translate(0, 0); }
            50% { transform: scale(1.06) translate(-1%, -0.5%); }
            100% { transform: scale(1.02) translate(1%, 0.5%); }
        }

        /* ========== FLUID GRADIENT BLOBS ========== */
        .background-blobs {
            position: absolute;
            inset: 0;
            z-index: 2;
            overflow: hidden;
            pointer-events: none;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            animation: moveBlob 30s infinite alternate ease-in-out;
            opacity: 0.7;
        }

        .blob-1 {
            width: 450px;
            height: 450px;
            background: var(--blob-1);
            top: -50px;
            left: 5%;
        }

        .blob-2 {
            width: 400px;
            height: 400px;
            background: var(--blob-2);
            bottom: -50px;
            right: 5%;
            animation-duration: 35s;
            animation-delay: -5s;
        }

        .blob-3 {
            width: 300px;
            height: 300px;
            background: var(--blob-3);
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-duration: 40s;
            animation-delay: -10s;
        }

        @keyframes moveBlob {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(40px, -50px) scale(1.1); }
            100% { transform: translate(-30px, 30px) scale(0.95); }
        }

        /* Partículas flotantes */
        .particles {
            position: absolute;
            inset: 0;
            z-index: 3;
            pointer-events: none;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            background: var(--primary);
            opacity: 0.2;
            border-radius: 50%;
            animation: floatParticle linear infinite;
        }

        @keyframes floatParticle {
            from { transform: translateY(105vh) rotate(0deg); opacity: 0; }
            10% { opacity: 0.35; }
            90% { opacity: 0.35; }
            to { transform: translateY(-100px) rotate(360deg); opacity: 0; }
        }

        /* ========== DISEÑO CONTENEDOR PRINCIPAL ========== */
        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 1140px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            gap: 60px;
        }

        /* ========== LADO IZQUIERDO: TARJETA DE LOGIN ========== */
        .login-left {
            flex: 0 0 420px;
            width: 100%;
        }

        .login-card {
            background: var(--card-bg);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: var(--shadow-card);
            padding: 45px 35px;
            width: 100%;
            animation: cardEntrance 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            transition: transform 0.1s ease-out, border-color 0.3s;
            transform-style: preserve-3d;
        }

        @keyframes cardEntrance {
            from { opacity: 0; transform: translateY(40px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .card-header-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-box {
            width: 70px;
            height: 70px;
            background: white;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
            margin-bottom: 18px;
            animation: pulseLogo 4s infinite alternate ease-in-out;
        }

        @keyframes pulseLogo {
            0% { transform: scale(1); }
            100% { transform: scale(1.05); }
        }

        .logo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .logo-system-title {
            font-size: 22px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }

        .logo-system-subtitle {
            font-size: 12px;
            font-weight: 600;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 6px;
            display: block;
        }

        .card-header-logo p.info-session {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 12px;
        }

        /* Alertas */
        .alert {
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 12.5px;
            margin-bottom: 18px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            animation: shake 0.4s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-4px); }
            75% { transform: translateX(4px); }
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: var(--danger);
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.25);
            color: var(--success);
            animation: none;
        }

        .alert i { font-size: 14px; margin-top: 1.5px; }

        /* Campos de Entrada */
        .field {
            margin-bottom: 20px;
        }

        .field label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i.icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 14px;
            pointer-events: none;
            transition: color 0.2s;
        }

        .field input {
            width: 100%;
            padding: 12px 42px 12px 40px;
            background: var(--input-bg);
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            color: #ffffff;
            font-family: inherit;
            transition: all 0.3s;
            box-shadow: var(--shadow-input);
        }

        .field input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.2);
            background: rgba(10, 10, 12, 0.95);
        }

        .field input:focus ~ i.icon {
            color: var(--primary);
        }

        .field input.valido {
            border-color: var(--success);
        }

        .field input.valido ~ i.icon {
            color: var(--success);
        }

        .btn-pass-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 14px;
            padding: 4px;
            transition: color 0.2s;
        }

        .btn-pass-toggle:hover {
            color: var(--primary);
        }

        .caps-warning {
            display: none;
            background: rgba(245, 158, 11, 0.15);
            border: 1px solid rgba(245, 158, 11, 0.2);
            color: #fb923c;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 11.5px;
            margin-top: 6px;
            align-items: center;
            gap: 6px;
            animation: slideDown 0.3s ease-out;
        }

        .caps-warning.show { display: flex; }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-row {
            display: flex;
            align-items: center;
            margin: 4px 0 22px 0;
        }

        .checkbox-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .checkbox-wrap input {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .checkbox-wrap span {
            font-size: 12.5px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        /* Botón Ingresar */
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #b45309 0%, #f59e0b 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14.5px;
            font-weight: 700;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: transform 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.25s ease, background 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: inherit;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(180, 83, 9, 0.3);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.25);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .btn-submit i {
            transition: transform 0.25s ease;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -50%;
            width: 200%;
            height: 100%;
            background: linear-gradient(
                to right,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.4) 30%,
                rgba(255, 255, 255, 0) 100%
            );
            transform: skewX(-25deg) translateX(-100%);
            transition: none;
            pointer-events: none;
            z-index: 1;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #d97706 0%, #fbbf24 100%);
            transform: translateY(-2.5px) scale(1.025);
            box-shadow: 0 8px 25px rgba(180, 83, 9, 0.5);
        }

        .btn-submit:hover::before {
            transform: skewX(-25deg) translateX(100%);
            transition: transform 0.8s ease-in-out;
        }

        .btn-submit:hover i {
            transform: translateX(4px) scale(1.15);
        }

        .btn-submit:active {
            transform: translateY(1px) scale(0.97);
            box-shadow: 0 2px 6px rgba(180, 83, 9, 0.2);
        }

        .btn-submit.loading {
            pointer-events: none;
            opacity: 0.85;
        }

        .btn-submit.loading .btn-text { display: none; }
        .btn-submit.loading .btn-spinner { display: inline-block; }

        .btn-spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .form-footer {
            margin-top: 25px;
            padding-top: 18px;
            border-top: 1px solid var(--border);
            text-align: center;
            font-size: 11.5px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        /* ========== LADO DERECHO: DETALLES DE BRANDING ========== */
        .login-right {
            flex: 1;
            max-width: 600px;
            animation: textEntrance 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both;
        }

        @keyframes textEntrance {
            from { opacity: 0; transform: translateX(40px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .pill-badge {
            display: inline-block;
            background: rgba(249, 115, 22, 0.12);
            border: 1px solid rgba(249, 115, 22, 0.25);
            color: #fb923c;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 22px;
        }

        .login-right h1 {
            font-size: 40px;
            font-weight: 850;
            line-height: 1.25;
            color: #ffffff;
            margin-bottom: 20px;
            letter-spacing: -1px;
        }

        .login-right p.description {
            font-size: 16px;
            line-height: 1.6;
            color: var(--text-secondary);
            margin-bottom: 35px;
            font-weight: 400;
        }

        /* Características inferiores */
        .features-inline {
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
            margin-top: 35px;
            padding-top: 25px;
            border-top: 1px solid var(--border);
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
        }

        .feature-item i {
            color: var(--primary);
            font-size: 14px;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 960px) {
            .login-wrapper { flex-direction: column; justify-content: center; gap: 40px; max-width: 460px; }
            .login-left { flex: 0 0 auto; width: 100%; }
            .login-right { display: none; }
            body { padding: 15px; }
        }

        @media (max-width: 480px) {
            .login-card { padding: 35px 20px; border-radius: 12px; }
        }
    </style>
</head>
<body>

    {{-- Imagen de fondo cinematográfica --}}
    <div class="background-image-container"></div>

    {{-- Fondo animado --}}
    <div class="background-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    {{-- Partículas flotantes --}}
    <div class="particles" id="particles"></div>

    {{-- CONTENEDOR PRINCIPAL --}}
    <div class="login-wrapper">

        {{-- LADO IZQUIERDO: TARJETA DE LOGIN --}}
        <div class="login-left">
            <div class="login-card">
                <div class="card-header-logo">
                    <div class="logo-box">
                        <img src="{{ asset('img/logo.png') }}" alt="Empresa Minera Torrez S.R.L.">
                    </div>
                    <h1 class="logo-system-title">Sección Catalina</h1>
                    <span class="logo-system-subtitle">Empresa Minera Torrez S.R.L.</span>
                    <p class="info-session">Iniciar Sesión</p>
                </div>

                {{-- Mensajes de estado y errores --}}
                @if (session('status'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <div>{{ session('status') }}</div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Formulario de login --}}
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <div class="field">
                        <label for="email">Correo electrónico</label>
                        <div class="input-wrap">
                            <input id="email"
                                   type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus
                                   autocomplete="username"
                                   placeholder="admin@mina.local">
                            <i class="fas fa-envelope icon"></i>
                        </div>
                    </div>

                    <div class="field">
                        <label for="password">Contraseña</label>
                        <div class="input-wrap">
                            <input id="password"
                                   type="password"
                                   name="password"
                                   required
                                   autocomplete="current-password"
                                   placeholder="••••••••">
                            <i class="fas fa-lock icon"></i>
                            <button type="button" class="btn-pass-toggle" onclick="togglePass()">
                                <i class="fas fa-eye" id="eye-icon"></i>
                            </button>
                        </div>
                        <div class="caps-warning" id="capsWarning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>Bloq Mayús está activado</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <label class="checkbox-wrap">
                            <input type="checkbox" name="remember">
                            <span>Mantener sesión iniciada</span>
                        </label>
                    </div>

                    <button type="submit" class="btn-submit" id="btnSubmit">
                        <span class="btn-text">
                            Iniciar Sesión
                            <i class="fas fa-arrow-right-to-bracket"></i>
                        </span>
                        <span class="btn-spinner"></span>
                    </button>
                </form>

                <div class="form-footer">
                    Acceso exclusivo para personal autorizado.<br>
                    Conexión local protegida.
                </div>
            </div>
        </div>

        {{-- LADO DERECHO: TEXTOS DE BRANDING --}}
        <div class="login-right">
            <div class="pill-badge">
                Sistema de Control de Inventario
            </div>

            <h1>
                Eficiencia y precisión en la gestión de recursos de almacén
            </h1>

            <p class="description">
                Gestión integral de inventario de materiales, trazabilidad de herramientas asignadas a trabajadores de frentes de trabajo y exportación automatizada de Kardex y reportes de stock.
            </p>

            <div class="features-inline">
                <div class="feature-item">
                    <i class="fas fa-boxes-stacked"></i>
                    <span>Stock en Tiempo Real</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-hard-hat"></i>
                    <span>Asignación Segura</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-file-invoice"></i>
                    <span>Reportes Automatizados</span>
                </div>
            </div>
        </div>

    </div>

    {{-- LÓGICA DE INTERACCIÓN --}}
    <script>
        // ===== VER / OCULTAR CONTRASEÑA =====
        function togglePass() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
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

        // ===== ADVERTENCIA BLOQ MAYÚS =====
        const passInput = document.getElementById('password');
        const capsWarn = document.getElementById('capsWarning');

        function checkCaps(e) {
            if (e.getModifierState && e.getModifierState('CapsLock')) {
                capsWarn.classList.add('show');
            } else {
                capsWarn.classList.remove('show');
            }
        }

        passInput.addEventListener('keydown', checkCaps);
        passInput.addEventListener('keyup', checkCaps);
        passInput.addEventListener('blur', () => capsWarn.classList.remove('show'));

        // ===== VALIDACIÓN VISUAL RÁPIDA =====
        const emailInput = document.getElementById('email');
        emailInput.addEventListener('input', function() {
            const ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value);
            this.classList.toggle('valido', ok);
        });

        passInput.addEventListener('input', function() {
            this.classList.toggle('valido', this.value.length >= 6);
        });

        // ===== LOADING ANIMATION =====
        document.getElementById('loginForm').addEventListener('submit', function() {
            document.getElementById('btnSubmit').classList.add('loading');
        });

        // ===== GENERADOR DE PARTÍCULAS DE FONDO =====
        const container = document.getElementById('particles');
        const numParts = 25;

        for (let i = 0; i < numParts; i++) {
            const p = document.createElement('div');
            p.classList.add('particle');
            const size = Math.random() * 6 + 3;
            p.style.width = `${size}px`;
            p.style.height = `${size}px`;
            p.style.left = `${Math.random() * 100}%`;
            const dur = Math.random() * 10 + 6;
            p.style.animationDuration = `${dur}s`;
            p.style.animationDelay = `${Math.random() * dur}s`;
            container.appendChild(p);
        }

        // ===== EFECTO DE INCLINACIÓN 3D INTERACTIVA (PARALLAX) =====
        const card = document.querySelector('.login-card');
        const bodyEl = document.body;

        if (window.innerWidth > 900) {
            bodyEl.addEventListener('mousemove', (e) => {
                const rect = bodyEl.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width/2;
                const y = e.clientY - rect.top - rect.height/2;
                
                const rotateX = -(y / (rect.height/2)) * 7;
                const rotateY = (x / (rect.width/2)) * 7;

                card.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            });

            bodyEl.addEventListener('mouseleave', () => {
                card.style.transition = 'transform 0.6s cubic-bezier(0.16, 1, 0.3, 1)';
                card.style.transform = `rotateX(0deg) rotateY(0deg)`;
            });

            bodyEl.addEventListener('mouseenter', () => {
                card.style.transition = 'transform 0.1s ease-out';
            });
        }
    </script>
</body>
</html>