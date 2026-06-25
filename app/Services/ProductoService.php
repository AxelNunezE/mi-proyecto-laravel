<?php

namespace App\Services;

use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductoService
{
    /**
     * Obtiene todos los productos activos con paginación
     * 
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function obtenerTodos($perPage = 10)
    {
        return Producto::activos()
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    /**
     * Obtiene un producto por su ID
     * 
     * @param int $id
     * @return Producto|null
     */
    public function obtenerPorId($id)
    {
        return Producto::activos()->find($id);
    }

    /**
     * Busca productos por nombre o descripción
     * 
     * @param string $termino
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function buscar($termino)
    {
        if (empty($termino)) {
            return collect([]);
        }

        return Producto::activos()
            ->buscar($termino)
            ->get();
    }

    /**
     * Crea un nuevo producto con toda la lógica de negocio
     * 
     * @param array $datos
     * @param int $usuarioId
     * @return array ['success' => bool, 'producto' => Producto|null, 'message' => string]
     */
    public function crearProducto($datos, $usuarioId)
    {
        try {
            // Generar código automáticamente
            $codigo = $this->generarCodigo();

            $producto = Producto::create([
                'codigo' => $codigo,
                'nombre' => $datos['nombre'],
                'categoria' => $datos['categoria'] ?? 'General',
                'descripcion' => $datos['descripcion'] ?? '',
                'precio_compra' => $datos['precio_compra'] ?? 0,
                'precio_venta' => $datos['precio_venta'],
                'unidad_medida' => $datos['unidad_medida'] ?? 'unidad',
                'capacidad_ml' => $datos['capacidad_ml'] ?? 0,
                'stock' => $datos['stock'] ?? 0,
                'stock_minimo' => $datos['stock_minimo'] ?? 5,
                'usuario_registra' => $usuarioId,
                'activo' => true
            ]);

            return [
                'success' => true,
                'producto' => $producto,
                'message' => '✅ Producto creado correctamente con código: ' . $codigo
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'producto' => null,
                'message' => '❌ Error al crear el producto: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Actualiza un producto existente
     * 
     * @param int $id
     * @param array $datos
     * @return array ['success' => bool, 'producto' => Producto|null, 'message' => string]
     */
    public function actualizarProducto($id, $datos)
    {
        try {
            $producto = Producto::activos()->find($id);

            if (!$producto) {
                return [
                    'success' => false,
                    'producto' => null,
                    'message' => 'Producto no encontrado'
                ];
            }

            $producto->update([
                'nombre' => $datos['nombre'],
                'categoria' => $datos['categoria'] ?? 'General',
                'descripcion' => $datos['descripcion'] ?? '',
                'precio_compra' => $datos['precio_compra'] ?? 0,
                'precio_venta' => $datos['precio_venta'],
                'unidad_medida' => $datos['unidad_medida'] ?? 'unidad',
                'capacidad_ml' => $datos['capacidad_ml'] ?? 0,
                'stock' => $datos['stock'] ?? 0,
                'stock_minimo' => $datos['stock_minimo'] ?? 5,
            ]);

            return [
                'success' => true,
                'producto' => $producto,
                'message' => '✅ Producto actualizado correctamente'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'producto' => null,
                'message' => '❌ Error al actualizar el producto: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Elimina un producto (borrado lógico)
     * 
     * @param int $id
     * @return array ['success' => bool, 'message' => string]
     */
    public function eliminarProducto($id)
    {
        try {
            $producto = Producto::activos()->find($id);

            if (!$producto) {
                return [
                    'success' => false,
                    'message' => 'Producto no encontrado'
                ];
            }

            $nombre = $producto->nombre;
            $producto->update(['activo' => false]);

            return [
                'success' => true,
                'message' => '✅ Producto "' . $nombre . '" eliminado correctamente',
                'id' => $id
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => '❌ Error al eliminar el producto: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtiene productos con stock bajo
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function obtenerProductosConStockBajo()
    {
        return Producto::activos()
            ->conStockBajo()
            ->orderBy('stock', 'asc')
            ->get();
    }

    /**
     * Obtiene el total de productos activos
     * 
     * @return int
     */
    public function contarProductos()
    {
        return Producto::activos()->count();
    }

    /**
     * Obtiene el total de productos con stock bajo
     * 
     * @return int
     */
    public function contarProductosStockBajo()
    {
        return Producto::activos()->conStockBajo()->count();
    }

    /**
     * Obtiene los productos más recientes
     * 
     * @param int $limite
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function obtenerProductosRecientes($limite = 5)
    {
        return Producto::activos()
            ->orderBy('created_at', 'desc')
            ->limit($limite)
            ->get();
    }

    /**
     * Obtiene resumen de productos agrupados por categoría
     * 
     * @return \Illuminate\Support\Collection
     */
    public function obtenerResumenPorCategoria()
    {
        return Producto::activos()
            ->select('categoria', DB::raw('COUNT(*) as total'), DB::raw('SUM(stock) as stock_total'), DB::raw('AVG(precio_venta) as precio_promedio'))
            ->groupBy('categoria')
            ->get();
    }

    /**
     * Genera un código único para el producto
     * 
     * @return string
     */
    private function generarCodigo()
    {
        $prefix = 'PRO';

        $last = Producto::where('codigo', 'LIKE', "{$prefix}%")
            ->orderBy('codigo', 'desc')
            ->first();

        if ($last) {
            $num = intval(substr($last->codigo, strlen($prefix))) + 1;
        } else {
            $num = 1;
        }

        return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}
