<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GaleriaController extends Controller
{
    public function index(Request $request)
    {
        $query = Articulo::with('grupo');

        // Filtro: solo con imagen / sin imagen
        if ($request->filled('filtro')) {
            if ($request->filtro === 'con_imagen') {
                $query->whereNotNull('imagen');
            } elseif ($request->filtro === 'sin_imagen') {
                $query->whereNull('imagen');
            }
        }

        // Búsqueda
        if ($request->filled('buscar')) {
            $termino = $request->buscar;
            $query->where(function (Builder $q) use ($termino) {
                $q->where('codigo', 'LIKE', "%{$termino}%")
                  ->orWhere('nombre', 'LIKE', "%{$termino}%");
            });
        }

        $articulos = $query
            ->orderByRaw('CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(codigo, "/", 1), "-", -1) AS UNSIGNED)')
            ->orderByRaw('CAST(SUBSTRING_INDEX(codigo, "/", -1) AS UNSIGNED)')
            ->paginate(24)
            ->withQueryString();

        return view('galeria.index', compact('articulos'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'articulo_id' => 'required|exists:articulos,id',
            'imagen' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120', // máximo 5MB
        ], [
            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.mimes' => 'Formatos permitidos: JPG, PNG, WEBP.',
            'imagen.max' => 'La imagen no puede pesar más de 5MB.',
        ]);

        $articulo = Articulo::findOrFail($request->articulo_id);

        // Si tenía imagen previa, eliminarla
        if ($articulo->imagen && Storage::disk('public')->exists($articulo->imagen)) {
            Storage::disk('public')->delete($articulo->imagen);
        }

        // Guardar nueva imagen
        $nombreArchivo = $articulo->codigo . '_' . time() . '.' . $request->imagen->extension();
        $nombreArchivo = str_replace('/', '_', $nombreArchivo); // por si el código tiene /
        $path = $request->imagen->storeAs('articulos', $nombreArchivo, 'public');

        $articulo->imagen = $path;
        $articulo->save();

        return redirect()->route('galeria.index')
                         ->with('success', "Imagen subida correctamente para {$articulo->codigo}");
    }

    public function destroy(Articulo $articulo)
    {
        // Solo admin puede eliminar
        if (!auth()->user()->esAdmin()) {
            return redirect()->route('galeria.index')
                             ->with('error', 'No tienes permiso para eliminar imágenes.');
        }

        if ($articulo->imagen) {
            Storage::disk('public')->delete($articulo->imagen);
            $articulo->imagen = null;
            $articulo->save();

            return redirect()->route('galeria.index')
                             ->with('success', "Imagen eliminada de {$articulo->codigo}");
        }

        return redirect()->route('galeria.index')
                         ->with('error', 'Este artículo no tenía imagen.');
    }
}