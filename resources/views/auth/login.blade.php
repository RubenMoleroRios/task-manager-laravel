@extends('layouts.app')

@section('title', 'Login | Gestor de Tareas')
@section('headline', 'Accede a tu cuenta')

@section('content')
<div class="auth-shell">
    <section class="panel auth-panel">
        <h2>Login</h2>
        <p class="intro">Introduce tu email y tu contrasena para entrar en el gestor.</p>

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" maxlength="255" autocomplete="email" required>

            <label for="password">Contraseña</label>
            <input id="password" type="password" name="password" autocomplete="current-password" required>

            <div class="auth-form-actions">
                <button type="submit">Entrar</button>
            </div>
        </form>
    </section>
</div>
@endsection