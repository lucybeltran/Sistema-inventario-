<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movimiento extends Model
{
    protected $table = 'movimientos';

    protected $fillable = [
        'numero_nota',
        'articulo_id',
        'tipo',
        'cantidad',
        'precio_unitario',
        'fecha',
        'notas',
        'user_id',
        'trabajador_id',
        'trabajador_nombre',
        'entregado_por',
        'cantidad_restante',
        'lote_id',
        'recibido_por',
        'turno',
        'editado_por_id',
        'editado_at',
    ];

    protected $casts = [
        'fecha' => 'date',
        'cantidad' => 'decimal:2',
        'cantidad_restante' => 'decimal:3',
        'editado_at' => 'datetime',
    ];

    public function articulo(): BelongsTo
    {
        return $this->belongsTo(Articulo::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function editadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'editado_por_id');
    }

    public function trabajador(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class);
    }

    public function lote(): BelongsTo
    {
        return $this->belongsTo(Movimiento::class, 'lote_id');
    }

    public function salidas()
    {
        return $this->hasMany(Movimiento::class, 'lote_id');
    }
    /**
     * Genera el siguiente número de nota correlativo.
     * Formato: 000001, 000002, etc.
     */
    public static function siguienteNumeroNota(): string
    {
        $ultimo = self::whereNotNull('numero_nota')
                      ->orderByRaw('CAST(numero_nota AS UNSIGNED) DESC')
                      ->value('numero_nota');

        $siguiente = $ultimo ? ((int) $ultimo + 1) : 1;

        return str_pad($siguiente, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Devuelve el nombre del trabajador (vivo o eliminado).
     */
    public function getNombreTrabajadorAttribute(): ?string
    {
        if ($this->trabajador) {
            return $this->trabajador->nombre;
        }
        return $this->trabajador_nombre;
    }

    /**
     * Obtiene el precio unitario de la entrada anterior más reciente para el mismo artículo.
     */
    public function obtenerPrecioAnterior(): ?float
    {
        if ($this->tipo !== 'entrada') {
            return null;
        }

        $prevPrice = self::where('articulo_id', $this->articulo_id)
                         ->where('tipo', 'entrada')
                         ->where('id', '<', $this->id)
                         ->orderBy('id', 'desc')
                         ->value('precio_unitario');

        return $prevPrice ? (float) $prevPrice : null;
    }

    /**
     * Devuelve la cantidad formateada (notación de tercios para dinamita, decimal estándar para el resto).
     */
    public function getCantidadFormateadaAttribute(): string
    {
        if (str_contains(strtolower($this->articulo?->nombre ?? ''), 'dinamita')) {
            $entero = floor($this->cantidad);
            $frac = (float)$this->cantidad - $entero;
            $tercios = round($frac * 3);
            if ($tercios >= 3) {
                return (string)($entero + 1);
            } elseif ($tercios > 0) {
                return $entero . '.' . $tercios;
            }
            return (string)$entero;
        }
        return number_format($this->cantidad, 2);
    }
}