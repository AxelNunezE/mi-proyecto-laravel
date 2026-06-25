<?php

namespace App\Http\Controllers;

use App\Services\ReporteService;

class DashboardController extends Controller
{
    protected $reporteService;

    public function __construct(ReporteService $reporteService)
    {
        $this->reporteService = $reporteService;
    }

    public function index()
    {
        $estadisticas = $this->reporteService->obtenerEstadisticasDashboard();

        return view('dashboard', [
            'totalProductos' => $estadisticas['totalProductos'],
            'stockBajo' => $estadisticas['stockBajo'],
            'productosRecientes' => $estadisticas['productosRecientes'],
            'categorias' => $estadisticas['categorias']
        ]);
    }
}
