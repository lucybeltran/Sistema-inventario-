<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'rol',
        'password',
        'permiso_almacen',
        'permiso_reportes',
        'permiso_editar_movimientos',
        'permiso_editar_materiales',
        'activo',
        'foto_perfil',
        'permitir_cambio_password',
        'intentos_fallidos',
        'bloqueado_hasta',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'permiso_almacen' => 'boolean',
            'permiso_reportes' => 'boolean',
            'permiso_editar_movimientos' => 'boolean',
            'permiso_editar_materiales' => 'boolean',
            'activo' => 'boolean',
            'permitir_cambio_password' => 'boolean',
            'bloqueado_hasta' => 'datetime',
        ];
    }

    // ============================================
    // HELPERS DE ROL Y PERMISOS
    // ============================================

    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function esAlmacenero(): bool
    {
        return $this->rol === 'almacenero';
    }

    public function esReportes(): bool
    {
        return $this->rol === 'reportes';
    }

    public function puedeEditar(): bool
    {
        return $this->esAdmin() || (bool)$this->permiso_almacen;
    }

    public function puedeEditarMovimientos(): bool
    {
        return $this->esAdmin() || (bool)$this->permiso_editar_movimientos;
    }

    public function puedeEditarMateriales(): bool
    {
        return $this->esAdmin() || (bool)$this->permiso_editar_materiales;
    }

    /**
     * Puede ver y descargar reportes?
     * Admin y Reportes pueden.
     */
    public function puedeReportes(): bool
    {
        return $this->esAdmin() || (bool)$this->permiso_reportes;
    }

    /**
     * Devuelve el nombre del rol en español.
     */
    public function nombreRol(): string
    {
        return match($this->rol) {
            'admin' => 'Administrador',
            'almacenero' => 'Almacenero',
            'reportes' => 'Reportes',
            default => 'Sin rol',
        };
    }

    /**
     * Devuelve el ícono del rol para mostrar en UI.
     */
    public function iconoRol(): string
    {
        return match($this->rol) {
            'admin' => 'crown',
            'almacenero' => 'box',
            'reportes' => 'file-alt',
            default => 'user',
        };
    }

    /**
     * Devuelve la URL de la foto de perfil del usuario o un fallback con iniciales estéticas.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->foto_perfil) {
            return asset('storage/' . $this->foto_perfil);
        }

        // Generar un avatar basado en iniciales
        $nameParts = explode(' ', trim($this->name));
        $initials = '';
        foreach ($nameParts as $part) {
            if ($part !== '') {
                $initials .= mb_substr($part, 0, 1);
            }
        }
        $initials = mb_strtoupper(mb_substr($initials, 0, 2));

        return 'https://ui-avatars.com/api/?name=' . urlencode($initials) . '&background=f97316&color=ffffff&bold=true&size=128';
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'user_id');
    }

    public function iniciosSesion()
    {
        return $this->hasMany(InicioSesion::class, 'user_id');
    }

    public function notificacionesNoLeidasCount(): int
    {
        return $this->notificaciones()->where('leido', false)->count();
    }
}