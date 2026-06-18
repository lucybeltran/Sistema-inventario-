<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Movimiento;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = now()->toDateString();

        $totalArticulos = Articulo::count();
        $totalMovimientos = Movimiento::count();
        $entradasHoy = Movimiento::where('tipo', 'entrada')->where('fecha', $hoy)->count();
        $salidasHoy = Movimiento::where('tipo', 'salida')->where('fecha', $hoy)->count();

        // Incluimos el trabajador en la consulta
        $ultimosMovimientos = Movimiento::with(['articulo', 'user', 'trabajador'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalArticulos',
            'totalMovimientos',
            'entradasHoy',
            'salidasHoy',
            'ultimosMovimientos'
        ));
    }
}