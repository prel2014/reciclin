@extends('profesor.layout')

@section('title', 'Gestión de Canjes')
@section('page-title', 'Gestión de Canjes - Sistema Recipuntos')

@section('content')

@if(session('success'))
    <div style="background: #d1fae5; border: 2px solid #10b981; color: #065f46; padding: 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
        ✓ {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div style="background: #fee2e2; border: 2px solid #ef4444; color: #991b1b; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
        <strong>⚠ Errores:</strong>
        <ul style="margin: 8px 0 0 20px; padding: 0;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<style>
    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s;
        cursor: pointer;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
    }

    .stat-card.active {
        border: 2px solid #10b981;
        background: #f0fdf4;
    }

    .stat-card.orange { border-left: 4px solid #f59e0b; }
    .stat-card.blue { border-left: 4px solid #3b82f6; }
    .stat-card.green { border-left: 4px solid #10b981; }
    .stat-card.purple { border-left: 4px solid #8b5cf6; }

    .stat-icon {
        font-size: 2rem;
        margin-bottom: 10px;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 5px;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 800;
        color: #111827;
    }

    .alert-pendientes {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .alert-pendientes h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .alert-pendientes p {
        opacity: 0.9;
    }

    .canjes-container {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e5e7eb;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
    }

    .btn-new-canje {
        padding: 12px 24px;
        background: #10b981;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-new-canje:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .canjes-table {
        width: 100%;
        border-collapse: collapse;
    }

    .canjes-table thead th {
        background: #f9fafb;
        padding: 12px 16px;
        text-align: left;
        font-size: 0.85rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e5e7eb;
    }

    .canjes-table tbody td {
        padding: 16px;
        border-bottom: 1px solid #e5e7eb;
        color: #374151;
    }

    .canjes-table tbody tr:hover {
        background: #f9fafb;
    }

    .alumno-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alumno-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1rem;
    }

    .alumno-details h4 {
        font-weight: 600;
        margin: 0;
        color: #111827;
    }

    .alumno-details p {
        margin: 0;
        font-size: 0.8rem;
        color: #6b7280;
    }

    .producto-name {
        font-weight: 600;
        color: #111827;
    }

    .producto-desc {
        font-size: 0.8rem;
        color: #6b7280;
        margin-top: 2px;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
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

    .badge-recipuntos {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-cantidad {
        background: #dbeafe;
        color: #1e40af;
    }

    .acciones-btn {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-estado {
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        color: white;
    }

    .btn-procesando {
        background: #3b82f6;
    }

    .btn-procesando:hover {
        background: #2563eb;
    }

    .btn-entregar {
        background: #10b981;
    }

    .btn-entregar:hover {
        background: #059669;
    }

    .btn-cancel {
        background: #ef4444;
    }

    .btn-cancel:hover {
        background: #dc2626;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
    }

    .pagination a,
    .pagination span {
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 500;
        text-decoration: none;
        color: #6b7280;
        background: #f9fafb;
        transition: all 0.2s;
    }

    .pagination a:hover {
        background: #10b981;
        color: white;
    }

    .pagination .active {
        background: #10b981;
        color: white;
    }

    @media (max-width: 768px) {
        .header-actions {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }

        .stats-cards {
            grid-template-columns: repeat(2, 1fr);
        }

        .canjes-table {
            font-size: 0.85rem;
        }

        .alumno-info {
            flex-direction: column;
            align-items: flex-start;
        }

        .acciones-btn {
            flex-direction: column;
        }
    }
</style>

<!-- Alerta de Canjes Pendientes -->
@if($canjesPendientes > 0)
<div class="alert-pendientes">
    <div>
        <h3>⚠️ Tienes {{ $canjesPendientes }} canje(s) pendiente(s) de atender</h3>
        <p>Revisa y procesa las solicitudes de canjes de tus alumnos</p>
    </div>
    <a href="?estado=pendiente" class="btn-new-canje" style="background: white; color: #f59e0b;">
        Ver Pendientes
    </a>
</div>
@endif

<!-- Estadísticas (Filtros Clickeables) -->
<div class="stats-cards">
    <a href="{{ route('profesor.canjes.index', ['estado' => 'pendiente']) }}" class="stat-card orange {{ $filtroEstado == 'pendiente' ? 'active' : '' }}" style="text-decoration: none;">
        <div class="stat-icon">⏳</div>
        <div class="stat-label">Pendientes</div>
        <div class="stat-value">{{ number_format($canjesPendientes) }}</div>
    </a>

    <a href="{{ route('profesor.canjes.index', ['estado' => 'procesando']) }}" class="stat-card blue {{ $filtroEstado == 'procesando' ? 'active' : '' }}" style="text-decoration: none;">
        <div class="stat-icon">🔄</div>
        <div class="stat-label">Procesando</div>
        <div class="stat-value">{{ number_format($canjesProcesando) }}</div>
    </a>

    <a href="{{ route('profesor.canjes.index', ['estado' => 'entregado']) }}" class="stat-card green {{ $filtroEstado == 'entregado' ? 'active' : '' }}" style="text-decoration: none;">
        <div class="stat-icon">✅</div>
        <div class="stat-label">Entregados</div>
        <div class="stat-value">{{ number_format($canjesEntregados) }}</div>
    </a>

    <a href="{{ route('profesor.canjes.index') }}" class="stat-card purple {{ $filtroEstado == 'todos' ? 'active' : '' }}" style="text-decoration: none;">
        <div class="stat-icon">📊</div>
        <div class="stat-label">Total Canjes</div>
        <div class="stat-value">{{ number_format($totalCanjes) }}</div>
    </a>
</div>

<!-- Tabla de Canjes -->
<div class="canjes-container">
    <div class="header-actions">
        <h2 class="page-title">
            📋
            @if($filtroEstado == 'pendiente')
                Canjes Pendientes
            @elseif($filtroEstado == 'procesando')
                Canjes en Proceso
            @elseif($filtroEstado == 'entregado')
                Canjes Entregados
            @else
                Todos los Canjes
            @endif
        </h2>
        <a href="{{ route('profesor.canjes.create') }}" class="btn-new-canje">
            <span>➕</span>
            <span>Realizar Nuevo Canje</span>
        </a>
    </div>

    @if($canjes->count() > 0)
        <table class="canjes-table">
            <thead>
                <tr>
                    <th>Alumno</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Recipuntos</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($canjes as $canje)
                    <tr>
                        <td>
                            <div class="alumno-info">
                                <div class="alumno-avatar">
                                    {{ strtoupper(substr($canje->usuario->nombre ?? 'A', 0, 1)) }}
                                </div>
                                <div class="alumno-details">
                                    <h4>{{ $canje->usuario->nombre ?? 'N/A' }} {{ $canje->usuario->apellido ?? '' }}</h4>
                                    <p>@{{ $canje->usuario->nick ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="producto-name">{{ $canje->publicacion->nombre ?? 'Producto no disponible' }}</div>
                            <div class="producto-desc">{{ Str::limit($canje->publicacion->descripcion ?? '', 45) }}</div>
                        </td>
                        <td>
                            <span class="badge badge-cantidad">{{ $canje->cantidad }} unid.</span>
                        </td>
                        <td>
                            <span class="badge badge-recipuntos">⭐ {{ number_format($canje->precio_t) }} pts</span>
                        </td>
                        <td>
                            @php
                                $status = $canje->status ?? 'pendiente';
                                $badgeClass = 'badge-' . $status;
                                $statusText = ucfirst($status);
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ \Carbon\Carbon::parse($canje->created_at)->format('d/m/Y') }}</div>
                            <div style="font-size: 0.8rem; color: #6b7280;">{{ \Carbon\Carbon::parse($canje->created_at)->format('H:i') }}</div>
                        </td>
                        <td>
                            <div class="acciones-btn">
                                @if($canje->status == 'pendiente')
                                    <form action="{{ route('profesor.canjes.update-estado', $canje->cod_compra) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="status" value="procesando">
                                        <button type="submit" class="btn-estado btn-procesando" title="Marcar como en proceso">
                                            🔄 Procesar
                                        </button>
                                    </form>
                                @endif

                                @if($canje->status == 'procesando')
                                    <form action="{{ route('profesor.canjes.update-estado', $canje->cod_compra) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="status" value="entregado">
                                        <button type="submit" class="btn-estado btn-entregar" title="Marcar como entregado">
                                            ✅ Entregar
                                        </button>
                                    </form>
                                @endif

                                @if($canje->status != 'cancelado' && $canje->status != 'entregado')
                                    <form action="{{ route('profesor.canjes.destroy', $canje->cod_compra) }}"
                                          method="POST"
                                          style="display: inline;"
                                          onsubmit="return confirm('¿Estás seguro de cancelar este canje? Se devolverán los Recipuntos al alumno.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-estado btn-cancel" title="Cancelar canje">
                                            ✖ Cancelar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="pagination">
            {{ $canjes->appends(['estado' => $filtroEstado])->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">🔄</div>
            <h3>No hay canjes
                @if($filtroEstado != 'todos')
                    con estado "{{ ucfirst($filtroEstado) }}"
                @else
                    registrados
                @endif
            </h3>
            <p>
                @if($filtroEstado != 'todos')
                    <a href="{{ route('profesor.canjes.index') }}" style="color: #10b981; font-weight: 600;">Ver todos los canjes</a>
                @else
                    Los canjes aparecerán aquí cuando los alumnos soliciten productos
                @endif
            </p>
        </div>
    @endif
</div>

@endsection
