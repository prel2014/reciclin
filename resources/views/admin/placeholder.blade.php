@extends('admin.layout')

@section('title', $title ?? 'Próximamente')
@section('page-title', $title ?? 'Próximamente')

@section('content')
<style>
    .placeholder-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 400px;
        background: white;
        border-radius: 15px;
        padding: 60px 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        text-align: center;
    }

    .placeholder-icon {
        font-size: 5rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .placeholder-title {
        font-size: 2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
    }

    .placeholder-message {
        font-size: 1.1rem;
        color: #888;
        max-width: 500px;
        margin: 0 auto;
    }

    .btn-back {
        margin-top: 30px;
        padding: 12px 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.2s;
    }

    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }
</style>

<div class="placeholder-container">
    <div class="placeholder-icon">🚧</div>
    <h1 class="placeholder-title">Sección en Desarrollo</h1>
    <p class="placeholder-message">
        {{ $message ?? 'Esta funcionalidad estará disponible próximamente.' }}
    </p>
    <a href="{{ url('/admin/dashboard') }}" class="btn-back">
        Volver al Dashboard
    </a>
</div>
@endsection
