<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener el usuario admin para asignar como creador
        $admin = User::where('email', 'admin@cerveceria.com')->first();
        $usuarioId = $admin ? $admin->id : 1;

        // Productos de ejemplo con categorías
        $productos = [
            // Cervezas Artesanales
            [
                'nombre' => 'IPA Artesanal',
                'categoria' => 'Cerveza Artesanal',
                'descripcion' => 'Cerveza IPA con lúpulos de la región',
                'precio_compra' => 12.00,
                'precio_venta' => 25.00,
                'unidad_medida' => 'Botella 620ml',
                'capacidad_ml' => 620,
                'stock' => 50,
                'stock_minimo' => 10,
                'activo' => true
            ],
            [
                'nombre' => 'Stout Negra',
                'categoria' => 'Cerveza Artesanal',
                'descripcion' => 'Cerveza negra con notas a café',
                'precio_compra' => 14.00,
                'precio_venta' => 28.00,
                'unidad_medida' => 'Botella 620ml',
                'capacidad_ml' => 620,
                'stock' => 35,
                'stock_minimo' => 8,
                'activo' => true
            ],
            [
                'nombre' => 'Golden Ale',
                'categoria' => 'Cerveza Artesanal',
                'descripcion' => 'Cerveza dorada y refrescante',
                'precio_compra' => 10.00,
                'precio_venta' => 22.00,
                'unidad_medida' => 'Botella 620ml',
                'capacidad_ml' => 620,
                'stock' => 60,
                'stock_minimo' => 12,
                'activo' => true
            ],

            // Cervezas Industriales
            [
                'nombre' => 'Pilsen Clásica',
                'categoria' => 'Cerveza Industrial',
                'descripcion' => 'Cerveza rubia estilo Pilsen',
                'precio_compra' => 6.00,
                'precio_venta' => 12.00,
                'unidad_medida' => 'Lata 355ml',
                'capacidad_ml' => 355,
                'stock' => 100,
                'stock_minimo' => 20,
                'activo' => true
            ],
            [
                'nombre' => 'Lager Premium',
                'categoria' => 'Cerveza Industrial',
                'descripcion' => 'Cerveza Lager de alta calidad',
                'precio_compra' => 8.00,
                'precio_venta' => 15.00,
                'unidad_medida' => 'Botella 355ml',
                'capacidad_ml' => 355,
                'stock' => 80,
                'stock_minimo' => 15,
                'activo' => true
            ],

            // Cocteles
            [
                'nombre' => 'Machu Picchu Sour',
                'categoria' => 'Coctel',
                'descripcion' => 'Coctel con pisco y frutas andinas',
                'precio_compra' => 15.00,
                'precio_venta' => 35.00,
                'unidad_medida' => 'Botella 750ml',
                'capacidad_ml' => 750,
                'stock' => 25,
                'stock_minimo' => 5,
                'activo' => true
            ],
            [
                'nombre' => 'Inca Mule',
                'categoria' => 'Coctel',
                'descripcion' => 'Coctel con jengibre y hierbas nativas',
                'precio_compra' => 13.00,
                'precio_venta' => 30.00,
                'unidad_medida' => 'Botella 750ml',
                'capacidad_ml' => 750,
                'stock' => 20,
                'stock_minimo' => 4,
                'activo' => true
            ],

            // Producto con stock bajo (para probar alertas)
            [
                'nombre' => 'Imperial IPA Doble',
                'categoria' => 'Cerveza Artesanal',
                'descripcion' => 'IPA doble con alto contenido de lúpulo',
                'precio_compra' => 18.00,
                'precio_venta' => 38.00,
                'unidad_medida' => 'Botella 620ml',
                'capacidad_ml' => 620,
                'stock' => 3,
                'stock_minimo' => 10,
                'activo' => true
            ],

            // Producto agotado
            [
                'nombre' => 'Honey Ale',
                'categoria' => 'Cerveza Artesanal',
                'descripcion' => 'Cerveza con miel de la selva',
                'precio_compra' => 16.00,
                'precio_venta' => 32.00,
                'unidad_medida' => 'Botella 620ml',
                'capacidad_ml' => 620,
                'stock' => 0,
                'stock_minimo' => 8,
                'activo' => true
            ],

            // Productos para otras categorías
            [
                'nombre' => 'Agua Mineral',
                'categoria' => 'Bebidas Sin Alcohol',
                'descripcion' => 'Agua mineral de manantial',
                'precio_compra' => 2.00,
                'precio_venta' => 5.00,
                'unidad_medida' => 'Botella 500ml',
                'capacidad_ml' => 500,
                'stock' => 200,
                'stock_minimo' => 30,
                'activo' => true
            ],
            [
                'nombre' => 'Gaseosa Cola',
                'categoria' => 'Bebidas Sin Alcohol',
                'descripcion' => 'Gaseosa sabor cola',
                'precio_compra' => 2.50,
                'precio_venta' => 6.00,
                'unidad_medida' => 'Botella 500ml',
                'capacidad_ml' => 500,
                'stock' => 150,
                'stock_minimo' => 25,
                'activo' => true
            ],
        ];

        // Crear productos
        foreach ($productos as $productoData) {
            // Generar código manualmente para el seeder
            $prefix = 'PRO';
            $last = Producto::where('codigo', 'LIKE', "{$prefix}%")
                ->orderBy('codigo', 'desc')
                ->first();

            if ($last) {
                $num = intval(substr($last->codigo, strlen($prefix))) + 1;
            } else {
                $num = 1;
            }
            $codigo = $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);

            Producto::create(array_merge($productoData, [
                'codigo' => $codigo,
                'usuario_registra' => $usuarioId
            ]));
        }

        $this->command->info('✅ Productos de prueba creados exitosamente!');
        $this->command->info('📊 Total de productos: ' . Producto::count());
    }
}
