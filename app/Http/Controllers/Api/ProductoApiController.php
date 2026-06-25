<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductoApiController extends Controller
{
    protected $productoService;

    public function __construct(ProductoService $productoService)
    {
        $this->productoService = $productoService;
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $productos = $this->productoService->obtenerTodos(100);
        return response()->json([
            'success' => true,
            'data' => $productos->items(),
            'total' => $productos->total()
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'categoria' => 'nullable|string|max:100',
            'precio_venta' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->productoService->crearProducto(
            $request->all(),
            auth()->id()
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => $result['producto']
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message']
        ], 500);
    }

    public function show($id)
    {
        $producto = $this->productoService->obtenerPorId($id);

        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $producto
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'categoria' => 'nullable|string|max:100',
            'precio_venta' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->productoService->actualizarProducto($id, $request->all());

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => $result['producto']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message']
        ], 500);
    }

    public function destroy($id)
    {
        $result = $this->productoService->eliminarProducto($id);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'id' => $id
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message']
        ], 404);
    }

    public function buscar(Request $request)
    {
        $termino = $request->get('q', '');

        if (empty($termino)) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $productos = $this->productoService->buscar($termino);

        return response()->json([
            'success' => true,
            'data' => $productos
        ]);
    }
}
