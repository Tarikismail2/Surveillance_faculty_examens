<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Surveillance des Examens - Faculté des Sciences El Jadida</title>
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/css/main.css', 'resources/js/main.js'])
    <style>
        /* Custom styles */
        .header-logo {
            max-height: 3rem;
        }
        .header-logo-container {
            margin-left: 4%;
        }
        .nav-container {
            margin-right: 4%;
        }
        .welcome-text {
            max-width: 60%;
        }
        .welcome-image {
            max-width: 35%;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-black dark:text-white/50">
    <div class="relative min-h-screen flex flex-col items-center justify-center">
        <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
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

            <!-- Hero Section -->
            <section id="hero" class="hero section">
                <img src="{{ asset('images/hero-bg.jpg') }}" alt="" data-aos="fade-in">
                <div class="container">
                    <h2 data-aos="fade-up" data-aos-delay="100">Welcome,<br>Bienvenue à la Plateforme pour la gestion de Surveillance des Examens</h2>
                    <p data-aos="fade-up" data-aos-delay="200">Cette Plateforme est dedié pour la gestion de la surveillance des examens au niveau de la faculté des sceiences el jadida université Chouaib Doukkali</p>
                    <div class="d-flex mt-4" data-aos="fade-up" data-aos-delay="300">
                        <a href="/dashboard" class="btn-get-started">Get Started</a>
                    </div>
                </div>
            </section>
            <!-- /Hero Section -->
        </div>
    </div>
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
