<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reciclin.com - Un pequeño héroe</title>

    <!-- Fuentes de Google -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;900&display=swap" rel="stylesheet">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="{{ asset('css/reciclin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reciclin-sections.css') }}">
</head>
<body>

<!-- ============================================================ -->
<!-- BARRA DE NAVEGACIÓN -->
<!-- ============================================================ -->
<div id="barra-reciclin">
    <header class="header">
        <nav class="nav-container">
            <div class="left-group">
                <button class="inicio-btn" onclick="window.location.href='{{ url('/') }}'">INICIO</button>

                <div class="main-nav">
                    <a href="#juegos" class="nav-item juegos">JUEGOS</a>
                    <a href="#videos" class="nav-item videos">VIDEOS</a>
                    <a href="#blog" class="nav-item blog">BLOG</a>

                    <a href="{{ url('/api/usuarios') }}" class="logo-central">RANKING</a>
                </div>
            </div>

            <div class="centered-logo">
                <img src="https://reciclin.com/imagenes/logo.png" alt="Logo Central" class="custom-logo">
            </div>

            <div class="right-section">
                <div class="brand-logo">
                    <span>Grandes Ideas</span>
                </div>

                <button class="action-btn btn-historietas" onclick="window.location.href='#historietas'">HISTORIETAS</button>
                <button class="action-btn btn-evidencias" onclick="window.location.href='{{ url('/publicaciones') }}'">EVIDENCIAS</button>

                @auth
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="color: white; font-weight: 500;">{{ Auth::user()->nick }}</span>
                        <form action="{{ url('/logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="action-btn btn-sesion" style="background: #e74c3c;">CERRAR SESIÓN</button>
                        </form>
                    </div>
                @else
                    <button class="action-btn btn-sesion" onclick="window.location.href='{{ url('/login') }}'">INICIAR SESIÓN</button>
                @endauth

                <button class="search-btn">🔍</button>
            </div>

            <button class="mobile-menu" onclick="toggleMenuReciclin()">☰</button>
        </nav>

        <div class="mobile-nav" id="mobileNavReciclin">
            <button class="inicio-btn" onclick="window.location.href='{{ url('/') }}'">INICIO</button>

            <a href="#juegos" class="nav-item juegos">JUEGOS</a>
            <a href="#videos" class="nav-item videos">VIDEOS</a>
            <a href="#blog" class="nav-item blog">BLOG</a>

            <a href="{{ url('/api/usuarios') }}" class="logo-central">RANKING</a>

            <img src="https://reciclin.com/imagenes/logo.png" alt="Logo Central Móvil" class="custom-logo">

            <div class="brand-logo"><span>Grandes Ideas</span></div>

            <button class="action-btn btn-historietas" onclick="window.location.href='#historietas'">HISTORIETAS</button>
            <button class="action-btn btn-evidencias" onclick="window.location.href='{{ url('/publicaciones') }}'">EVIDENCIAS</button>

            @auth
                <div style="padding: 10px; color: white; font-weight: 500;">{{ Auth::user()->nick }}</div>
                <form action="{{ url('/logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="action-btn btn-sesion" style="background: #e74c3c;">CERRAR SESIÓN</button>
                </form>
            @else
                <button class="action-btn btn-sesion" onclick="window.location.href='{{ url('/login') }}'">INICIAR SESIÓN</button>
            @endauth
        </div>
    </header>
</div>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 15px; text-align: center; border-bottom: 2px solid #c3e6cb;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background: #f8d7da; color: #721c24; padding: 15px; text-align: center; border-bottom: 2px solid #f5c6cb;">
        {{ session('error') }}
    </div>
@endif

<!-- ============================================================ -->
<!-- SLIDER DE VIDEOS / BANNERS -->
<!-- ============================================================ -->
<section id="seccion-videos-slide-final">
    <div class="swiper-container mySwiperFinal">
        <div class="swiper-wrapper">

            @if(isset($banners) && $banners->count() > 0)
                @foreach($banners as $index => $banner)
                <div class="swiper-slide">
                    <div class="video-card">
                        @if($banner->es_imagen)
                            <img src="{{ $banner->archivo_url }}"
                                 alt="{{ $banner->titulo }}"
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        @elseif($banner->tiene_video_local)
                            <video autoplay muted loop playsinline preload="none"
                                   src="{{ $banner->archivo_video_url }}"
                                   style="width: 100%; height: 100%; object-fit: cover;">
                                Tu navegador no soporta el tag de video.
                            </video>
                        @else
                            <iframe src="{{ $banner->youtube_embed_url }}"
                                    style="width: 100%; height: 100%; border: none;"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                        @endif
                        <div class="video-overlay">
                            <span class="tag">{{ sprintf('%02d', $index + 1) }} / DESTACADO</span>
                            <h3>{{ strtoupper($banner->titulo) }}</h3>
                            @if($banner->descripcion)
                                <p class="subtitulo">{{ $banner->descripcion }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <!-- Fallback: contenido por defecto si no hay banners -->
                <div class="swiper-slide">
                    <div class="video-card">
                        <video autoplay muted loop playsinline preload="none"
                               poster="URL_IMAGEN_DE_PREVIEW.jpeg"
                               src="https://reciclin.com/imagenes/video1.mp4">
                            Tu navegador no soporta el tag de video.
                        </video>
                        <div class="video-overlay">
                            <span class="tag">01 / ESTRENO SEMANAL</span>
                            <h3>LA AVENTURA DE RECICLIN: MUNDO SOSTENIBLE</h3>
                            <p class="subtitulo">Acompaña a nuestros héroes en su misión por salvar los océanos y la tierra.</p>
                        </div>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="video-card">
                        <video autoplay muted loop playsinline preload="none"
                               poster="URL_IMAGEN_DE_PREVIEW_2.jpeg"
                               src="https://www.pexels.com/download/video/856863">
                            Tu navegador no soporta el tag de video.
                        </video>
                        <div class="video-overlay">
                            <span class="tag">02 / ECO-JUEGOS</span>
                            <h3>DESCUBRE CÓMO CADA GOTA CUENTA EN LA TIERRA</h3>
                            <p class="subtitulo">Aprende sobre la importancia del agua y cómo podemos conservarla con juegos sencillos.</p>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>

        <div class="swiper-pagination"></div>
    </div>
</section>

<!-- ============================================================ -->
<!-- DESCRIPCIÓN DE LA PLATAFORMA -->
<!-- ============================================================ -->
<div class="text-block-container">
    <p class="plataform-text">
       Reciclin.com es una plataforma de reciclaje gamificada creada en la I.E.P. Enrique Pelach y Feliu de Abancay, con un<span class="highlight">robot tacho inteligente</span>
        que motiva a los estudiantes y colegios a cuidar el planeta jugando y aprendiendo.
    </p>
</div>

<!-- ============================================================ -->
<!-- DASHBOARD Y RANKING -->
<!-- ============================================================ -->
<div class="dashboard-wrapper">
    <div class="dashboard-grid">

        <div>
            <div class="seccion-titulo">👥 Nuestros Usuarios</div>
            <div class="tarjeta">

                <div class="metricas-grid">

                    <div class="metrica-card">
                        <div class="metrica-header">
                            <span class="metrica-icon">📅</span>
                            <span class="metrica-label">HOY</span>
                        </div>
                        <div class="metrica-valor" id="botellas-hoy-valor">{{ $metricas['hoy'] ?? '0 BTL' }}</div>
                    </div>

                    <div class="metrica-card">
                        <div class="metrica-header">
                            <span class="metrica-icon">🗓️</span>
                            <span class="metrica-label">ESTA SEMANA</span>
                        </div>
                        <div class="metrica-valor" id="botellas-semana-valor">{{ $metricas['semana'] ?? '0 BTL' }}</div>
                    </div>

                    <div class="metrica-card">
                        <div class="metrica-header">
                            <span class="metrica-icon">🌎</span>
                            <span class="metrica-label">TOTAL HISTÓRICO</span>
                        </div>
                        <div class="metrica-valor" id="total-historico-valor">{{ $metricas['total'] ?? '0 KGS' }}</div>
                    </div>

                    <div class="metrica-card">
                        <div class="metrica-header">
                            <span class="metrica-icon">🧑‍🤝‍🧑</span>
                            <span class="metrica-label">USUARIOS ACTIVOS</span>
                        </div>
                        <div class="metrica-valor" id="usuarios-activos-valor">{{ $metricas['usuarios'] ?? '0' }}</div>
                    </div>
                </div>

                <!-- Botón Actualizar -->
                <button class="btn-actualizar" onclick="actualizarRanking()">
                    <span>🔄</span> Actualizar Ranking
                </button>

                <div class="seccion-titulo" style="margin-top: 20px; border-bottom: none; font-size: 1.4em;">🥇 Ranking TOP 10</div>
                <ul class="ranking-lista" id="rankingLista">

                    <li style="display: flex; justify-content: space-between; font-size: 0.85em; color: #555; padding: 0 15px 8px 60px; font-weight: 700; text-transform: uppercase;">
                        <span style="flex-grow: 1;">Usuario</span>
                        <span style="width: 100px; text-align: right; color: #38c172; font-size: 1.1em; display: flex; align-items: center; justify-content: flex-end; gap: 5px;">
                            <span style="font-size: 1.3em;">⭐</span> ReciPuntos
                        </span>
                        <span style="width: 80px; text-align: right; margin-left: 15px;">Total</span>
                    </li>

                    @if(isset($ranking) && count($ranking) > 0)
                        @foreach($ranking as $index => $usuario)
                        <li class="ranking-item {{ $index == 3 ? 'destacado' : '' }}">
                            @if($index == 0)
                                <span class="posicion-icon-wrapper gold">{{ $index + 1 }}</span>
                            @elseif($index == 1)
                                <span class="posicion-icon-wrapper silver">{{ $index + 1 }}</span>
                            @elseif($index == 2)
                                <span class="posicion-icon-wrapper bronze">{{ $index + 1 }}</span>
                            @else
                                <span class="posicion-icon-wrapper rank">{{ $index + 1 }}</span>
                            @endif
                            <span class="rank-nick">{{ $usuario->nick }}</span>
                            <span class="rank-data">{{ number_format($usuario->publicaciones ?? 0) }}</span>
                            <span class="rank-data total">{{ $usuario->total ?? 0 }}</span>
                        </li>
                        @endforeach
                    @else
                        <li class="ranking-item">
                            <span class="posicion-icon-wrapper rank">1</span>
                            <span class="rank-nick">Sin datos</span>
                            <span class="rank-data">0</span>
                            <span class="rank-data total">0</span>
                        </li>
                    @endif

                </ul>
            </div>
        </div>

        <div>
            <div class="seccion-titulo">⏳ Actividad Reciente</div>
            <div class="tarjeta actividad-reciente">

                @if(isset($actividades) && count($actividades) > 0)
                    @foreach($actividades as $actividad)
                    <div class="actividad-item">
                        <span class="actividad-descripcion">
                            <span class="btn-tipo {{ $actividad->tipo == 'reciclaje' ? 'reciclaje' : 'canje' }}">
                                {{ $actividad->tipo_nombre ?? 'Acción' }}
                            </span>
                            <span class="user-nick">{{ $actividad->usuario->nick ?? 'Usuario' }}</span>
                            <span>{{ $actividad->descripcion ?? 'realizó una acción' }}</span>
                        </span>
                        <span class="actividad-fecha">{{ $actividad->fecha ?? 'Hoy' }}</span>
                    </div>
                    @endforeach
                @else
                    <div class="actividad-item">
                        <span class="actividad-descripcion">
                            <span class="btn-tipo reciclaje">Botellas</span>
                            <span class="user-nick">MiNick</span>
                            <span>registró botellas de plástico</span>
                        </span>
                        <span class="actividad-fecha">Hace 2h</span>
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>

<!-- ============================================================ -->
<!-- GALERÍA MULTIMEDIA -->
<!-- ============================================================ -->
@if(isset($galeria) && $galeria->count() > 0)
<section class="galeria-multimedia-section" style="padding: 80px 20px; background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);">
    <style>
        .galeria-multimedia-section h2 {
            text-align: center;
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 15px;
            color: #111827;
            font-family: 'Montserrat', sans-serif;
        }

        .galeria-subtitle {
            text-align: center;
            font-size: 1.1rem;
            color: #6b7280;
            margin-bottom: 50px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .galeria-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .galeria-item {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background: white;
            position: relative;
        }

        .galeria-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }

        .galeria-media {
            position: relative;
            width: 100%;
            height: 250px;
            overflow: hidden;
            background: #e5e7eb;
        }

        .galeria-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .galeria-item:hover .galeria-media img {
            transform: scale(1.08);
        }

        .galeria-video-wrapper {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
        }

        .galeria-video-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .galeria-content {
            padding: 25px;
            background: white;
        }

        .galeria-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #111827;
            line-height: 1.4;
        }

        .galeria-description {
            color: #6b7280;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .media-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(16, 185, 129, 0.95);
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            backdrop-filter: blur(10px);
            z-index: 10;
        }

        .media-badge.video {
            background: rgba(239, 68, 68, 0.95);
        }

        @media (max-width: 768px) {
            .galeria-multimedia-section h2 {
                font-size: 2rem;
            }

            .galeria-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>

    <div class="container">
        <h2>📸 Galería de <span style="color: #10b981;">Reciclin</span></h2>
        <p class="galeria-subtitle">Descubre nuestros momentos más especiales y aprende más sobre el reciclaje</p>

        <div class="galeria-grid">
            @foreach($galeria as $item)
            <div class="galeria-item">
                @if($item->es_imagen)
                    <div class="galeria-media">
                        <span class="media-badge">Imagen</span>
                        <img src="{{ $item->archivo_url }}" alt="{{ $item->titulo }}">
                    </div>
                @else
                    <div class="galeria-video-wrapper">
                        <span class="media-badge video">Video</span>
                        <iframe src="{{ $item->youtube_embed_url }}"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                    </div>
                @endif

                <div class="galeria-content">
                    <h3 class="galeria-title">{{ $item->titulo }}</h3>
                    @if($item->descripcion)
                        <p class="galeria-description">{{ $item->descripcion }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ============================================================ -->
<!-- SECCIÓN DE VENTAJAS -->
<!-- ============================================================ -->
<div class="ventajas-reciclin-ejemplo-wrapper">

    <h2 class="ventajas-titulo-ejemplo">
        Participar en Reciclín tiene <span class="resaltado-verde">muchas ventajas</span>:
    </h2>

    <div class="ventajas-grid-ejemplo">

        <div class="ventaja-card-ejemplo">
            <span class="ventaja-icon-ejemplo icon-trofeo">🏆</span>
            <div class="ventaja-texto-ejemplo">
                Gana puntos y accede a premios exclusivos
            </div>
        </div>

        <div class="ventaja-card-ejemplo">
            <span class="ventaja-icon-ejemplo icon-luz">💡</span>
            <div class="ventaja-texto-ejemplo">
                Fomenta el aprendizaje creativo en el aula
            </div>
        </div>

        <div class="ventaja-card-ejemplo">
            <span class="ventaja-icon-ejemplo icon-planeta">🌎</span>
            <div class="ventaja-texto-ejemplo">
                Genera un impacto positivo en el medio ambiente
            </div>
        </div>

        <div class="ventaja-card-ejemplo">
            <span class="ventaja-icon-ejemplo icon-colegio">🏫</span>
            <div class="ventaja-texto-ejemplo">
                Promueve el liderazgo ecológico en tu colegio
            </div>
        </div>

        <div class="ventaja-card-ejemplo">
            <span class="ventaja-icon-ejemplo icon-manos">🖐️</span>
            <div class="ventaja-texto-ejemplo">
                Ayuda a crear hábitos de reciclaje a los niños
            </div>
        </div>

    </div>
</div>

<!-- ============================================================ -->
<!-- CONTENIDO DESTACADO -->
<!-- ============================================================ -->
@if(isset($destacados) && $destacados->count() > 0)
<section class="contenido-destacado-section" style="padding: 80px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); position: relative; overflow: hidden;">
    <style>
        .contenido-destacado-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="white" opacity="0.1"/></svg>');
            opacity: 0.3;
        }

        .contenido-destacado-section h2 {
            text-align: center;
            font-size: 2.8rem;
            font-weight: 900;
            margin-bottom: 15px;
            color: white;
            font-family: 'Montserrat', sans-serif;
            position: relative;
            text-shadow: 0 2px 20px rgba(0,0,0,0.2);
        }

        .destacado-subtitle {
            text-align: center;
            font-size: 1.1rem;
            color: rgba(255,255,255,0.9);
            margin-bottom: 50px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            position: relative;
        }

        .destacados-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 35px;
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
        }

        .destacado-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
        }

        .destacado-card:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 30px 80px rgba(0,0,0,0.4);
        }

        .destacado-media {
            position: relative;
            width: 100%;
            height: 240px;
            overflow: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .destacado-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .destacado-card:hover .destacado-media img {
            transform: scale(1.15) rotate(2deg);
        }

        .destacado-video-wrapper {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            background: #000;
        }

        .destacado-video-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .destacado-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(0deg, rgba(0,0,0,0.6) 0%, transparent 100%);
            display: flex;
            align-items: flex-end;
            padding: 20px;
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .destacado-card:hover .destacado-overlay {
            opacity: 1;
        }

        .destacado-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            background: #f59e0b;
            color: white;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
            z-index: 10;
        }

        .destacado-content {
            padding: 30px;
            background: white;
        }

        .destacado-title {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 15px;
            color: #111827;
            line-height: 1.3;
            font-family: 'Montserrat', sans-serif;
        }

        .destacado-description {
            color: #6b7280;
            font-size: 1rem;
            line-height: 1.7;
        }

        @media (max-width: 768px) {
            .contenido-destacado-section h2 {
                font-size: 2.2rem;
            }

            .destacados-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }
        }
    </style>

    <div class="container">
        <h2>⭐ Contenido Destacado</h2>
        <p class="destacado-subtitle">Lo mejor de Reciclin seleccionado especialmente para ti</p>

        <div class="destacados-grid">
            @foreach($destacados as $destacado)
            <div class="destacado-card">
                <span class="destacado-badge">Destacado</span>

                @if($destacado->es_imagen)
                    <div class="destacado-media">
                        <img src="{{ $destacado->archivo_url }}" alt="{{ $destacado->titulo }}">
                        <div class="destacado-overlay">
                            <span style="color: white; font-weight: 700; font-size: 0.9rem;">👁️ Ver más</span>
                        </div>
                    </div>
                @else
                    <div class="destacado-video-wrapper">
                        <iframe src="{{ $destacado->youtube_embed_url }}"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                    </div>
                @endif

                <div class="destacado-content">
                    <h3 class="destacado-title">{{ $destacado->titulo }}</h3>
                    @if($destacado->descripcion)
                        <p class="destacado-description">{{ $destacado->descripcion }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ============================================================ -->
<!-- SECCIÓN FAQ -->
<!-- ============================================================ -->
<div class="faq-section-container">
    <div class="faq-wrapper">
        <div class="faq-contenido-area">
            <h2 class="faq-titulo" id="faq-main-title">🧾 1. ¿Cómo funciona el registro?</h2>

            <div class="faq-step active" id="tab-registro">
                <div class="step-item">
                    <div class="step-icon-illustration-container icon-registro"></div>
                    <div class="step-text">
                        <strong>Registro en Plataforma:</strong>
                        Los estudiantes se registran con su nombre y grado en la plataforma Reciclin.com.
                    </div>
                </div>

                <div class="step-item reverse">
                    <div class="step-icon-illustration-container icon-reconocimiento"></div>
                    <div class="step-text">
                        <strong>Reconocimiento:</strong>
                        Cada vez que depositan una botella plástica en el robot tacho inteligente, el sistema reconoce el material reciclado.
                    </div>
                </div>

                <div class="step-item">
                    <div class="step-icon-illustration-container icon-puntos"></div>
                    <div class="step-text">
                        <strong>Suma Automática:</strong>
                        Automáticamente, Reciclín suma los Recipuntos del estudiante en su perfil personal, que podrá visualizar desde la web o aplicación.
                    </div>
                </div>
            </div>

            <div class="faq-step" id="tab-gamificacion">
                <div class="step-item">
                    <div class="step-icon-illustration-container icon-juego"></div>
                    <div class="step-text">
                        <strong>Reciclaje como Juego:</strong>
                        El proyecto convierte el reciclaje en un juego educativo, donde los niños ganan puntos por sus buenas acciones ecológicas.
                    </div>
                </div>

                <div class="step-item reverse">
                    <div class="step-icon-illustration-container icon-retos"></div>
                    <div class="step-text">
                        <strong>Competencia y Equipo:</strong>
                        Cada reto o actividad se transforma en una experiencia divertida que promueve la competencia sana y el trabajo en equipo.
                    </div>
                </div>

                <div class="step-item">
                    <div class="step-icon-illustration-container icon-aprendizaje"></div>
                    <div class="step-text">
                        <strong>Conciencia Ambiental:</strong>
                        Aprendizaje sobre cuidado ambiental, responsabilidad y sostenibilidad mientras disfrutan de una experiencia digital motivadora.
                    </div>
                </div>
            </div>

            <div class="faq-step" id="tab-canje">
                <div class="step-item">
                    <div class="step-icon-illustration-container icon-canje"></div>
                    <div class="step-text">
                        <strong>Canje en Recitienda:</strong>
                        Los Recipuntos acumulados pueden canjearse en la Recitienda, donde se ofrecen útiles escolares y premios ecológicos.
                    </div>
                </div>

                <div class="step-item reverse">
                    <div class="step-icon-illustration-container icon-reconocido"></div>
                    <div class="step-text">
                        <strong>Reconocimiento Público:</strong>
                        Los estudiantes más comprometidos son reconocidos públicamente como ejemplo de buenas prácticas ambientales.
                    </div>
                </div>

                <div class="step-item">
                    <div class="step-icon-illustration-container icon-motivacion"></div>
                    <div class="step-text">
                        <strong>Fomento Continuo:</strong>
                        Este sistema de recompensas fortalece la motivación y el compromiso con el reciclaje continuo y sostenible.
                    </div>
                </div>
            </div>

            <div class="faq-step" id="tab-docentes">
                <div class="step-item">
                    <div class="step-icon-illustration-container icon-guia"></div>
                    <div class="step-text">
                        <strong>Guía Educativa:</strong>
                        Los docentes acompañan el proceso guiando a los estudiantes en actividades educativas sobre el reciclaje y el cuidado del medio ambiente.
                    </div>
                </div>

                <div class="step-item reverse">
                    <div class="step-icon-illustration-container icon-supervision"></div>
                    <div class="step-text">
                        <strong>Supervisión y Uso Correcto:</strong>
                        Supervisan el uso correcto del robot y promueven la participación equitativa de todos los niños.
                    </div>
                </div>

                <div class="step-item">
                    <div class="step-icon-illustration-container icon-integracion"></div>
                    <div class="step-text">
                        <strong>Integración Curricular:</strong>
                        Además, integran el proyecto en áreas como Ciencia, Ciudadanía y Tecnología, reforzando aprendizajes significativos.
                    </div>
                </div>
            </div>
        </div>

        <div class="faq-botones">
            <div class="faq-boton active-btn" data-target="tab-registro" data-title="🧾 1. ¿Cómo funciona el registro?">
                🧾 1. ¿Cómo funciona el registro?
            </div>
            <div class="faq-boton" data-target="tab-gamificacion" data-title="🎮 2. Gamificación y Aprendizaje">
                🎮 2. Gamificación y Aprendizaje
            </div>
            <div class="faq-boton" data-target="tab-canje" data-title="🏆 3. Canje y Reconocimiento">
                🏆 3. Canje y Reconocimiento
            </div>
            <div class="faq-boton" data-target="tab-docentes" data-title="👩‍🏫 4. Rol de los Docentes">
                👩‍🏫 4. Rol de los Docentes
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- TARJETAS DE CATEGORÍAS -->
<!-- ============================================================ -->
<div class="smile-and-learn-component">
    <div class="container">
        <h1>Pequeños artistas con <span class="highlight">grandes ideas</span></h1>

        <div class="filter-buttons">
            <button class="filter-btn active" data-filter="all">VER TODO</button>
            <button class="filter-btn" data-filter="inicial">INICIAL</button>
            <button class="filter-btn" data-filter="infantil">INFANTIL</button>
            <button class="filter-btn" data-filter="primaria">PRIMARIA</button>
            <button class="filter-btn" data-filter="secundaria">SECUNDARIA</button>
            <button class="filter-btn" data-filter="especial">ESPECIAL</button>
        </div>

        <div class="cards-grid" id="cardsGrid"></div>
    </div>
</div>

<!-- ============================================================ -->
<!-- SECCIÓN DE BOTELLAS -->
<!-- ============================================================ -->
<div class="bottles-section">
    <div class="bottles-container">
        <div class="bottle">
            <img src="https://reciclin.com/imagenes/botella1.png" alt="Ícono Reciclaje 1">
        </div>
        <div class="bottle">
            <img src="https://reciclin.com/imagenes/botella2.png" alt="Ícono Reciclaje 2">
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- SCRIPTS -->
<!-- ============================================================ -->

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Script del Menú Móvil -->
<script>
    function toggleMenuReciclin() {
        const nav = document.getElementById('mobileNavReciclin');
        nav.classList.toggle('active');
    }

    document.addEventListener('click', function(e) {
        const nav = document.getElementById('mobileNavReciclin');
        const menuBtn = document.querySelector('#barra-reciclin .mobile-menu');

        if (nav && menuBtn && !nav.contains(e.target) && !menuBtn.contains(e.target)) {
            nav.classList.remove('active');
        }
    });

    window.addEventListener('resize', function() {
        if (window.innerWidth > 992) {
            const nav = document.getElementById('mobileNavReciclin');
            if (nav) {
                nav.classList.remove('active');
            }
        }
    });
</script>

<!-- Script del Slider de Videos -->
<script>
    let swiperInstance;

    document.addEventListener('DOMContentLoaded', function() {
        swiperInstance = new Swiper('.mySwiperFinal', {
            loop: true,
            slidesPerView: 1,
            spaceBetween: 0,
            allowTouchMove: true,

            autoplay: {
                delay: 15000,
                disableOnInteraction: false,
            },

            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },

            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },

            on: {
                init: function () {
                    iniciarVideoActual(this);
                },
                slideChangeTransitionStart: function () {
                    const videos = document.querySelectorAll('#seccion-videos-slide-final video');
                    videos.forEach(video => {
                        if (!video.paused) {
                            video.pause();
                        }
                    });
                },
                slideChangeTransitionEnd: function () {
                    iniciarVideoActual(this);
                }
            }
        });
    });

    function iniciarVideoActual(swiperInstance) {
        if (!swiperInstance) return;
        const activeSlide = swiperInstance.slides[swiperInstance.activeIndex];
        const activeVideo = activeSlide ? activeSlide.querySelector('video') : null;

        if (activeVideo) {
            activeVideo.currentTime = 0;
            activeVideo.play().catch(error => {
                console.log("Autoplay blocked or other error:", error);
            });
        }
    }
</script>

<!-- Script del Ranking -->
<script>
function actualizarRanking() {
    const btn = document.querySelector('.btn-actualizar');
    const rankingLista = document.getElementById('rankingLista');

    btn.classList.add('loading');
    btn.innerHTML = '<span>⏳</span> Actualizando...';

    // Llamada AJAX a la API de Laravel
    fetch('{{ url('/api/usuarios') }}')
        .then(response => response.json())
        .then(data => {
            rankingLista.classList.add('updating');

            // Aquí podrías actualizar el ranking con los datos reales
            // Por ahora solo simulamos la actualización

            btn.classList.remove('loading');
            btn.innerHTML = '<span>✅</span> Actualizado';

            setTimeout(() => {
                btn.innerHTML = '<span>🔄</span> Actualizar Ranking';
                rankingLista.classList.remove('updating');
            }, 1500);
        })
        .catch(error => {
            console.error('Error:', error);
            btn.classList.remove('loading');
            btn.innerHTML = '<span>❌</span> Error';
            setTimeout(() => {
                btn.innerHTML = '<span>🔄</span> Actualizar Ranking';
            }, 2000);
        });
}
</script>

<!-- Script del FAQ -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (document.querySelector('.faq-section-container')) {
            const buttons = document.querySelectorAll('.faq-boton');
            const contents = document.querySelectorAll('.faq-step');
            const mainTitle = document.getElementById('faq-main-title');

            function activateTab(button) {
                const targetId = button.getAttribute('data-target');
                const newTitle = button.getAttribute('data-title');

                buttons.forEach(btn => btn.classList.remove('active-btn'));
                contents.forEach(content => content.classList.remove('active'));

                button.classList.add('active-btn');
                const targetContent = document.getElementById(targetId);

                if (targetContent) {
                    setTimeout(() => {
                        targetContent.classList.add('active');
                    }, 50);
                }

                if (mainTitle && newTitle) {
                    mainTitle.textContent = newTitle;
                }
            }

            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    activateTab(button);
                });
            });

            const initialActiveBtn = document.querySelector('.faq-boton.active-btn');
            if (initialActiveBtn) {
                mainTitle.textContent = initialActiveBtn.getAttribute('data-title');
            }
        }
    });
</script>

<!-- Script de las Tarjetas -->
<script>
    (function() {
        const cardsData = [
            {
                category: 'inicial',
                title: 'Aprende colores y formas',
                bg: '#FFD166'
            },
            {
                category: 'infantil',
                title: 'Una montaña de ideas',
                bg: '#7b9d6f'
            },
            {
                category: 'primaria',
                title: 'Ecopalabra',
                bg: '#9b9b9b'
            },
            {
                category: 'secundaria',
                title: 'Introducción a la Biología',
                bg: '#764BA2'
            },
            {
                category: 'especial',
                title: 'Taller de Emociones (TEA)',
                bg: '#1ABC9C'
            },
            {
                category: 'infantil',
                title: 'Mis primeros números',
                bg: '#EBEBEB'
            }
        ];

        const component = document.querySelector('.smile-and-learn-component');
        if (!component) return;

        const cardsGrid = component.querySelector('#cardsGrid');
        const filterBtns = component.querySelectorAll('.filter-btn');

        function createCard(card) {
            return `
                <div class="card ${card.category}" data-category="${card.category}">
                    <div class="card-bg" style="background-color: ${card.bg};"></div>
                    <div class="card-tag">${card.category.toUpperCase()}</div>
                    <div class="card-content">
                        <h3 class="card-title">${card.title}</h3>
                        <div class="card-arrow"></div>
                    </div>
                </div>
            `;
        }

        function renderCards(filter = 'all') {
            const filteredCards = filter === 'all'
                ? cardsData
                : cardsData.filter(card => card.category === filter);

            cardsGrid.innerHTML = filteredCards.map(card => createCard(card)).join('');
        }

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                const filter = btn.getAttribute('data-filter');
                renderCards(filter);
            });
        });

        renderCards('all');

        cardsGrid.addEventListener('click', (e) => {
            const card = e.target.closest('.card');
            if (card) {
                card.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    card.style.transform = '';
                }, 200);
            }
        });
    })();
</script>

</body>
</html>
