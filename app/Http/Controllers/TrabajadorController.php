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
                  ->orWhere('ci', 'LIKE', "%{$termino}%")
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

        $trabajadores = $query->withCount('movimientos')->orderBy('nombre')->paginate(20)->withQueryString();
        $total = Trabajador::count();
        $activos = Trabajador::where('activo', true)->count();

        return view('trabajadores.index', compact('trabajadores', 'total', 'activos'));
    }

    public function store(Request $request)
    {
        $this->verificarPuedeEditar();

        $request->validate([
            'nombre' => 'required|string|max:150',
            'ci' => 'required|string|max:20|unique:trabajadores,ci',
            'cargo' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
        ], [
            'ci.unique' => 'Este CI ya está registrado.',
        ]);

        Trabajador::create([
            'nombre' => strtoupper($request->nombre),
            'ci' => $request->ci,
            'cargo' => $request->cargo ? strtoupper($request->cargo) : null,
            'telefono' => $request->telefono,
            'activo' => true,
        ]);

        return redirect()->route('trabajadores.index')
                         ->with('success', "Trabajador '{$request->nombre}' agregado.");
    }

    public function update(Request $request, Trabajador $trabajador)
    {
        $this->verificarPuedeEditar();

        $request->validate([
            'nombre' => 'required|string|max:150',
            'ci' => 'required|string|max:20|unique:trabajadores,ci,' . $trabajador->id,
            'cargo' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
        ]);

        $trabajador->update([
            'nombre' => strtoupper($request->nombre),
            'ci' => $request->ci,
            'cargo' => $request->cargo ? strtoupper($request->cargo) : null,
            'telefono' => $request->telefono,
        ]);

        return redirect()->route('trabajadores.index')
                         ->with('success', "Trabajador '{$trabajador->nombre}' actualizado.");
    }

    public function toggleActivo(Trabajador $trabajador)
    {
        $this->verificarSoloAdmin();

        $trabajador->activo = !$trabajador->activo;
        $trabajador->save();

        $estado = $trabajador->activo ? 'activado' : 'desactivado';
        return redirect()->route('trabajadores.index')
                         ->with('success', "Trabajador '{$trabajador->nombre}' {$estado}.");
    }

    /**
     * Eliminar un trabajador.
     * Si tiene movimientos, el nombre se conserva en cada movimiento.
     */
    public function destroy(\App\Models\Trabajador $trabajador)
    {
        if (!auth()->user()->esAdmin()) {
            abort(403, 'Solo el administrador puede eliminar trabajadores.');
        }

        $nombre = $trabajador->nombre;

        // Si tiene movimientos, primero copiar su nombre a esos movimientos
        // (por si algunos no lo tienen guardado todavía)
        \App\Models\Movimiento::where('trabajador_id', $trabajador->id)
            ->whereNull('trabajador_nombre')
            ->update(['trabajador_nombre' => $nombre]);

        // Quitar referencia (trabajador_id → null) y eliminar
        \App\Models\Movimiento::where('trabajador_id', $trabajador->id)
            ->update(['trabajador_id' => null]);

        $trabajador->delete();

        return redirect()->route('trabajadores.index')
            ->with('success', "Trabajador \"{$nombre}\" eliminado correctamente. Su historial se conserva en los movimientos.");
    }
}