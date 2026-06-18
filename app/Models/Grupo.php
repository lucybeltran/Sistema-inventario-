<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grupo extends Model
{
    protected $table = 'grupos';

    // La PK es un string (G-1, G-2...), no es autoincremental
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'nombre'];

    /**
     * Un grupo tiene muchos artículos.
     */
    public function articulos(): HasMany
    {
        return $this->hasMany(Articulo::class, 'grupo_id');
    }
}