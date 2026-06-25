@extends('layouts.app')

@section('title', 'Productos')
@section('page-title', 'Lista de Productos')

@section('content')
    <style>
        .card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .table-responsive {
            overflow-x: auto;
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

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-success {
            background: #22c55e;
            color: white;
        }

        .btn-warning {
            background: #f59e0b;
            color: white;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-info {
            background: #06b6d4;
            color: white;
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

        .badge-danger {
            background: #fee2e2;
            color: #dc2626;
        }

        .search-box {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-box input {
            flex: 1;
            padding: 10px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
        }

        .search-box button {
            padding: 10px 24px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 12px;
            cursor: pointer;
        }

        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 14px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            text-decoration: none;
            color: #3b82f6;
            background: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .pagination a:hover {
            background: #f1f5f9;
        }

        .pagination .active span {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .pagination .disabled span {
            color: #94a3b8;
            cursor: not-allowed;
        }
    </style>

    <div class="card">
        <div class="header-actions">
            <h2 style="font-size: 20px; color: #1e293b;">
                <i class="fas fa-box"></i> Productos
            </h2>
            <a href="{{ route('productos.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Nuevo Producto
            </a>
        </div>

        <form action="{{ route('productos.buscar') }}" method="GET" class="search-box">
            <input type="text" name="q" placeholder="Buscar productos por nombre, código o descripción..."
                value="{{ request('q') }}">
            <button type="submit"><i class="fas fa-search"></i> Buscar</button>
            @if (request('q'))
                <a href="{{ route('productos.index') }}" class="btn btn-warning" style="align-self: center;">
                    <i class="fas fa-times"></i> Limpiar
                </a>
            @endif
        </form>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th>Precio Venta</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $producto)
                        <tr>
                            <td><strong>{{ $producto->codigo }}</strong></td>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ Str::limit($producto->descripcion, 50) ?: 'Sin descripción' }}</td>
                            <td>{{ $producto->categoria }}</td>
                            <td>S/ {{ number_format($producto->precio_venta, 2) }}</td>
                            <td>
                                <span
                                    class="badge {{ $producto->stock <= $producto->stock_minimo ? 'badge-danger' : 'badge-success' }}">
                                    {{ $producto->stock }} und
                                    @if ($producto->stock <= $producto->stock_minimo)
                                        <i class="fas fa-exclamation-triangle"></i>
                                    @endif
                                </span>
                            </td>
                            <td>
                                @if ($producto->activo)
                                    <span class="badge badge-success"><i class="fas fa-check-circle"></i> Activo</span>
                                @else
                                    <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('productos.show', $producto->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('productos.destroy', $producto->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px;">
                                <i class="fas fa-box" style="font-size: 48px; color: #cbd5e1;"></i>
                                <p style="margin-top: 12px; color: #94a3b8;">No hay productos registrados</p>
                                <a href="{{ route('productos.create') }}" class="btn btn-success"
                                    style="margin-top: 12px;">
                                    <i class="fas fa-plus"></i> Crear primer producto
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($productos->hasPages())
            <div class="pagination">
                {{ $productos->links() }}
            </div>
        @endif
    </div>
@endsection
