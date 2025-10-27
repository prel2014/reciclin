@extends('auth.layout')

@section('title', 'Registro')

@section('content')
<div class="auth-header">
    <div class="auth-logo">Reciclin.com</div>
    <p class="auth-subtitle">Crea tu cuenta y empieza a reciclar</p>
</div>

@if($errors->any())
    <div class="alert alert-error">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<form action="{{ url('/register') }}" method="POST">
    @csrf

    <div class="form-group">
        <label for="nick" class="form-label">Nick de Usuario *</label>
        <input
            type="text"
            id="nick"
            name="nick"
            class="form-input"
            value="{{ old('nick') }}"
            placeholder="tu_nick"
            required
            autofocus
        >
        @error('nick')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="correo" class="form-label">Correo Electrónico *</label>
        <input
            type="email"
            id="correo"
            name="correo"
            class="form-input"
            value="{{ old('correo') }}"
            placeholder="tucorreo@ejemplo.com"
            required
        >
        @error('correo')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="nombre" class="form-label">Nombre *</label>
        <input
            type="text"
            id="nombre"
            name="nombre"
            class="form-input"
            value="{{ old('nombre') }}"
            placeholder="Juan"
            required
        >
        @error('nombre')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="apellido" class="form-label">Apellido *</label>
        <input
            type="text"
            id="apellido"
            name="apellido"
            class="form-input"
            value="{{ old('apellido') }}"
            placeholder="Pérez"
            required
        >
        @error('apellido')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="telefono" class="form-label">Teléfono (Opcional)</label>
        <input
            type="tel"
            id="telefono"
            name="telefono"
            class="form-input"
            value="{{ old('telefono') }}"
            placeholder="+51 999 999 999"
        >
        @error('telefono')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="contrasena" class="form-label">Contraseña *</label>
        <input
            type="password"
            id="contrasena"
            name="contrasena"
            class="form-input"
            placeholder="Mínimo 6 caracteres"
            required
        >
        @error('contrasena')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="contrasena_confirmation" class="form-label">Confirmar Contraseña *</label>
        <input
            type="password"
            id="contrasena_confirmation"
            name="contrasena_confirmation"
            class="form-input"
            placeholder="Repite tu contraseña"
            required
        >
    </div>

    <button type="submit" class="btn-primary">
        Crear Cuenta
    </button>
</form>

<div class="auth-footer">
    <p>
        ¿Ya tienes cuenta? <a href="{{ url('/login') }}" class="auth-link">Inicia sesión aquí</a>
    </p>
</div>
@endsection
