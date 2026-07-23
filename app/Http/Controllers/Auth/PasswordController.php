<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // 1. Validar autorización de cambio de contraseña (para no admins)
        if (!$user->esAdmin() && !$user->permitir_cambio_password) {
            return back()->withErrors(['password' => 'No estás autorizado para cambiar tu contraseña. Solicita habilitación a tu Administrador.'], 'updatePassword');
        }

        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'],
        ]);

        $user->password = Hash::make($validated['password']);

        // 2. Apagar flag de cambio de contraseña para no admins
        if (!$user->esAdmin()) {
            $user->permitir_cambio_password = false;
        }

        $user->save();

        // 3. Registrar auditoría
        \App\Models\AuditoriaLog::registrar(
            'CAMBIO_CONTRASENA_USUARIO',
            "El usuario {$user->name} ({$user->email}) ha cambiado su contraseña de acceso correctamente."
        );

        // 4. Notificar a todos los administradores
        $admins = \App\Models\User::where('rol', 'admin')->get();
        foreach ($admins as $admin) {
            \App\Models\Notificacion::create([
                'user_id' => $admin->id,
                'titulo' => 'Cambio de Contraseña',
                'mensaje' => "El usuario {$user->name} ({$user->email}) ha cambiado con éxito su contraseña de acceso.",
            ]);
        }

        return back()->with('status', 'password-updated');
    }
}
