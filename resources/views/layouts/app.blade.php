<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Patriots Österreich')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Alpine.js für Dropdowns -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles') <!-- DataTables und andere Styles einbinden -->
</head>
<body class="bg-gradient-to-br from-neutral-100 to-white min-h-screen flex flex-col">

    <!-- Navigation/Header -->
    <nav class="bg-neutral-700 border-b border-neutral-800 shadow-sm" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 flex justify-between items-center" style="height:64px;">
            <div class="flex items-center space-x-3 h-full">
                <img src="{{ asset('images/logo.png') }}" alt="Patriots Österreich Logo" class="h-16 w-auto">
                <a href="{{ route('home') }}" class="text-2xl font-bold tracking-tight flex items-center">
                    <span class="text-red-700">Patriots</span>
                    <span class="text-white">&nbsp;Österreich</span>
                </a>
            </div>
            <!-- Burger Button (mobil sichtbar) -->
            <button @click="open = !open" class="sm:hidden text-white focus:outline-none" aria-label="Menü öffnen">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 16h16"></path>
                </svg>
            </button>
            <!-- Desktop-Menü -->
            <div class="hidden sm:flex items-center h-full space-x-2">
                @auth
                    <span class="mr-3 text-white text-sm">Hallo, {{ Auth::user()->first_name }}!</span>
                    <!-- Dropdown-Menü für Auth -->
                    <div x-data="{ userMenu: false }" class="relative">
                        <button @click="userMenu = !userMenu" @keydown.escape="userMenu = false"
                                class="px-3 py-1 bg-red-700 text-white rounded-sm text-sm focus:outline-none focus:bg-red-800 transition flex items-center"
                                aria-haspopup="true" :aria-expanded="userMenu.toString()">
                            Menü
                            <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div
                            x-cloak
                            x-show="userMenu"
                            @click.away="userMenu = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-90 -translate-y-2"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-90 -translate-y-2"
                            class="absolute right-0 mt-2 w-44 bg-white border border-neutral-300 rounded shadow-lg z-50"
                        >
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-neutral-800 hover:bg-neutral-100 text-sm">Dashboard</a>
                            @if(Auth::user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-neutral-800 hover:bg-neutral-100 text-sm">Admin Dashboard</a>
                            @endif
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-neutral-800 hover:bg-neutral-100 text-sm">Meine Daten</a>
                            <a href="{{ route('ticket-orders.index') }}" class="block px-4 py-2 text-neutral-800 hover:bg-neutral-100 text-sm">Meine Bestellungen</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-neutral-800 hover:bg-neutral-100 text-sm">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div x-data="{ guestMenu: false }" class="relative">
                        <button @click="guestMenu = !guestMenu" @keydown.escape="guestMenu = false"
                                class="px-3 py-1 bg-red-700 text-white rounded-sm text-sm focus:outline-none focus:bg-red-800 transition flex items-center"
                                aria-haspopup="true" :aria-expanded="guestMenu.toString()">
                            Menü
                            <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div
                            x-cloak
                            x-show="guestMenu"
                            @click.away="guestMenu = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-90 -translate-y-2"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-90 -translate-y-2"
                            class="absolute right-0 mt-2 w-44 bg-white border border-neutral-300 rounded shadow-lg z-50"
                        >
                            <a href="{{ route('login') }}" class="block px-4 py-2 text-neutral-800 hover:bg-neutral-100 text-sm">Login</a>
                            <a href="{{ route('register') }}" class="block px-4 py-2 text-neutral-800 hover:bg-neutral-100 text-sm">Registrieren</a>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
        <!-- Mobile-Menü -->
        <div class="sm:hidden" x-show="open" x-cloak>
            <div class="bg-neutral-700 px-4 pb-4 pt-2 space-y-1">
                @auth
                    <span class="block text-white mb-2 text-sm">Hallo, {{ Auth::user()->first_name }}!</span>
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-white rounded hover:bg-neutral-600 text-sm">Dashboard</a>
                    @if(Auth::user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-white rounded hover:bg-neutral-600 text-sm">Admin Dashboard</a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-white rounded hover:bg-neutral-600 text-sm">Meine Daten</a>
                    <a href="#" class="block px-3 py-2 text-white rounded hover:bg-neutral-600 text-sm">Meine Bestellungen</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2 text-white rounded hover:bg-neutral-600 text-sm">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-white rounded hover:bg-neutral-600 text-sm">Login</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 text-white rounded hover:bg-red-700 text-sm">Registrieren</a>
                @endauth
            </div>
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
    @stack('scripts') <!-- DataTables und andere Scripts einbinden -->
</body>
</html>