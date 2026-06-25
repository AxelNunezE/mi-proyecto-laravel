<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Services\ProductoService;

class ProductoController extends Controller
{
    protected $productoService;

    public function __construct(ProductoService $productoService)
    {
        $this->productoService = $productoService;
    }

    public function index()
    {
        $productos = Producto::activos()->orderBy('codigo', 'asc')->paginate(10);
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'categoria' => 'nullable|string|max:100',
            'descripcion' => 'nullable|string',
            'precio_compra' => 'nullable|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'unidad_medida' => 'nullable|string|max:50',
            'capacidad_ml' => 'nullable|integer|min:0',
            'stock' => 'nullable|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $usuarioId = Auth::id() ?? 1;
        $result = $this->productoService->crearProducto($request->all(), $usuarioId);

        if ($result['success']) {
            return redirect()->route('productos.index')
                ->with('success', $result['message']);
        }

        return redirect()->back()
            ->with('error', $result['message'])
            ->withInput();
    }

    public function show($id)
    {
        $producto = Producto::activos()->find($id);

        if (!$producto) {
            return redirect()->route('productos.index')
                ->with('error', 'Producto no encontrado');
        }

        return view('productos.show', compact('producto'));
    }

    public function edit($id)
    {
        $producto = Producto::activos()->find($id);

        if (!$producto) {
            return redirect()->route('productos.index')
                ->with('error', 'Producto no encontrado');
        }

        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'categoria' => 'nullable|string|max:100',
            'descripcion' => 'nullable|string',
            'precio_compra' => 'nullable|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'unidad_medida' => 'nullable|string|max:50',
            'capacidad_ml' => 'nullable|integer|min:0',
            'stock' => 'nullable|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $result = $this->productoService->actualizarProducto($id, $request->all());

        if ($result['success']) {
            return redirect()->route('productos.index')
                ->with('success', $result['message']);
        }

        return redirect()->back()
            ->with('error', $result['message'])
            ->withInput();
    }

    public function destroy($id)
    {
        $result = $this->productoService->eliminarProducto($id);

        if ($result['success']) {
            return redirect()->route('productos.index')
                ->with('success', $result['message']);
        }

        return redirect()->route('productos.index')
            ->with('error', $result['message']);
    }

    public function buscar(Request $request)
    {
        $termino = $request->get('q', '');

        if (empty($termino)) {
            if ($request->ajax()) {
                return response()->json(['success' => true, 'data' => []]);
            }
            return redirect()->route('productos.index');
        }

        $productos = Producto::activos()
            ->where('nombre', 'LIKE', "%{$termino}%")
            ->orWhere('descripcion', 'LIKE', "%{$termino}%")
            ->orWhere('codigo', 'LIKE', "%{$termino}%")
            ->orderBy('codigo', 'asc')
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $productos->items(),
                'total' => $productos->total()
            ]);
        }

        return view('productos.index', compact('productos'));
    }
}
