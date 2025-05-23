<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Исчезающие виды Чеченской Республики')</title>
    <!-- Шрифты -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Основные стили -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Дополнительные стили -->
    @yield('styles')
</head>
<body>
    <nav class="main-nav">
        <div class="container">
            <a href="{{ route('home') }}" class="nav-logo">Флора и фауна Чечни</a>
            <div class="nav-links">
                <a href="{{ route('home') }}">Главная</a>
                <a href="{{ route('map') }}">Карта регионов</a>
                @auth
                    <a href="{{ route('admin.index') }}">Админ-панель</a>
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit" class="btn-link">Выйти</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Войти</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="main-content">
        @yield('content')
    </main>

    <footer class="main-footer">
        <div class="container">
            <p>© {{ date('Y') }} Исчезающие виды Чеченской Республики</p>
        </div>
    </footer>

    <!-- Основные скрипты -->
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- Дополнительные скрипты -->
    @yield('scripts')
</body>
</html>