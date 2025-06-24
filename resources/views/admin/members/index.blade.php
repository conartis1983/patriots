@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 text-xs">
    <div class="bg-white rounded shadow p-6">
        <h2 class="font-bold text-xl mb-6">Mitgliederübersicht</h2>

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

            <table id="membersTable" class="min-w-full bg-white shadow rounded text-xs">
                <thead>
                    <tr>
                        <th class="px-2 py-1 cursor-pointer border-b" onclick="sortTable(0)">Vorname <span class="text-xs">↕</span></th>
                        <th class="px-2 py-1 cursor-pointer border-b" onclick="sortTable(1)">Nachname <span class="text-xs">↕</span></th>
                        <th class="px-2 py-1 cursor-pointer border-b" onclick="sortTable(2)">E-Mail <span class="text-xs">↕</span></th>
                        <th class="px-2 py-1 cursor-pointer border-b" onclick="sortTable(3)">Rolle <span class="text-xs">↕</span></th>
                        <th class="px-2 py-1 text-center cursor-pointer border-b" onclick="sortTable(4)">Beitrag {{ now()->year }} bezahlt? <span class="text-xs">↕</span></th>
                        <th class="px-2 py-1 border-b"></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    @php
                        $year = now()->year;
                        $fee = $user->membershipFees->firstWhere('year', $year);
                    @endphp
                    <tr class="border-t">
                        <td class="px-2 py-1">{{ $user->first_name }}</td>
                        <td class="px-2 py-1">{{ $user->last_name }}</td>
                        <td class="px-2 py-1">{{ $user->email }}</td>
                        <td class="px-2 py-1">
                            @if($user->is_admin)
                                <span class="inline-flex items-center text-blue-700">
                                    <!-- Schlüssel/Key-Icon -->
                                    <svg class="w-4 h-4 mr-1 text-blue-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="15" cy="9" r="3" stroke="currentColor" stroke-width="2" fill="none"/>
                                        <path d="M21 2l-8.5 8.5m0 0a5.5 5.5 0 1 0 7.78 7.78c1.53-1.53 1.53-4.01 0-5.54a5.5 5.5 0 0 0-7.78 0z" stroke="currentColor" stroke-width="2" fill="none"/>
                                    </svg>
                                    Admin
                                </span>
                            @else
                                <span class="inline-flex items-center text-gray-800">
                                    <!-- Benutzer/User-Icon -->
                                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2" fill="none"/>
                                        <path d="M4 20v-1a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v1" stroke="currentColor" stroke-width="2" fill="none"/>
                                    </svg>
                                    Mitglied
                                </span>
                            @endif
                        </td>
                        <td class="px-2 py-1 text-center">
                            @if($fee && $fee->not_needed)
                                <span style="color: #78350f; padding: 2px 8px; border-radius: 4px; font-weight: 600; font-size: 0.95em; display: inline-flex; align-items: center;">
                                    <!-- Info-Icon -->
                                    <svg style="width:1em;height:1em;margin-right:0.35em;vertical-align:-0.125em;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                                        <line x1="12" y1="8" x2="12" y2="8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        <line x1="12" y1="12" x2="12" y2="16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                    Nicht notwendig
                                </span>
                            @elseif($fee && $fee->paid)
                                <span style="color: #16a34a; font-weight: 600; font-size: 1.05em;">✓ Ja</span>
                            @else
                                <span style="color: #b91c1c; font-weight: 600; font-size: 1.05em;">✗ Nein</span>
                            @endif
                        </td>
                        <td class="px-2 py-1 text-center">
                            <x-gray-button class="mr-1 text-xs px-2 py-1">
                                <a href="{{ route('admin.members.edit', $user->id) }}" class="block w-full h-full text-gray-800">Bearbeiten</a>
                            </x-gray-button>
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
    let table = document.getElementById("membersTable");
    let trs = table.getElementsByTagName("tr");
    for (let i = 1; i < trs.length; i++) { // Zeile 0 ist thead
        let tds = trs[i].getElementsByTagName("td");
        let show = false;
        for (let j = 0; j < tds.length-1; j++) { // letzte Spalte (Bearbeiten) ignorieren
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
    let table = document.getElementById("membersTable");
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