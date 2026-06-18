<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Movimiento;
use App\Models\Trabajador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MovimientoController extends Controller
{
    public function index(Request $request)
    {
        $query = Movimiento::with(['articulo.grupo', 'user', 'trabajador']);

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('buscar')) {
            $termino = $request->buscar;
            $query->whereHas('articulo', function ($q) use ($termino) {
                $q->where('codigo', 'LIKE', "%{$termino}%")
                  ->orWhere('nombre', 'LIKE', "%{$termino}%");
            });
        }

        if ($request->filled('trabajador_id')) {
            $query->where('trabajador_id', $request->trabajador_id);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        $movimientos = $query->orderBy('fecha', 'desc')
                             ->orderBy('created_at', 'desc')
                             ->paginate(20)
                             ->withQueryString();

        $articulos = Articulo::with('grupo')
                             ->orderByRaw('CAST(SUBSTRING(grupo_id, 3) AS UNSIGNED) ASC')
                             ->orderBy('codigo')
                             ->get();
        $trabajadores = Trabajador::activos()->orderBy('nombre')->get();

        $proximoNumeroNota = Movimiento::siguienteNumeroNota();

        return view('movimientos.index', compact('movimientos', 'articulos', 'trabajadores', 'proximoNumeroNota'));
    }

    public function store(Request $request)
    {
        // Validación base
        $reglas = [
            'articulo_id' => 'required|exists:articulos,id',
            'tipo' => 'required|in:entrada,salida',
            'cantidad' => 'required|numeric|min:0.001',
            'precio_unitario' => 'required|numeric|min:0',
            'fecha' => 'required|date',
            'notas' => 'nullable|string|max:500',
        ];

        // Si es salida, el trabajador es OBLIGATORIO
        if ($request->tipo === 'salida') {
            $reglas['trabajador_id'] = 'required|exists:trabajadores,id';
        } else {
            $reglas['trabajador_id'] = 'nullable';
        }

        $request->validate($reglas, [
            'articulo_id.required' => 'Debes seleccionar un artículo.',
            'tipo.required' => 'Selecciona el tipo de movimiento.',
            'cantidad.required' => 'Ingresa la cantidad.',
            'cantidad.min' => 'La cantidad debe ser mayor a 0.',
            'fecha.required' => 'La fecha es obligatoria.',
            'trabajador_id.required' => 'En una salida debes indicar a qué trabajador se le entrega el material.',
            'trabajador_id.exists' => 'El trabajador seleccionado no existe.',
        ]);

        // ===== VALIDACIÓN: decimales según la unidad del artículo =====
        $articuloValidar = Articulo::find($request->articulo_id);
        if ($articuloValidar) {
            $unidadesEnteras = ['UNIDAD', 'UNIDADES'];
            $unidad = strtoupper($articuloValidar->unidad);

            if (in_array($unidad, $unidadesEnteras)) {
                if (floor($request->cantidad) != $request->cantidad) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "{$articuloValidar->nombre} se mide en {$unidad}. No se permiten decimales (ejemplo válido: 1, 2, 3...).");
                }
            }
        }

        try {
            DB::transaction(function () use ($request) {
                $articulo = Articulo::findOrFail($request->articulo_id);

                if ($request->tipo === 'salida') {
                    if ($articulo->cantidad < $request->cantidad) {
                        throw new \Exception(
                            "Stock insuficiente. Disponible: {$articulo->cantidad} {$articulo->unidad}"
                        );
                    }
                    $articulo->cantidad -= $request->cantidad;
                } else {
                    // ENTRADA: sumar cantidad Y actualizar el precio si cambió
                    $articulo->cantidad += $request->cantidad;
                    $articulo->precio = $request->precio_unitario;
                }

                $articulo->save();

                // Si es salida, también guardamos el nombre del trabajador (para conservarlo si lo eliminan)
                $trabajadorNombre = null;
                if ($request->tipo === 'salida' && $request->trabajador_id) {
                    $trab = \App\Models\Trabajador::find($request->trabajador_id);
                    $trabajadorNombre = $trab?->nombre;
                }

                Movimiento::create([
                    'numero_nota' => Movimiento::siguienteNumeroNota(),
                    'articulo_id' => $request->articulo_id,
                    'tipo' => $request->tipo,
                    'cantidad' => $request->cantidad,
                    'precio_unitario' => $request->precio_unitario,
                    'fecha' => $request->fecha,
                    'notas' => $request->notas,
                    'user_id' => Auth::id(),
                    'trabajador_id' => $request->tipo === 'salida' ? $request->trabajador_id : null,
                    'trabajador_nombre' => $trabajadorNombre,
                ]);
            });

            $tipo = $request->tipo === 'entrada' ? 'Entrada' : 'Salida';
            return redirect()->route('movimientos.index')
                             ->with('success', "{$tipo} registrada correctamente.");

        } catch (\Exception $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', $e->getMessage());
        }
    }
}