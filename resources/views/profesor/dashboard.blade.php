@extends('profesor.layout')

@section('title', 'Dashboard Profesor')
@section('page-title', 'Mi Dashboard')

@section('content')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 15px;
        padding: 30px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(16, 185, 129, 0.3);
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
        background: rgba(16, 185, 129, 0.1);
    }

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

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.2s;
    }

    .btn-primary {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
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
    }

    .table td {
        padding: 15px 12px;
        border-bottom: 1px solid #f0f0f0;
        color: #333;
    }

    .table tr:hover {
        background: #f8f9fa;
    }

    .alumno-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 12px;
        transition: all 0.2s;
    }

    .alumno-card:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .alumno-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .alumno-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
    }

    .alumno-details h3 {
        font-size: 1.1rem;
        margin-bottom: 3px;
    }

    .alumno-details p {
        font-size: 0.85rem;
        color: #888;
    }

    .alumno-puntos {
        text-align: right;
    }

    .puntos-valor {
        font-size: 1.5rem;
        font-weight: 700;
        color: #10b981;
    }

    .puntos-label {
        font-size: 0.75rem;
        color: #888;
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

    .badge-warning {
        background: #fff3cd;
        color: #856404;
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

    .two-columns {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    @media (max-width: 968px) {
        .two-columns {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Welcome Banner -->
<div class="welcome-banner">
    <h1>👋 ¡Hola, Profesor(a) {{ $profesor->nombre }}!</h1>
    <p>Gestiona a tus alumnos y asigna Recipuntos por sus logros</p>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">👨‍🎓</div>
        <div class="stat-label">Mis Alumnos</div>
        <div class="stat-value">{{ $totalAlumnos }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">⭐</div>
        <div class="stat-label">Recipuntos Asignados</div>
        <div class="stat-value">{{ number_format($totalRecipuntos) }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">🔄</div>
        <div class="stat-label">Canjes Realizados</div>
        <div class="stat-value">{{ $canjesRecientes->count() }}</div>
    </div>
</div>

<div class="two-columns">
    <!-- Mis Alumnos -->
    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">👨‍🎓 Mis Alumnos</h2>
            <a href="{{ url('/profesor/alumnos') }}" class="btn btn-primary">+ Registrar Alumno</a>
        </div>

        @if($misAlumnos->count() > 0)
            @foreach($misAlumnos as $alumno)
                <div class="alumno-card">
                    <div class="alumno-info">
                        <div class="alumno-avatar">{{ strtoupper(substr($alumno->nombre, 0, 1)) }}</div>
                        <div class="alumno-details">
                            <h3>{{ $alumno->nombre }} {{ $alumno->apellido }}</h3>
                            <p>{{ $alumno->nick }} • {{ $alumno->correo }}</p>
                        </div>
                    </div>
                    <div class="alumno-puntos">
                        <div class="puntos-valor">⭐ {{ number_format($alumno->recipuntos ?? 0) }}</div>
                        <div class="puntos-label">Recipuntos</div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-state">
                <div class="empty-state-icon">👨‍🎓</div>
                <p><strong>No tienes alumnos registrados</strong></p>
                <p style="font-size: 0.9rem;">Comienza registrando tus primeros alumnos</p>
            </div>
        @endif
    </div>

    <!-- Canjes Recientes -->
    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">🔄 Canjes Recientes</h2>
            <a href="{{ url('/profesor/canjes') }}" class="btn btn-primary">Realizar Canje</a>
        </div>

        @if($canjesRecientes->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Producto</th>
                        <th>Puntos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($canjesRecientes->take(5) as $canje)
                        <tr>
                            <td>
                                <strong>{{ $canje->usuario->nombre ?? 'N/A' }}</strong>
                                <div style="font-size: 0.75rem; color: #888;">{{ $canje->usuario->nick ?? '' }}</div>
                            </td>
                            <td>{{ Str::limit($canje->publicacion->nombre ?? 'N/A', 30) }}</td>
                            <td>
                                <span class="badge badge-warning">
                                    ⭐ {{ number_format($canje->precio_t ?? 0) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">🔄</div>
                <p><strong>No hay canjes registrados</strong></p>
                <p style="font-size: 0.9rem;">Los canjes de tus alumnos aparecerán aquí</p>
            </div>
        @endif
    </div>
</div>

<!-- Productos Disponibles -->
<div class="content-card">
    <div class="card-header">
        <h2 class="card-title">🎒 Productos Disponibles para Canje</h2>
        <a href="{{ url('/profesor/productos') }}" class="btn btn-primary">Ver Todos</a>
    </div>

    @if($productosDisponibles->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Disponibilidad</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productosDisponibles as $producto)
                    <tr>
                        <td><strong>{{ $producto->nombre }}</strong></td>
                        <td>
                            <span style="color: #10b981; font-weight: 700;">
                                ⭐ {{ number_format($producto->precio ?? 0) }} Recipuntos
                            </span>
                        </td>
                        <td>{{ number_format($producto->disponibilidad ?? 0) }} unidades</td>
                        <td>
                            <span class="badge badge-success">Disponible</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">🎒</div>
            <p><strong>No hay productos disponibles</strong></p>
            <p style="font-size: 0.9rem;">Los productos para canje aparecerán aquí</p>
        </div>
    @endif
</div>

@endsection
