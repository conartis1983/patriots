<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Patriots Österreich</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-neutral-100 to-white min-h-screen flex flex-col">

    <!-- Navigation/Header -->
    <nav class="flex justify-between items-center px-8 bg-neutral-700 border-b border-neutral-800 shadow-sm" style="height:64px;">
        <div class="flex items-center space-x-3 h-full">
            <img src="{{ asset('images/logo.png') }}" alt="Patriots Österreich Logo" class="h-16 w-auto">
            <a href="{{ route('home') }}" class="text-2xl font-bold tracking-tight flex items-center">
                <span class="text-red-700">Patriots</span>
                <span class="text-white">&nbsp;Österreich</span>
            </a>
        </div>
        <div class="flex items-center h-full">
            @auth
                <span class="mr-4 text-white hidden sm:inline">Hallo, {{ Auth::user()->first_name }}!</span>
                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-red-700 text-white rounded hover:bg-red-800 transition">Zum Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 text-white hover:underline">Login</a>
                <a href="{{ route('register') }}" class="ml-2 px-4 py-2 bg-red-700 text-white rounded hover:bg-red-800 transition">Registrieren</a>
            @endauth
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col items-center justify-center text-center">
        <h1 class="text-4xl md:text-6xl font-extrabold text-neutral-900 mb-4 drop-shadow">
            <span class="text-neutral-700">Willkommen bei </span><span class="text-red-700">Patriots</span> <span class="text-neutral-700">Österreich!</span>
        </h1>
        <p class="text-lg text-neutral-700 mb-8 max-w-xl">
            est. 1998
        </p>
        @guest
            <a href="{{ route('register') }}" class="px-8 py-3 bg-red-700 text-white text-lg rounded shadow hover:bg-red-800 transition">
                Jetzt kostenlos registrieren
            </a>
        @endguest
    </main>

    <!-- Footer -->
    <footer class="text-center text-neutral-400 py-6">
        &copy; {{ date('Y') }} Patriots Österreich · Powered by Laravel
    </footer>
</body>
</html>