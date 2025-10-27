@extends('alumno.layout')

@section('title', 'Productos Disponibles')
@section('page-title', 'Productos Disponibles')

@section('content')
<style>
    .filters-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 25px;
    }

    .filters-form {
        display: grid;
        grid-template-columns: 2fr 1fr auto;
        gap: 15px;
        align-items: end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #666;
        margin-bottom: 8px;
    }

    .form-input,
    .form-select {
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.2s;
        font-family: 'Inter', sans-serif;
    }

    .form-input:focus,
    .form-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .btn {
        padding: 12px 25px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
    }

    .stats-banner {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 15px;
        padding: 20px 25px;
        color: white;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 5px 20px rgba(16, 185, 129, 0.3);
    }

    .stats-item {
        text-align: center;
    }

    .stats-value {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .stats-label {
        font-size: 0.85rem;
        opacity: 0.9;
    }

    .productos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .producto-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .producto-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .producto-img {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        position: relative;
    }

    .producto-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .badge-disponible {
        background: #d4edda;
        color: #155724;
    }

    .badge-sin-puntos {
        background: #fff3cd;
        color: #856404;
    }

    .producto-content {
        padding: 20px;
    }

    .producto-nombre {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: #333;
    }

    .producto-descripcion {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 15px;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .producto-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 2px solid #f0f0f0;
    }

    .producto-precio {
        font-size: 1.8rem;
        font-weight: 800;
        color: #3b82f6;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .producto-stock {
        font-size: 0.85rem;
        color: #888;
        font-weight: 600;
    }

    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 30px;
    }

    .pagination {
        display: flex;
        gap: 8px;
        list-style: none;
    }

    .page-item {
        display: inline-block;
    }

    .page-link {
        padding: 10px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        color: #333;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s;
    }

    .page-link:hover {
        border-color: #3b82f6;
        color: #3b82f6;
        background: rgba(59, 130, 246, 0.05);
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        border-color: #3b82f6;
    }

    .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }

    .empty-state {
        background: white;
        border-radius: 15px;
        padding: 60px 30px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .empty-icon {
        font-size: 5rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
    }

    .empty-text {
        color: #666;
        font-size: 1rem;
    }

    @media (max-width: 768px) {
        .filters-form {
            grid-template-columns: 1fr;
        }

        .stats-banner {
            flex-direction: column;
            gap: 15px;
        }

        .productos-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        }
    }
</style>

<!-- Stats Banner -->
<div class="stats-banner">
    <div class="stats-item">
        <div class="stats-value">⭐ {{ number_format($misRecipuntos) }}</div>
        <div class="stats-label">Mis Recipuntos</div>
    </div>
    <div class="stats-item">
        <div class="stats-value">{{ $productos->total() }}</div>
        <div class="stats-label">Productos Disponibles</div>
    </div>
    <div class="stats-item">
        <div class="stats-value">{{ $productosQueAsalcanzar }}</div>
        <div class="stats-label">Puedes Canjear</div>
    </div>
</div>

<!-- Filtros -->
<div class="filters-card">
    <form action="{{ route('alumno.productos.index') }}" method="GET" class="filters-form">
        <div class="form-group">
            <label class="form-label">🔍 Buscar Producto</label>
            <input type="text" name="buscar" class="form-input" placeholder="Nombre o descripción..." value="{{ $buscar }}">
        </div>
        <div class="form-group">
            <label class="form-label">📊 Ordenar Por</label>
            <select name="orden" class="form-select">
                <option value="precio_asc" {{ $orden == 'precio_asc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                <option value="precio_desc" {{ $orden == 'precio_desc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                <option value="nombre" {{ $orden == 'nombre' ? 'selected' : '' }}>Nombre A-Z</option>
                <option value="recientes" {{ $orden == 'recientes' ? 'selected' : '' }}>Más Recientes</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filtrar</button>
    </form>
</div>

<!-- Productos Grid -->
@if($productos->count() > 0)
    <div class="productos-grid">
        @foreach($productos as $producto)
            <a href="{{ route('alumno.productos.show', $producto->cod_publicacion) }}" class="producto-card">
                <div class="producto-img">
                    🎒
                    @if(($producto->precio ?? 0) <= $misRecipuntos)
                        <span class="producto-badge badge-disponible">¡Puedes Canjearlo!</span>
                    @else
                        <span class="producto-badge badge-sin-puntos">Te faltan {{ number_format(($producto->precio ?? 0) - $misRecipuntos) }} pts</span>
                    @endif
                </div>
                <div class="producto-content">
                    <h3 class="producto-nombre">{{ $producto->nombre }}</h3>
                    @if($producto->descripcion)
                        <p class="producto-descripcion">{{ $producto->descripcion }}</p>
                    @endif
                    <div class="producto-footer">
                        <div class="producto-precio">
                            <span>⭐</span>
                            <span>{{ number_format($producto->precio ?? 0) }}</span>
                        </div>
                        <div class="producto-stock">
                            📦 {{ number_format($producto->disponibilidad ?? 0) }} disponibles
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Paginación -->
    @if($productos->hasPages())
        <div class="pagination-container">
            {{ $productos->links() }}
        </div>
    @endif
@else
    <div class="empty-state">
        <div class="empty-icon">📦</div>
        <h3 class="empty-title">No se encontraron productos</h3>
        <p class="empty-text">
            @if($buscar)
                No hay productos que coincidan con tu búsqueda "{{ $buscar }}"
            @else
                No hay productos disponibles en este momento
            @endif
        </p>
        @if($buscar)
            <a href="{{ route('alumno.productos.index') }}" class="btn btn-primary" style="margin-top: 20px;">Ver Todos los Productos</a>
        @endif
    </div>
@endif

@endsection
