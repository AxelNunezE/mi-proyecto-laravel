@extends('layouts.app')

@section('title', 'Detalle del Producto')
@section('page-title', 'Detalle del Producto')

@section('content')
    <style>
        .card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
        }

        .detail-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .detail-label {
            font-weight: 600;
            width: 150px;
            color: #475569;
        }

        .detail-value {
            flex: 1;
            color: #1e293b;
        }

        .badge {
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 13px;
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

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary {
            background: #94a3b8;
            color: white;
        }

        .btn-warning {
            background: #f59e0b;
            color: white;
        }

        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
        }
    </style>

    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px;">
            <h2 style="font-size: 24px; color: #1e293b;">{{ $producto->nombre }}</h2>
            <span class="badge {{ $producto->activo ? 'badge-success' : 'badge-danger' }}">
                {{ $producto->activo ? 'Activo' : 'Inactivo' }}
            </span>
        </div>

        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-barcode"></i> Código</span>
            <span class="detail-value"><strong>{{ $producto->codigo }}</strong></span>
        </div>

        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-folder"></i> Categoría</span>
            <span class="detail-value">{{ $producto->categoria }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-align-left"></i> Descripción</span>
            <span class="detail-value">{{ $producto->descripcion ?: 'Sin descripción' }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-dollar-sign"></i> Precio Compra</span>
            <span class="detail-value">S/ {{ number_format($producto->precio_compra, 2) }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-dollar-sign"></i> Precio Venta</span>
            <span class="detail-value"><strong>S/ {{ number_format($producto->precio_venta, 2) }}</strong></span>
        </div>

        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-ruler"></i> Unidad de Medida</span>
            <span class="detail-value">{{ $producto->unidad_medida }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-flask"></i> Capacidad</span>
            <span class="detail-value">{{ $producto->capacidad_ml }} ml</span>
        </div>

        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-boxes"></i> Stock</span>
            <span class="detail-value">
                <span class="badge {{ $producto->stock <= $producto->stock_minimo ? 'badge-danger' : 'badge-success' }}">
                    {{ $producto->stock }} unidades
                    @if ($producto->stock <= $producto->stock_minimo)
                        <i class="fas fa-exclamation-triangle"></i>
                    @endif
                </span>
            </span>
        </div>

        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-exclamation-triangle"></i> Stock Mínimo</span>
            <span class="detail-value">{{ $producto->stock_minimo }} unidades</span>
        </div>

        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-user"></i> Registrado por</span>
            <span class="detail-value">{{ $producto->usuarioRegistra->name ?? 'Sistema' }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-calendar"></i> Fecha de registro</span>
            <span class="detail-value">{{ $producto->created_at->format('d/m/Y H:i') }}</span>
        </div>

        <div class="btn-group">
            <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
@endsection
