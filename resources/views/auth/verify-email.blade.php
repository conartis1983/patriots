@extends('layouts.app')

@section('title', 'E-Mail bestätigen | Patriots Österreich')

@section('content')
    <div class="w-full max-w-sm mx-auto bg-white/95 rounded-lg shadow border border-neutral-200 p-6 mt-8">
        <h2 class="text-xl font-bold mb-6 text-neutral-900 tracking-tight">E-Mail-Adresse bestätigen</h2>

        <div class="mb-4 text-sm text-gray-600">
            Danke für deine Anmeldung! Bevor es losgeht, bestätige bitte deine E-Mail-Adresse, indem du auf den Link klickst, den wir dir gerade geschickt haben.<br>
            Falls du die E-Mail nicht erhalten hast, kannst du dir eine neue senden lassen.
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 border border-green-200 rounded px-3 py-2">
                Ein neuer Bestätigungslink wurde an deine E-Mail-Adresse gesendet, die du bei der Registrierung angegeben hast.
            </div>
        @endif

        <div class="mt-4 flex flex-col gap-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full py-2 bg-red-700 text-white font-bold rounded shadow-sm hover:bg-red-800 transition text-sm tracking-wide uppercase">
                    Bestätigungs-E-Mail erneut senden
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full underline text-sm text-neutral-600 hover:text-red-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-700">
                    Logout
                </button>
            </form>
        </div>
    </div>
@endsection