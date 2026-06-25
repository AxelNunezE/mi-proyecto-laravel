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
        'password',
        'telefono',
        'rol',
        'activo'
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
            'activo' => 'boolean',
        ];
    }

    // Métodos de autorización
    public function esAdmin()
    {
        return $this->rol === 'admin';
    }

    public function esAlmacenero()
    {
        return $this->rol === 'almacenero';
    }

    public function esVendedor()
    {
        return $this->rol === 'vendedor';
    }

    public function tienePermiso($rolRequerido)
    {
        $roles = ['vendedor' => 1, 'almacenero' => 2, 'admin' => 3];
        return $roles[$this->rol] >= $roles[$rolRequerido];
    }

    // Relación con productos creados
    public function productosCreados()
    {
        return $this->hasMany(Producto::class, 'usuario_registra');
    }
}
