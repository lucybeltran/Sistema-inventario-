<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PuedeEditarOReportes
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if (!$user || (!$user->puedeEditar() && !$user->puedeReportes())) {
            abort(403, 'No tienes permisos para realizar esta acción. Se requiere ser Administrador, Almacenero o Reportes.');
        }

        return $next($request);
    }
}
