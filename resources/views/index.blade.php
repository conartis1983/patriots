@extends('layouts.app')

@section('title', 'Patriots Österreich - Startseite')

@section('content')
    <h1 class="text-4xl md:text-6xl font-extrabold text-neutral-900 mb-4 drop-shadow">
        <span class="text-neutral-700">Willkommen bei den <span class="text-red-700">Patriots</span> Österreich!</span>
    </h1>
    <p class="text-lg text-neutral-700 mb-8 max-w-xl">
       est. 1998<br>
    </p>
    @guest
        <a href="{{ route('register') }}" class="px-8 py-3 bg-red-700 text-white text-lg rounded shadow hover:bg-red-800 transition">
            Jetzt kostenlos registrieren
        </a>
    @endguest
@endsection