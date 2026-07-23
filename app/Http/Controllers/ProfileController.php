<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        $user->fill($request->safe()->except('foto_perfil'));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Subida de foto de perfil si está presente
        if ($request->hasFile('foto_perfil')) {
            $file = $request->file('foto_perfil');
            
            // Eliminar foto de perfil anterior si existía
            if ($user->foto_perfil) {
                Storage::disk('public')->delete($user->foto_perfil);
            }

            // Guardar nueva imagen
            $path = $file->store('avatars', 'public');
            $user->foto_perfil = $path;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Limpia el historial de inicios de sesión del usuario actual, conservando únicamente la sesión actual.
     */
    public function limpiarAccesos(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        // Eliminar todos los registros
        $user->iniciosSesion()->delete();

        // Volver a registrar la sesión actual
        \App\Models\InicioSesion::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip() ?? '127.0.0.1',
            'user_agent' => $request->userAgent() ?? 'Desconocido',
        ]);

        return Redirect::route('profile.edit')->with('success', 'El historial de dispositivos y accesos se ha limpiado correctamente.');
    }
}
