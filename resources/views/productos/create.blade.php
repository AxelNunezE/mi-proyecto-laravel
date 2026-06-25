@extends('layouts.app')

@section('title', 'Nuevo Producto')
@section('page-title', 'Agregar Nuevo Producto')

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

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #1e293b;
            font-size: 14px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            font-size: 15px;
        }

        .btn-success {
            background: #22c55e;
            color: white;
        }

        .btn-secondary {
            background: #94a3b8;
            color: white;
        }

        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 10px;
        }

        .error-text {
            color: #dc2626;
            font-size: 13px;
            margin-top: 4px;
        }
    </style>

    <div class="card">
        <form method="POST" action="{{ route('productos.store') }}">
            @csrf

            <div class="form-group">
                <label><i class="fas fa-tag"></i> Nombre del Producto *</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required placeholder="Ej: Cerveza IPA">
                @error('nombre')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label><i class="fas fa-folder"></i> Categoría</label>
                <input type="text" name="categoria" value="{{ old('categoria', 'General') }}"
                    placeholder="Ej: Cerveza Artesanal">
                @error('categoria')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label><i class="fas fa-align-left"></i> Descripción</label>
                <textarea name="descripcion" rows="3" placeholder="Descripción del producto">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-dollar-sign"></i> Precio Compra</label>
                    <input type="number" name="precio_compra" value="{{ old('precio_compra', 0) }}" step="0.01"
                        min="0">
                    @error('precio_compra')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label><i class="fas fa-dollar-sign"></i> Precio Venta *</label>
                    <input type="number" name="precio_venta" value="{{ old('precio_venta') }}" step="0.01"
                        min="0" required>
                    @error('precio_venta')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-ruler"></i> Unidad de Medida</label>
                    <input type="text" name="unidad_medida" value="{{ old('unidad_medida', 'unidad') }}"
                        placeholder="Ej: Botella 620ml">
                    @error('unidad_medida')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label><i class="fas fa-flask"></i> Capacidad (ml)</label>
                    <input type="number" name="capacidad_ml" value="{{ old('capacidad_ml', 0) }}" min="0">
                    @error('capacidad_ml')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-boxes"></i> Stock *</label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" required>
                    @error('stock')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label><i class="fas fa-exclamation-triangle"></i> Stock Mínimo</label>
                    <input type="number" name="stock_minimo" value="{{ old('stock_minimo', 5) }}" min="0">
                    @error('stock_minimo')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar Producto
                </button>
                <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection
