<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestor de Tareas')</title>
    @vite(['resources/css/tasks.css', 'resources/js/tasks.js'])
</head>

<body>
    <div class="container">
        <header class="topbar">
            <div>
                <p class="eyebrow">Task Manager</p>
                <h1>@yield('headline', 'Gestor de Tareas')</h1>
            </div>

            <div class="auth-actions">
                @auth
                <span class="auth-welcome">{{ auth()->user()->name }}</span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="button-link secondary-link">Cerrar sesión</button>
                </form>
                @else
                <a href="{{ route('register') }}" class="button-link secondary-link">Registrarse</a>
                <a href="{{ route('login') }}" class="button-link">Login</a>
                @endauth
            </div>
        </header>

        @if (session('status'))
        <div class="status">
            {{ session('status') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="errors">
            <strong>Errores de validacion:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @yield('content')
    </div>
</body>

</html>