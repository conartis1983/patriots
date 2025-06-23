@extends('layouts.app')

@section('title', 'Registrieren | Patriots Österreich')

@section('content')
    <div class="w-full max-w-sm mx-auto bg-white/95 rounded-lg shadow border border-neutral-200 p-6 mt-8">
        <h2 class="text-xl font-bold mb-6 text-neutral-900 tracking-tight">Registrieren</h2>
        @if ($errors->any())
            <div class="mb-4 text-red-600 rounded bg-red-50 px-3 py-2 text-xs border border-red-200">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <div>
                <label for="first_name" class="block text-xs font-semibold mb-1 uppercase text-neutral-700">Vorname</label>
                <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required autofocus
                    class="w-full px-3 py-2 border border-neutral-300 rounded focus:ring-2 focus:ring-red-700 focus:border-red-700 outline-none transition text-sm bg-neutral-100 font-mono" autocomplete="given-name">
            </div>
            <div>
                <label for="last_name" class="block text-xs font-semibold mb-1 uppercase text-neutral-700">Nachname</label>
                <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required
                    class="w-full px-3 py-2 border border-neutral-300 rounded focus:ring-2 focus:ring-red-700 focus:border-red-700 outline-none transition text-sm bg-neutral-100 font-mono" autocomplete="family-name">
            </div>
            <div>
                <label for="email" class="block text-xs font-semibold mb-1 uppercase text-neutral-700">E-Mail</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-3 py-2 border border-neutral-300 rounded focus:ring-2 focus:ring-red-700 focus:border-red-700 outline-none transition text-sm bg-neutral-100 font-mono" autocomplete="email">
            </div>
            <div>
                <label for="password" class="block text-xs font-semibold mb-1 uppercase text-neutral-700">Passwort</label>
                <input id="password" type="password" name="password" required
                    class="w-full px-3 py-2 border border-neutral-300 rounded focus:ring-2 focus:ring-red-700 focus:border-red-700 outline-none transition text-sm bg-neutral-100 font-mono" autocomplete="new-password">
            </div>
            <div>
                <label for="password_confirmation" class="block text-xs font-semibold mb-1 uppercase text-neutral-700">Passwort bestätigen</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    class="w-full px-3 py-2 border border-neutral-300 rounded focus:ring-2 focus:ring-red-700 focus:border-red-700 outline-none transition text-sm bg-neutral-100 font-mono" autocomplete="new-password">
            </div>
            <button type="submit" class="w-full py-2 bg-red-700 text-white font-bold rounded shadow-sm hover:bg-red-800 transition text-sm tracking-wide uppercase mt-1">
                Registrieren
            </button>
        </form>
        <p class="mt-6 text-center text-neutral-500 text-xs">
            Bereits Mitglied?
            <a href="{{ route('login') }}" class="text-red-700 hover:underline font-semibold">Hier einloggen</a>
        </p>
    </div>
@endsection