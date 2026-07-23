<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $user = \App\Models\User::where('email', $this->email)->first();

        // 1. Verificar si la cuenta está bloqueada temporalmente por fuerza bruta
        if ($user && $user->bloqueado_hasta && $user->bloqueado_hasta->isFuture()) {
            $diffInSeconds = $user->bloqueado_hasta->diffInSeconds(now());
            $minutos = floor($diffInSeconds / 60);
            $segundos = $diffInSeconds % 60;
            
            $tiempoRestante = "";
            if ($minutos > 0) {
                $tiempoRestante .= "{$minutos} " . ($minutos == 1 ? "minuto" : "minutos");
            }
            if ($segundos > 0) {
                if ($minutos > 0) $tiempoRestante .= " y ";
                $tiempoRestante .= "{$segundos} " . ($segundos == 1 ? "segundo" : "segundos");
            }
            
            throw ValidationException::withMessages([
                'email' => "Esta cuenta ha sido bloqueada temporalmente por exceso de intentos fallidos. Inténtalo de nuevo en {$tiempoRestante}.",
            ]);
        }

        // 2. Validar las credenciales
        if (! Auth::validate($this->only('email', 'password'))) {
            RateLimiter::hit($this->throttleKey());

            if ($user) {
                $user->increment('intentos_fallidos');
                
                if ($user->intentos_fallidos >= 5) {
                    $user->update([
                        'bloqueado_hasta' => now()->addMinutes(10),
                        'intentos_fallidos' => 0
                    ]);

                    \App\Models\AuditoriaLog::registrar(
                        'CUENTA_BLOQUEADA_TEMPORALMENTE',
                        "La cuenta del usuario {$user->name} ({$user->email}) ha sido bloqueada por 10 minutos debido a 5 intentos fallidos de contraseña."
                    );

                    // Notificar a todos los administradores
                    $admins = \App\Models\User::where('rol', 'admin')->get();
                    foreach ($admins as $admin) {
                        \App\Models\Notificacion::create([
                            'user_id' => $admin->id,
                            'titulo' => 'Alerta de Bloqueo',
                            'mensaje' => "La cuenta de {$user->name} ({$user->email}) ha sido bloqueada por 10 minutos debido a 5 intentos fallidos de contraseña.",
                        ]);
                    }

                    throw ValidationException::withMessages([
                        'email' => 'Has superado el número de intentos permitidos. Tu cuenta ha sido bloqueada por 10 minutos.',
                    ]);
                }

                $restantes = 5 - $user->intentos_fallidos;
                throw ValidationException::withMessages([
                    'email' => "Contraseña incorrecta. Te quedan {$restantes} intentos antes de bloquear la cuenta.",
                ]);
            }

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // 3. Verificar si la cuenta está desactivada por el Admin
        if ($user && !$user->activo) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Esta cuenta ha sido desactivada por el Administrador.',
            ]);
        }

        // 4. Limpiar contadores de intentos fallidos al tener éxito
        if ($user) {
            $user->update([
                'intentos_fallidos' => 0,
                'bloqueado_hasta' => null
            ]);

            // 5. Registrar el inicio de sesión (Auditoría de Dispositivo/IP)
            \App\Models\InicioSesion::create([
                'user_id' => $user->id,
                'ip_address' => $this->ip() ?? '127.0.0.1',
                'user_agent' => $this->userAgent() ?? 'Desconocido',
            ]);
        }

        // 6. Iniciar sesión
        Auth::login($user, $this->boolean('remember'));

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
