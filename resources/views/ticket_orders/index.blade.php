@extends('layouts.app')

@section('title', 'Meine Ticket-Bestellungen')

@section('content')
<div class="max-w-4xl mx-auto mt-10 text-xs">

    @if(session('success'))
        <x-alert type="success">
            {{ session('success') }}
        </x-alert>
    @endif

    <h2 class="text-xl font-bold mb-6">Meine Ticket-Bestellungen</h2>

    <!-- Suchfeld -->
    <div class="mb-4">
        <input type="text" id="searchInput" placeholder="Suchen..." class="w-full border px-2 py-1 rounded text-xs" onkeyup="filterTables()">
    </div>

    {{-- Verfügbare Tickets --}}
    <div class="bg-white rounded-lg shadow p-4 mb-8">
        <h3 class="text-base font-bold mb-4">Verfügbare Tickets</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs" id="table-available" style="border-collapse: separate; border-spacing: 0;">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-3 py-2 font-semibold text-left">Event</th>
                        <th class="px-3 py-2 font-semibold text-center">Bestellschluss</th>
                        <th class="px-3 py-2 font-semibold text-center">Verfügbar</th>
                        <th class="px-3 py-2 font-semibold text-center">Aktion</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($availableQuotas as $quota)
                    @php
                        $ordered = $quota->ticketOrders->sum(fn($o) => $o->member_count + $o->non_member_count);
                        $remaining = $quota->total_tickets - $ordered;
                    @endphp
                    <tr class="border-b last:border-0">
                        <td class="px-3 py-2 align-middle text-left">{{ $quota->event->title ?? '—' }}</td>
                        <td class="px-3 py-2 text-center align-middle">
                            {{ $quota->expires_at ? \Carbon\Carbon::parse($quota->expires_at)->format('d.m.Y') : '—' }}
                        </td>
                        <td class="px-3 py-2 text-center align-middle">{{ $remaining }}</td>
                        <td class="px-3 py-2 text-center align-middle">
                            <x-primary-button class="text-xs px-2 py-1">
                                <a href="{{ route('ticket-orders.create', ['ticket_quota_id' => $quota->id]) }}" class="block w-full h-full">
                                Bestellen
                                </a>
                            </x-primary-button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-3 py-4 text-center text-gray-500">Du hast bereits für alle aktuellen Events bestellt.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Aktuelle Bestellungen --}}
    <div class="bg-white rounded-lg shadow p-4 mb-8">
        <h3 class="text-base font-bold mb-4">Aktuelle Bestellungen</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs" id="table-current" style="border-collapse: separate; border-spacing: 0;">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-3 py-2 font-semibold text-left">Event</th>
                        <th class="px-3 py-2 font-semibold text-center">Bestelldatum</th>
                        <th class="px-3 py-2 font-semibold text-center">Mitglieder</th>
                        <th class="px-3 py-2 font-semibold text-center">Nicht-Mitglieder</th>
                        <th class="px-3 py-2 font-semibold text-center">Fanclub-Anreise</th>
                        <th class="px-3 py-2 font-semibold text-center">Status</th>
                        <th class="px-3 py-2 font-semibold text-right">Gesamtpreis</th>
                        <th class="px-3 py-2 font-semibold text-center">Aktion</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($currentOrders as $order)
                    @php
                        $quota = $order->ticketQuota;
                    @endphp
                    <tr class="border-b last:border-0">
                        <td class="px-3 py-2 align-middle text-left">{{ $quota->event->title ?? '—' }}</td>
                        <td class="px-3 py-2 text-center align-middle">{{ $order->created_at->format('d.m.Y') }}</td>
                        <td class="px-3 py-2 text-center align-middle">{{ $order->member_count }}</td>
                        <td class="px-3 py-2 text-center align-middle">{{ $order->non_member_count }}</td>
                        <td class="px-3 py-2 text-center align-middle">
                            @if($quota->fanclub_travel && $order->travel_count)
                                <span class="text-gray-500">Ja ({{ $order->travel_count }})</span>
                            @else
                                <span class="text-gray-500">Nein</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-center align-middle">
                            @if($order->confirmed)
                                <span class="px-2 py-1 rounded bg-green-100 text-green-700 font-semibold">bestätigt</span>
                            @else
                                <span class="px-2 py-1 rounded bg-gray-100 text-gray-700 font-semibold">offen</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-right align-middle">{{ number_format($order->total_price, 2, ',', '.') }} €</td>
                        <td class="px-3 py-2 text-center align-middle whitespace-nowrap">
                            <x-gray-button class="mr-1 text-xs px-2 py-1">
                                <a href="{{ route('ticket-orders.edit', $order) }}" class="block w-full h-full text-gray-800">Bearbeiten</a>
                            </x-gray-button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-3 py-4 text-center text-gray-500">Keine aktuellen Bestellungen vorhanden.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Vergangene Bestellungen --}}
    <div class="bg-white rounded-lg shadow p-4 mb-8">
        <h3 class="text-base font-bold mb-4">Vergangene Bestellungen</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs" id="table-past" style="border-collapse: separate; border-spacing: 0;">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-3 py-2 font-semibold text-left">Event</th>
                        <th class="px-3 py-2 font-semibold text-center">Bestelldatum</th>
                        <th class="px-3 py-2 font-semibold text-center">Mitglieder</th>
                        <th class="px-3 py-2 font-semibold text-center">Nicht-Mitglieder</th>
                        <th class="px-3 py-2 font-semibold text-center">Fanclub-Anreise</th>
                        <th class="px-3 py-2 font-semibold text-right">Gesamtpreis</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($pastOrders as $order)
                    @php $quota = $order->ticketQuota; @endphp
                    <tr class="border-b last:border-0">
                        <td class="px-3 py-2 align-middle text-left">{{ $quota->event->title ?? '—' }}</td>
                        <td class="px-3 py-2 text-center align-middle">{{ $order->created_at->format('d.m.Y') }}</td>
                        <td class="px-3 py-2 text-center align-middle">{{ $order->member_count }}</td>
                        <td class="px-3 py-2 text-center align-middle">{{ $order->non_member_count }}</td>
                        <td class="px-3 py-2 text-center align-middle">
                            @if($quota->fanclub_travel && $order->travel_count)
                                <span class="text-gray-500">Ja ({{ $order->travel_count }})</span>
                            @else
                                <span class="text-gray-500">Nein</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-right align-middle">{{ number_format($order->total_price, 2, ',', '.') }} €</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-3 py-4 text-center text-gray-500">Keine vergangenen Bestellungen vorhanden.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8 flex justify-end">
        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline font-medium">Zurück zum Dashboard</a>
    </div>
</div>

<script>
function filterTables() {
    let filter = document.getElementById("searchInput").value.toLowerCase();
    ["table-available", "table-current", "table-past"].forEach(function(tblId) {
        let table = document.getElementById(tblId);
        if(!table) return;
        let trs = table.getElementsByTagName("tr");
        for (let i = 1; i < trs.length; i++) { // Zeile 0 ist thead
            let tds = trs[i].getElementsByTagName("td");
            let show = false;
            for (let j = 0; j < tds.length; j++) {
                if (tds[j] && tds[j].innerText.toLowerCase().indexOf(filter) > -1) {
                    show = true;
                }
            }
            trs[i].style.display = show ? "" : "none";
        }
    });
}
</script>
@endsection