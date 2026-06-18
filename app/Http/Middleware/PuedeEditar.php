<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PuedeEditar
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->user() || !auth()->user()->puedeEditar()) {
            abort(403, 'No tienes permisos para realizar esta acción. Solo Admin y Almacenero pueden editar.');
        }

        return $next($request);
    }
}