<!-- resources/views/layouts/guest.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom styles -->
    <style>
        body {
            background-color: #f0f2f5;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header-logo {
            max-height: 3rem;
        }
        .header-logo-container {
            margin-left: 4%;
        }
        .nav-container {
            margin-right: 4%;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <header class="d-flex justify-content-between align-items-center py-3 bg-white shadow-sm">
        <div class="d-flex align-items-center header-logo-container">
            <a href="{{ route('welcome') }}" class="d-flex align-items-center text-decoration-none">
                <img src="{{ asset('images/fslogo.png') }}" alt="Logo" class="header-logo">
                <span class="text-xl font-semibold ms-2 text-dark">SurveilUCD</span>
            </a>
        </div>
        <nav class="d-flex align-items-center nav-container">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-outline-dark me-2">Dashboard</a>
            @else
                <div class="d-flex">
                    <a href="{{ route('login') }}" class="btn btn-outline-dark me-2">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-outline-dark">Register</a>
                    @endif
                </div>
            @endauth
        </nav>
    </header>

    <!-- Main Content Section -->
    <main class="login-container">
        @yield('content')
    </main>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
