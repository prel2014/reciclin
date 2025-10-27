@extends('auth.layout')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="auth-header">
    <div class="auth-logo">Reciclin.com</div>
    <p class="auth-subtitle">Inicia sesión para continuar</p>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-error">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<form action="{{ url('/login') }}" method="POST">
    @csrf

    <div class="form-group">
        <label for="correo" class="form-label">Correo Electrónico</label>
        <input
            type="email"
            id="correo"
            name="correo"
            class="form-input"
            value="{{ old('correo') }}"
            placeholder="tucorreo@ejemplo.com"
            required
            autofocus
        >
        @error('correo')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="contrasena" class="form-label">Contraseña</label>
        <input
            type="password"
            id="contrasena"
            name="contrasena"
            class="form-input"
            placeholder="••••••••"
            required
        >
        @error('contrasena')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-checkbox">
            <input type="checkbox" name="remember">
            <span>Recordarme</span>
        </label>
    </div>

    <button type="submit" class="btn-primary">
        Iniciar Sesión
    </button>
</form>

<div class="auth-footer">
    <p style="margin-bottom: 10px;">
        <a href="{{ url('/forgot-password') }}" class="auth-link">¿Olvidaste tu contraseña?</a>
    </p>
    <p>
        ¿No tienes cuenta? <a href="{{ url('/register') }}" class="auth-link">Regístrate aquí</a>
    </p>
</div>
@endsection
