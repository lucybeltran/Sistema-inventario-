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
        if (!$request->has('tipo') || !in_array($request->tipo, ['entrada', 'salida'])) {
            return redirect()->route('movimientos.index', array_merge($request->all(), ['tipo' => 'entrada']));
        }

        $query = Movimiento::with(['articulo.grupo', 'user', 'trabajador', 'editadoPor']);

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

        $periodo = $request->input('periodo');
        if (!$request->has('periodo') && !$request->filled('fecha_desde') && !$request->filled('fecha_hasta')) {
            $periodo = 'mensual';
        }

        if ($periodo && $periodo !== 'todos') {
            if ($periodo === 'diario') {
                $query->whereDate('fecha', today());
            } elseif ($periodo === 'semanal') {
                $query->whereBetween('fecha', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($periodo === 'mensual') {
                $query->whereBetween('fecha', [now()->startOfMonth(), now()->endOfMonth()]);
            } elseif ($periodo === 'personalizado') {
                if ($request->filled('fecha_desde')) {
                    $query->whereDate('fecha', '>=', $request->fecha_desde);
                }
                if ($request->filled('fecha_hasta')) {
                    $query->whereDate('fecha', '<=', $request->fecha_hasta);
                }
            }
        } else {
            if ($request->filled('fecha_desde')) {
                $query->whereDate('fecha', '>=', $request->fecha_desde);
            }
            if ($request->filled('fecha_hasta')) {
                $query->whereDate('fecha', '<=', $request->fecha_hasta);
            }
        }

        // Excluir entradas de stock inicial y cargas de excel (solo mostrar lo que el usuario registró manualmente)
        if ($request->tipo === 'entrada') {
            $query->where(function ($q) {
                $q->whereNull('notas')
                  ->orWhere(function ($sq) {
                      $sq->where('notas', 'NOT LIKE', '%Stock inicial al implementar el sistema%')
                         ->where('notas', 'NOT LIKE', '%Stock inicial consolidado%');
                  });
            })->where(function ($q) {
                $q->whereNull('entregado_por')
                  ->orWhere('entregado_por', '!=', 'CARGA EXCEL')
                  ->where('entregado_por', '!=', 'INVENTARIO INICIAL');
            })->where('numero_nota', '!=', 'INICIAL');
        }

        $movimientos = $query->orderBy('fecha', 'desc')
                             ->orderBy('created_at', 'desc')
                             ->paginate(20)
                             ->withQueryString();

        // Calcular cambios de precio con respecto a la entrada anterior
        $movimientos->getCollection()->transform(function ($mov) {
            if ($mov->tipo === 'entrada') {
                // Buscar la entrada anterior de este artículo (de forma cronológica robusta)
                $entradaAnterior = Movimiento::where('articulo_id', $mov->articulo_id)
                    ->where('tipo', 'entrada')
                    ->where(function($query) use ($mov) {
                        $query->where('fecha', '<', $mov->fecha)
                              ->orWhere(function($q) use ($mov) {
                                  $q->where('fecha', '=', $mov->fecha)
                                    ->where('id', '<', $mov->id);
                              });
                    })
                    ->orderBy('fecha', 'desc')
                    ->orderBy('id', 'desc')
                    ->first();

                if ($entradaAnterior && $entradaAnterior->precio_unitario !== null) {
                    $mov->precio_anterior = $entradaAnterior->precio_unitario;
                    $diff = $mov->precio_unitario - $entradaAnterior->precio_unitario;
                    if ($diff > 0.001) {
                        $mov->precio_cambio = 'subio';
                        $mov->precio_diferencia_porcentaje = (($mov->precio_unitario - $entradaAnterior->precio_unitario) / $entradaAnterior->precio_unitario) * 100;
                    } elseif ($diff < -0.001) {
                        $mov->precio_cambio = 'bajo';
                        $mov->precio_diferencia_porcentaje = (($entradaAnterior->precio_unitario - $mov->precio_unitario) / $entradaAnterior->precio_unitario) * 100;
                    } else {
                        $mov->precio_cambio = 'igual';
                    }
                } else {
                    $mov->precio_cambio = 'nuevo'; // Primera entrada o no hay registros anteriores
                }
            }
            return $mov;
        });

        $articulos = Articulo::with('grupo')
                             ->orderByRaw('CAST(SUBSTRING(grupo_id, 3) AS UNSIGNED) ASC')
                             ->orderBy('codigo')
                             ->get();
        $trabajadores = Trabajador::activos()->orderBy('nombre')->get();

        $proximoNumeroNota = Movimiento::siguienteNumeroNota();

        // Para calcular el monto total gastado en entradas (sin paginación)
        $totalGastado = null;
        if ($request->tipo === 'entrada') {
            $queryTotal = Movimiento::where('tipo', 'entrada')
                ->where(function ($q) {
                    $q->whereNull('notas')
                      ->orWhere(function ($sq) {
                          $sq->where('notas', 'NOT LIKE', '%Stock inicial al implementar el sistema%')
                             ->where('notas', 'NOT LIKE', '%Stock inicial consolidado%');
                      });
                })->where(function ($q) {
                    $q->whereNull('entregado_por')
                      ->orWhere('entregado_por', '!=', 'CARGA EXCEL')
                      ->where('entregado_por', '!=', 'INVENTARIO INICIAL');
                })->where('numero_nota', '!=', 'INICIAL');

            if ($request->filled('buscar')) {
                $termino = $request->buscar;
                $queryTotal->whereHas('articulo', function ($q) use ($termino) {
                    $q->where('codigo', 'LIKE', "%{$termino}%")
                      ->orWhere('nombre', 'LIKE', "%{$termino}%");
                });
            }
            if ($periodo && $periodo !== 'todos') {
                if ($periodo === 'diario') {
                    $queryTotal->whereDate('fecha', today());
                } elseif ($periodo === 'semanal') {
                    $queryTotal->whereBetween('fecha', [now()->startOfWeek(), now()->endOfWeek()]);
                } elseif ($periodo === 'mensual') {
                    $queryTotal->whereBetween('fecha', [now()->startOfMonth(), now()->endOfMonth()]);
                } elseif ($periodo === 'personalizado') {
                    if ($request->filled('fecha_desde')) {
                        $queryTotal->whereDate('fecha', '>=', $request->fecha_desde);
                    }
                    if ($request->filled('fecha_hasta')) {
                        $queryTotal->whereDate('fecha', '<=', $request->fecha_hasta);
                    }
                }
            } else {
                if ($request->filled('fecha_desde')) {
                    $queryTotal->whereDate('fecha', '>=', $request->fecha_desde);
                }
                if ($request->filled('fecha_hasta')) {
                    $queryTotal->whereDate('fecha', '<=', $request->fecha_hasta);
                }
            }
            $totalGastado = $queryTotal->selectRaw('SUM(precio_unitario * cantidad) as total')
                                       ->value('total');
        }

        // Último precio registrado en entrada para cada artículo (para el recordatorio en el formulario)
        $preciosUltimos = Movimiento::where('tipo', 'entrada')
            ->whereNotNull('precio_unitario')
            ->where('numero_nota', '!=', 'INICIAL')
            ->where(function ($q) {
                $q->whereNull('entregado_por')
                  ->orWhere('entregado_por', '!=', 'INVENTARIO INICIAL');
            })
            ->orderBy('created_at', 'desc')
            ->get(['articulo_id', 'precio_unitario', 'created_at'])
            ->unique('articulo_id')
            ->keyBy('articulo_id')
            ->map(fn($m) => [
                'precio' => $m->precio_unitario,
                'fecha'  => $m->created_at->format('d/m/Y'),
            ]);

        return view('movimientos.index', compact('movimientos', 'articulos', 'trabajadores', 'proximoNumeroNota', 'totalGastado', 'preciosUltimos'));
    }

    public function store(Request $request)
    {
        // Validación base para los datos de cabecera
        $reglas = [
            'tipo'               => 'required|in:entrada,salida',
            'fecha'              => 'required|date',
            'notas'              => 'nullable|string|max:500',
            'items.*.nota_item'  => 'nullable|string|max:300',
        ];

        if ($request->tipo === 'salida') {
            $reglas['destino_tipo'] = 'required|in:contratista,nivel';
            if ($request->destino_tipo === 'nivel') {
                $reglas['nivel'] = 'required|string|max:150';
                $reglas['trabajador_id'] = 'nullable';
            } else {
                $reglas['trabajador_id'] = 'required|exists:trabajadores,id';
            }
            $reglas['turno'] = 'required|in:Primera,Segunda,Tercera';
            $reglas['entregado_por'] = 'required|string|max:100';
        } else {
            $reglas['entregado_por'] = 'required|string|max:100';
            $reglas['recibido_por'] = 'required|string|max:100';
        }

        // Si es formato tradicional (un solo artículo), lo convertimos en la estructura de items para validar y procesar uniformemente
        if (!$request->has('items')) {
            $request->merge([
                'items' => [
                    [
                        'articulo_id' => $request->articulo_id,
                        'cantidad' => $request->cantidad,
                        'lote_id' => $request->lote_id,
                        'precio_unitario' => $request->precio_unitario,
                    ]
                ]
            ]);
        }

        // Conversión especial para dinamita: interpretar notación de tercios (ej: 8.2 -> 8 + 2/3, 4.1 -> 4 + 1/3)
        if ($request->has('items')) {
            $items = $request->items;
            foreach ($items as &$item) {
                if (isset($item['articulo_id']) && isset($item['cantidad'])) {
                    $articulo = \App\Models\Articulo::find($item['articulo_id']);
                    if ($articulo && str_contains(strtolower($articulo->nombre), 'dinamita')) {
                        $valStr = (string)$item['cantidad'];
                        if (strpos($valStr, '.') !== false) {
                            list($entero, $decimal) = explode('.', $valStr);
                            $y = (int)substr($decimal, 0, 1);
                            if ($y === 1) {
                                $item['cantidad'] = (float)$entero + (1/3);
                            } elseif ($y === 2) {
                                $item['cantidad'] = (float)$entero + (2/3);
                            } else {
                                $item['cantidad'] = (float)$entero;
                            }
                        } else {
                            $item['cantidad'] = (float)$item['cantidad'];
                        }
                    }
                }
            }
            $request->merge(['items' => $items]);
        }

        $reglas['items'] = 'required|array|min:1';
        $reglas['items.*.articulo_id'] = 'required|exists:articulos,id';
        $reglas['items.*.cantidad'] = 'required|numeric|min:0.001';

        if ($request->tipo === 'salida') {
            $reglas['items.*.lote_id'] = 'required|exists:movimientos,id';
        } else {
            $reglas['items.*.precio_unitario'] = 'required|numeric|min:0';
        }

        $request->validate($reglas, [
            'tipo.required' => 'Selecciona el tipo de movimiento.',
            'fecha.required' => 'La fecha es obligatoria.',
            'destino_tipo.required' => 'Selecciona el tipo de destino de la salida.',
            'destino_tipo.in' => 'El tipo de destino seleccionado no es válido.',
            'nivel.required' => 'Debes indicar a qué nivel de la mina se envía el material.',
            'trabajador_id.required' => 'En una salida debes indicar a qué contratista se le entrega el material.',
            'trabajador_id.exists' => 'El contratista seleccionado no existe.',
            'entregado_por.required' => 'Debes indicar quién está entregando el material al almacén.',
            'recibido_por.required' => 'Debes indicar quién está recibiendo el material en el almacén.',
            'turno.required' => 'Debes indicar en qué turno se realizó la salida.',
            'turno.in' => 'El turno seleccionado no es válido.',
            'items.required' => 'Debes agregar al menos un artículo.',
            'items.min' => 'Debes agregar al menos un artículo.',
            'items.*.articulo_id.required' => 'Falta seleccionar el artículo en una de las filas.',
            'items.*.articulo_id.exists' => 'Uno de los artículos seleccionados no existe.',
            'items.*.cantidad.required' => 'Falta ingresar la cantidad en una de las filas.',
            'items.*.cantidad.min' => 'La cantidad de cada fila debe ser mayor a 0.',
            'items.*.lote_id.required' => 'Debes seleccionar el lote/precio de origen en todas las filas de salida.',
            'items.*.lote_id.exists' => 'Uno de los lotes seleccionados no es válido.',
            'items.*.precio_unitario.required' => 'El precio unitario es obligatorio para todas las filas de entrada.',
            'items.*.precio_unitario.min' => 'El precio unitario de cada fila debe ser mayor o igual a 0.',
        ]);

        try {
            $trabajadorNombre = null;
            if ($request->tipo === 'salida') {
                if ($request->destino_tipo === 'nivel') {
                    $trabajadorNombre = $request->nivel;
                } elseif ($request->trabajador_id) {
                    $trab = \App\Models\Trabajador::find($request->trabajador_id);
                    $trabajadorNombre = $trab?->nombre;
                }
            }

            $numeroNota = Movimiento::siguienteNumeroNota();

            DB::transaction(function () use ($request, $trabajadorNombre, $numeroNota) {
                foreach ($request->items as $item) {
                    $articulo = Articulo::findOrFail($item['articulo_id']);

                    // ===== VALIDACIÓN: decimales según la unidad del artículo =====
                    $unidadesEnteras = ['UNIDAD', 'UNIDADES'];
                    $unidad = strtoupper($articulo->unidad);
                    $isDinamita = str_contains(strtolower($articulo->nombre), 'dinamita');

                    if (!$isDinamita && in_array($unidad, $unidadesEnteras)) {
                        if (floor($item['cantidad']) != $item['cantidad']) {
                            throw new \Exception(
                                "El artículo {$articulo->nombre} se mide en {$unidad}. No se permiten decimales (ejemplo válido: 1, 2, 3...)."
                            );
                        }
                    }

                    if ($request->tipo === 'salida') {
                        $loteReferencia = Movimiento::findOrFail($item['lote_id']);
                        $cantSolicitada = (float) $item['cantidad'];

                        // 1. Verificar stock total disponible en el almacén para este artículo
                        $todosLotesDisponibles = Movimiento::where('articulo_id', $item['articulo_id'])
                                                           ->where('tipo', 'entrada')
                                                           ->where('cantidad_restante', '>', 0)
                                                           ->get();

                        $stockTotalDisponible = (float) $todosLotesDisponibles->sum('cantidad_restante');

                        if ($stockTotalDisponible < $cantSolicitada || $articulo->cantidad < $cantSolicitada) {
                            throw new \Exception(
                                "Stock total insuficiente para {$articulo->nombre} en el almacén. Solicitado: " . number_format($cantSolicitada, 2) . " {$articulo->unidad}, Disponible total: " . number_format(min($stockTotalDisponible, $articulo->cantidad), 2) . " {$articulo->unidad}."
                            );
                        }

                        // 2. Restar de la cantidad general del artículo
                        $articulo->cantidad -= $cantSolicitada;
                        $articulo->save();

                        // 3. Determinar si requiere desglose multilote (si la cantidad supera el lote inicial)
                        $dispLoteInicial = (float) $loteReferencia->cantidad_restante;
                        $esMultilote = ($dispLoteInicial < $cantSolicitada);

                        // 4. Armar la cola de lotes a consumir: Primero el seleccionado por el usuario, luego FIFO
                        $lotesAProcesar = collect([$loteReferencia])
                            ->concat(
                                Movimiento::where('articulo_id', $item['articulo_id'])
                                          ->where('tipo', 'entrada')
                                          ->where('id', '!=', $loteReferencia->id)
                                          ->where('cantidad_restante', '>', 0)
                                          ->orderBy('fecha', 'asc')
                                          ->orderBy('created_at', 'asc')
                                          ->get()
                            )->unique('id');

                        $cantPendiente = $cantSolicitada;
                        $notaItemBase  = trim($item['nota_item'] ?? '');
                        $notaGenBase   = trim($request->notas ?? '');

                        foreach ($lotesAProcesar as $loteObj) {
                            if ($cantPendiente <= 0) {
                                break;
                            }
                            if ($loteObj->cantidad_restante <= 0) {
                                continue;
                            }

                            $deducir = min((float)$loteObj->cantidad_restante, $cantPendiente);

                            // Restar del lote de origen
                            $loteObj->cantidad_restante -= $deducir;
                            $loteObj->save();

                            // Formatear nota explicativa de multilote si aplica
                            $notaMultilote = '';
                            if ($esMultilote) {
                                $notaMultilote = "(Salida multilote de " . number_format($cantSolicitada, 2) . " " . $articulo->unidad . " totales: " . number_format($deducir, 2) . " " . $articulo->unidad . " tomados de este lote)";
                            }

                            $notaPartes = array_filter([$notaItemBase, $notaGenBase, $notaMultilote]);
                            $notaFinal  = implode(' | ', $notaPartes);

                            Movimiento::create([
                                'numero_nota'       => $numeroNota,
                                'articulo_id'       => $item['articulo_id'],
                                'tipo'              => 'salida',
                                'cantidad'          => $deducir,
                                'precio_unitario'   => $loteObj->precio_unitario,
                                'fecha'             => $request->fecha,
                                'notas'             => $notaFinal ?: null,
                                'user_id'           => Auth::id(),
                                'trabajador_id'     => $request->trabajador_id,
                                'trabajador_nombre' => $trabajadorNombre,
                                'entregado_por'     => $request->entregado_por,
                                'lote_id'           => $loteObj->id,
                                'turno'             => $request->turno,
                            ]);

                            $cantPendiente -= $deducir;
                        }
                    } else {
                        // ENTRADA: sumar cantidad Y actualizar el precio si cambió
                        $articulo->cantidad += $item['cantidad'];
                        $articulo->precio = $item['precio_unitario'];
                        $articulo->save();

                        // Combinar nota del ítem + nota general
                        $notaItem    = trim($item['nota_item'] ?? '');
                        $notaGeneral = trim($request->notas ?? '');
                        $notaFinal   = implode(' | ', array_filter([$notaItem, $notaGeneral]));

                        Movimiento::create([
                            'numero_nota'      => $numeroNota,
                            'articulo_id'      => $item['articulo_id'],
                            'tipo'             => 'entrada',
                            'cantidad'         => $item['cantidad'],
                            'cantidad_restante' => $item['cantidad'],
                            'precio_unitario'  => $item['precio_unitario'],
                            'fecha'            => $request->fecha,
                            'notas'            => $notaFinal ?: null,
                            'user_id'          => Auth::id(),
                            'entregado_por'    => $request->entregado_por,
                            'recibido_por'     => $request->recibido_por,
                        ]);
                    }
                }
            });

            // Registrar en la Bitácora de Auditoría
            $cantMovs = count($request->items);
            if ($request->tipo === 'entrada') {
                \App\Models\AuditoriaLog::registrar(
                    'REGISTRO_ENTRADA',
                    "Registró {$cantMovs} entrada(s) en lote (Nota {$numeroNota}). Entregado por: {$request->entregado_por}."
                );
            } else {
                \App\Models\AuditoriaLog::registrar(
                    'REGISTRO_SALIDA',
                    "Registró {$cantMovs} salida(s) en lote (Nota {$numeroNota}). Entregado a: {$trabajadorNombre} (Turno {$request->turno})."
                );
            }

            $tipoStr = $request->tipo === 'entrada' ? 'Entrada(s)' : 'Salida(s)';
            return redirect()->route('movimientos.index', ['tipo' => $request->tipo])
                             ->with('success', "{$tipoStr} registrada(s) correctamente.");

        } catch (\Exception $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Error al registrar el movimiento: ' . $e->getMessage());
        }
    }

    public function getLotes(Request $request)
    {
        $request->validate([
            'articulo_id' => 'required|exists:articulos,id',
        ]);

        $lotes = Movimiento::where('articulo_id', $request->articulo_id)
                           ->where('tipo', 'entrada')
                           ->where('cantidad_restante', '>', 0)
                           ->orderBy('created_at', 'asc') // FIFO: El más antiguo primero
                           ->get();

        // ─── Stock Inicial (material cargado antes del sistema) ─────────────────
        // Si el artículo tiene stock en inventario pero ningún lote de entrada,
        // se crea automáticamente un lote de "Stock Inicial" para poder registrar salidas.
        if ($lotes->isEmpty()) {
            $articulo = \App\Models\Articulo::find($request->articulo_id);

            if ($articulo && $articulo->cantidad > 0) {
                // Crear el lote de Stock Inicial en la base de datos para que el FIFO funcione
                $loteInicial = Movimiento::create([
                    'numero_nota'       => 'STOCK-INI',
                    'articulo_id'       => $articulo->id,
                    'tipo'              => 'entrada',
                    'cantidad'          => $articulo->cantidad,
                    'cantidad_restante' => $articulo->cantidad,
                    'precio_unitario'   => $articulo->precio ?? 0,
                    'fecha'             => $articulo->updated_at->toDateString(),
                    'notas'             => 'Stock inicial cargado antes de la implementación del sistema.',
                    'user_id'           => auth()->id(),
                    'entregado_por'     => 'Stock Inicial',
                    'recibido_por'      => 'Almacén',
                ]);

                $lotes = collect([$loteInicial]);

                \App\Models\AuditoriaLog::registrar(
                    'STOCK_INICIAL_AUTO',
                    "Lote de Stock Inicial creado automáticamente para {$articulo->codigo} — {$articulo->nombre} ({$articulo->cantidad} {$articulo->unidad} a Bs. " . number_format($articulo->precio ?? 0, 2) . ")."
                );
            }
        }

        // Agrupar por precio unitario para consolidar las opciones en el dropdown
        $lotesAgrupados = $lotes->groupBy(function ($l) {
            return number_format($l->precio_unitario, 4); // Usar mayor precisión para evitar colisiones
        })->map(function ($grupo) {
            $primerLote = $grupo->first(); // Lote más antiguo de este precio
            $ultimoLote = $grupo->last();  // Lote más reciente de este precio

            return [
                'id'               => $primerLote->id, // Referencia al lote más antiguo para comenzar la deducción FIFO
                'precio_unitario'  => $primerLote->precio_unitario,
                'cantidad_restante' => (float) $grupo->sum('cantidad_restante'),
                'created_at'       => $ultimoLote->created_at->toIso8601String(),
                'numero_nota'      => $grupo->pluck('numero_nota')->filter()->unique()->implode(', '),
                'entregado_por'    => $grupo->pluck('entregado_por')->filter()->unique()->implode(', '),
            ];
        })->values();

        return response()->json($lotesAgrupados);
    }

    /**
     * Corregir el precio_unitario de una entrada histórica específica.
     *
     * Solo aplica a movimientos de tipo 'entrada'.
     * Solo administradores pueden hacerlo.
     * No modifica cantidad ni cantidad_restante.
     * Actualiza las salidas vinculadas a este lote con el nuevo precio.
     * Recalcula el precio actual del artículo a partir de la entrada más reciente.
     */
    public function actualizarPrecio(Request $request, Movimiento $movimiento)
    {
        // Solo administradores
        if (!auth()->user()->esAdmin()) {
            return response()->json([
                'error' => 'Solo los administradores pueden corregir precios de entradas.',
            ], 403);
        }

        // Solo se puede corregir el precio de entradas
        if ($movimiento->tipo !== 'entrada') {
            return response()->json([
                'error' => 'Solo se puede corregir el precio de movimientos de tipo entrada.',
            ], 422);
        }

        $request->validate([
            'precio_unitario' => 'required|numeric|min:0',
        ], [
            'precio_unitario.required' => 'El nuevo precio unitario es obligatorio.',
            'precio_unitario.numeric'  => 'El precio debe ser un número.',
            'precio_unitario.min'      => 'El precio no puede ser negativo.',
        ]);

        $precioAnterior = $movimiento->precio_unitario;
        $nuevoPrecio    = (float) $request->precio_unitario;

        if ($precioAnterior == $nuevoPrecio) {
            return response()->json([
                'message' => 'El precio no cambió.',
            ]);
        }

        DB::transaction(function () use ($movimiento, $nuevoPrecio, $precioAnterior) {
            // 1. Actualizar el precio de la entrada
            $movimiento->precio_unitario = $nuevoPrecio;
            $movimiento->save();

            // 2. Propagar el nuevo precio a todas las salidas que salieron de este lote
            //    (para mantener coherencia en los reportes de costos)
            Movimiento::where('lote_id', $movimiento->id)
                      ->where('tipo', 'salida')
                      ->update(['precio_unitario' => $nuevoPrecio]);

            // 3. Recalcular el precio actual del artículo tomando el precio
            //    de la entrada más reciente (la que tiene el created_at más alto)
            $articulo          = $movimiento->articulo;
            $ultimaEntrada     = Movimiento::where('articulo_id', $articulo->id)
                                           ->where('tipo', 'entrada')
                                           ->whereNotNull('precio_unitario')
                                           ->orderBy('created_at', 'desc')
                                           ->first();

            if ($ultimaEntrada) {
                $articulo->precio = $ultimaEntrada->precio_unitario;
                $articulo->save();
            }

            // 4. Registrar en la bitácora
            \App\Models\AuditoriaLog::registrar(
                'CORRECCION_PRECIO_ENTRADA',
                "Corrigió el precio unitario de la entrada #{$movimiento->numero_nota} "
                . "del artículo {$articulo->codigo} — {$articulo->nombre}. "
                . "Precio anterior: Bs. " . number_format($precioAnterior, 2) . " → "
                . "Nuevo precio: Bs. " . number_format($nuevoPrecio, 2) . "."
            );
        });

        return response()->json([
            'success' => true,
            'message' => 'Precio corregido correctamente.',
            'precio_anterior' => $precioAnterior,
            'precio_nuevo'    => $nuevoPrecio,
        ]);
    }

    /**
     * Actualizar únicamente la nota/observación de un movimiento.
     */
    public function updateNota(Request $request, $id)
    {
        if (!Auth::user()->puedeEditar()) {
            return response()->json(['error' => 'No tienes permiso para realizar esta acción.'], 403);
        }

        $movimiento = Movimiento::findOrFail($id);
        $notaAnterior = $movimiento->notas;
        $nuevaNota = $request->input('notas');

        $movimiento->notas = $nuevaNota;
        $movimiento->save();

        \App\Models\AuditoriaLog::registrar(
            'EDICION_NOTA_MOVIMIENTO',
            "Editó la nota del movimiento #{$movimiento->numero_nota} ({$movimiento->tipo}) del artículo {$movimiento->articulo->codigo}. Anterior: '" . ($notaAnterior ?? 'sin nota') . "' → Nueva: '" . ($nuevaNota ?? 'sin nota') . "'"
        );

        // Notificar a todos los admins y a quienes tengan permisos de reportes
        $editadoPor = Auth::user()->name;
        $articuloNombre = $movimiento->articulo->nombre;
        $numNota = $movimiento->numero_nota;
        
        $destinatarios = \App\Models\User::where('rol', 'admin')
            ->orWhere('permiso_reportes', true)
            ->get();
            
        foreach ($destinatarios as $dest) {
            if ($dest->id === Auth::id()) continue;
            
            \App\Models\Notificacion::create([
                'user_id' => $dest->id,
                'titulo' => 'Observación de Material',
                'mensaje' => "El usuario {$editadoPor} agregó/editó la observación en el movimiento de {$articuloNombre} (Nota {$numNota}): '" . ($nuevaNota ?? 'sin nota') . "'",
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Nota actualizada correctamente.',
            'nueva_nota' => $movimiento->notas ?? '—'
        ]);
    }

    /**
     * Permite a un Administrador editar un movimiento existente (Entrada o Salida)
     * registrando los cambios en la auditoría y ajustando el stock si cambia la cantidad.
     */
    public function update(Request $request, $id)
    {
        if (!Auth::user()->puedeEditarMovimientos()) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'No tienes permiso para editar movimientos.'], 403);
            }
            return redirect()->back()->with('error', 'No tienes permiso para editar movimientos.');
        }

        $movimiento = Movimiento::with(['articulo', 'trabajador'])->findOrFail($id);

        $request->validate([
            'fecha' => 'required|date',
            'cantidad' => 'required|numeric|min:0.001',
            'precio_unitario' => 'nullable|numeric|min:0',
            'entregado_por' => 'nullable|string|max:150',
            'recibido_por' => 'nullable|string|max:150',
            'trabajador_id' => 'nullable|exists:trabajadores,id',
            'turno' => 'nullable|string|max:50',
            'notas' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $movimiento) {
            $articulo = $movimiento->articulo;
            $cambios = [];

            // 1. Cambio de Fecha
            if ($movimiento->fecha->format('Y-m-d') !== $request->fecha) {
                $cambios[] = "Fecha: {$movimiento->fecha->format('d/m/Y')} → " . \Carbon\Carbon::parse($request->fecha)->format('d/m/Y');
                $movimiento->fecha = $request->fecha;
            }

            // 2. Cambio de Cantidad
            $nuevaCantidad = (float) $request->cantidad;
            $cantidadAnterior = (float) $movimiento->cantidad;
            if (abs($nuevaCantidad - $cantidadAnterior) > 0.0001) {
                $diff = $nuevaCantidad - $cantidadAnterior;
                $cambios[] = "Cantidad: {$cantidadAnterior} → {$nuevaCantidad} {$articulo->unidad}";

                if ($movimiento->tipo === 'entrada') {
                    $articulo->cantidad += $diff;
                    $movimiento->cantidad_restante = max(0, (float)$movimiento->cantidad_restante + $diff);
                } else { // salida
                    $articulo->cantidad -= $diff;
                    if ($movimiento->lote_id) {
                        $lote = Movimiento::find($movimiento->lote_id);
                        if ($lote) {
                            $lote->cantidad_restante = max(0, (float)$lote->cantidad_restante - $diff);
                            $lote->save();
                        }
                    }
                }
                $articulo->save();
                $movimiento->cantidad = $nuevaCantidad;
            }

            // 3. Cambio de Precio Unitario (para Entradas)
            if ($movimiento->tipo === 'entrada' && $request->filled('precio_unitario')) {
                $nuevoPrecio = (float) $request->precio_unitario;
                $precioAnterior = (float) ($movimiento->precio_unitario ?? 0);
                if (abs($nuevoPrecio - $precioAnterior) > 0.001) {
                    $cambios[] = "Precio Unitario: Bs. " . number_format($precioAnterior, 2) . " → Bs. " . number_format($nuevoPrecio, 2);
                    $movimiento->precio_unitario = $nuevoPrecio;
                    // Propagar a salidas asociadas
                    Movimiento::where('lote_id', $movimiento->id)
                              ->where('tipo', 'salida')
                              ->update(['precio_unitario' => $nuevoPrecio]);
                }
            }

            // 4. Datos de Personal y Entrega
            if ($request->has('entregado_por') && $movimiento->entregado_por !== $request->entregado_por) {
                $cambios[] = "Entregado por: '" . ($movimiento->entregado_por ?? '—') . "' → '" . ($request->entregado_por ?? '—') . "'";
                $movimiento->entregado_por = $request->entregado_por;
            }

            if ($request->has('recibido_por') && $movimiento->recibido_por !== $request->recibido_por) {
                $cambios[] = "Recibido por: '" . ($movimiento->recibido_por ?? '—') . "' → '" . ($request->recibido_por ?? '—') . "'";
                $movimiento->recibido_por = $request->recibido_por;
            }

            if ($request->has('trabajador_id')) {
                if ($movimiento->trabajador_id != $request->trabajador_id) {
                    $trabNuevo = $request->trabajador_id ? Trabajador::find($request->trabajador_id) : null;
                    $nomAnterior = $movimiento->trabajador?->nombre ?? $movimiento->trabajador_nombre ?? '—';
                    $nomNuevo = $trabNuevo?->nombre ?? '—';
                    $cambios[] = "Contratista/Trabajador: '{$nomAnterior}' → '{$nomNuevo}'";
                    $movimiento->trabajador_id = $request->trabajador_id;
                    $movimiento->trabajador_nombre = $trabNuevo?->nombre;
                }
            }

            if ($request->has('turno') && $movimiento->turno !== $request->turno) {
                $cambios[] = "Turno: '" . ($movimiento->turno ?? '—') . "' → '" . ($request->turno ?? '—') . "'";
                $movimiento->turno = $request->turno;
            }

            // 5. Notas
            if ($request->has('notas') && $movimiento->notas !== $request->notas) {
                $cambios[] = "Notas: '" . ($movimiento->notas ?? '—') . "' → '" . ($request->notas ?? '—') . "'";
                $movimiento->notas = $request->notas;

                // Notificar a todos los admins y reportes
                $editadoPor = Auth::user()->name;
                $articuloNombre = $movimiento->articulo->nombre;
                $numNota = $movimiento->numero_nota;
                $nuevaNota = $request->notas;
                
                $destinatarios = \App\Models\User::where('rol', 'admin')
                    ->orWhere('permiso_reportes', true)
                    ->get();
                    
                foreach ($destinatarios as $dest) {
                    if ($dest->id === Auth::id()) continue;
                    
                    \App\Models\Notificacion::create([
                        'user_id' => $dest->id,
                        'titulo' => 'Observación de Material',
                        'mensaje' => "El usuario {$editadoPor} actualizó la observación del movimiento de {$articuloNombre} (Nota {$numNota}): '" . ($nuevaNota ?? 'sin nota') . "'",
                    ]);
                }
            }

            // 6. Marcar edición por Admin
            $movimiento->editado_por_id = Auth::id();
            $movimiento->editado_at = now();
            $movimiento->save();

            // 7. Registrar en la Bitácora de Auditoría
            $detalleCambios = !empty($cambios) ? implode('; ', $cambios) : 'Sin cambios detectados';
            $usuarioRol = Auth::user()->nombreRol();
            \App\Models\AuditoriaLog::registrar(
                'EDICION_MOVIMIENTO',
                "El {$usuarioRol} " . Auth::user()->name . " editó el movimiento #{$movimiento->numero_nota} ({$movimiento->tipo}) de {$articulo->codigo} — {$articulo->nombre}. Modificaciones: [{$detalleCambios}]."
            );
        });

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Movimiento actualizado correctamente por el Administrador.',
            ]);
        }

        return redirect()->back()->with('success', 'Movimiento actualizado correctamente.');
    }
}