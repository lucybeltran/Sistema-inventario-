<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PuedeReportes
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->user() || !auth()->user()->puedeReportes()) {
            abort(403, 'No tienes permisos para acceder a Reportes.');
        }

        return $next($request);
    }
}