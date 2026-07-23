<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Grupo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    private function verificarAdmin()
    {
        if (!auth()->user()->esAdmin()) {
            abort(403, 'Solo los administradores pueden realizar esta acción.');
        }
    }

    public function index(Request $request)
    {
        $query = Articulo::with('grupo')->withCount('movimientos');

        if ($request->filled('buscar')) {
            $termino = $request->buscar;
            $query->where(function (Builder $q) use ($termino) {
                $q->where('codigo', 'LIKE', "%{$termino}%")
                  ->orWhere('nombre', 'LIKE', "%{$termino}%");
            });
        }

        if ($request->filled('grupo')) {
            $query->where('grupo_id', $request->grupo);
        }

        if ($request->filled('stock')) {
            if ($request->stock === 'sin_stock') {
                $query->where('cantidad', '<=', 0);
            } elseif ($request->stock === 'con_stock') {
                $query->where('cantidad', '>', 0);
            }
        }

        $articulos = $query
            ->orderByRaw('CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(codigo, "/", 1), "-", -1) AS UNSIGNED)')
            ->orderByRaw('CAST(SUBSTRING_INDEX(codigo, "/", -1) AS UNSIGNED)')
            ->paginate(20)
            ->withQueryString();
        $grupos = Grupo::orderByRaw('CAST(SUBSTRING(id, 3) AS UNSIGNED)')->get();
        $total = Articulo::count();

        // Valor total del inventario = SUM(precio x cantidad) directo de la tabla articulos
        // (no depende de los lotes de movimientos, refleja siempre el saldo real del almacén)
        $valorInventario = Articulo::selectRaw('SUM(precio * cantidad) as total')->value('total') ?? 0;

        // Lotes activos (cantidad_restante > 0) agrupados por artículo — para precios PEPS
        $lotesActivos = \App\Models\Movimiento::where('tipo', 'entrada')
            ->where('cantidad_restante', '>', 0)
            ->whereNotNull('precio_unitario')
            ->orderBy('created_at', 'asc')
            ->get(['articulo_id', 'precio_unitario', 'cantidad_restante', 'created_at'])
            ->groupBy('articulo_id');

        // Por artículo: lotes agrupados por precio
        $preciosPorArticulo = $lotesActivos->map(function ($movs) {
            return $movs->groupBy(fn($m) => number_format($m->precio_unitario, 2))
                ->map(fn($g) => [
                    'precio'   => $g->first()->precio_unitario,
                    'cantidad' => $g->sum('cantidad_restante'),
                    'primera'  => $g->first()->created_at->format('d/m/Y'),
                    'ultima'   => $g->last()->created_at->format('d/m/Y'),
                ])
                ->values();
        });

        // Valor por artículo desde lotes (si hay); si no, usa precio x cantidad del artículo
        $valorPorArticulo = collect();
        foreach ($articulos as $art) {
            if (isset($lotesActivos[$art->id])) {
                $valorPorArticulo[$art->id] = $lotesActivos[$art->id]
                    ->sum(fn($l) => $l->cantidad_restante * $l->precio_unitario);
            } else {
                $valorPorArticulo[$art->id] = $art->precio * $art->cantidad;
            }
        }

        return view('inventario.index', compact('articulos', 'grupos', 'total', 'valorInventario', 'preciosPorArticulo', 'valorPorArticulo'));

    }

    public function store(Request $request)
    {
        if (!auth()->user()->puedeEditar()) {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        $request->validate([
            'codigo' => 'required|string|max:20|unique:articulos,codigo',
            'nombre' => 'required|string|max:200',
            'unidad' => 'required|string|max:30',
            'grupo_id' => 'required|exists:grupos,id',
            'cantidad' => 'nullable|numeric|min:0',
            'precio' => 'nullable|numeric|min:0',
            'rotacion' => 'nullable|string|in:diario,consumible,ocasional,prestamo',
            'stock_minimo' => 'nullable|numeric|min:0',
        ], [
            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique' => 'Este código ya existe. Usa otro.',
            'nombre.required' => 'El nombre es obligatorio.',
            'unidad.required' => 'La unidad es obligatoria.',
            'grupo_id.required' => 'Debes seleccionar un grupo.',
            'precio.numeric' => 'El precio debe ser un número.',
            'precio.min' => 'El precio no puede ser negativo.',
            'stock_minimo.numeric' => 'El stock mínimo debe ser un número.',
            'stock_minimo.min' => 'El stock mínimo no puede ser negativo.',
        ]);

        // Validación: decimales según la unidad
        $unidadesEnteras = ['UNIDAD', 'UNIDADES'];
        if (in_array(strtoupper($request->unidad), $unidadesEnteras)) {
            $cant = $request->cantidad ?? 0;
            if (floor($cant) != $cant) {
                return redirect()->back()->withInput()
                    ->with('error', "La unidad \"{$request->unidad}\" no permite decimales. Usa un número entero en la cantidad.");
            }
        }

        $articulo = Articulo::create([
            'codigo' => strtoupper($request->codigo),
            'nombre' => strtoupper($request->nombre),
            'unidad' => strtoupper($request->unidad),
            'grupo_id' => $request->grupo_id,
            'cantidad' => $request->cantidad ?? 0,
            'precio' => $request->precio ?? 0,
            'rotacion' => $request->rotacion ?? 'ocasional',
            'stock_minimo' => $request->stock_minimo ?? 0,
            'notas' => $request->notas ?? null,
        ]);

        \App\Models\AuditoriaLog::registrar(
            'CREACION_ARTICULO',
            "Creó el artículo " . strtoupper($request->codigo) . " — " . strtoupper($request->nombre) . ". Stock inicial: " . ($request->cantidad ?? 0) . " " . strtoupper($request->unidad) . ". Stock mínimo: " . ($request->stock_minimo ?? 0) . "."
        );

        return back()
                         ->with('success', "Artículo '{$request->codigo}' creado correctamente.");
    }

    public function update(Request $request, Articulo $articulo)
    {
        if (!auth()->user()->puedeEditarMateriales()) {
            abort(403, 'No tienes permiso para editar materiales del inventario.');
        }

        // Si no es administrador, no puede cambiar la cantidad (stock)
        if (!auth()->user()->esAdmin()) {
            $request->merge(['cantidad' => $articulo->cantidad]);
        }

        // Verificar si el artículo tiene múltiples precios activos
        $preciosActivos = \App\Models\Movimiento::where('articulo_id', $articulo->id)
            ->where('tipo', 'entrada')
            ->where('cantidad_restante', '>', 0)
            ->whereNotNull('precio_unitario')
            ->distinct()
            ->pluck('precio_unitario');

        if ($preciosActivos->count() > 1) {
            return redirect()->back()->withInput()
                ->with('error', "No se puede editar este artículo porque cuenta con múltiples lotes activos a diferentes precios. Los precios y el stock deben gestionarse a través de los movimientos correspondientes.");
        }

        $request->validate([
            'codigo' => 'required|string|max:20|unique:articulos,codigo,' . $articulo->id,
            'nombre' => 'required|string|max:200',
            'unidad' => 'required|string|max:30',
            'grupo_id' => 'required|exists:grupos,id',
            'cantidad' => 'nullable|numeric|min:0',
            'precio' => 'nullable|numeric|min:0',
            'rotacion' => 'nullable|string|in:diario,consumible,ocasional,prestamo',
            'stock_minimo' => 'nullable|numeric|min:0',
        ]);

        // Validación: decimales según la unidad
        $unidadesEnteras = ['UNIDAD', 'UNIDADES'];
        if (in_array(strtoupper($request->unidad), $unidadesEnteras)) {
            $cant = $request->cantidad ?? 0;
            if (floor($cant) != $cant) {
                return redirect()->back()->withInput()
                    ->with('error', "La unidad \"{$request->unidad}\" no permite decimales. Usa un número entero en la cantidad.");
            }
        }

        $notasAnteriores = $articulo->notas;
        $notasNuevas = $request->notas ?? null;

        $articulo->update([
            'codigo' => strtoupper($request->codigo),
            'nombre' => strtoupper($request->nombre),
            'unidad' => strtoupper($request->unidad),
            'grupo_id' => $request->grupo_id,
            'cantidad' => $request->cantidad ?? $articulo->cantidad,
            'precio' => $request->precio ?? $articulo->precio,
            'rotacion' => $request->rotacion ?? $articulo->rotacion ?? 'ocasional',
            'stock_minimo' => $request->stock_minimo ?? $articulo->stock_minimo ?? 0,
            'notas' => $notasNuevas,
        ]);

        if ($notasAnteriores !== $notasNuevas) {
            $editadoPor = auth()->user()->name;
            $articuloNombre = $articulo->nombre;
            
            $admins = \App\Models\User::where('rol', 'admin')->get();
            foreach ($admins as $admin) {
                if ($admin->id === auth()->id()) continue;
                
                \App\Models\Notificacion::create([
                    'user_id' => $admin->id,
                    'titulo' => 'Nota de Material Editada',
                    'mensaje' => "El usuario {$editadoPor} actualizó la nota/observación del material {$articuloNombre}: '" . ($notasNuevas ?? 'sin nota') . "'",
                ]);
            }
        }

        $cambios = [];
        foreach ($articulo->getChanges() as $key => $val) {
            $cambios[] = "$key: $val";
        }
        $cambiosTexto = implode(', ', $cambios);
        if (!empty($cambiosTexto)) {
            \App\Models\AuditoriaLog::registrar(
                'MODIFICACION_ARTICULO',
                "Modificó el artículo {$articulo->codigo}. Cambios: {$cambiosTexto}."
            );
        }

        return back()
                         ->with('success', "Artículo '{$articulo->codigo}' actualizado correctamente.");
    }

    public function destroy(Articulo $articulo)
    {
        $this->verificarAdmin();

        if ($articulo->movimientos()->exists()) {
            return back()
                             ->with('error', "No se puede eliminar '{$articulo->codigo}' porque tiene movimientos registrados.");
        }

        if ($articulo->cantidad > 0) {
            return back()
                             ->with('error', "No se puede eliminar '{$articulo->codigo}' porque tiene stock ({$articulo->cantidad} {$articulo->unidad}).");
        }

        $codigo = $articulo->codigo;
        $nombre = $articulo->nombre;
        $articulo->delete();

        \App\Models\AuditoriaLog::registrar(
            'ELIMINACION_ARTICULO',
            "Eliminó el artículo {$codigo} — {$nombre}."
        );

        return back()
                         ->with('success', "Artículo '{$codigo}' eliminado correctamente.");
    }

    public function storeGrupo(Request $request)
    {
        $this->verificarAdmin();

        $request->validate([
            'id' => 'required|string|max:10|unique:grupos,id',
            'nombre' => 'required|string|max:100',
        ], [
            'id.required' => 'El ID del grupo es obligatorio.',
            'id.unique' => 'Este ID de grupo ya existe.',
        ]);

        Grupo::create([
            'id' => strtoupper($request->id),
            'nombre' => strtoupper($request->nombre),
        ]);

        \App\Models\AuditoriaLog::registrar(
            'CREACION_GRUPO',
            "Creó el grupo de artículos: " . strtoupper($request->id) . " — " . strtoupper($request->nombre) . "."
        );

        return back()
                         ->with('success', "Grupo '{$request->id}' creado correctamente.");
    }

    /**
     * Actualizar el nombre de un grupo existente.
     */
    public function updateGrupo(Request $request, $id)
    {
        $this->verificarAdmin();

        $grupo = \App\Models\Grupo::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
        ], [
            'nombre.required' => 'El nombre del grupo es obligatorio.',
        ]);

        $nombreAnterior = $grupo->nombre;
        $grupo->update([
            'nombre' => strtoupper($request->nombre),
        ]);

        \App\Models\AuditoriaLog::registrar(
            'MODIFICACION_GRUPO',
            "Modificó el nombre del grupo {$grupo->id}: '{$nombreAnterior}' → '{$grupo->nombre}'."
        );

        return back()
                         ->with('success', "Grupo '{$grupo->id}' actualizado: '{$nombreAnterior}' → '{$grupo->nombre}'.");
    }

    /**
     * Eliminar un grupo (solo si no tiene artículos).
     */
    public function destroyGrupo($id)
    {
        $this->verificarAdmin();

        $grupo = \App\Models\Grupo::findOrFail($id);

        $cantidadArticulos = $grupo->articulos()->count();

        if ($cantidadArticulos > 0) {
            return back()
                ->with('error', "No se puede eliminar el grupo \"{$grupo->nombre}\" porque tiene {$cantidadArticulos} artículo(s). Primero mueve o elimina esos artículos.");
        }

        $nombreGrupo = $grupo->nombre;
        $idGrupo = $grupo->id;
        $grupo->delete();

        \App\Models\AuditoriaLog::registrar(
            'ELIMINACION_GRUPO',
            "Eliminó el grupo {$idGrupo} — {$nombreGrupo}."
        );

        return back()
            ->with('success', "Grupo \"{$nombreGrupo}\" eliminado correctamente.");
    }

    /**
     * Devuelve el siguiente código disponible para un grupo.
     * Usado por JavaScript para auto-sugerir el código al crear artículo.
     */
    public function siguienteCodigo(string $grupo_id)
    {
        // Verificar que el grupo existe
        if (!\App\Models\Grupo::where('id', $grupo_id)->exists()) {
            return response()->json(['codigo' => '']);
        }

        // Buscar el último artículo del grupo
        $ultimo = Articulo::where('grupo_id', $grupo_id)
            ->where('codigo', 'LIKE', $grupo_id . '/%')
            ->orderByRaw('CAST(SUBSTRING_INDEX(codigo, "/", -1) AS UNSIGNED) DESC')
            ->first();

        if (!$ultimo) {
            // Si no hay artículos en ese grupo, empieza en 0001
            $numero = 1;
        } else {
            // Extraer el número del código (ej: G-6/0028 → 28)
            $partes = explode('/', $ultimo->codigo);
            $ultimoNumero = (int) end($partes);
            $numero = $ultimoNumero + 1;
        }

        // Formatear con 4 dígitos: 1 → 0001, 29 → 0029
        $codigoSugerido = $grupo_id . '/' . str_pad($numero, 4, '0', STR_PAD_LEFT);

        return response()->json(['codigo' => $codigoSugerido]);
    }

    /**
     * Vista de gestión manual de la rotación/uso de materiales.
     */
    public function rotacionIndex(Request $request)
    {
        $tab = $request->input('tab', 'diario');
        $query = Articulo::with('grupo');

        if ($tab === 'diario') {
            $query->whereIn('rotacion', ['diario', 'consumible']);
        } elseif ($tab === 'prestamo') {
            $query->where('rotacion', 'prestamo');
        } else {
            $query->where(function($q) {
                $q->where('rotacion', 'ocasional')
                  ->orWhereNull('rotacion')
                  ->orWhere('rotacion', '');
            });
        }

        if ($request->filled('buscar')) {
            $termino = $request->buscar;
            $query->where(function ($q) use ($termino) {
                $q->where('codigo', 'LIKE', "%{$termino}%")
                  ->orWhere('nombre', 'LIKE', "%{$termino}%");
            });
        }

        if ($request->filled('grupo')) {
            $query->where('grupo_id', $request->grupo);
        }

        $articulos = $query
            ->orderByRaw('CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(codigo, "/", 1), "-", -1) AS UNSIGNED)')
            ->orderByRaw('CAST(SUBSTRING_INDEX(codigo, "/", -1) AS UNSIGNED)')
            ->paginate(30)
            ->withQueryString();

        $totalDiario = Articulo::whereIn('rotacion', ['diario', 'consumible'])->count();
        $totalOcasional = Articulo::where(function($q) {
            $q->where('rotacion', 'ocasional')
              ->orWhereNull('rotacion')
              ->orWhere('rotacion', '');
        })->count();
        $totalPrestamo = Articulo::where('rotacion', 'prestamo')->count();

        $grupos = Grupo::orderByRaw('CAST(SUBSTRING(id, 3) AS UNSIGNED)')->get();

        // Calcular lotes activos y precios por artículo
        $lotesActivos = \App\Models\Movimiento::where('tipo', 'entrada')
            ->where('cantidad_restante', '>', 0)
            ->whereNotNull('precio_unitario')
            ->orderBy('created_at', 'asc')
            ->get(['articulo_id', 'precio_unitario', 'cantidad_restante', 'created_at'])
            ->groupBy('articulo_id');

        $preciosPorArticulo = $lotesActivos->map(function ($movs) {
            return $movs->groupBy(fn($m) => number_format($m->precio_unitario, 2))
                ->map(fn($g) => [
                    'precio'   => $g->first()->precio_unitario,
                    'cantidad' => $g->sum('cantidad_restante'),
                    'primera'  => $g->first()->created_at->format('d/m/Y'),
                    'ultima'   => $g->last()->created_at->format('d/m/Y'),
                ])
                ->values();
        });

        return view('inventario.rotacion', compact('articulos', 'tab', 'totalDiario', 'totalOcasional', 'totalPrestamo', 'grupos', 'preciosPorArticulo'));
    }

    /**
     * Método rápido para cambiar la rotación de un artículo.
     */
    public function cambiarRotacion(Articulo $articulo, Request $request)
    {
        $request->validate([
            'rotacion' => 'required|in:diario,consumible,ocasional,prestamo',
        ]);

        $articulo->rotacion = $request->rotacion;
        $articulo->save();

        $rotMap = [
            'diario' => 'Consumible',
            'consumible' => 'Consumible',
            'ocasional' => 'Repuesto / Reserva',
            'prestamo' => 'Equipo / Herramienta',
        ];
        $rot = $rotMap[$request->rotacion] ?? 'Clasificado';
        return back()->with('success', "Clasificación de '{$articulo->codigo}' actualizada a '{$rot}'.");
    }
}