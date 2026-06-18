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

        // Valor total del inventario (precio * cantidad de todos)
        $valorInventario = Articulo::selectRaw('SUM(precio * cantidad) as total')->value('total') ?? 0;

        return view('inventario.index', compact('articulos', 'grupos', 'total', 'valorInventario'));
    }

    public function store(Request $request)
    {
        $this->verificarAdmin();

        $request->validate([
            'codigo' => 'required|string|max:20|unique:articulos,codigo',
            'nombre' => 'required|string|max:200',
            'unidad' => 'required|string|max:30',
            'grupo_id' => 'required|exists:grupos,id',
            'cantidad' => 'nullable|numeric|min:0',
            'precio' => 'nullable|numeric|min:0',
        ], [
            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique' => 'Este código ya existe. Usa otro.',
            'nombre.required' => 'El nombre es obligatorio.',
            'unidad.required' => 'La unidad es obligatoria.',
            'grupo_id.required' => 'Debes seleccionar un grupo.',
            'precio.numeric' => 'El precio debe ser un número.',
            'precio.min' => 'El precio no puede ser negativo.',
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

        Articulo::create([
            'codigo' => strtoupper($request->codigo),
            'nombre' => strtoupper($request->nombre),
            'unidad' => strtoupper($request->unidad),
            'grupo_id' => $request->grupo_id,
            'cantidad' => $request->cantidad ?? 0,
            'precio' => $request->precio ?? 0,
        ]);

        return back()
                         ->with('success', "Artículo '{$request->codigo}' creado correctamente.");
    }

    public function update(Request $request, Articulo $articulo)
    {
        $this->verificarAdmin();

        $request->validate([
            'codigo' => 'required|string|max:20|unique:articulos,codigo,' . $articulo->id,
            'nombre' => 'required|string|max:200',
            'unidad' => 'required|string|max:30',
            'grupo_id' => 'required|exists:grupos,id',
            'cantidad' => 'nullable|numeric|min:0',
            'precio' => 'nullable|numeric|min:0',
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

        $articulo->update([
            'codigo' => strtoupper($request->codigo),
            'nombre' => strtoupper($request->nombre),
            'unidad' => strtoupper($request->unidad),
            'grupo_id' => $request->grupo_id,
            'cantidad' => $request->cantidad ?? $articulo->cantidad,
            'precio' => $request->precio ?? $articulo->precio,
        ]);

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
        $articulo->delete();

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
        $grupo->delete();

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
    }}