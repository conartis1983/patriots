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
        <x-alert type="success">
            {{ session('success') }}
        </x-alert>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow rounded text-xs" id="ticketQuotaTable">
            <thead>
                <tr>
                    <th class="px-2 py-1 border-b">Event</th>
                    <th class="px-2 py-1 border-b text-center">Kontingent</th>
                    <th class="px-2 py-1 border-b text-center">Bestellschluss</th>
                    <th class="px-2 py-1 border-b text-center">Status</th>
                    <th class="px-2 py-1 border-b text-right">€ Mitglied</th>
                    <th class="px-2 py-1 border-b text-right">€ Nicht-Mitglied</th>
                    <th class="px-2 py-1 border-b text-center">Fanclub-Anreise</th>
                    <th class="px-2 py-1 border-b text-right">Preis Anreise</th>
                    <th class="px-2 py-1 border-b text-center">Aktionen</th>
                </tr>
            </thead>
            <tbody>
            @forelse($ticketQuotas as $quota)
                @php
                    $today = \Carbon\Carbon::today();
                    $expiresAt = $quota->expires_at ? \Carbon\Carbon::parse($quota->expires_at) : null;
                    // Für Status "aufgebraucht": Passe die nächste Zeile an, wenn du eine Buchungen-Relation hast!
                    $usedTickets = $quota->used_tickets ?? 0; // Setze dies ggf. durch Relation/Tabelle
                    $isSoldOut = ($usedTickets >= $quota->total_tickets);
                    $isClosed = $expiresAt && $expiresAt->lt($today);
                @endphp
                <tr>
                    <td class="px-2 py-1 border-b">{{ $quota->event->title ?? '—' }}</td>
                    <td class="px-2 py-1 border-b text-center">{{ $quota->total_tickets }}</td>
                    <td class="px-2 py-1 border-b text-center">
                        {{ $quota->expires_at ? \Carbon\Carbon::parse($quota->expires_at)->format('d.m.Y') : '—' }}
                    </td>
                    <td class="px-2 py-1 border-b text-center">
                        @if($isSoldOut)
                            <span class="px-2 py-1 rounded text-red-700 bg-red-100 text-xs">aufgebraucht</span>
                        @elseif($isClosed)
                            <span class="px-2 py-1 rounded text-gray-700 bg-gray-100 text-xs">abgeschlossen</span>
                        @else
                            <span class="px-2 py-1 rounded text-green-700 bg-green-100 text-xs">aktiv</span>
                        @endif
                    </td>
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
                    <td colspan="9" class="px-2 py-4 text-center text-gray-500">Noch keine Ticketkontingente angelegt.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <div class="mt-8 flex justify-end">
        <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline font-medium">Zurück zum Admin Dashboard</a>
        </div>
    </div>
</div>

<!-- Live-Suche Script -->
<script>
function filterTable() {
    let input = document.getElementById("searchInput");
    let filter = input.value.toLowerCase();
    let table = document.getElementById("ticketQuotaTable");
    let trs = table.getElementsByTagName("tr");
    for (let i = 1; i < trs.length; i++) { // Zeile 0 ist thead
        let tds = trs[i].getElementsByTagName("td");
        let show = false;
        for (let j = 0; j < tds.length-1; j++) { // letzte Spalte (Aktionen) ignorieren
            if (tds[j] && tds[j].innerText.toLowerCase().indexOf(filter) > -1) {
                show = true;
            }
        }
        trs[i].style.display = show ? "" : "none";
    }
}
</script>
@endsection