<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace - Reciclin.com</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/reciclin.css') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fa;
            color: #333;
        }

        /* Header */
        .marketplace-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 20px 40px;
            text-align: center;
        }

        .marketplace-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .marketplace-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Toolbar */
        .toolbar {
            background: white;
            padding: 25px;
            margin: -20px 20px 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }

        .search-box {
            flex: 1;
            min-width: 250px;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 12px 45px 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-btn {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            padding: 8px 15px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }

        .filter-select {
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
        }

        .filter-select:focus {
            outline: none;
            border-color: #667eea;
        }

        .btn-clear {
            padding: 12px 20px;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-clear:hover {
            background: #5a6268;
        }

        /* Products Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-body {
            padding: 20px;
        }

        .product-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-description {
            font-size: 0.9rem;
            color: #888;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: 800;
            color: #667eea;
        }

        .product-stock {
            font-size: 0.85rem;
            color: #888;
        }

        .product-user {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .user-avatar-small {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .user-name {
            font-size: 0.9rem;
            color: #666;
        }

        .product-location {
            font-size: 0.85rem;
            color: #888;
            margin-bottom: 15px;
        }

        .btn-view {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .empty-icon {
            font-size: 5rem;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .empty-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .empty-message {
            font-size: 1rem;
            color: #888;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin: 40px 0;
            flex-wrap: wrap;
        }

        .pagination a,
        .pagination span {
            padding: 10px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }

        .pagination .active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .pagination a:hover {
            background: #f8f9fa;
            border-color: #667eea;
        }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .badge-new {
            background: #28a745;
            color: white;
        }

        .badge-popular {
            background: #ffc107;
            color: #333;
        }

        @media (max-width: 768px) {
            .marketplace-title {
                font-size: 2rem;
            }

            .toolbar {
                flex-direction: column;
            }

            .search-box,
            .filter-select {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar from index -->
    <div id="barra-reciclin">
        <header class="header">
            <nav class="nav-container">
                <div class="left-group">
                    <button class="inicio-btn" onclick="window.location.href='{{ url('/') }}'">INICIO</button>
                    <div class="main-nav">
                        <a href="{{ url('/marketplace') }}" class="nav-item" style="color: #fff;">MARKETPLACE</a>
                    </div>
                </div>

                <div class="centered-logo">
                    <img src="https://reciclin.com/imagenes/logo.png" alt="Logo Central" class="custom-logo">
                </div>

                <div class="right-section">
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
                </div>
            </nav>
        </header>
    </div>

    <!-- Header -->
    <div class="marketplace-header">
        <h1 class="marketplace-title">Marketplace</h1>
        <p class="marketplace-subtitle">Descubre materiales reciclables de calidad</p>
    </div>

    <div class="container">
        <!-- Toolbar -->
        <form method="GET" class="toolbar">
            <div class="search-box">
                <input
                    type="text"
                    name="search"
                    class="search-input"
                    placeholder="Buscar publicaciones..."
                    value="{{ request('search') }}"
                >
                <button type="submit" class="search-btn">🔍</button>
            </div>

            <select name="categoria" class="filter-select">
                <option value="">Todas las categorías</option>
                @foreach($categorias as $cat)
                    <option value="{{ $cat->cod_categoria }}" {{ request('categoria') == $cat->cod_categoria ? 'selected' : '' }}>
                        {{ $cat->nombre_categoria }}
                    </option>
                @endforeach
            </select>

            <select name="order" class="filter-select">
                <option value="recent" {{ request('order') == 'recent' ? 'selected' : '' }}>Más recientes</option>
                <option value="price_asc" {{ request('order') == 'price_asc' ? 'selected' : '' }}>Menor precio</option>
                <option value="price_desc" {{ request('order') == 'price_desc' ? 'selected' : '' }}>Mayor precio</option>
                <option value="popular" {{ request('order') == 'popular' ? 'selected' : '' }}>Más populares</option>
            </select>

            @if(request('search') || request('categoria') || request('order'))
                <a href="{{ url('/marketplace') }}" class="btn-clear">Limpiar</a>
            @endif
        </form>

        <!-- Products Grid -->
        @if($publicaciones->count() > 0)
            <div class="products-grid">
                @foreach($publicaciones as $pub)
                    <div class="product-card" onclick="window.location.href='{{ route('marketplace.show', $pub->cod_publicacion) }}'">
                        @if($pub->vistas > 50)
                            <span class="badge badge-popular">Popular</span>
                        @elseif($pub->created_at && $pub->created_at->diffInDays(now()) < 7)
                            <span class="badge badge-new">Nuevo</span>
                        @endif

                        <div class="product-image">
                            @if($pub->foto1)
                                <img src="{{ $pub->foto1 }}" alt="{{ $pub->nombre }}">
                            @else
                                ♻️
                            @endif
                        </div>

                        <div class="product-body">
                            <h3 class="product-title">{{ $pub->nombre }}</h3>
                            <p class="product-description">{{ Str::limit($pub->descripcion, 80) }}</p>

                            <div class="product-meta">
                                <div class="product-price">
                                    S/ {{ number_format($pub->precio, 2) }}
                                    <div class="product-stock">{{ $pub->disponibilidad }} {{ $pub->disponibilidad == 1 ? 'unidad' : 'unidades' }}</div>
                                </div>
                            </div>

                            <div class="product-user">
                                <div class="user-avatar-small">
                                    {{ strtoupper(substr($pub->usuario->nick ?? 'U', 0, 1)) }}
                                </div>
                                <span class="user-name">{{ $pub->usuario->nick ?? 'Usuario' }}</span>
                            </div>

                            @if($pub->distrito)
                                <div class="product-location">📍 {{ $pub->distrito }}, {{ $pub->provincia }}</div>
                            @endif

                            <a href="{{ route('marketplace.show', $pub->cod_publicacion) }}" class="btn-view">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination">
                {{ $publicaciones->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">🔍</div>
                <h2 class="empty-title">No se encontraron publicaciones</h2>
                <p class="empty-message">
                    Intenta ajustar los filtros de búsqueda o explora todas las categorías
                </p>
            </div>
        @endif
    </div>
</body>
</html>
