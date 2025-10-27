<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $publicacion->nombre }} - Reciclin.com</title>
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

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .breadcrumb {
            margin-bottom: 30px;
            font-size: 0.9rem;
            color: #888;
        }

        .breadcrumb a {
            color: #667eea;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .product-detail {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 50px;
        }

        .product-gallery {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .main-image {
            width: 100%;
            height: 400px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 5rem;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .main-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .thumbnail-gallery {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .thumbnail {
            height: 100px;
            background: #f0f0f0;
            border-radius: 8px;
            cursor: pointer;
            overflow: hidden;
            border: 2px solid transparent;
            transition: all 0.2s;
        }

        .thumbnail:hover {
            border-color: #667eea;
        }

        .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-info {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .product-title {
            font-size: 2rem;
            font-weight: 800;
            color: #333;
            margin-bottom: 15px;
        }

        .product-price {
            font-size: 2.5rem;
            font-weight: 800;
            color: #667eea;
            margin-bottom: 20px;
        }

        .product-stock {
            display: inline-block;
            padding: 8px 15px;
            background: #d4edda;
            color: #155724;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .product-description {
            font-size: 1rem;
            color: #666;
            line-height: 1.6;
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 2px solid #f0f0f0;
        }

        .product-details {
            margin-bottom: 25px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .detail-label {
            color: #888;
            font-weight: 500;
        }

        .detail-value {
            color: #333;
            font-weight: 600;
        }

        .seller-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .seller-title {
            font-size: 0.9rem;
            color: #888;
            margin-bottom: 15px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .seller-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .seller-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 1.2rem;
        }

        .seller-details {
            flex: 1;
        }

        .seller-name {
            font-weight: 700;
            color: #333;
            font-size: 1.1rem;
        }

        .seller-stats {
            font-size: 0.85rem;
            color: #888;
            margin-top: 3px;
        }

        .purchase-form {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .total-price {
            text-align: right;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #e0e0e0;
        }

        .total-label {
            font-size: 1rem;
            color: #888;
        }

        .total-value {
            font-size: 1.8rem;
            font-weight: 800;
            color: #667eea;
            margin-top: 5px;
        }

        .btn-buy {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-buy:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-back {
            display: inline-block;
            padding: 10px 20px;
            background: #6c757d;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-back:hover {
            background: #5a6268;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 25px;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .related-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
            cursor: pointer;
        }

        .related-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .related-image {
            width: 100%;
            height: 150px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
        }

        .related-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .related-body {
            padding: 15px;
        }

        .related-title {
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
            font-size: 1rem;
        }

        .related-price {
            font-size: 1.2rem;
            font-weight: 800;
            color: #667eea;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 10px;
        }

        .badge-active {
            background: #28a745;
            color: white;
        }

        .stats-row {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: #667eea;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #888;
            margin-top: 5px;
        }

        @media (max-width: 968px) {
            .product-detail {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
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

    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="{{ url('/') }}">Inicio</a> /
            <a href="{{ url('/marketplace') }}">Marketplace</a> /
            <span>{{ $publicacion->nombre }}</span>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">✗ {{ session('error') }}</div>
        @endif

        <!-- Product Detail -->
        <div class="product-detail">
            <!-- Gallery -->
            <div class="product-gallery">
                <div class="main-image" id="mainImage">
                    @if($publicacion->foto1)
                        <img src="{{ $publicacion->foto1 }}" alt="{{ $publicacion->nombre }}">
                    @else
                        ♻️
                    @endif
                </div>

                <div class="thumbnail-gallery">
                    @if($publicacion->foto1)
                        <div class="thumbnail" onclick="changeImage('{{ $publicacion->foto1 }}')">
                            <img src="{{ $publicacion->foto1 }}" alt="Foto 1">
                        </div>
                    @endif
                    @if($publicacion->foto2)
                        <div class="thumbnail" onclick="changeImage('{{ $publicacion->foto2 }}')">
                            <img src="{{ $publicacion->foto2 }}" alt="Foto 2">
                        </div>
                    @endif
                    @if($publicacion->foto3)
                        <div class="thumbnail" onclick="changeImage('{{ $publicacion->foto3 }}')">
                            <img src="{{ $publicacion->foto3 }}" alt="Foto 3">
                        </div>
                    @endif
                </div>
            </div>

            <!-- Info -->
            <div class="product-info">
                <h1 class="product-title">
                    {{ $publicacion->nombre }}
                    @if($publicacion->status === 'activo')
                        <span class="badge badge-active">Activo</span>
                    @endif
                </h1>

                <div class="product-price">S/ {{ number_format($publicacion->precio, 2) }}</div>

                @if($publicacion->disponibilidad > 0)
                    <div class="product-stock">
                        ✓ {{ $publicacion->disponibilidad }} {{ $publicacion->disponibilidad == 1 ? 'unidad disponible' : 'unidades disponibles' }}
                    </div>
                @else
                    <div class="product-stock" style="background: #f8d7da; color: #721c24;">
                        ✗ Sin stock
                    </div>
                @endif

                <div class="product-description">
                    {{ $publicacion->descripcion ?? 'Sin descripción disponible' }}
                </div>

                <!-- Details -->
                <div class="product-details">
                    <div class="detail-row">
                        <span class="detail-label">Calidad</span>
                        <span class="detail-value">{{ ucfirst($publicacion->calidad) ?? 'No especificada' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Moneda</span>
                        <span class="detail-value">{{ $publicacion->moneda ?? 'PEN' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Ubicación</span>
                        <span class="detail-value">{{ $publicacion->distrito }}, {{ $publicacion->provincia }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Publicado</span>
                        <span class="detail-value">{{ $publicacion->fecha_p ?? 'Recientemente' }}</span>
                    </div>
                </div>

                <!-- Seller Info -->
                <div class="seller-info">
                    <div class="seller-title">Vendedor</div>
                    <div class="seller-user">
                        <div class="seller-avatar">
                            {{ strtoupper(substr($publicacion->usuario->nick ?? 'U', 0, 1)) }}
                        </div>
                        <div class="seller-details">
                            <div class="seller-name">{{ $publicacion->usuario->nick ?? 'Usuario' }}</div>
                            <div class="seller-stats">
                                {{ number_format($publicacion->usuario->publicaciones ?? 0) }} publicaciones
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Purchase Form -->
                @auth
                    @if($publicacion->disponibilidad > 0)
                        <form action="{{ route('marketplace.comprar', $publicacion->cod_publicacion) }}" method="POST" class="purchase-form">
                            @csrf
                            <div class="form-group">
                                <label for="cantidad" class="form-label">Cantidad</label>
                                <input
                                    type="number"
                                    id="cantidad"
                                    name="cantidad"
                                    class="form-input"
                                    min="1"
                                    max="{{ $publicacion->disponibilidad }}"
                                    value="1"
                                    onchange="updateTotal()"
                                    required
                                >
                            </div>

                            <div class="total-price">
                                <div class="total-label">Total a pagar</div>
                                <div class="total-value" id="totalValue">
                                    S/ {{ number_format($publicacion->precio, 2) }}
                                </div>
                            </div>

                            <button type="submit" class="btn-buy">Comprar Ahora</button>
                        </form>
                    @endif
                @else
                    <div style="background: #fff3cd; padding: 20px; border-radius: 10px; text-align: center;">
                        <p style="color: #856404; margin-bottom: 15px;">
                            Debes iniciar sesión para realizar una compra
                        </p>
                        <a href="{{ url('/login') }}" class="btn-buy" style="display: inline-block; width: auto; padding: 12px 30px;">
                            Iniciar Sesión
                        </a>
                    </div>
                @endauth

                <!-- Stats -->
                <div class="stats-row">
                    <div class="stat-item">
                        <div class="stat-value">{{ number_format($publicacion->vistas ?? 0) }}</div>
                        <div class="stat-label">Visitas</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">#{{ $publicacion->cod_publicacion }}</div>
                        <div class="stat-label">ID</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relacionadas->count() > 0)
            <h2 class="section-title">Publicaciones Relacionadas</h2>
            <div class="related-grid">
                @foreach($relacionadas as $rel)
                    <div class="related-card" onclick="window.location.href='{{ route('marketplace.show', $rel->cod_publicacion) }}'">
                        <div class="related-image">
                            @if($rel->foto1)
                                <img src="{{ $rel->foto1 }}" alt="{{ $rel->nombre }}">
                            @else
                                ♻️
                            @endif
                        </div>
                        <div class="related-body">
                            <div class="related-title">{{ Str::limit($rel->nombre, 40) }}</div>
                            <div class="related-price">S/ {{ number_format($rel->precio, 2) }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div style="margin-top: 40px;">
            <a href="{{ url('/marketplace') }}" class="btn-back">← Volver al Marketplace</a>
        </div>
    </div>

    <script>
        const basePrice = {{ $publicacion->precio }};

        function updateTotal() {
            const cantidad = document.getElementById('cantidad').value;
            const total = basePrice * cantidad;
            document.getElementById('totalValue').textContent = 'S/ ' + total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }

        function changeImage(src) {
            const mainImage = document.getElementById('mainImage');
            mainImage.innerHTML = `<img src="${src}" alt="Imagen principal">`;
        }
    </script>
</body>
</html>
