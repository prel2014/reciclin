@extends('alumno.layout')

@section('title', 'Ranking de Alumnos')
@section('page-title', 'Ranking de Alumnos')

@section('content')
<style>
    .mi-posicion-banner {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border-radius: 15px;
        padding: 30px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(59, 130, 246, 0.3);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .mi-posicion-info h2 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .mi-posicion-info p {
        opacity: 0.9;
        font-size: 1.1rem;
    }

    .mi-posicion-stats {
        text-align: right;
    }

    .posicion-numero {
        font-size: 4rem;
        font-weight: 900;
        line-height: 1;
        margin-bottom: 5px;
    }

    .posicion-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        text-align: center;
    }

    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 10px;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: #333;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #888;
        font-weight: 600;
    }

    .podium-section {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #333;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .podium-container {
        display: flex;
        justify-content: center;
        align-items: flex-end;
        gap: 20px;
        margin-bottom: 30px;
    }

    .podium-place {
        text-align: center;
        transition: all 0.3s;
    }

    .podium-place:hover {
        transform: translateY(-10px);
    }

    .podium-place.first {
        order: 2;
    }

    .podium-place.second {
        order: 1;
    }

    .podium-place.third {
        order: 3;
    }

    .podium-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin: 0 auto 15px;
        position: relative;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
    }

    .podium-place.first .podium-avatar {
        background: linear-gradient(135deg, #FFD700, #FFA500);
        width: 120px;
        height: 120px;
        font-size: 3rem;
    }

    .podium-place.second .podium-avatar {
        background: linear-gradient(135deg, #C0C0C0, #A8A8A8);
    }

    .podium-place.third .podium-avatar {
        background: linear-gradient(135deg, #CD7F32, #8B4513);
    }

    .podium-medal {
        position: absolute;
        bottom: -10px;
        right: -10px;
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .podium-place.first .podium-medal {
        font-size: 2rem;
        width: 50px;
        height: 50px;
    }

    .podium-nombre {
        font-size: 1.1rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 8px;
    }

    .podium-place.first .podium-nombre {
        font-size: 1.3rem;
    }

    .podium-puntos {
        font-size: 1.5rem;
        font-weight: 800;
        color: #10b981;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    .podium-place.first .podium-puntos {
        font-size: 1.8rem;
    }

    .ranking-list {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .ranking-item {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 15px;
        background: #f8f9fa;
        transition: all 0.3s;
    }

    .ranking-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .ranking-item.me {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(37, 99, 235, 0.1));
        border: 2px solid #3b82f6;
    }

    .ranking-numero {
        font-size: 1.8rem;
        font-weight: 900;
        color: #666;
        min-width: 50px;
        text-align: center;
    }

    .ranking-item.me .ranking-numero {
        color: #3b82f6;
        background: white;
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ranking-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 800;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .ranking-info {
        flex: 1;
    }

    .ranking-nombre {
        font-size: 1.1rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 5px;
    }

    .ranking-item.me .ranking-nombre {
        color: #3b82f6;
    }

    .ranking-profesor {
        font-size: 0.85rem;
        color: #888;
    }

    .ranking-puntos {
        font-size: 1.8rem;
        font-weight: 800;
        color: #10b981;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .medalla-emoji {
        font-size: 2rem;
    }

    @media (max-width: 768px) {
        .mi-posicion-banner {
            flex-direction: column;
            text-align: center;
            gap: 20px;
        }

        .mi-posicion-stats {
            text-align: center;
        }

        .podium-container {
            flex-direction: column;
            align-items: center;
        }

        .podium-place {
            order: initial !important;
        }

        .ranking-item {
            flex-wrap: wrap;
            gap: 15px;
        }

        .ranking-numero {
            min-width: 40px;
            font-size: 1.5rem;
        }
    }
</style>

<!-- Mi Posición Banner -->
<div class="mi-posicion-banner">
    <div class="mi-posicion-info">
        <h2>🎓 Tu Posición en el Ranking</h2>
        <p>¡Sigue ganando Recipuntos para subir de posición!</p>
    </div>
    <div class="mi-posicion-stats">
        <div class="posicion-numero">#{{ $miPosicion }}</div>
        <div class="posicion-label">de {{ $totalAlumnos }} alumnos</div>
    </div>
</div>

<!-- Estadísticas Generales -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-value">{{ $totalAlumnos }}</div>
        <div class="stat-label">Total Alumnos</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">⭐</div>
        <div class="stat-value">{{ number_format(Auth::user()->recipuntos ?? 0) }}</div>
        <div class="stat-label">Mis Recipuntos</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📊</div>
        <div class="stat-value">{{ number_format($promedioRecipuntos) }}</div>
        <div class="stat-label">Promedio General</div>
    </div>
</div>

<!-- Podium Top 3 -->
@if($top3->count() >= 3)
<div class="podium-section">
    <h2 class="section-title">🏆 Top 3 del Ranking</h2>

    <div class="podium-container">
        @foreach($top3 as $index => $estudiante)
            @php
                $posicionClass = ['first', 'second', 'third'][$index];
                $medallas = ['🥇', '🥈', '🥉'];
            @endphp
            <div class="podium-place {{ $posicionClass }}">
                <div class="podium-avatar">
                    {{ strtoupper(substr($estudiante->nombre, 0, 1)) }}
                    <div class="podium-medal">{{ $medallas[$index] }}</div>
                </div>
                <div class="podium-nombre">
                    {{ $estudiante->nombre }} {{ Str::limit($estudiante->apellido, 10) }}
                    @if($estudiante->cod_usuario === Auth::user()->cod_usuario)
                        <br><span style="color: #3b82f6; font-size: 0.9rem;">(Tú)</span>
                    @endif
                </div>
                <div class="podium-puntos">
                    <span>⭐</span>
                    <span>{{ number_format($estudiante->recipuntos ?? 0) }}</span>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

<!-- Ranking Completo -->
<div class="ranking-list">
    <h2 class="section-title">📋 Ranking Completo</h2>

    @foreach($alumnos as $index => $estudiante)
        @php
            $posicion = $index + 1;
            $esYo = $estudiante->cod_usuario === Auth::user()->cod_usuario;
            $medallas = [1 => '🥇', 2 => '🥈', 3 => '🥉'];
        @endphp
        <div class="ranking-item {{ $esYo ? 'me' : '' }}">
            <div class="ranking-numero">
                @if(isset($medallas[$posicion]))
                    <span class="medalla-emoji">{{ $medallas[$posicion] }}</span>
                @else
                    #{{ $posicion }}
                @endif
            </div>
            <div class="ranking-avatar">
                {{ strtoupper(substr($estudiante->nombre, 0, 1)) }}
            </div>
            <div class="ranking-info">
                <div class="ranking-nombre">
                    {{ $estudiante->nombre }} {{ $estudiante->apellido }}
                    @if($esYo)
                        <span style="background: #3b82f6; color: white; padding: 3px 10px; border-radius: 12px; font-size: 0.75rem; margin-left: 8px;">TÚ</span>
                    @endif
                </div>
                @if($estudiante->profesor)
                    <div class="ranking-profesor">
                        👨‍🏫 Profesor: {{ $estudiante->profesor->nombre }} {{ $estudiante->profesor->apellido }}
                    </div>
                @endif
            </div>
            <div class="ranking-puntos">
                <span>⭐</span>
                <span>{{ number_format($estudiante->recipuntos ?? 0) }}</span>
            </div>
        </div>
    @endforeach
</div>

@endsection
