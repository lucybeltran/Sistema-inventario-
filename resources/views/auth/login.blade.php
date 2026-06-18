<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar Sesión - Mina Tres Amigos</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary: #667eea;
            --primary-dark: #5568d3;
            --secondary: #764ba2;
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-radial: radial-gradient(ellipse at top left, #667eea 0%, #764ba2 50%, #5e35b1 100%);

            --bg-body: #ffffff;
            --bg-form: #ffffff;
            --text-primary: #1a202c;
            --text-secondary: #4a5568;
            --text-muted: #718096;
            --border: #e2e8f0;
            --input-bg: #f7fafc;

            --success: #38a169;
            --danger: #e53e3e;
        }

        [data-theme="dark"] {
            --bg-body: #0f172a;
            --bg-form: #1e293b;
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            --border: #334155;
            --input-bg: #334155;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            background: var(--bg-body);
            color: var(--text-primary);
            display: flex;
            position: relative;
            overflow-x: hidden;
        }

        /* ========== LADO IZQUIERDO (Branding) ========== */
        .branding-side {
            flex: 1;
            background: var(--gradient-radial);
            color: white;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            min-height: 100vh;
        }

        /* Patrón decorativo de fondo */
        .branding-side::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 400px;
            height: 400px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            filter: blur(60px);
        }

        .branding-side::after {
            content: '';
            position: absolute;
            bottom: -150px;
            left: -100px;
            width: 350px;
            height: 350px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
            filter: blur(80px);
        }

        /* Partículas flotantes */
        .particles {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
            animation: float linear infinite;
        }

        @keyframes float {
            from { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10%, 90% { opacity: 1; }
            to { transform: translateY(-100px) rotate(360deg); opacity: 0; }
        }

        /* Top: Logo + Empresa */
        .brand-top {
            position: relative;
            z-index: 2;
        }

        .brand-logo {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 8px;
        }

        .brand-logo-img {
    width: 64px;
    height: 64px;
    background: white;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    padding: 6px;
    overflow: hidden;
}

.brand-logo-img img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

        .brand-logo-text {
            font-size: 16px;
            font-weight: 600;
            opacity: 0.95;
        }

        /* Middle: Frase principal */
        .brand-message {
            position: relative;
            z-index: 2;
            max-width: 480px;
        }

        .brand-message h1 {
            font-size: 42px;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 16px;
            letter-spacing: -0.5px;
        }

        .brand-message h1 .highlight {
            background: linear-gradient(135deg, #fcd34d 0%, #f59e0b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .brand-message p {
            font-size: 16px;
            opacity: 0.85;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        /* Features */
        .features {
            list-style: none;
            display: grid;
            gap: 14px;
        }

        .features li {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            opacity: 0.95;
        }

        .feature-icon {
            width: 34px;
            height: 34px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
            border: 1px solid rgba(255,255,255,0.25);
        }

        /* Bottom: Empresa */
        .brand-footer {
            position: relative;
            z-index: 2;
            font-size: 12px;
            opacity: 0.7;
        }

        .brand-footer strong {
            opacity: 1;
        }

        /* ========== LADO DERECHO (Formulario) ========== */
        .form-side {
            flex: 1;
            background: var(--bg-body);
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 100vh;
            position: relative;
        }

        /* Theme toggle */
        .theme-toggle {
            position: absolute;
            top: 24px;
            right: 24px;
            background: var(--input-bg);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            width: 42px;
            height: 42px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .theme-toggle:hover {
            background: var(--primary);
            color: white;
            transform: rotate(180deg);
        }

        .form-container {
            max-width: 420px;
            width: 100%;
            margin: 0 auto;
            animation: fadeUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-header {
            margin-bottom: 36px;
        }

        .form-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
            letter-spacing: -0.3px;
        }

        .form-header p {
            font-size: 14px;
            color: var(--text-muted);
        }

        /* Mensajes */
        .alert {
            padding: 14px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            animation: shake 0.4s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .alert-error {
            background: #fed7d7;
            border-left: 4px solid var(--danger);
            color: #742a2a;
        }

        .alert-success {
            background: #c6f6d5;
            border-left: 4px solid var(--success);
            color: #22543d;
            animation: none;
        }

        [data-theme="dark"] .alert-error {
            background: rgba(229, 62, 62, 0.15);
            color: #feb2b2;
        }

        [data-theme="dark"] .alert-success {
            background: rgba(56, 161, 105, 0.15);
            color: #9ae6b4;
        }

        .alert i { font-size: 16px; margin-top: 1px; }

        /* Form fields */
        .field {
            margin-bottom: 18px;
        }

        .field label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i.icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 15px;
            pointer-events: none;
            transition: color 0.2s;
        }

        .field input {
            width: 100%;
            padding: 14px 50px 14px 46px;
            background: var(--input-bg);
            border: 2px solid var(--border);
            border-radius: 12px;
            font-size: 14px;
            color: var(--text-primary);
            font-family: inherit;
            transition: all 0.3s;
        }

        .field input:focus {
            outline: none;
            border-color: var(--primary);
            background: var(--bg-form);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
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
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 15px;
            padding: 4px;
            transition: color 0.2s;
        }

        .btn-pass-toggle:hover {
            color: var(--primary);
        }

        /* Caps Lock */
        .caps-warning {
            display: none;
            background: #fef3c7;
            color: #92400e;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12px;
            margin-top: 8px;
            align-items: center;
            gap: 8px;
            animation: slideDown 0.3s;
        }

        .caps-warning.show { display: flex; }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Row entre remember y forgot */
        .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 6px 0 24px 0;
        }

        .checkbox-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .checkbox-wrap input {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .checkbox-wrap span {
            font-size: 13px;
            color: var(--text-secondary);
        }

        .forgot-link {
            font-size: 13px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Botón principal */
        .btn-submit {
            width: 100%;
            padding: 15px;
            background: var(--gradient);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-family: inherit;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.3px;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
            transition: left 0.6s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-submit:hover::before {
            left: 100%;
        }

        .btn-submit.loading {
            pointer-events: none;
            opacity: 0.85;
        }

        .btn-submit.loading .btn-text { display: none; }
        .btn-submit.loading .btn-spinner { display: inline-block; }

        .btn-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Footer mini del formulario */
        .form-footer {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
            text-align: center;
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .form-footer .shield {
            color: var(--success);
            margin-right: 4px;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 1024px) {
            body { flex-direction: column; }
            .branding-side {
                min-height: auto;
                padding: 40px 30px 60px;
            }
            .brand-message h1 { font-size: 32px; }
            .features { display: none; }
            .form-side {
                padding: 40px 30px;
                min-height: auto;
            }
        }

        @media (max-width: 600px) {
            .branding-side { padding: 30px 20px; }
            .brand-message h1 { font-size: 26px; }
            .form-side { padding: 30px 20px; }
            .form-container { max-width: 100%; }
            .form-header h2 { font-size: 22px; }
            .theme-toggle {
                top: 14px;
                right: 14px;
                width: 38px;
                height: 38px;
            }
        }
    </style>
</head>
<body>

    {{-- ========== LADO IZQUIERDO: BRANDING ========== --}}
    <div class="branding-side">
        <div class="particles" id="particles"></div>

        {{-- Logo arriba --}}
        <div class="brand-top">
            <div class="brand-logo">
    <div class="brand-logo-img">
        <img src="{{ asset('img/logo.png') }}" alt="Empresa Minera Torrez S.R.L.">
    </div>
    <div class="brand-logo-text">Santa Catalina</div>
</div>
        </div>

        {{-- Mensaje principal en el medio --}}
        <div class="brand-message">
            <h1>
                Gestión del inventario minero
            </h1>
            <p>
                Sistema integral para el control de materiales,
                herramientas y movimientos de la Mina Tres Amigos.
            </p>

            <ul class="features">
                <li>
                    <div class="feature-icon"><i class="fas fa-boxes-stacked"></i></div>
                    <span>Control completo de inventario y stock</span>
                </li>
                <li>
                    <div class="feature-icon"><i class="fas fa-hard-hat"></i></div>
                    <span>Registro de trabajadores y entregas</span>
                </li>
                <li>
                    <div class="feature-icon"><i class="fas fa-file-export"></i></div>
                    <span>Reportes Excel y PDF </span>
                </li>
                <li>
                    <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                    <span>Acceso seguro con roles diferenciados</span>
                </li>
            </ul>
        </div>

        {{-- Footer pequeño --}}
        <div class="brand-footer">
            &copy; {{ date('Y') }} <strong>Mina Tres Amigos</strong> · Todos los derechos reservados
        </div>
    </div>

    {{-- ========== LADO DERECHO: FORMULARIO ========== --}}
    <div class="form-side">
        <button class="theme-toggle" onclick="toggleTheme()" title="Cambiar tema">
            <i class="fas fa-moon" id="theme-icon"></i>
        </button>

        <div class="form-container">

            <div class="form-header">
                <h2>Bienvenido</h2>
                <p>Ingresa tus credenciales para acceder al sistema.</p>
            </div>

            {{-- Mensajes --}}
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

            {{-- Formulario --}}
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
                        <span>Recordarme</span>
                    </label>
                </div>

                <button type="submit" class="btn-submit" id="btnSubmit">
                    <span class="btn-text">
                        <i class="fas fa-sign-in-alt"></i>
                        Ingresar al Sistema
                    </span>
                    <span class="btn-spinner"></span>
                </button>
            </form>

            <div class="form-footer">
                <i class="fas fa-shield-alt shield"></i>
                Acceso autorizado solo para personal autorizado.<br>
                Sistema seguro con conexión protegida.
            </div>
        </div>
    </div>

    <script>
        // ===== MODO OSCURO =====
        function toggleTheme() {
            const html = document.documentElement;
            const icon = document.getElementById('theme-icon');
            const current = html.getAttribute('data-theme');
            const newTheme = current === 'light' ? 'dark' : 'light';

            html.setAttribute('data-theme', newTheme);
            try { localStorage.setItem('theme', newTheme); } catch(e) {}

            if (newTheme === 'dark') {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            }
        }

        // Cargar tema guardado
        (function() {
            try {
                const saved = localStorage.getItem('theme') || 'light';
                document.documentElement.setAttribute('data-theme', saved);
                if (saved === 'dark') {
                    const icon = document.getElementById('theme-icon');
                    if (icon) {
                        icon.classList.remove('fa-moon');
                        icon.classList.add('fa-sun');
                    }
                }
            } catch(e) {}
        })();

        // ===== VER/OCULTAR CONTRASEÑA =====
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

        // ===== CAPS LOCK =====
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

        // ===== VALIDACIÓN VISUAL =====
        const emailInput = document.getElementById('email');
        emailInput.addEventListener('input', function() {
            const ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value);
            this.classList.toggle('valido', ok);
        });

        passInput.addEventListener('input', function() {
            this.classList.toggle('valido', this.value.length >= 6);
        });

        // ===== LOADING AL ENVIAR =====
        document.getElementById('loginForm').addEventListener('submit', function() {
            document.getElementById('btnSubmit').classList.add('loading');
        });

        // ===== PARTÍCULAS FLOTANTES =====
        const container = document.getElementById('particles');
        const numParts = 20;

        for (let i = 0; i < numParts; i++) {
            const p = document.createElement('div');
            p.classList.add('particle');
            const size = Math.random() * 8 + 4;
            p.style.width = `${size}px`;
            p.style.height = `${size}px`;
            p.style.left = `${Math.random() * 100}%`;
            const dur = Math.random() * 15 + 10;
            p.style.animationDuration = `${dur}s`;
            p.style.animationDelay = `${Math.random() * dur}s`;
            container.appendChild(p);
        }
    </script>
</body>
</html>