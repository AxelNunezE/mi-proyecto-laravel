<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductoCreacionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Caso de Prueba: CS-PROD-01 - Registro de productos en inventario
     * 
     * Requisito: RF-02: Gestión de inventario de productos.
     * Objetivo: Verificar que el administrador puede agregar un nuevo producto cervecero al inventario.
     * Prioridad: Alta
     * Tipo: Prueba de Integración (Happy Path)
     */
    public function test_administrador_puede_crear_un_producto_exitosamente()
    {
        // ============================================
        // PRECONDICIÓN: El administrador debe haber iniciado sesión
        // ============================================

        // Crear un usuario administrador en la base de datos
        $admin = User::create([
            'name' => 'Administrador Test',
            'email' => 'admin_test@cerveceria.com',
            'password' => bcrypt('password123'),
            'rol' => 'admin',
            'activo' => true
        ]);

        // Iniciar sesión como administrador
        $this->actingAs($admin);

        // ============================================
        // DATOS DE PRUEBA
        // ============================================

        $datosProducto = [
            'nombre' => 'Cerveza Stout Cusco',
            'categoria' => 'General',
            'descripcion' => 'Cerveza oscura artesanal con notas de café',
            'precio_compra' => 8.50,
            'precio_venta' => 15.00,
            'unidad_medida' => 'Botella',
            'capacidad_ml' => 330,
            'stock' => 45,
            'stock_minimo' => 10,
        ];

        // ============================================
        // PASOS DE PRUEBA
        // ============================================

        // PASO 1: Acceder al formulario de creación
        $response = $this->get(route('productos.create'));
        $response->assertStatus(200); // Verifica que el formulario se carga correctamente

        // PASO 2 y 3: Enviar el formulario con los datos de prueba
        $response = $this->post(route('productos.store'), $datosProducto);

        // ============================================
        // RESULTADO ESPERADO
        // ============================================

        // 1. El formulario se cierra y se muestra un mensaje de éxito
        $response->assertRedirect(route('productos.index'));
        $response->assertSessionHas('success'); // Verifica que hay mensaje de éxito

        // 2. El producto existe en la base de datos con los datos correctos
        $producto = Producto::where('nombre', 'Cerveza Stout Cusco')->first();

        $this->assertNotNull($producto, 'El producto no fue encontrado en la base de datos');
        $this->assertEquals('Cerveza Stout Cusco', $producto->nombre);
        $this->assertEquals('General', $producto->categoria);
        $this->assertEquals('Cerveza oscura artesanal con notas de café', $producto->descripcion);
        $this->assertEquals(8.50, $producto->precio_compra);
        $this->assertEquals(15.00, $producto->precio_venta);
        $this->assertEquals('Botella', $producto->unidad_medida);
        $this->assertEquals(330, $producto->capacidad_ml);
        $this->assertEquals(45, $producto->stock);
        $this->assertEquals(10, $producto->stock_minimo);
        $this->assertEquals($admin->id, $producto->usuario_registra); // Verifica que se asignó el usuario correcto
        $this->assertEquals(1, $producto->activo); // Verifica que está activo por defecto

        // Verifica que el código se generó automáticamente (debe empezar con 'PRO')
        $this->assertStringStartsWith('PRO', $producto->codigo);

        // 3. El producto aparece en la lista de productos
        $responseList = $this->get(route('productos.index'));
        $responseList->assertStatus(200);
        $responseList->assertSee('Cerveza Stout Cusco');
        $responseList->assertSee('45'); // Stock visible en la tabla
        $responseList->assertSee('15.00'); // Precio visible en la tabla
    }
}
