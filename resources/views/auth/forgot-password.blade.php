@extends('auth.layout')

@section('title', 'Recuperar Contraseña')

@section('content')
<div class="auth-header">
    <div class="auth-logo">Reciclin.com</div>
    <p class="auth-subtitle">Recupera tu contraseña</p>
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

<p style="margin-bottom: 20px; color: #666; font-size: 0.9rem;">
    Ingresa tu correo electrónico y te enviaremos instrucciones para recuperar tu contraseña.
</p>

<form action="{{ url('/forgot-password') }}" method="POST">
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

    <button type="submit" class="btn-primary">
        Enviar Instrucciones
    </button>
</form>

<div class="auth-footer">
    <p>
        <a href="{{ url('/login') }}" class="auth-link">← Volver al inicio de sesión</a>
    </p>
</div>
@endsection
