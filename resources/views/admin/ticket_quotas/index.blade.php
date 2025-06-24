@extends('layouts.app')

@section('title', 'Ticketkontingente Übersicht')

@section('content')
<div class="max-w-4xl mx-auto mt-10 text-xs">
    <h2 class="text-xl font-bold mb-6">Ticketkontingente Übersicht</h2>

    <div class="flex justify-end mb-4">
        <x-primary-button class="text-xs px-2 py-1">
            <a href="{{ route('admin.ticket-quotas.create') }}" class="block w-full h-full">
                + Ticketkontingent erstellen
            </a>
        </x-primary-button>
    </div>

    <!-- Suchfeld -->
    <div class="mb-4">
        <input type="text" id="searchInput" placeholder="Suchen..." class="w-full border px-2 py-1 rounded text-xs" onkeyup="filterTable()">
    </div>

    @if(session('success'))
        <div class="mb-4 p-2 bg-green-200 text-green-600 rounded text-xs">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow rounded text-xs">
            <thead>
                <tr>
                    <th class="px-2 py-1 border-b">Event</th>
                    <th class="px-2 py-1 border-b">Kontingent</th>
                    <th class="px-2 py-1 border-b">€ Mitglied</th>
                    <th class="px-2 py-1 border-b">€ Nicht-Mitglied</th>
                    <th class="px-2 py-1 border-b">Fanclub-Anreise</th>
                    <th class="px-2 py-1 border-b">Preis Anreise</th>
                    <th class="px-2 py-1 border-b">Aktionen</th>
                </tr>
            </thead>
            <tbody>
            @forelse($ticketQuotas as $quota)
                <tr>
                    <td class="px-2 py-1 border-b">{{ $quota->event->title ?? '—' }}</td>
                    <td class="px-2 py-1 border-b text-center">{{ $quota->total_tickets }}</td>
                    <td class="px-2 py-1 border-b text-right">{{ number_format($quota->price_member,2,',','.') }}</td>
                    <td class="px-2 py-1 border-b text-right">{{ number_format($quota->price_non_member,2,',','.') }}</td>
                    <td class="px-2 py-1 border-b text-center">
                        @if($quota->fanclub_travel)
                            <span class="text-green-700 font-semibold">Ja</span>
                        @else
                            <span class="text-gray-500">Nein</span>
                        @endif
                    </td>
                    <td class="px-2 py-1 border-b text-right">
                        @if($quota->fanclub_travel)
                            {{ number_format($quota->fanclub_travel_price,2,',','.') }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="px-2 py-1 border-b text-center">
                        <x-gray-button class="mr-1 text-xs px-2 py-1">
                            <a href="{{ route('admin.ticket-quotas.edit', $quota) }}" class="block w-full h-full text-gray-800">Bearbeiten</a>
                        </x-gray-button>
                        <form action="{{ route('admin.ticket-quotas.destroy', $quota) }}" method="POST" class="inline-block" onsubmit="return confirm('Wirklich löschen?');">
                            @csrf
                            @method('DELETE')
                            <x-danger-button type="submit" class="text-xs px-2 py-1">Löschen</x-danger-button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-2 py-4 text-center text-gray-500">Noch keine Ticketkontingente angelegt.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <div class="mt-8 flex justify-end">
        <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline font-medium">Zurück zum Admin Dashboard</a>
        </div>
    </div>
</div>
@endsection