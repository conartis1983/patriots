@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="max-w-xl mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>
        <p>Willkommen im Admin-Bereich.</p>
        <ul class="mt-6 space-y-2 text-left">
            <li>– Nutzerverwaltung</li>
            <li>– Statistiken</li>
            <li>– Bestellungen einsehen</li>
            <li>– Einstellungen für Admins</li>
            <!-- Füge hier weitere Admin-Features hinzu -->
        </ul>
    </div>
@endsection