<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::where('id', '!=', Auth::id())
            ->orderBy('name')
            ->get();

        return view('usuarios.index', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ], [
            'name.required'     => 'El nombre es obligatorio.',
            'email.required'    => 'El correo es obligatorio.',
            'email.unique'      => 'Este correo ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed'=> 'Las contraseñas ingresadas no coinciden.',
        ]);

        $permisoAlmacen = $request->has('permiso_almacen');
        $permisoReportes = $request->has('permiso_reportes');
        $permisoEditarMovimientos = $request->has('permiso_editar_movimientos');
        $permisoEditarMateriales = $request->has('permiso_editar_materiales');
        $permitirCambioPassword = $request->has('permitir_cambio_password');

        if (!$permisoAlmacen && !$permisoReportes) {
            return redirect()->back()->withInput()->with('error', 'Debe seleccionar al menos un permiso (Almacén o Reportes).');
        }

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'rol'      => $permisoAlmacen ? 'almacenero' : 'reportes',
            'permiso_almacen' => $permisoAlmacen,
            'permiso_reportes' => $permisoReportes,
            'permiso_editar_movimientos' => $permisoEditarMovimientos,
            'permiso_editar_materiales' => $permisoEditarMateriales,
            'permitir_cambio_password' => $permitirCambioPassword,
            'activo' => true,
        ]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, User $usuario)
    {
        if ($usuario->id === Auth::id()) {
            return redirect()->route('usuarios.index')
                ->with('error', 'No puedes editar tu propia cuenta desde aquí.');
        }

        $rules = [
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($usuario->id)],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()];
        }

        $request->validate($rules, [
            'email.required' => 'El correo es obligatorio.',
            'email.unique'   => 'Este correo ya está en uso por otro usuario.',
            'password.confirmed'=> 'Las contraseñas ingresadas no coinciden.',
        ]);

        $usuario->email = $request->email;

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        // Actualizar permisos también en la edición
        $permisoAlmacen = $request->has('permiso_almacen');
        $permisoReportes = $request->has('permiso_reportes');
        $permisoEditarMovimientos = $request->has('permiso_editar_movimientos');
        $permisoEditarMateriales = $request->has('permiso_editar_materiales');
        $permitirCambioPassword = $request->has('permitir_cambio_password');

        if (!$permisoAlmacen && !$permisoReportes) {
            return redirect()->back()->with('error', 'Debe seleccionar al menos un permiso (Almacén o Reportes).');
        }

        $cambios = [];
        if ($usuario->permiso_almacen != $permisoAlmacen) {
            $cambios[] = $permisoAlmacen ? "Se te concedió el permiso de Almacén" : "Se te revocó el permiso de Almacén";
        }
        if ($usuario->permiso_reportes != $permisoReportes) {
            $cambios[] = $permisoReportes ? "Se te concedió el permiso de Contabilidad / Reportes" : "Se te revocó el permiso de Contabilidad / Reportes";
        }
        if ($usuario->permiso_editar_movimientos != $permisoEditarMovimientos) {
            $cambios[] = $permisoEditarMovimientos ? "Se te concedió permiso para Editar Movimientos" : "Se te revocó permiso para Editar Movimientos";
        }
        if ($usuario->permiso_editar_materiales != $permisoEditarMateriales) {
            $cambios[] = $permisoEditarMateriales ? "Se te concedió permiso para Editar Materiales" : "Se te revocó permiso para Editar Materiales";
        }
        if ($usuario->permitir_cambio_password != $permitirCambioPassword) {
            $cambios[] = $permitirCambioPassword ? "Se te autorizó a cambiar tu contraseña" : "Se te desautorizó a cambiar tu contraseña";
        }
        if ($request->filled('password')) {
            $cambios[] = "Se reestableció tu contraseña de acceso";
        }

        $usuario->permiso_almacen = $permisoAlmacen;
        $usuario->permiso_reportes = $permisoReportes;
        $usuario->permiso_editar_movimientos = $permisoEditarMovimientos;
        $usuario->permiso_editar_materiales = $permisoEditarMateriales;
        $usuario->permitir_cambio_password = $permitirCambioPassword;
        $usuario->rol = $permisoAlmacen ? 'almacenero' : 'reportes';

        $usuario->save();

        if (!empty($cambios)) {
            \App\Models\Notificacion::create([
                'user_id' => $usuario->id,
                'titulo' => 'Actualización de Seguridad',
                'mensaje' => 'El Administrador actualizó tu perfil de seguridad: ' . implode(', ', $cambios) . '.',
            ]);
        }

        return redirect()->route('usuarios.index')
            ->with('success', "Usuario \"{$usuario->name}\" actualizado correctamente.");
    }

    public function destroy(User $usuario)
    {
        return redirect()->route('usuarios.index')
            ->with('error', 'La eliminación de usuarios está deshabilitada.');
    }

    public function toggleActivo(User $usuario)
    {
        if ($usuario->id === Auth::id()) {
            return redirect()->route('usuarios.index')
                ->with('error', 'No puedes desactivar tu propia cuenta.');
        }

        $usuario->activo = !$usuario->activo;
        $usuario->save();

        $estado = $usuario->activo ? 'activó' : 'desactivó';

        \App\Models\AuditoriaLog::registrar(
            'ESTADO_USUARIO_MODIFICADO',
            "El Administrador " . Auth::user()->name . " {$estado} la cuenta de {$usuario->name} ({$usuario->email})."
        );

        return redirect()->route('usuarios.index')
            ->with('success', "La cuenta de {$usuario->name} ha sido " . ($usuario->activo ? 'activada' : 'desactivada') . " correctamente.");
    }

    public function desbloquear(User $usuario)
    {
        if ($usuario->id === Auth::id()) {
            return redirect()->route('usuarios.index')
                ->with('error', 'No puedes desbloquear tu propia cuenta.');
        }

        $usuario->update([
            'intentos_fallidos' => 0,
            'bloqueado_hasta' => null
        ]);

        \App\Models\AuditoriaLog::registrar(
            'DESBLOQUEO_USUARIO',
            "El Administrador " . Auth::user()->name . " desbloqueó la cuenta de {$usuario->name} ({$usuario->email}) antes de cumplirse el tiempo de bloqueo."
        );

        return redirect()->route('usuarios.index')
            ->with('success', "La cuenta de {$usuario->name} ha sido desbloqueada correctamente.");
    }
}
