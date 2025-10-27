@extends('alumno.layout')

@section('title', 'Dashboard Alumno')
@section('page-title', 'Mi Dashboard')

@section('content')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border-radius: 15px;
        padding: 30px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(59, 130, 246, 0.3);
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
    }

    .stat-card.blue .stat-icon { background: rgba(59, 130, 246, 0.1); }
    .stat-card.green .stat-icon { background: rgba(16, 185, 129, 0.1); }
    .stat-card.orange .stat-icon { background: rgba(251, 146, 60, 0.1); }

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
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
    }

    .producto-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }

    .producto-card {
        background: white;
        border: 2px solid #f0f0f0;
        border-radius: 12px;
        padding: 20px;
        transition: all 0.3s;
        cursor: pointer;
    }

    .producto-card:hover {
        border-color: #3b82f6;
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(59, 130, 246, 0.2);
    }

    .producto-img {
        width: 100%;
        height: 150px;
        background: #f0f0f0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        margin-bottom: 15px;
    }

    .producto-nombre {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: #333;
    }

    .producto-precio {
        font-size: 1.3rem;
        font-weight: 800;
        color: #3b82f6;
        margin-bottom: 8px;
    }

    .producto-disponible {
        font-size: 0.85rem;
        color: #888;
    }

    .ranking-card {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 12px;
    }

    .ranking-number {
        font-size: 1.5rem;
        font-weight: 800;
        color: #3b82f6;
        min-width: 40px;
        text-align: center;
    }

    .ranking-number.gold { color: #FFD700; }
    .ranking-number.silver { color: #C0C0C0; }
    .ranking-number.bronze { color: #CD7F32; }
    .ranking-number.me {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        border-radius: 8px;
        padding: 5px 10px;
    }

    .ranking-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1rem;
    }

    .ranking-info {
        flex: 1;
    }

    .ranking-name {
        font-weight: 600;
        color: #333;
    }

    .ranking-puntos {
        font-size: 1.2rem;
        font-weight: 700;
        color: #10b981;
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
    }

    .table td {
        padding: 15px 12px;
        border-bottom: 1px solid #f0f0f0;
        color: #333;
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
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }

    @media (max-width: 968px) {
        .two-columns {
            grid-template-columns: 1fr;
        }

        .producto-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        }
    }
</style>

<!-- Welcome Banner -->
<div class="welcome-banner">
    <h1>🎓 ¡Hola, {{ $alumno->nombre }}!</h1>
    <p>Tienes <strong>{{ number_format($misRecipuntos) }} Recipuntos</strong> disponibles para canjear</p>
    @if($miProfesor)
        <p style="font-size: 0.9rem; margin-top: 10px;">
            👨‍🏫 Tu profesor: {{ $miProfesor->nombre }} {{ $miProfesor->apellido }}
        </p>
    @endif
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-icon">⭐</div>
        <div class="stat-label">Mis Recipuntos</div>
        <div class="stat-value">{{ number_format($misRecipuntos) }}</div>
        <div class="stat-subtitle">Disponibles para canjear</div>
    </div>

    <div class="stat-card green">
        <div class="stat-icon">🔄</div>
        <div class="stat-label">Canjes Realizados</div>
        <div class="stat-value">{{ $misCanjes->count() }}</div>
        <div class="stat-subtitle">{{ number_format($recipuntosCanjeados) }} pts gastados</div>
    </div>

    <div class="stat-card orange">
        <div class="stat-icon">🏆</div>
        <div class="stat-label">Mi Posición</div>
        <div class="stat-value">#{{ $miPosicion }}</div>
        <div class="stat-subtitle">En el ranking general</div>
    </div>
</div>

<div class="two-columns">
    <!-- Productos que puedo canjear -->
    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">🎒 Puedes Canjear</h2>
            <a href="{{ url('/alumno/productos') }}" class="btn btn-primary">Ver Todos</a>
        </div>

        @if($productosDisponibles->count() > 0)
            <div class="producto-grid">
                @foreach($productosDisponibles->take(4) as $producto)
                    <div class="producto-card">
                        <div class="producto-img">🎒</div>
                        <div class="producto-nombre">{{ Str::limit($producto->nombre, 30) }}</div>
                        <div class="producto-precio">⭐ {{ number_format($producto->precio ?? 0) }}</div>
                        <div class="producto-disponible">
                            <span class="badge badge-success">
                                {{ number_format($producto->disponibilidad ?? 0) }} disponibles
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">🎒</div>
                <p><strong>No tienes suficientes Recipuntos</strong></p>
                <p style="font-size: 0.9rem;">¡Sigue reciclando y mejorando tus notas para ganar más puntos!</p>
            </div>
        @endif
    </div>

    <!-- Top 5 del Ranking -->
    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">🏆 Top Ranking</h2>
            <a href="{{ url('/alumno/ranking') }}" class="btn btn-primary">Ver Todo</a>
        </div>

        @foreach($todosAlumnos->take(5) as $index => $estudiante)
            <div class="ranking-card {{ $estudiante->cod_usuario === $alumno->cod_usuario ? 'border-primary' : '' }}"
                 style="{{ $estudiante->cod_usuario === $alumno->cod_usuario ? 'border: 2px solid #3b82f6;' : '' }}">
                <div class="ranking-number {{ $estudiante->cod_usuario === $alumno->cod_usuario ? 'me' : ($index === 0 ? 'gold' : ($index === 1 ? 'silver' : ($index === 2 ? 'bronze' : ''))) }}">
                    #{{ $index + 1 }}
                </div>
                <div class="ranking-avatar">{{ strtoupper(substr($estudiante->nombre, 0, 1)) }}</div>
                <div class="ranking-info">
                    <div class="ranking-name">
                        {{ $estudiante->nombre }}
                        @if($estudiante->cod_usuario === $alumno->cod_usuario)
                            <span style="color: #3b82f6; font-weight: 700;">(Tú)</span>
                        @endif
                    </div>
                </div>
                <div class="ranking-puntos">⭐ {{ number_format($estudiante->recipuntos ?? 0) }}</div>
            </div>
        @endforeach
    </div>
</div>

<!-- Mis Canjes Recientes -->
@if($misCanjes->count() > 0)
<div class="content-card">
    <div class="card-header">
        <h2 class="card-title">🔄 Mis Canjes Recientes</h2>
        <a href="{{ url('/alumno/canjes') }}" class="btn btn-primary">Ver Historial</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Recipuntos Gastados</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($misCanjes->take(5) as $canje)
                <tr>
                    <td><strong>{{ $canje->publicacion->nombre ?? 'N/A' }}</strong></td>
                    <td>{{ $canje->cantidad ?? 1 }}</td>
                    <td>
                        <span style="color: #3b82f6; font-weight: 700;">
                            ⭐ {{ number_format($canje->precio_t ?? 0) }}
                        </span>
                    </td>
                    <td style="font-size: 0.85rem;">
                        {{ \Carbon\Carbon::parse($canje->created_at)->format('d/m/Y') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<!-- Todos los Productos -->
<div class="content-card">
    <div class="card-header">
        <h2 class="card-title">📦 Todos los Productos Disponibles</h2>
    </div>

    @if($todosProductos->count() > 0)
        <div class="producto-grid">
            @foreach($todosProductos as $producto)
                <div class="producto-card">
                    <div class="producto-img">🎒</div>
                    <div class="producto-nombre">{{ Str::limit($producto->nombre, 30) }}</div>
                    <div class="producto-precio">⭐ {{ number_format($producto->precio ?? 0) }}</div>
                    <div class="producto-disponible">
                        @if(($producto->precio ?? 0) <= $misRecipuntos)
                            <span class="badge badge-success">¡Puedes canjearlo!</span>
                        @else
                            <span class="badge" style="background: #f0f0f0; color: #666;">
                                Te faltan {{ number_format(($producto->precio ?? 0) - $misRecipuntos) }} pts
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">📦</div>
            <p><strong>No hay productos disponibles</strong></p>
            <p style="font-size: 0.9rem;">Pronto habrá más productos para canjear</p>
        </div>
    @endif
</div>

@endsection
