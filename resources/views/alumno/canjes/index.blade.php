@extends('alumno.layout')

@section('title', 'Mis Canjes')
@section('page-title', 'Historial de Canjes')

@section('content')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        width: 55px;
        height: 55px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 15px;
    }

    .stat-card.blue .stat-icon { background: rgba(59, 130, 246, 0.1); }
    .stat-card.green .stat-icon { background: rgba(16, 185, 129, 0.1); }
    .stat-card.orange .stat-icon { background: rgba(251, 146, 60, 0.1); }
    .stat-card.purple .stat-icon { background: rgba(139, 92, 246, 0.1); }

    .stat-label {
        font-size: 0.85rem;
        color: #888;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: #333;
    }

    .filters-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 25px;
    }

    .filters-form {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr auto;
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
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
    }

    .canjes-table-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead {
        background: #f8f9fa;
    }

    .table th {
        text-align: left;
        padding: 15px;
        font-weight: 700;
        color: #666;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table td {
        padding: 18px 15px;
        border-bottom: 1px solid #f0f0f0;
        color: #333;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: #f8f9fa;
    }

    .producto-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .producto-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .producto-details h4 {
        font-size: 1rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 3px;
    }

    .producto-details p {
        font-size: 0.8rem;
        color: #888;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-block;
    }

    .badge-pendiente {
        background: #fff3cd;
        color: #856404;
    }

    .badge-procesando {
        background: #cfe2ff;
        color: #084298;
    }

    .badge-entregado {
        background: #d4edda;
        color: #155724;
    }

    .badge-cancelado {
        background: #f8d7da;
        color: #721c24;
    }

    .precio-badge {
        font-size: 1.1rem;
        font-weight: 800;
        color: #3b82f6;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .cantidad-badge {
        background: #e0e0e0;
        padding: 5px 12px;
        border-radius: 8px;
        font-weight: 700;
        color: #333;
    }

    .empty-state {
        text-align: center;
        padding: 60px 30px;
        color: #999;
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
        font-size: 1rem;
        margin-bottom: 25px;
    }

    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 25px;
    }

    @media (max-width: 968px) {
        .filters-form {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        }

        .table {
            font-size: 0.85rem;
        }

        .producto-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
    }
</style>

<!-- Estadísticas -->
<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-icon">📦</div>
        <div class="stat-label">Total de Canjes</div>
        <div class="stat-value">{{ $totalCanjes }}</div>
    </div>

    <div class="stat-card green">
        <div class="stat-icon">⭐</div>
        <div class="stat-label">Total Gastado</div>
        <div class="stat-value">{{ number_format($totalGastado) }}</div>
    </div>

    <div class="stat-card orange">
        <div class="stat-icon">⏳</div>
        <div class="stat-label">Pendientes</div>
        <div class="stat-value">{{ $canjesPendientes }}</div>
    </div>

    <div class="stat-card purple">
        <div class="stat-icon">✅</div>
        <div class="stat-label">Entregados</div>
        <div class="stat-value">{{ $canjesEntregados }}</div>
    </div>
</div>

<!-- Filtros -->
<div class="filters-card">
    <form action="{{ route('alumno.canjes.index') }}" method="GET" class="filters-form">
        <div class="form-group">
            <label class="form-label">📅 Desde</label>
            <input type="date" name="desde" class="form-input" value="{{ $desde }}">
        </div>
        <div class="form-group">
            <label class="form-label">📅 Hasta</label>
            <input type="date" name="hasta" class="form-input" value="{{ $hasta }}">
        </div>
        <div class="form-group">
            <label class="form-label">📊 Estado</label>
            <select name="estado" class="form-select">
                <option value="todos" {{ $estado == 'todos' ? 'selected' : '' }}>Todos los Estados</option>
                <option value="pendiente" {{ $estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="procesando" {{ $estado == 'procesando' ? 'selected' : '' }}>Procesando</option>
                <option value="entregado" {{ $estado == 'entregado' ? 'selected' : '' }}>Entregado</option>
                <option value="cancelado" {{ $estado == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filtrar</button>
    </form>
</div>

<!-- Tabla de Canjes -->
<div class="canjes-table-card">
    @if($canjes->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Recipuntos</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($canjes as $index => $canje)
                    <tr>
                        <td>{{ $canjes->firstItem() + $index }}</td>
                        <td>
                            <div class="producto-info">
                                <div class="producto-icon">🎒</div>
                                <div class="producto-details">
                                    <h4>{{ $canje->publicacion->nombre ?? 'Producto Eliminado' }}</h4>
                                    <p>Código: #{{ $canje->cod_compra }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="cantidad-badge">{{ $canje->cantidad ?? 1 }}x</span>
                        </td>
                        <td>
                            <div class="precio-badge">
                                <span>⭐</span>
                                <span>{{ number_format($canje->precio_t ?? 0) }}</span>
                            </div>
                        </td>
                        <td>
                            @php
                                $status = $canje->status ?? 'pendiente';
                                $badgeClass = 'badge-' . $status;
                                $statusText = ucfirst($status);
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($canje->created_at)->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($canjes->hasPages())
            <div class="pagination-container">
                {{ $canjes->links() }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <div class="empty-icon">📦</div>
            <h3 class="empty-title">No tienes canjes registrados</h3>
            <p class="empty-text">
                @if($estado && $estado != 'todos')
                    No se encontraron canjes con el filtro "{{ ucfirst($estado) }}"
                @else
                    Aún no has canjeado ningún producto
                @endif
            </p>
            <a href="{{ route('alumno.productos.index') }}" class="btn btn-primary">
                Ver Productos Disponibles
            </a>
        </div>
    @endif
</div>

@endsection
