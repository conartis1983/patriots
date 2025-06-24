@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 text-xs">
    <div class="bg-white rounded shadow p-6">
        <h2 class="font-bold text-xl mb-6">Eventübersicht</h2>

        <!-- Button zum Erstellen eines neuen Events -->
        <div class="flex justify-end mb-4">
            <x-primary-button class="text-xs px-2 py-1 font-bold">
                <a href="{{ route('admin.events.create') }}" class="block w-full h-full">+ Event erstellen</a>
            </x-primary-button>
        </div>

        <!-- Suchfeld -->
        <div class="mb-4">
            <input type="text" id="searchInput" placeholder="Suchen..." class="w-full border px-2 py-1 rounded text-xs" onkeyup="filterTable()">
        </div>

        <div class="overflow-x-auto">
            @if(session('success'))
                <div class="mb-4 p-2 bg-green-200 text-green-600 rounded text-xs text-center">
                    {{ session('success') }}
                </div>
            @endif
            <table id="eventsTable" class="min-w-full bg-white shadow rounded text-xs">
                <thead>
                    <tr>
                        <th class="px-2 py-1 cursor-pointer border-b" onclick="sortTable(0)">Titel <span class="text-xs">↕</span></th>
                        <th class="px-2 py-1 cursor-pointer border-b" onclick="sortTable(1)">Kategorie <span class="text-xs">↕</span></th>
                        <th class="px-2 py-1 cursor-pointer border-b" onclick="sortTable(2)">Typ <span class="text-xs">↕</span></th>
                        <th class="px-2 py-1 cursor-pointer border-b" onclick="sortTable(3)">Datum <span class="text-xs">↕</span></th>
                        <th class="px-2 py-1 cursor-pointer border-b" onclick="sortTable(4)">Uhrzeit <span class="text-xs">↕</span></th>
                        <th class="px-2 py-1 cursor-pointer border-b" onclick="sortTable(5)">Ort <span class="text-xs">↕</span></th>
                        <th class="px-2 py-1 border-b">Status</th>
                        <th class="px-2 py-1 border-b"></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($events as $event)
                    <tr class="border-t">
                        <td class="px-2 py-1">{{ $event->title }}</td>
                        <td class="px-2 py-1">{{ $event->category->name ?? '' }}</td>
                        <td class="px-2 py-1">{{ $event->type->name ?? '' }}</td>
                        <td class="px-2 py-1">
                            {{ \Carbon\Carbon::parse($event->start)->format('d.m.Y') }}
                        </td>
                        <td class="px-2 py-1">
                            {{ \Carbon\Carbon::parse($event->start)->format('H:i') }}
                        </td>
                        <td class="px-2 py-1">{{ $event->location }}</td>
                        <td class="px-2 py-1">
                            @php
                                $start = \Carbon\Carbon::parse($event->start);
                                $heute = \Carbon\Carbon::today();
                                $istAktiv = $start->gte($heute);
                            @endphp
                            @if($istAktiv)
                                <span class="px-2 py-1 rounded text-green-700 bg-green-100 text-xs">aktiv</span>
                            @else
                                <span class="px-2 py-1 rounded text-gray-600 bg-gray-100 text-xs">abgelaufen</span>
                            @endif
                        </td>
                        <td class="px-2 py-1 text-center">
                            <x-gray-button class="mr-1 text-xs px-2 py-1">
                                <a href="{{ route('admin.events.edit', $event->id) }}" class="block w-full h-full text-gray-800">Bearbeiten</a>
                            </x-gray-button>
                            <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Wirklich löschen?');">
                                @csrf
                                @method('DELETE')
                                <x-danger-button type="submit" class="text-xs px-2 py-1">Löschen</x-danger-button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Link zurück zum Admin Dashboard -->
        <div class="mt-8 flex justify-end">
            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline font-medium">Zurück zum Admin Dashboard</a>
        </div>
    </div>
</div>

<!-- Live-Suche und Sortier-Script -->
<script>
function filterTable() {
    let input = document.getElementById("searchInput");
    let filter = input.value.toLowerCase();
    let table = document.getElementById("eventsTable");
    let trs = table.getElementsByTagName("tr");
    for (let i = 1; i < trs.length; i++) { // Zeile 0 ist thead
        let tds = trs[i].getElementsByTagName("td");
        let show = false;
        for (let j = 0; j < tds.length-1; j++) { // letzte Spalte (Bearbeiten/Löschen) ignorieren
            if (tds[j] && tds[j].innerText.toLowerCase().indexOf(filter) > -1) {
                show = true;
            }
        }
        trs[i].style.display = show ? "" : "none";
    }
}

// Einfache Sortierung (ASC/DESC toggle)
let sortDirection = {};
function sortTable(n) {
    let table = document.getElementById("eventsTable");
    let rows = Array.from(table.rows).slice(1); // skip thead
    let dir = sortDirection[n] === "asc" ? "desc" : "asc";
    sortDirection[n] = dir;

    rows.sort(function(a, b) {
        let x = a.cells[n].innerText.toLowerCase();
        let y = b.cells[n].innerText.toLowerCase();
        if (dir === "asc") {
            return x.localeCompare(y, 'de', {numeric:true});
        } else {
            return y.localeCompare(x, 'de', {numeric:true});
        }
    });
    // Neu einsortieren
    for (let i = 0; i < rows.length; i++) {
        table.tBodies[0].appendChild(rows[i]);
    }
}
</script>
@endsection