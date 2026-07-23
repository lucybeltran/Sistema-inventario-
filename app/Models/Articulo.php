<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Articulo extends Model
{
    protected $table = 'articulos';

    protected $fillable = [
        'codigo',
        'nombre',
        'unidad',
        'grupo_id',
        'cantidad',
        'precio',          // ← NUEVO
        'imagen',
        'rotacion',
        'stock_minimo',
        'notas',           // ← Observaciones/notas del artículo
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
        'precio' => 'decimal:2',
        'stock_minimo' => 'decimal:2',
    ];

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(Movimiento::class);
    }

    /**
     * Valor total = precio × cantidad
     */
    public function getValorTotalAttribute(): float
    {
        return $this->precio * $this->cantidad;
    }

    public function esConsumoDiario(): bool
    {
        return $this->rotacion === 'diario' || $this->rotacion === 'consumible';
    }

    public function esUsoOcasional(): bool
    {
        return $this->rotacion === 'ocasional' || empty($this->rotacion);
    }

    public function esPrestamo(): bool
    {
        return $this->rotacion === 'prestamo';
    }
}