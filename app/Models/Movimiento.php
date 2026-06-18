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
    ];

    protected $casts = [
        'fecha' => 'date',
        'cantidad' => 'decimal:2',
    ];

    public function articulo(): BelongsTo
    {
        return $this->belongsTo(Articulo::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trabajador(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class);
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
}