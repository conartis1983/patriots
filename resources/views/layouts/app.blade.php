<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Patriots Österreich')</title>
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
                <span class="mr-3 text-white hidden sm:inline text-sm">Hallo, {{ Auth::user()->first_name }}!</span>

                <!-- Menü Dropdown per Klick mit schöner "Pop & Fade"-Animation (Alpine.js + Tailwind) -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @keydown.escape="open = false"
                        class="px-3 py-1 bg-red-700 text-white rounded-sm text-sm focus:outline-none focus:bg-red-800 transition flex items-center"
                        aria-haspopup="true" :aria-expanded="open.toString()">
                        Menü
                        <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div
                        x-cloak
                        x-show="open"
                        @click.away="open = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-90 -translate-y-2"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-90 -translate-y-2"
                        class="absolute right-0 mt-2 w-44 bg-white border border-neutral-300 rounded shadow-lg z-50"
                    >
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-neutral-800 hover:bg-neutral-100 text-sm">Dashboard</a>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-neutral-800 hover:bg-neutral-100 text-sm">Meine Daten</a>
                        <a href="" class="block px-4 py-2 text-neutral-800 hover:bg-neutral-100 text-sm">Meine Bestellungen</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left block px-4 py-2 text-neutral-800 hover:bg-neutral-100 text-sm">Logout</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="px-3 py-1 text-white hover:underline text-sm">Login</a>
                <a href="{{ route('register') }}" class="ml-2 px-3 py-1 bg-red-700 text-white rounded-sm hover:bg-red-800 transition text-sm">Registrieren</a>
            @endauth
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col items-center justify-center text-center">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="text-center text-neutral-400 py-6">
        &copy; {{ date('Y') }} Patriots Österreich · Powered by Laravel
    </footer>
</body>
</html>