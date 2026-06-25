<?php

namespace App\Http\Controllers;

use App\Services\ReporteService;

class ReporteController extends Controller
{
    protected $reporteService;

    public function __construct(ReporteService $reporteService)
    {
        $this->reporteService = $reporteService;
    }

    public function index()
    {
        $stockBajo = $this->reporteService->obtenerProductosStockBajo();
        $categorias = $this->reporteService->obtenerResumenCategorias();
        $topProductos = $this->reporteService->obtenerTopProductos(10);
        $valorInventario = $this->reporteService->obtenerValorInventario();
        $precioPromedio = $this->reporteService->obtenerPrecioPromedio();
        $productosAgotados = $this->reporteService->obtenerProductosAgotados();

        return view('reportes.index', compact(
            'stockBajo',
            'categorias',
            'topProductos',
            'valorInventario',
            'precioPromedio',
            'productosAgotados'
        ));
    }
}
