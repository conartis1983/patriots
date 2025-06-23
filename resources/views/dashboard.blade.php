@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="max-w-xl mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-6">Willkommen im User Dashboard</h1>
        <p>Hier findest du deine persönlichen Informationen und Aktionen.</p>
        <ul class="mt-6 space-y-2 text-left">
            <li>– Profil bearbeiten</li>
            <li>– Bestellungen ansehen</li>
            <li>– Passwort ändern</li>
            <!-- Füge hier weitere User-Features hinzu -->
        </ul>
    </div>
@endsection