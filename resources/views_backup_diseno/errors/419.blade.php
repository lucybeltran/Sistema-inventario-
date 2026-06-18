<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesión Expirada - Inventario Mina Tres Amigos</title>
    <meta http-equiv="refresh" content="3;url={{ route('login') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background: white;
            border-radius: 20px;
            padding: 50px 40px;
            max-width: 450px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
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
            background: linear-gradient(135deg, #f59f00 0%, #f76707 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
            box-shadow: 0 10px 25px rgba(245, 159, 0, 0.4);
        }

        h1 {
            color: #2d3748;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .subtitulo {
            color: #718096;
            font-size: 14px;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .info-box {
            background: #fff8e1;
            border-left: 4px solid #f59f00;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            text-align: left;
            color: #7c2d12;
            font-size: 13px;
            line-height: 1.5;
        }

        .info-box i {
            color: #f59f00;
            margin-right: 8px;
        }

        .countdown {
            color: #667eea;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
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