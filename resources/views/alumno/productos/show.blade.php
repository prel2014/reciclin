@extends('alumno.layout')

@section('title', 'Detalle del Producto')
@section('page-title', 'Detalle del Producto')

@section('content')
<style>
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #3b82f6;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 20px;
        transition: all 0.2s;
    }

    .back-link:hover {
        color: #2563eb;
        transform: translateX(-5px);
    }

    .producto-detail-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-bottom: 30px;
    }

    .producto-image-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .producto-image {
        width: 100%;
        height: 400px;
        background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 8rem;
        margin-bottom: 20px;
    }

    .producto-info-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .producto-nombre {
        font-size: 2rem;
        font-weight: 800;
        color: #333;
        margin-bottom: 15px;
    }

    .producto-precio-display {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .precio-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .precio-value {
        font-size: 2.5rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-section {
        margin-bottom: 25px;
    }

    .info-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }

    .info-value {
        font-size: 1.1rem;
        color: #333;
        line-height: 1.6;
    }

    .stock-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 1rem;
    }

    .stock-disponible {
        background: #d4edda;
        color: #155724;
    }

    .stock-bajo {
        background: #fff3cd;
        color: #856404;
    }

    .canje-form-card {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border-radius: 15px;
        padding: 30px;
        margin-top: 25px;
    }

    .canje-form-card.disabled {
        background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
    }

    .canje-title {
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
    }

    .form-input {
        width: 100%;
        padding: 15px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 10px;
        font-size: 1.1rem;
        font-weight: 700;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        transition: all 0.2s;
    }

    .form-input:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.3);
        border-color: white;
    }

    .form-input::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }

    .cantidad-controls {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .btn-cantidad {
        width: 50px;
        height: 50px;
        border: 2px solid white;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border-radius: 10px;
        font-size: 1.5rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-cantidad:hover {
        background: white;
        color: #10b981;
    }

    .precio-total {
        background: rgba(255, 255, 255, 0.2);
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .precio-total-label {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 5px;
    }

    .precio-total-value {
        font-size: 2rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .recipuntos-restantes {
        font-size: 0.85rem;
        opacity: 0.8;
        margin-top: 8px;
    }

    .btn {
        padding: 15px 30px;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.2s;
        width: 100%;
        text-align: center;
        text-decoration: none;
        display: inline-block;
    }

    .btn-success {
        background: white;
        color: #10b981;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .btn-disabled {
        background: rgba(255, 255, 255, 0.3);
        color: rgba(255, 255, 255, 0.7);
        cursor: not-allowed;
    }

    .alert-warning {
        background: rgba(251, 146, 60, 0.2);
        border: 2px solid rgba(251, 146, 60, 0.5);
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .mis-recipuntos-display {
        background: rgba(255, 255, 255, 0.2);
        padding: 15px 20px;
        border-radius: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    @media (max-width: 968px) {
        .producto-detail-container {
            grid-template-columns: 1fr;
        }

        .producto-image {
            height: 300px;
            font-size: 6rem;
        }
    }
</style>

<a href="{{ route('alumno.productos.index') }}" class="back-link">
    ← Volver a Productos
</a>

<div class="producto-detail-container">
    <!-- Imagen del Producto -->
    <div class="producto-image-card">
        <div class="producto-image">🎒</div>
        <div class="info-section">
            <div class="info-label">Disponibilidad</div>
            <div class="info-value">
                <span class="stock-badge {{ ($producto->disponibilidad ?? 0) > 10 ? 'stock-disponible' : 'stock-bajo' }}">
                    📦 {{ number_format($producto->disponibilidad ?? 0) }} unidades disponibles
                </span>
            </div>
        </div>
    </div>

    <!-- Información del Producto -->
    <div class="producto-info-card">
        <h1 class="producto-nombre">{{ $producto->nombre }}</h1>

        <div class="producto-precio-display">
            <div>
                <div class="precio-label">Precio de Canje</div>
                <div class="precio-value">
                    <span>⭐</span>
                    <span>{{ number_format($producto->precio ?? 0) }}</span>
                </div>
            </div>
        </div>

        @if($producto->descripcion)
            <div class="info-section">
                <div class="info-label">Descripción</div>
                <div class="info-value">{{ $producto->descripcion }}</div>
            </div>
        @endif

        <!-- Formulario de Canje -->
        <div class="canje-form-card {{ !$puedoCanjear ? 'disabled' : '' }}">
            @if($puedoCanjear)
                <h3 class="canje-title">🎉 ¡Solicitar Canje!</h3>

                <div class="mis-recipuntos-display">
                    <span>Tus Recipuntos:</span>
                    <span style="font-size: 1.5rem; font-weight: 800;">⭐ {{ number_format($misRecipuntos) }}</span>
                </div>

                <form action="{{ route('alumno.productos.canjear', $producto->cod_publicacion) }}" method="POST" id="canjeForm">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Cantidad a Canjear (Máximo: {{ $cantidadMaxima }})</label>
                        <div class="cantidad-controls">
                            <button type="button" class="btn-cantidad" onclick="decrementCantidad()">-</button>
                            <input type="number"
                                   name="cantidad"
                                   id="cantidad"
                                   class="form-input"
                                   value="1"
                                   min="1"
                                   max="{{ $cantidadMaxima }}"
                                   style="text-align: center;"
                                   onchange="updatePrecioTotal()">
                            <button type="button" class="btn-cantidad" onclick="incrementCantidad()">+</button>
                        </div>
                    </div>

                    <div class="precio-total">
                        <div class="precio-total-label">Total a Pagar:</div>
                        <div class="precio-total-value">
                            <span>⭐</span>
                            <span id="precioTotal">{{ number_format($producto->precio ?? 0) }}</span>
                        </div>
                        <div class="recipuntos-restantes" id="recipuntosRestantes">
                            Te quedarán <strong id="restante">{{ number_format($misRecipuntos - ($producto->precio ?? 0)) }}</strong> Recipuntos
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success">
                        🎒 Solicitar Canje Ahora
                    </button>
                </form>
            @else
                <h3 class="canje-title">❌ No Disponible</h3>

                @if(($producto->disponibilidad ?? 0) <= 0)
                    <div class="alert-warning">
                        ⚠️ Este producto está agotado temporalmente.
                    </div>
                @elseif(($producto->precio ?? 0) > $misRecipuntos)
                    <div class="alert-warning">
                        ⚠️ Te faltan <strong>{{ number_format(($producto->precio ?? 0) - $misRecipuntos) }} Recipuntos</strong> para canjear este producto.
                    </div>
                    <div class="mis-recipuntos-display">
                        <span>Tus Recipuntos:</span>
                        <span style="font-size: 1.5rem; font-weight: 800;">⭐ {{ number_format($misRecipuntos) }}</span>
                    </div>
                    <p style="margin-top: 15px; opacity: 0.9;">
                        💡 ¡Sigue reciclando y mejorando tus notas para ganar más puntos!
                    </p>
                @endif
            @endif
        </div>
    </div>
</div>

<script>
    const precioPorUnidad = {{ $producto->precio ?? 0 }};
    const misRecipuntos = {{ $misRecipuntos }};
    const cantidadMaxima = {{ $cantidadMaxima }};

    function incrementCantidad() {
        const input = document.getElementById('cantidad');
        const currentValue = parseInt(input.value) || 1;
        if (currentValue < cantidadMaxima) {
            input.value = currentValue + 1;
            updatePrecioTotal();
        }
    }

    function decrementCantidad() {
        const input = document.getElementById('cantidad');
        const currentValue = parseInt(input.value) || 1;
        if (currentValue > 1) {
            input.value = currentValue - 1;
            updatePrecioTotal();
        }
    }

    function updatePrecioTotal() {
        const cantidad = parseInt(document.getElementById('cantidad').value) || 1;
        const total = precioPorUnidad * cantidad;
        const restante = misRecipuntos - total;

        document.getElementById('precioTotal').textContent = total.toLocaleString();
        document.getElementById('restante').textContent = restante.toLocaleString();

        // Cambiar color si se queda sin puntos
        const recipuntosRestantesDiv = document.getElementById('recipuntosRestantes');
        if (restante < 0) {
            recipuntosRestantesDiv.style.color = '#fca5a5';
        } else if (restante < 100) {
            recipuntosRestantesDiv.style.color = '#fcd34d';
        } else {
            recipuntosRestantesDiv.style.color = 'rgba(255, 255, 255, 0.8)';
        }
    }

    // Validación antes de enviar
    document.getElementById('canjeForm')?.addEventListener('submit', function(e) {
        const cantidad = parseInt(document.getElementById('cantidad').value) || 1;
        const total = precioPorUnidad * cantidad;

        if (total > misRecipuntos) {
            e.preventDefault();
            alert('No tienes suficientes Recipuntos para esta cantidad.');
            return false;
        }

        if (cantidad > cantidadMaxima) {
            e.preventDefault();
            alert('La cantidad máxima es ' + cantidadMaxima);
            return false;
        }

        if (!confirm('¿Confirmas que deseas canjear ' + cantidad + ' unidad(es) por ' + total.toLocaleString() + ' Recipuntos?')) {
            e.preventDefault();
            return false;
        }
    });
</script>

@endsection
