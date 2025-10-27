<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Admin Reciclin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 260px;
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            color: white;
            overflow-y: auto;
            transition: transform 0.3s ease;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            text-decoration: none;
            display: block;
        }

        .sidebar-subtitle {
            font-size: 0.85rem;
            opacity: 0.8;
            margin-top: 5px;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-section {
            margin-bottom: 25px;
        }

        .menu-section-title {
            padding: 0 20px;
            font-size: 0.75rem;
            text-transform: uppercase;
            opacity: 0.6;
            font-weight: 600;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            border-left-color: white;
        }

        .menu-item.active {
            background: rgba(255, 255, 255, 0.15);
            border-left-color: white;
            font-weight: 600;
        }

        .menu-item-icon {
            margin-right: 12px;
            font-size: 1.2rem;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* Header */
        .header {
            background: white;
            padding: 20px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #333;
        }

        .header-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1rem;
        }

        .user-details {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: #333;
        }

        .user-role {
            font-size: 0.8rem;
            color: #888;
        }

        .btn-logout {
            padding: 8px 20px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        /* Content Area */
        .content-area {
            padding: 30px;
        }

        /* Alerts */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
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

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        /* Stats Cards */
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
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #888;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: #333;
        }

        /* Loading Overlay */
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .loading-overlay.active {
            display: flex;
        }

        .loading-spinner {
            width: 80px;
            height: 80px;
            border: 8px solid rgba(255, 255, 255, 0.2);
            border-top: 8px solid #8b5cf6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .loading-content {
            text-align: center;
        }

        .loading-text {
            color: white;
            font-size: 18px;
            font-weight: 600;
            margin-top: 20px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .menu-toggle {
                display: block;
            }

            .user-details {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ url('/admin/dashboard') }}" class="sidebar-logo">Reciclin Admin</a>
            <div class="sidebar-subtitle">Panel de Administración</div>
        </div>

        <div class="sidebar-menu">
            <div class="menu-section">
                <div class="menu-section-title">Principal</div>
                <a href="{{ url('/admin/dashboard') }}" class="menu-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <span class="menu-item-icon">📊</span>
                    <span>Dashboard</span>
                </a>
                <a href="{{ url('/') }}" class="menu-item">
                    <span class="menu-item-icon">🏠</span>
                    <span>Ver Sitio Público</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Gestión de Usuarios</div>
                <a href="{{ url('/admin/usuarios') }}" class="menu-item {{ request()->is('admin/usuarios*') ? 'active' : '' }}">
                    <span class="menu-item-icon">👥</span>
                    <span>Usuarios</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Tienda Recipuntos</div>
                <a href="{{ url('/admin/productos') }}" class="menu-item {{ request()->is('admin/productos*') ? 'active' : '' }}">
                    <span class="menu-item-icon">🎒</span>
                    <span>Útiles Escolares</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Sistema Recipuntos</div>
                <a href="{{ url('/admin/materiales') }}" class="menu-item {{ request()->is('admin/materiales*') ? 'active' : '' }}">
                    <span class="menu-item-icon">♻️</span>
                    <span>Materiales Reciclables</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Contenido</div>
                <a href="{{ url('/admin/multimedia') }}" class="menu-item {{ request()->is('admin/multimedia*') ? 'active' : '' }}">
                    <span class="menu-item-icon">📸</span>
                    <span>Multimedia</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Sistema</div>
                <a href="{{ url('/admin/configuracion') }}" class="menu-item {{ request()->is('admin/configuracion*') ? 'active' : '' }}">
                    <span class="menu-item-icon">⚙️</span>
                    <span>Configuración</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
                <h1 class="header-title">@yield('page-title', 'Dashboard')</h1>
            </div>

            <div class="header-right">
                <div class="user-info">
                    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->nick, 0, 1)) }}</div>
                    <div class="user-details">
                        <div class="user-name">{{ Auth::user()->nick }}</div>
                        <div class="user-role">Administrador</div>
                    </div>
                </div>

                <form action="{{ url('/logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-logout">Cerrar Sesión</button>
                </form>
            </div>
        </div>

        <!-- Content -->
        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success">
                    <span>✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <span>✗</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <div>
                        <strong>Errores:</strong>
                        <ul style="margin-top: 5px; padding-left: 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <div class="loading-text">Procesando...</div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        // Funciones globales para mostrar/ocultar loading
        function showLoading(message = 'Procesando...') {
            const overlay = document.getElementById('loadingOverlay');
            const text = overlay.querySelector('.loading-text');
            text.textContent = message;
            overlay.classList.add('active');
        }

        function hideLoading() {
            const overlay = document.getElementById('loadingOverlay');
            overlay.classList.remove('active');
        }

        // Auto-mostrar loading en todos los formularios
        document.addEventListener('DOMContentLoaded', function() {
            // Interceptar todos los envíos de formularios
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    // Solo mostrar loading si no es un formulario de logout
                    if (!this.action.includes('/logout')) {
                        const submitBtn = this.querySelector('button[type="submit"]');
                        if (submitBtn) {
                            const btnText = submitBtn.textContent.trim();
                            let message = 'Procesando...';

                            if (btnText.includes('Crear') || btnText.includes('Guardar')) {
                                message = 'Guardando cambios...';
                            } else if (btnText.includes('Actualizar') || btnText.includes('Editar')) {
                                message = 'Actualizando...';
                            } else if (btnText.includes('Eliminar')) {
                                message = 'Eliminando...';
                            }

                            showLoading(message);
                        }
                    }
                });
            });

            // Interceptar clicks en botones de toggle/activar/desactivar
            document.addEventListener('click', function(e) {
                // Solo activar loading si es un botón o elemento clickeable específico
                if ((e.target.tagName === 'BUTTON' || e.target.tagName === 'A') &&
                    (e.target.classList.contains('btn-toggle') ||
                     e.target.textContent.includes('Activar') ||
                     e.target.textContent.includes('Desactivar'))) {
                    showLoading('Actualizando estado...');
                }
            });
        });
    </script>
</body>
</html>
