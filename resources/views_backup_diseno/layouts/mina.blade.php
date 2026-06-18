<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Sistema de Inventario') | Mina Tres Amigos</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        /* HEADER */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header h1 {
            font-size: 28px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-info { font-size: 14px; opacity: 0.9; }

        .user-info {
            text-align: right;
            font-size: 14px;
        }

        .user-info strong { font-size: 16px; }

        .badge-rol {
            display: inline-block;
            background: rgba(255,255,255,0.25);
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 11px;
            margin-top: 4px;
        }

        /* TABS */
        .nav-tabs {
            display: flex;
            background: #f8f9fa;
            border-bottom: 2px solid #e0e0e0;
        }

        .nav-tab {
            flex: 1;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            text-decoration: none;
            color: #666;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
            border-bottom: 3px solid transparent;
            background: none;
            border-left: none;
            border-right: none;
            border-top: none;
        }

        .nav-tab:hover { background: #f0f0f0; }
        .nav-tab.active {
            color: #667eea;
            border-bottom-color: #667eea;
            background: white;
        }

        /* LOGOUT BUTTON */
        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.4);
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            margin-top: 8px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
        }

        .logout-btn:hover { background: rgba(255,255,255,0.35); }

        /* PROFILE BUTTON */
        .btn-perfil {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s;
            margin-right: 8px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-perfil:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
            color: white;
        }

        /* CONTENT */
        .content { padding: 30px; }

        /* SUCCESS / ERROR MESSAGES */
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }
        .alert-success {
            background: #d3f9d8;
            color: #2b8a3e;
            border-color: #51cf66;
        }
        .alert-error {
            background: #ffe3e3;
            color: #862e2e;
            border-color: #ff6b6b;
        }

        @media (max-width: 768px) {
            .header { flex-direction: column; text-align: center; }
            .nav-tabs { flex-direction: column; }
            .nav-tab { padding: 10px; }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="container">
        {{-- HEADER --}}
        <div class="header">
            <div>
                <h1><i class="fas fa-boxes"></i> Sistema de Gestión de Inventario</h1>
                <p class="header-info">Santa Catalina — Mina Tres Amigos | {{ now()->locale('es')->isoFormat('MMMM YYYY') }}</p>
            </div>
            <div class="user-info">
                <strong>{{ Auth::user()->name }}</strong><br>
                <div class="rol-info">
                    <p class="rol-nombre">{{ Auth::user()->nombreRol() }}</p>
                    <span class="badge-rol">
                        <i class="fas fa-{{ Auth::user()->iconoRol() }}"></i>
                        {{ strtoupper(Auth::user()->rol) }}
                    </span>
                </div>
                <a href="{{ route('profile.edit') }}" class="btn-perfil" title="Editar mi perfil">
                    <i class="fas fa-user-cog"></i> Mi Perfil
                </a>
                <form method="POST" action="{{ route('logout') }}" id="formLogout" style="display:inline;">
                    @csrf
                    <button type="button" onclick="cerrarSesionSeguro()" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                    </button>
                </form>

                <script>
                    function cerrarSesionSeguro() {
                        if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
                            // Actualizar CSRF token antes del logout
                            const token = document.querySelector('meta[name="csrf-token"]')?.content;
                            if (token) {
                                document.getElementById('formLogout').submit();
                            } else {
                                // Si no hay token, ir directo al login
                                window.location.href = "{{ route('login') }}";
                            }
                        }
                    }
                </script>
            </div>
        </div>

        {{-- TABS DE NAVEGACIÓN --}}
        <nav class="nav-tabs">
            <a href="{{ route('dashboard') }}" class="nav-tab {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i> Dashboard
            </a>
            <a href="{{ route('inventario.index') }}" class="nav-tab {{ request()->routeIs('inventario.*') ? 'active' : '' }}">
                <i class="fas fa-list"></i> Inventario
            </a>
            <a href="{{ route('movimientos.index') }}" class="nav-tab {{ request()->routeIs('movimientos.*') ? 'active' : '' }}">
                <i class="fas fa-exchange-alt"></i> Movimientos
            </a>
            <a href="{{ route('trabajadores.index') }}" class="nav-tab {{ request()->routeIs('trabajadores.*') ? 'active' : '' }}">
                <i class="fas fa-hard-hat"></i> Trabajadores
            </a>
            <a href="{{ route('galeria.index') }}" class="nav-tab {{ request()->routeIs('galeria.*') ? 'active' : '' }}">
                <i class="fas fa-images"></i> Galería
            </a>
            @if(Auth::user()->puedeReportes())
                <a href="{{ route('reportes.index') }}" class="nav-tab {{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                    <i class="fas fa-file-pdf"></i> Reportes
                </a>
            @endif
        </nav>

        {{-- CONTENIDO --}}
        <div class="content">
            {{-- Mensajes Flash --}}
            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            @yield('contenido')
        </div>
    </div>

    @stack('scripts')
</body>
</html>