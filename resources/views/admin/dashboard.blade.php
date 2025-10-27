@extends('admin.layout')

@section('title', 'Dashboard Recipuntos')
@section('page-title', 'Dashboard General - Sistema Recipuntos')

@section('content')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 30px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
    }

    .welcome-banner h1 {
        font-size: 2rem;
        margin-bottom: 10px;
    }

    .welcome-banner p {
        opacity: 0.9;
        font-size: 1.1rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        opacity: 0.1;
        transform: translate(30%, -30%);
    }

    .stat-card.blue::before { background: #667eea; }
    .stat-card.green::before { background: #28a745; }
    .stat-card.orange::before { background: #fd7e14; }
    .stat-card.purple::before { background: #764ba2; }
    .stat-card.red::before { background: #e74c3c; }
    .stat-card.teal::before { background: #20c997; }

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

    .stat-card.blue .stat-icon { background: rgba(102, 126, 234, 0.1); }
    .stat-card.green .stat-icon { background: rgba(40, 167, 69, 0.1); }
    .stat-card.orange .stat-icon { background: rgba(253, 126, 20, 0.1); }
    .stat-card.purple .stat-icon { background: rgba(118, 75, 162, 0.1); }
    .stat-card.red .stat-icon { background: rgba(231, 76, 60, 0.1); }
    .stat-card.teal .stat-icon { background: rgba(32, 201, 151, 0.1); }

    .stat-label {
        font-size: 0.9rem;
        color: #888;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .stat-value {
        font-size: 2.2rem;
        font-weight: 800;
        color: #333;
    }

    .stat-subtitle {
        font-size: 0.8rem;
        color: #999;
        margin-top: 8px;
    }

    .content-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .card-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #333;
    }

    .btn-view-all {
        padding: 8px 16px;
        background: #667eea;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-view-all:hover {
        background: #5568d3;
        transform: translateY(-2px);
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th {
        text-align: left;
        padding: 12px;
        background: #f8f9fa;
        color: #666;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table td {
        padding: 15px 12px;
        border-bottom: 1px solid #f0f0f0;
        color: #333;
    }

    .table tr:last-child td {
        border-bottom: none;
    }

    .table tr:hover {
        background: #f8f9fa;
    }

    .badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }

    .badge-success {
        background: #d4edda;
        color: #155724;
    }

    .badge-primary {
        background: #cfe2ff;
        color: #084298;
    }

    .badge-warning {
        background: #fff3cd;
        color: #856404;
    }

    .user-mini {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-mini-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .ranking-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 12px;
        transition: all 0.2s;
    }

    .ranking-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .ranking-number {
        font-size: 1.5rem;
        font-weight: 800;
        color: #667eea;
        min-width: 35px;
        text-align: center;
    }

    .ranking-number.gold { color: #FFD700; }
    .ranking-number.silver { color: #C0C0C0; }
    .ranking-number.bronze { color: #CD7F32; }

    .two-columns {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #999;
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    @media (max-width: 968px) {
        .two-columns {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
    }
</style>

<!-- Welcome Banner -->
<div class="welcome-banner">
    <h1>🌟 Bienvenido al Sistema Recipuntos</h1>
    <p>Promoviendo el reciclaje y la educación ambiental</p>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-icon">👥</div>
        <div class="stat-label">Total Usuarios</div>
        <div class="stat-value">{{ number_format($totalUsuarios) }}</div>
        <div class="stat-subtitle">
            {{ $totalProfesores }} profesores • {{ $totalAlumnos }} alumnos
        </div>
    </div>

    <div class="stat-card green">
        <div class="stat-icon">⭐</div>
        <div class="stat-label">Recipuntos en Circulación</div>
        <div class="stat-value">{{ number_format($totalRecipuntosCirculacion) }}</div>
        <div class="stat-subtitle">Puntos acumulados por alumnos</div>
    </div>

    <div class="stat-card orange">
        <div class="stat-icon">🔄</div>
        <div class="stat-label">Total de Canjes</div>
        <div class="stat-value">{{ number_format($totalCanjes) }}</div>
        <div class="stat-subtitle">{{ $canjesMes }} este mes</div>
    </div>

    <div class="stat-card purple">
        <div class="stat-icon">💎</div>
        <div class="stat-label">Recipuntos Canjeados</div>
        <div class="stat-value">{{ number_format($totalRecipuntosCanjeados ?? 0) }}</div>
        <div class="stat-subtitle">Puntos gastados en productos</div>
    </div>

    <div class="stat-card teal">
        <div class="stat-icon">🎒</div>
        <div class="stat-label">Productos Disponibles</div>
        <div class="stat-value">{{ number_format($totalProductos) }}</div>
        <div class="stat-subtitle">Útiles escolares activos</div>
    </div>

    <div class="stat-card red">
        <div class="stat-icon">👨‍🏫</div>
        <div class="stat-label">Profesores Activos</div>
        <div class="stat-value">{{ number_format($totalProfesores) }}</div>
        <div class="stat-subtitle">Gestionando alumnos</div>
    </div>
</div>

<div class="two-columns">
    <!-- Canjes Recientes -->
    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">🔄 Canjes Recientes</h2>
        </div>

        @if($canjesRecientes->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Producto</th>
                        <th>Recipuntos</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($canjesRecientes as $canje)
                        <tr>
                            <td>
                                <div class="user-mini">
                                    <div class="user-mini-avatar">{{ strtoupper(substr($canje->usuario->nick ?? 'A', 0, 1)) }}</div>
                                    <div>
                                        <div style="font-weight: 600;">{{ $canje->usuario->nick ?? 'N/A' }}</div>
                                        <div style="font-size: 0.75rem; color: #888;">{{ $canje->usuario->nombre ?? '' }} {{ $canje->usuario->apellido ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong>{{ Str::limit($canje->publicacion->nombre ?? 'N/A', 35) }}</strong>
                                <div style="font-size: 0.75rem; color: #888;">Cantidad: {{ $canje->cantidad }}</div>
                            </td>
                            <td>
                                <span class="badge badge-warning">
                                    ⭐ {{ number_format($canje->precio_t ?? 0) }} pts
                                </span>
                            </td>
                            <td style="font-size: 0.85rem;">
                                {{ \Carbon\Carbon::parse($canje->created_at)->diffForHumans() }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">🔄</div>
                <p><strong>No hay canjes registrados</strong></p>
                <p style="font-size: 0.9rem;">Los canjes aparecerán aquí cuando los alumnos canjeen Recipuntos</p>
            </div>
        @endif
    </div>

    <!-- Top Alumnos con más Recipuntos -->
    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">🏆 Top Alumnos</h2>
            <a href="{{ url('/admin/usuarios?tipo=alumno') }}" class="btn-view-all">Ver Todos</a>
        </div>

        @if($topAlumnos->count() > 0)
            <div>
                @foreach($topAlumnos as $index => $alumno)
                    <div class="ranking-item">
                        <div class="ranking-number {{ $index === 0 ? 'gold' : ($index === 1 ? 'silver' : ($index === 2 ? 'bronze' : '')) }}">
                            #{{ $index + 1 }}
                        </div>
                        <div class="user-mini-avatar">{{ strtoupper(substr($alumno->nick, 0, 1)) }}</div>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: #333;">{{ $alumno->nick }}</div>
                            <div style="font-size: 0.85rem; color: #888;">{{ $alumno->nombre }} {{ $alumno->apellido }}</div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-weight: 700; color: #28a745; font-size: 1.1rem;">
                                ⭐ {{ number_format($alumno->recipuntos ?? 0) }}
                            </div>
                            <div style="font-size: 0.75rem; color: #888;">Recipuntos</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">👨‍🎓</div>
                <p><strong>No hay alumnos registrados</strong></p>
                <p style="font-size: 0.9rem;">Los profesores pueden registrar alumnos</p>
            </div>
        @endif
    </div>
</div>

<!-- Productos Más Canjeados -->
@if($productosMasCanjeados->count() > 0)
<div class="content-card">
    <div class="card-header">
        <h2 class="card-title">📊 Productos Más Canjeados</h2>
        <a href="{{ url('/admin/productos') }}" class="btn-view-all">Ver Productos</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio (Recipuntos)</th>
                <th>Canjes Totales</th>
                <th>Disponibilidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productosMasCanjeados as $item)
                @if($item->publicacion)
                <tr>
                    <td>
                        <strong>{{ $item->publicacion->nombre ?? 'N/A' }}</strong>
                        <div style="font-size: 0.8rem; color: #888;">
                            {{ Str::limit($item->publicacion->descripcion ?? '', 50) }}
                        </div>
                    </td>
                    <td>
                        <strong style="color: #28a745;">⭐ {{ number_format($item->publicacion->precio ?? 0) }}</strong>
                    </td>
                    <td>
                        <strong>{{ number_format($item->total_canjes) }}</strong> canjes
                    </td>
                    <td>
                        @if(($item->publicacion->disponibilidad ?? 0) > 0)
                            <span class="badge badge-success">{{ number_format($item->publicacion->disponibilidad) }} disponibles</span>
                        @else
                            <span class="badge badge-warning">Agotado</span>
                        @endif
                    </td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
@endif

@endsection
