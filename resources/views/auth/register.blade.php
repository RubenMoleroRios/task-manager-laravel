@extends('layouts.app')

@section('title', 'Registro | Gestor de Tareas')
@section('headline', 'Crea tu cuenta')

@section('content')
<div class="auth-shell">
    <section class="panel auth-panel">
        <h2>Registro</h2>
        <p class="intro">Crea un usuario con nombre, email unico y contrasena.</p>

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <label for="name">Nombre</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" maxlength="255" autocomplete="name" required>

            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" maxlength="255" autocomplete="email" required>

            <label for="password">Contraseña</label>
            <input id="password" type="password" name="password" autocomplete="new-password" required>

            <div class="auth-form-actions">
                <button type="submit">Crear cuenta</button>
            </div>
        </form>
    </section>
</div>
@endsection