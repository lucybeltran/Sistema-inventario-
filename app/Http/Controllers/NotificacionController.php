<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    /**
     * Devuelve las notificaciones no leídas en formato JSON para el Polling de Toasts en tiempo real.
     */
    public function getNuevas(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        // Obtener notificaciones creadas en los últimos 2 minutos que estén sin leer
        // Esto evita que salgan Toasts de notificaciones antiguas en cada recarga
        $notificaciones = $user->notificaciones()
            ->where('leido', false)
            ->where('created_at', '>=', now()->subMinutes(2))
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'notificaciones' => $notificaciones,
            'total_no_leidas' => $user->notificacionesNoLeidasCount(),
        ]);
    }

    /**
     * Marca todas las notificaciones del usuario autenticado como leídas.
     */
    public function marcarLeidas(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        $user->notificaciones()->where('leido', false)->update(['leido' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Todas las notificaciones marcadas como leídas.'
        ]);
    }

    /**
     * Elimina una notificación específica del usuario autenticado.
     */
    public function destroy(Notificacion $notificacion)
    {
        if ($notificacion->user_id !== Auth::id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $notificacion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notificación eliminada correctamente.'
        ]);
    }

    /**
     * Elimina todas las notificaciones del usuario autenticado de la base de datos.
     */
    public function limpiarTodas(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        $user->notificaciones()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Todas las notificaciones eliminadas correctamente.'
        ]);
    }

    /**
     * Marca una notificación específica como leída.
     */
    public function marcarUnaLeida(Notificacion $notificacion)
    {
        if ($notificacion->user_id !== Auth::id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $notificacion->update(['leido' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Notificación marcada como leída.',
            'total_no_leidas' => Auth::user()->notificacionesNoLeidasCount()
        ]);
    }
}
