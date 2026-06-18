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
        ];
    }

    // ============================================
    // HELPERS DE ROL
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

    /**
     * Puede modificar inventario (artículos, grupos, movimientos, trabajadores)?
     * Admin y Almacenero pueden.
     */
    public function puedeEditar(): bool
    {
        return in_array($this->rol, ['admin', 'almacenero']);
    }

    /**
     * Puede ver y descargar reportes?
     * Admin y Reportes pueden.
     */
    public function puedeReportes(): bool
    {
        return in_array($this->rol, ['admin', 'reportes']);
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
}