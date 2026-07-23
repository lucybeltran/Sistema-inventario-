<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditoriaLog extends Model
{
    protected $table = 'auditoria_logs';

    protected $fillable = [
        'user_id',
        'accion',
        'detalles',
        'ip_address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Helper para registrar logs de auditoría rápidamente.
     */
    public static function registrar(string $accion, string $detalles)
    {
        try {
            self::create([
                'user_id' => auth()->id(),
                'accion' => $accion,
                'detalles' => $detalles,
                'ip_address' => request()->ip(),
            ]);

            // Eliminar automáticamente registros con más de una semana (7 días) de antigüedad
            self::where('created_at', '<', now()->subDays(7))->delete();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error registrando auditoria: " . $e->getMessage());
        }
    }
}
