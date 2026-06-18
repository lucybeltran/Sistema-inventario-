<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://cdnjs.cloudflare.com">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            
            body {
                font-family: 'Figtree', sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #00f2fe 100%);
                background-size: 400% 400%;
                animation: gradientShift 15s ease infinite;
                min-height: 100vh;
            }
            
            @keyframes gradientShift {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            
            .login-container {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                padding: 20px;
            }
            
            .logo-section {
                margin-bottom: 40px;
                text-align: center;
                animation: slideDown 0.8s ease-out;
            }
            
            .logo-section .logo-circle {
                width: 80px;
                height: 80px;
                background: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 20px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                animation: pulse 2s ease-in-out infinite;
            }
            
            .logo-section .logo-circle i {
                font-size: 40px;
                color: #667eea;
            }
            
            .logo-section h1 {
                color: white;
                font-size: 32px;
                font-weight: 700;
                margin-bottom: 8px;
                text-shadow: 0 2px 10px rgba(0,0,0,0.2);
            }
            
            .logo-section p {
                color: rgba(255,255,255,0.9);
                font-size: 14px;
                text-shadow: 0 1px 5px rgba(0,0,0,0.1);
            }
            
            .login-box {
                width: 100%;
                max-width: 450px;
                background: white;
                border-radius: 20px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                padding: 50px 40px;
                animation: slideUp 0.8s ease-out;
                position: relative;
                overflow: hidden;
            }
            
            .login-box::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 5px;
                background: linear-gradient(90deg, #667eea, #764ba2, #f093fb);
            }
            
            @keyframes slideDown {
                from { opacity: 0; transform: translateY(-30px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            @keyframes slideUp {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            @keyframes pulse {
                0%, 100% { box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
                50% { box-shadow: 0 10px 40px rgba(102, 126, 234, 0.4); }
            }
        </style>
    </head>
    <body>
        <div class="login-container">
            <div class="logo-section">
                <div class="logo-circle">
                    <i class="fas fa-warehouse"></i>
                </div>
                <h1>Inventario Mina</h1>
                <p>Gestión inteligente de inventario</p>
            </div>

            <div class="login-box">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
