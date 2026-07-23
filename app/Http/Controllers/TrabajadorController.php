<?php

namespace App\Http\Controllers;

use App\Models\Trabajador;
use Illuminate\Http\Request;

class TrabajadorController extends Controller
{
    private function verificarPuedeEditar()
    {
        if (!auth()->user()->puedeEditar()) {
            abort(403, 'No tienes permisos para gestionar trabajadores.');
        }
    }

    private function verificarSoloAdmin()
    {
        if (!auth()->user()->esAdmin()) {
            abort(403, 'Solo el Administrador puede realizar esta acción.');
        }
    }

    public function index(Request $request)
    {
        $query = Trabajador::query();

        if ($request->filled('buscar')) {
            $termino = $request->buscar;
            $query->where(function ($q) use ($termino) {
                $q->where('nombre', 'LIKE', "%{$termino}%")
                  ->orWhere('codigo', 'LIKE', "%{$termino}%")
                  ->orWhere('ci', 'LIKE', "%{$termino}%")
                  ->orWhere('ayudante', 'LIKE', "%{$termino}%")
                  ->orWhere('nivel', 'LIKE', "%{$termino}%")
                  ->orWhere('labor', 'LIKE', "%{$termino}%")
                  ->orWhere('area_trabajo', 'LIKE', "%{$termino}%")
                  ->orWhere('cargo', 'LIKE', "%{$termino}%");
            });
        }

        if ($request->filled('estado')) {
            if ($request->estado === 'activos') {
                $query->where('activo', true);
            } elseif ($request->estado === 'inactivos') {
                $query->where('activo', false);
            }
        }

        $trabajadores = $query->withCount('movimientos')
            ->orderByRaw('CAST(SUBSTRING(codigo, 5) AS UNSIGNED) ASC')
            ->orderBy('codigo', 'asc')
            ->paginate(20)
            ->withQueryString();
        $total = Trabajador::count();
        $activos = Trabajador::where('activo', true)->count();

        // Calcular el siguiente código recomendado (ej. CON-11)
        $ultimoTrabajador = Trabajador::where('codigo', 'LIKE', 'CON-%')
            ->orderByRaw('CAST(SUBSTRING(codigo, 5) AS UNSIGNED) DESC')
            ->first();
        $numero = 1;
        if ($ultimoTrabajador && preg_match('/^CON-(\d+)$/', $ultimoTrabajador->codigo, $matches)) {
            $numero = intval($matches[1]) + 1;
        }
        $siguienteCodigo = 'CON-' . str_pad($numero, 2, '0', STR_PAD_LEFT);

        return view('trabajadores.index', compact('trabajadores', 'total', 'activos', 'siguienteCodigo'));
    }

    private function limpiarTexto($texto)
    {
        return $texto ? trim($texto) : null;
    }

    private function limpiarLista($array)
    {
        if (!$array) return null;
        $filtrado = array_filter(array_map('trim', $array), function($v) {
            return $v !== '';
        });
        return count($filtrado) > 0 ? implode(', ', $filtrado) : null;
    }

    public function store(Request $request)
    {
        $this->verificarPuedeEditar();

        $request->validate([
            'codigo' => 'nullable|string|max:50|unique:trabajadores,codigo',
            'nombre' => 'required|string|max:150',
            'ci' => 'nullable|string|max:20|unique:trabajadores,ci',
            'cargo' => 'nullable|string|max:100',
            'ayudante' => 'nullable|string|max:150',
            'nivel' => 'nullable|array',
            'labor' => 'nullable|array',
            'area_trabajo' => 'nullable|array',
            'telefono' => 'nullable|string|max:20',
        ], [
            'codigo.unique' => 'Este código de contratista ya está registrado.',
            'ci.unique' => 'Este CI ya está registrado.',
        ]);

        $codigo = $this->limpiarTexto($request->codigo);
        $nombre = $this->limpiarTexto($request->nombre);

        $trabajador = Trabajador::create([
            'codigo' => $codigo,
            'nombre' => $nombre,
            'ci' => $request->ci ?: null,
            'cargo' => $this->limpiarTexto($request->cargo),
            'ayudante' => $this->limpiarTexto($request->ayudante),
            'nivel' => $this->limpiarLista($request->input('nivel', [])),
            'labor' => $this->limpiarLista($request->input('labor', [])),
            'area_trabajo' => $this->limpiarLista($request->input('area_trabajo', [])),
            'telefono' => $request->telefono ?: null,
            'activo' => true,
        ]);

        \App\Models\AuditoriaLog::registrar(
            'CREACION_TRABAJADOR',
            "Agregó al contratista " . $nombre . " (Código: " . ($codigo ?: 'Ninguno') . ")."
        );

        return redirect()->route('trabajadores.index')
                         ->with('success', "Contratista '{$nombre}' agregado.");
    }

    public function update(Request $request, Trabajador $trabajador)
    {
        $this->verificarPuedeEditar();

        $request->validate([
            'codigo' => 'nullable|string|max:50|unique:trabajadores,codigo,' . $trabajador->id,
            'nombre' => 'required|string|max:150',
            'ci' => 'nullable|string|max:20|unique:trabajadores,ci,' . $trabajador->id,
            'cargo' => 'nullable|string|max:100',
            'ayudante' => 'nullable|string|max:150',
            'nivel' => 'nullable|array',
            'labor' => 'nullable|array',
            'area_trabajo' => 'nullable|array',
            'telefono' => 'nullable|string|max:20',
        ], [
            'codigo.unique' => 'Este código de contratista ya está registrado.',
            'ci.unique' => 'Este CI ya está registrado.',
        ]);

        $codigo = $this->limpiarTexto($request->codigo);
        $nombre = $this->limpiarTexto($request->nombre);

        $trabajador->update([
            'codigo' => $codigo,
            'nombre' => $nombre,
            'ci' => $request->ci ?: null,
            'cargo' => $this->limpiarTexto($request->cargo),
            'ayudante' => $this->limpiarTexto($request->ayudante),
            'nivel' => $this->limpiarLista($request->input('nivel', [])),
            'labor' => $this->limpiarLista($request->input('labor', [])),
            'area_trabajo' => $this->limpiarLista($request->input('area_trabajo', [])),
            'telefono' => $request->telefono ?: null,
        ]);

        $cambios = [];
        foreach ($trabajador->getChanges() as $key => $val) {
            $cambios[] = "$key: $val";
        }
        $cambiosTexto = implode(', ', $cambios);
        if (!empty($cambiosTexto)) {
            \App\Models\AuditoriaLog::registrar(
                'MODIFICACION_TRABAJADOR',
                "Modificó al contratista {$trabajador->nombre}. Cambios: {$cambiosTexto}."
            );
        }

        return redirect()->route('trabajadores.index')
                         ->with('success', "Contratista '{$trabajador->nombre}' actualizado.");
    }

    public function toggleActivo(Trabajador $trabajador)
    {
        $this->verificarSoloAdmin();

        $trabajador->activo = !$trabajador->activo;
        $trabajador->save();

        \App\Models\AuditoriaLog::registrar(
            'ESTADO_TRABAJADOR',
            "Cambió el estado del contratista {$trabajador->nombre} a " . ($trabajador->activo ? 'ACTIVO' : 'INACTIVO') . "."
        );

        $estado = $trabajador->activo ? 'activado' : 'desactivado';
        return redirect()->route('trabajadores.index')
                         ->with('success', "Contratista '{$trabajador->nombre}' {$estado}.");
    }

    /**
     * Eliminar un trabajador.
     * Si tiene movimientos, el nombre se conserva en cada movimiento.
     */
    public function destroy(\App\Models\Trabajador $trabajador)
    {
        if (!auth()->user()->esAdmin()) {
            abort(403, 'Solo el administrador puede eliminar contratistas.');
        }

        $nombre = $trabajador->nombre;
        $ci = $trabajador->ci;

        // Si tiene movimientos, primero copiar su nombre a esos movimientos
        \App\Models\Movimiento::where('trabajador_id', $trabajador->id)
            ->whereNull('trabajador_nombre')
            ->update(['trabajador_nombre' => $nombre]);

        // Quitar referencia (trabajador_id → null) y eliminar
        \App\Models\Movimiento::where('trabajador_id', $trabajador->id)
            ->update(['trabajador_id' => null]);

        $trabajador->delete();

        \App\Models\AuditoriaLog::registrar(
            'ELIMINACION_TRABAJADOR',
            "Eliminó al contratista {$nombre}."
        );

        return redirect()->route('trabajadores.index')
            ->with('success', "Contratista \"{$nombre}\" eliminado correctamente. Su historial se conserva en los movimientos.");
    }
}