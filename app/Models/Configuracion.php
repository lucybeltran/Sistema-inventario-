<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuraciones';
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['key', 'value'];

    /**
     * Obtener el valor de una configuración.
     */
    public static function obtener($key, $default = null)
    {
        $config = self::find($key);
        return $config ? $config->value : $default;
    }

    /**
     * Guardar o actualizar una configuración.
     */
    public static function guardar($key, $value)
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
