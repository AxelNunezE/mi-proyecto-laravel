<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'codigo',
        'nombre',
        'categoria',
        'descripcion',
        'precio_compra',
        'precio_venta',
        'unidad_medida',
        'capacidad_ml',
        'stock',
        'stock_minimo',
        'usuario_registra',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2'
    ];

    public function usuarioRegistra()
    {
        return $this->belongsTo(User::class, 'usuario_registra');
    }

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }

    public function scopeConStockBajo(Builder $query): Builder
    {
        return $query->whereColumn('stock', '<=', 'stock_minimo');
    }

    public function scopeBuscar(Builder $query, string $termino): Builder
    {
        return $query->where('nombre', 'LIKE', "%{$termino}%")
            ->orWhere('descripcion', 'LIKE', "%{$termino}%")
            ->orWhere('codigo', 'LIKE', "%{$termino}%");
    }

    public function tieneStockBajo(): bool
    {
        return $this->stock <= $this->stock_minimo;
    }

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->codigo} - {$this->nombre}";
    }

    public function getPrecioVentaFormateadoAttribute(): string
    {
        return 'S/ ' . number_format($this->precio_venta, 2);
    }
}
