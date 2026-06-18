<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SoloAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->user() || !auth()->user()->esAdmin()) {
            abort(403, 'Esta acción es exclusiva del Administrador.');
        }

        return $next($request);
    }
}