<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesión Expirada - Sección Catalina - Empresa Minera Torrez S.R.L.</title>
    <meta http-equiv="refresh" content="3;url={{ route('login') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #090d16 0%, #111827 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background: rgba(30, 41, 59, 0.45);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 50px 40px;
            max-width: 450px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            animation: aparecer 0.6s ease-out;
        }

        @keyframes aparecer {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .icono {
            width: 90px;
            height: 90px;
            margin: 0 auto 25px;
            background: linear-gradient(135deg, #f97316 0%, #eab308 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
            box-shadow: 0 10px 25px rgba(249, 115, 22, 0.3);
        }

        h1 {
            color: #f8fafc;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .subtitulo {
            color: #cbd5e1;
            font-size: 14px;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .info-box {
            background: rgba(245, 158, 11, 0.1);
            border-left: 4px solid #f97316;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            text-align: left;
            color: #ffedd5;
            font-size: 13px;
            line-height: 1.5;
        }

        .info-box i {
            color: #eab308;
            margin-right: 8px;
        }

        .countdown {
            color: #f97316;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .countdown strong {
            font-size: 18px;
            font-weight: 700;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            background: linear-gradient(135deg, #f97316 0%, #eab308 100%);
            color: white;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.2);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(249, 115, 22, 0.4);
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="icono">
            <i class="fas fa-clock"></i>
        </div>

        <h1>Sesión Expirada</h1>
        <p class="subtitulo">
            Tu sesión ha expirado por inactividad.<br>
            Por favor, vuelve a iniciar sesión para continuar.
        </p>

        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            <strong>¿Por qué pasa esto?</strong><br>
            Es una medida de seguridad. Si dejas el sistema abierto mucho tiempo sin actividad, te pedimos volver a iniciar sesión.
        </div>

        <div class="countdown">
            Redirigiendo al login en <strong id="contador">3</strong> segundos...
        </div>

        <a href="{{ route('login') }}" class="btn">
            <i class="fas fa-sign-in-alt"></i>
            Ir al Login ahora
        </a>
    </div>

    <script>
        // Cuenta regresiva
        let segundos = 3;
        const contador = document.getElementById('contador');
        const intervalo = setInterval(() => {
            segundos--;
            if (segundos <= 0) {
                clearInterval(intervalo);
                window.location.href = '{{ route("login") }}';
            } else {
                contador.textContent = segundos;
            }
        }, 1000);
    </script>
</body>
</html>