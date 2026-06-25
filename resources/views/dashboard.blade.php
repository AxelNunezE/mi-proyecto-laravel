@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }

        .stat-info h3 {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 8px;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #667eea15, #764ba215);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }

        .section-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
        }

        .btn-ver {
            padding: 8px 16px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: background 0.3s;
        }

        .btn-ver:hover {
            background: #2563eb;
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

        .badge {
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-success {
            background: #dcfce7;
            color: #16a34a;
        }

        .badge-warning {
            background: #fed7aa;
            color: #c2410c;
        }
    </style>

    <div class="stats-grid">
        <a href="{{ route('productos.index') }}" class="stat-card">
            <div class="stat-info">
                <h3><i class="fas fa-box"></i> Total Productos</h3>
                <div class="stat-number">{{ $totalProductos }}</div>
            </div>
            <div class="stat-icon" style="color: #3b82f6;">
                <i class="fas fa-boxes"></i>
            </div>
        </a>

        <a href="{{ route('reportes.index') }}" class="stat-card">
            <div class="stat-info">
                <h3><i class="fas fa-exclamation-triangle"></i> Stock Bajo</h3>
                <div class="stat-number" style="color: {{ $stockBajo > 0 ? '#dc2626' : '#16a34a' }};">
                    {{ $stockBajo }}
                </div>
            </div>
            <div class="stat-icon" style="color: #f59e0b;">
                <i class="fas fa-chart-line"></i>
            </div>
        </a>

        <a href="{{ route('reportes.index') }}" class="stat-card">
            <div class="stat-info">
                <h3><i class="fas fa-tags"></i> Categorías</h3>
                <div class="stat-number">{{ $categorias->count() }}</div>
            </div>
            <div class="stat-icon" style="color: #10b981;">
                <i class="fas fa-tags"></i>
            </div>
        </a>
    </div>

    <div class="section-card">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-clock"></i> Productos Registrados Recientemente
            </div>
            <a href="{{ route('productos.index') }}" class="btn-ver">
                <i class="fas fa-arrow-right"></i> Ver Productos
            </a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Precio Venta</th>
                    <th>Stock</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productosRecientes as $producto)
                    <tr>
                        <td>{{ $producto->codigo }}</td>
                        <td><strong>{{ $producto->nombre }}</strong></td>
                        <td>{{ $producto->categoria }}</td>
                        <td>S/ {{ number_format($producto->precio_venta, 2) }}</td>
                        <td>{{ $producto->stock }} und</td>
                        <td>
                            @if ($producto->stock <= $producto->stock_minimo)
                                <span class="badge badge-warning">
                                    <i class="fas fa-exclamation-circle"></i> Stock bajo
                                </span>
                            @else
                                <span class="badge badge-success">
                                    <i class="fas fa-check-circle"></i> Normal
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:40px;">
                            <i class="fas fa-box" style="font-size:48px; color:#cbd5e1;"></i>
                            <p style="margin-top:12px; color:#94a3b8;">No hay productos registrados</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
