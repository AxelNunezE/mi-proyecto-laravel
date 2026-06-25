@extends('layouts.app')

@section('title', 'Reportes')
@section('page-title', 'Reportes y Estadísticas')

@section('content')
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 16px;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
        }

        .report-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .report-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 16px;
            color: #1e293b;
            border-left: 4px solid #f59e0b;
            padding-left: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 12px;
            background: #f8fafc;
            font-weight: 600;
            color: #475569;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        .badge-warning {
            background: #fed7aa;
            color: #c2410c;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 12px;
            display: inline-block;
        }

        .badge-danger {
            background: #fee2e2;
            color: #dc2626;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 12px;
            display: inline-block;
        }
    </style>

    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-number">{{ count($stockBajo) }}</div>
            <div>Productos con Stock Bajo</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $categorias->count() }}</div>
            <div>Categorías</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">S/ {{ number_format($valorInventario, 2) }}</div>
            <div>Valor del Inventario</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">S/ {{ number_format($precioPromedio, 2) }}</div>
            <div>Precio Promedio</div>
        </div>
    </div>

    <!-- Productos con Stock Bajo -->
    <div class="report-card">
        <div class="report-title">
            <i class="fas fa-exclamation-triangle" style="color: #f59e0b;"></i>
            Productos con Stock Bajo
        </div>
        @if ($stockBajo->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Stock Actual</th>
                        <th>Stock Mínimo</th>
                        <th>Necesita comprar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stockBajo as $p)
                        <tr>
                            <td><strong>{{ $p->nombre }}</strong></td>
                            <td>{{ $p->categoria }}</td>
                            <td><span class="badge-danger">{{ $p->stock }} und</span></td>
                            <td>{{ $p->stock_minimo }} und</td>
                            <td><span class="badge-warning">{{ $p->stock_minimo - $p->stock + 10 }} unidades</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align:center;padding:20px;">✅ Todos los productos tienen stock suficiente</p>
        @endif
    </div>

    <!-- Productos Agotados -->
    @if ($productosAgotados->count() > 0)
        <div class="report-card">
            <div class="report-title">
                <i class="fas fa-times-circle" style="color: #dc2626;"></i>
                Productos Agotados (Stock = 0)
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Stock Mínimo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productosAgotados as $p)
                        <tr>
                            <td><strong>{{ $p->nombre }}</strong></td>
                            <td>{{ $p->categoria }}</td>
                            <td>{{ $p->stock_minimo }} und</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Resumen por Categoría -->
    <div class="report-card">
        <div class="report-title">
            <i class="fas fa-chart-pie"></i>
            Resumen por Categoría
        </div>
        <table>
            <thead>
                <tr>
                    <th>Categoría</th>
                    <th>Cantidad Productos</th>
                    <th>Stock Total</th>
                    <th>Precio Promedio</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categorias as $cat)
                    <tr>
                        <td>{{ $cat->categoria }}</td>
                        <td>{{ $cat->total }}</td>
                        <td>{{ $cat->stock_total }} und</td>
                        <td>S/ {{ number_format($cat->precio_promedio, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Top Productos -->
    <div class="report-card">
        <div class="report-title">
            <i class="fas fa-trophy"></i>
            Top Productos más Demandados
        </div>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Precio Venta</th>
                    <th>Stock Actual</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($topProductos as $p)
                    <tr>
                        <td><strong>{{ $p->nombre }}</strong></td>
                        <td>{{ $p->categoria }}</td>
                        <td>S/ {{ number_format($p->precio_venta, 2) }}</td>
                        <td>{{ $p->stock }} und</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
