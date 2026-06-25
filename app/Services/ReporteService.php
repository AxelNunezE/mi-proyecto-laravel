<?php

namespace App\Services;

use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class ReporteService
{
    protected $productoService;

    public function __construct(ProductoService $productoService)
    {
        $this->productoService = $productoService;
    }

    /**
     * Obtiene todas las estadísticas para el dashboard
     * 
     * @return array
     */
    public function obtenerEstadisticasDashboard()
    {
        return [
            'totalProductos' => $this->productoService->contarProductos(),
            'stockBajo' => $this->productoService->contarProductosStockBajo(),
            'productosRecientes' => $this->productoService->obtenerProductosRecientes(5),
            'categorias' => $this->productoService->obtenerResumenPorCategoria()
        ];
    }

    /**
     * Obtiene la lista de productos con stock bajo
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function obtenerProductosStockBajo()
    {
        return $this->productoService->obtenerProductosConStockBajo();
    }

    /**
     * Obtiene el resumen de productos por categoría
     * 
     * @return \Illuminate\Support\Collection
     */
    public function obtenerResumenCategorias()
    {
        return $this->productoService->obtenerResumenPorCategoria();
    }

    /**
     * Obtiene los top productos más vendidos (simulado)
     * 
     * @param int $limite
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function obtenerTopProductos($limite = 10)
    {
        // Simulación de productos más vendidos basado en stock y stock mínimo
        return Producto::activos()
            ->select('*', DB::raw('(stock_minimo * 10) as ventas_simuladas'))
            ->orderBy('ventas_simuladas', 'desc')
            ->limit($limite)
            ->get();
    }

    /**
     * Obtiene productos con stock crítico (stock = 0)
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function obtenerProductosAgotados()
    {
        return Producto::activos()
            ->where('stock', 0)
            ->get();
    }

    /**
     * Obtiene el valor total del inventario
     * 
     * @return float
     */
    public function obtenerValorInventario()
    {
        return Producto::activos()
            ->select(DB::raw('SUM(stock * precio_compra) as total'))
            ->first()
            ->total ?? 0;
    }

    /**
     * Obtiene el precio promedio de todos los productos
     * 
     * @return float
     */
    public function obtenerPrecioPromedio()
    {
        return Producto::activos()
            ->avg('precio_venta') ?? 0;
    }
}
