@extends('layouts.app')

@section('title', 'Login | Patriots Österreich')

@section('content')
    <div class="w-full max-w-sm mx-auto bg-white/95 rounded-lg shadow border border-neutral-200 p-6 mt-8">
        <h2 class="text-xl font-bold mb-6 text-neutral-900 tracking-tight">Login</h2>
        @if ($errors->any())
            <div class="mb-4 text-red-600 rounded bg-red-50 px-3 py-2 text-xs border border-red-200">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-semibold mb-1 uppercase text-neutral-700">E-Mail</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-3 py-2 border border-neutral-300 rounded focus:ring-2 focus:ring-red-700 focus:border-red-700 outline-none transition text-sm bg-neutral-100 font-mono" autocomplete="email">
            </div>
            <div>
                <label for="password" class="block text-xs font-semibold mb-1 uppercase text-neutral-700">Passwort</label>
                <input id="password" type="password" name="password" required
                    class="w-full px-3 py-2 border border-neutral-300 rounded focus:ring-2 focus:ring-red-700 focus:border-red-700 outline-none transition text-sm bg-neutral-100 font-mono" autocomplete="current-password">
            </div>
            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center select-none">
                    <input type="checkbox" name="remember" class="rounded border-neutral-300 mr-2">
                    <span class="text-neutral-700">Angemeldet bleiben</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-red-700 hover:underline">Passwort vergessen?</a>
            </div>
            <button type="submit" class="w-full py-2 bg-red-700 text-white font-bold rounded shadow-sm hover:bg-red-800 transition text-sm tracking-wide uppercase mt-1">
                Login
            </button>
        </form>
        <p class="mt-6 text-center text-neutral-500 text-xs">
            Noch kein Account?
            <a href="{{ route('register') }}" class="text-red-700 hover:underline font-semibold">Jetzt registrieren</a>
        </p>
    </div>
@endsection