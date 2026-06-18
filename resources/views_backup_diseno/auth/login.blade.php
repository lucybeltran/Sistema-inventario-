<x-guest-layout>
    <style>
        .login-form { margin: 0; }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: inherit;
        }
        
        .form-group input[type="email"]:focus,
        .form-group input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: #f8f9ff;
        }
        
        .form-group input::placeholder {
            color: #9ca3af;
        }
        
        .form-error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .form-error i {
            font-size: 11px;
        }
        
        .remember-section {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 28px;
        }
        
        .remember-section input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #667eea;
        }
        
        .remember-section label {
            margin: 0;
            font-size: 13px;
            color: #666;
            cursor: pointer;
            text-transform: none;
            letter-spacing: normal;
            font-weight: 500;
        }
        
        .form-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }
        
        .forgot-password {
            font-size: 12px;
            color: #667eea;
            text-decoration: none;
            transition: all 0.3s ease;
            flex: 1;
        }
        
        .forgot-password:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 32px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(102, 126, 234, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .alert-session {
            background: #d1fae5;
            color: #065f46;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 13px;
            border-left: 4px solid #10b981;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .alert-session i {
            font-size: 16px;
        }
    </style>

    @if (session('status'))
        <div class="alert-session">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="login-form">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email">
                <i class="fas fa-envelope" style="color:#667eea; margin-right:6px;"></i> Correo Electrónico
            </label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                placeholder="tu@email.com"
                required 
                autofocus 
                autocomplete="username" 
            />
            @if ($errors->has('email'))
                <div class="form-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first('email') }}</span>
                </div>
            @endif
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password">
                <i class="fas fa-lock" style="color:#667eea; margin-right:6px;"></i> Contraseña
            </label>
            <input 
                id="password" 
                type="password"
                name="password"
                placeholder="••••••••"
                required 
                autocomplete="current-password" 
            />
            @if ($errors->has('password'))
                <div class="form-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first('password') }}</span>
                </div>
            @endif
        </div>

        <!-- Remember Me -->
        <div class="remember-section">
            <input id="remember_me" type="checkbox" name="remember" id="remember_me">
            <label for="remember_me">Recuérdame en este dispositivo</label>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            @if (Route::has('password.request'))
                <a class="forgot-password" href="{{ route('password.request') }}">
                    <i class="fas fa-question-circle"></i> ¿Olvidaste tu contraseña?
                </a>
            @endif
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Ingresar
            </button>
        </div>
    </form>
</x-guest-layout>
