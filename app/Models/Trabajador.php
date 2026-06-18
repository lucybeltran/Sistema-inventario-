<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trabajador extends Model
{
    protected $table = 'trabajadores';

    protected $fillable = [
        'nombre',
        'ci',
        'cargo',
        'telefono',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Movimientos donde este trabajador recibió material.
     */
    public function movimientos(): HasMany
    {
        return $this->hasMany(Movimiento::class);
    }

    /**
     * Scope para listar solo trabajadores activos.
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}