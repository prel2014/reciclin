<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Cuenta') - Reciclin.com</title>
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
            padding: 30px 20px;
        }

        .page-header {
            background: white;
            padding: 25px 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
        }

        .user-menu {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .menu-link {
            padding: 12px 24px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.2s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .menu-link:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .menu-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
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

        .content-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
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
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="color: white; font-weight: 500;">{{ Auth::user()->nick }}</span>
                        <form action="{{ url('/logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="action-btn btn-sesion" style="background: #e74c3c;">CERRAR SESIÓN</button>
                        </form>
                    </div>
                </div>
            </nav>
        </header>
    </div>

    <div class="container">
        <!-- User Menu -->
        <div class="user-menu">
            <a href="{{ route('user.publicaciones.index') }}" class="menu-link {{ request()->routeIs('user.publicaciones.*') ? 'active' : '' }}">
                📝 Mis Publicaciones
            </a>
            <a href="#" class="menu-link">
                🛒 Mis Compras
            </a>
            <a href="#" class="menu-link">
                👤 Mi Perfil
            </a>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                ✗ {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <strong>Errores:</strong>
                <ul style="margin-top: 5px; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Content -->
        @yield('content')
    </div>
</body>
</html>
