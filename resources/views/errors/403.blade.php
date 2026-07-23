<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Denegado - Sección Catalina - Empresa Minera Torrez S.R.L.</title>
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
            max-width: 480px;
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
            background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
            box-shadow: 0 10px 25px rgba(239, 68, 68, 0.3);
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
            background: rgba(239, 68, 68, 0.1);
            border-left: 4px solid #ef4444;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            text-align: left;
            color: #fee2e2;
            font-size: 13px;
            line-height: 1.5;
        }

        .info-box i {
            color: #ef4444;
            margin-right: 8px;
            font-size: 14px;
        }

        .btn-container {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.08);
            border: 1.5px solid rgba(255, 255, 255, 0.1);
            color: #f8fafc;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="icono">
            <i class="fas fa-shield-halved"></i>
        </div>

        <h1>Acceso Restringido (403)</h1>
        <p class="subtitulo">
            Lo sentimos, tu usuario no cuenta con los permisos necesarios para realizar esta acción.
        </p>

        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            @if(isset($exception) && $exception->getMessage())
                {{ $exception->getMessage() }}
            @else
                Esta zona está limitada únicamente a usuarios autorizados de la administración.
            @endif
        </div>

        <div class="btn-container">
            <a href="javascript:history.back()" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Volver Atrás
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                <i class="fas fa-home"></i>
                Ir al Dashboard
            </a>
        </div>
    </div>

</body>
</html>
