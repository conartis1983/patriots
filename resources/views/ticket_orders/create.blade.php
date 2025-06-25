@extends('layouts.app')

@section('title', 'Ticket bestellen')

@section('content')
<div class="max-w-md mx-auto mt-10 text-xs">
    <div class="bg-white rounded shadow p-6">

        @if(session('success'))
            <x-alert type="success">
                {{ session('success') }}
            </x-alert>
        @endif

        @if($errors->any())
            <x-alert type="error">
                {{ $errors->first() }}
            </x-alert>
        @endif

        @if(isset($preselectedQuota) && $preselectedQuota)
        <h2 class="text-xl font-bold mb-6">Neue Ticket-Bestellung</h2>
        <form method="POST" action="{{ route('ticket-orders.store') }}" id="ticketOrderForm">
            @csrf

            <div class="mb-4">
                <label class="block text-xs mb-1 font-bold">Event/Kontingent</label>
                <div class="py-2 font-semibold">
                    {{ $preselectedQuota->event->title ?? '—' }}
                    &ndash; noch {{ $preselectedQuota->total_tickets - $preselectedQuota->ticketOrders->sum(fn($o) => $o->member_count + $o->non_member_count) }}
                    Tickets &ndash; Bestellschluss: {{ \Carbon\Carbon::parse($preselectedQuota->expires_at)->format('d.m.Y') }}
                </div>
                <input type="hidden" name="ticket_quota_id" value="{{ $preselectedQuota->id }}">
                <input type="hidden"
                    id="hidden_quota_data"
                    data-member-price="{{ $preselectedQuota->price_member }}"
                    data-nonmember-price="{{ $preselectedQuota->price_non_member }}"
                    data-fanclub="{{ $preselectedQuota->fanclub_travel ? '1' : '0' }}"
                    data-fanclub-price="{{ $preselectedQuota->fanclub_travel_price }}"
                    data-remaining="{{ $preselectedQuota->total_tickets - $preselectedQuota->ticketOrders->sum(fn($o) => $o->member_count + $o->non_member_count) }}"
                    data-expires="{{ \Carbon\Carbon::parse($preselectedQuota->expires_at) }}"
                >
            </div>

            <div id="dynamic-fields">
                <div class="mb-4">
                    <label class="block mb-1 text-xs font-bold">Mitgliedertickets (<span id="member_price">0,00</span> € pro Stück)</label>
                    <select name="member_count" id="member_count" class="w-full border px-2 py-1 rounded text-xs" required>
                    </select>
                    @error('member_count')
                        <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block mb-1 text-xs font-bold">Nicht-Mitgliedertickets (<span id="nonmember_price">0,00</span> € pro Stück)</label>
                    <select name="non_member_count" id="non_member_count" class="w-full border px-2 py-1 rounded text-xs" required>
                    </select>
                    @error('non_member_count')
                        <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4" id="fanclub_travel_field" style="display:none;">
                    <label class="block mb-1 text-xs font-bold">Fanclub-Anreise (<span id="fanclub_price">0,00</span> € pro Person)</label>
                    <select name="travel_count" id="travel_count" class="w-full border px-2 py-1 rounded text-xs"></select>
                    @error('travel_count')
                        <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="text-xs text-gray-600 mb-2" id="verfuegbar_info"></div>
                <div class="font-bold text-base mb-2">
                    Gesamtpreis: <span id="total_price">0,00</span> €
                </div>
            </div>

            <div class="flex justify-center items-center mt-4">
                <x-primary-button type="submit" class="text-xs px-3 py-1 font-bold">
                    Bestellen
                </x-primary-button>
                <a href="{{ route('ticket-orders.index') }}" class="ml-3 text-neutral-500 hover:underline">Abbrechen</a>
            </div>
        </form>
        @else
            <div class="text-center py-8 text-red-600 font-bold">
                Es ist kein Event vorausgewählt oder verfügbar.<br>
                <a href="{{ route('ticket-orders.index') }}" class="underline text-blue-600">Zurück zur Übersicht</a>
            </div>
        @endif
    </div>
</div>

<script>
function updateDynamicFields() {
    const hidden = document.getElementById('hidden_quota_data');
    const memberPrice = parseFloat(hidden.dataset.memberPrice || 0);
    const nonMemberPrice = parseFloat(hidden.dataset.nonmemberPrice || 0);
    const fanclub = hidden.dataset.fanclub === "1";
    const fanclubPrice = parseFloat(hidden.dataset.fanclubPrice || 0);
    const remaining = parseInt(hidden.dataset.remaining || 0);

    document.getElementById('member_price').innerText = memberPrice.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('nonmember_price').innerText = nonMemberPrice.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('fanclub_price').innerText = fanclubPrice.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2});

    // Mitgliederticket Optionen
    const memberSelect = document.getElementById('member_count');
    memberSelect.innerHTML = '';
    for (let i = 1; i <= Math.min(10, remaining); i++) {
        let o = document.createElement('option');
        o.value = i;
        o.text = i;
        memberSelect.appendChild(o);
    }
    // Nichtmitgliederticket Optionen
    const nonMemberSelect = document.getElementById('non_member_count');
    nonMemberSelect.innerHTML = '';
    for (let i = 0; i <= Math.min(10, remaining); i++) {
        let o = document.createElement('option');
        o.value = i;
        o.text = i;
        nonMemberSelect.appendChild(o);
    }
    // Fanclub-Anreise
    if (fanclub) {
        document.getElementById('fanclub_travel_field').style.display = '';
        updateTravelOptions();
    } else {
        document.getElementById('fanclub_travel_field').style.display = 'none';
    }

    document.getElementById('verfuegbar_info').innerHTML = "Noch verfügbar: <span class='font-bold'>" + remaining + "</span> Tickets.";
    updateTotalPrice();
}

function updateTravelOptions() {
    const memberCount = parseInt(document.getElementById('member_count').value) || 0;
    const nonMemberCount = parseInt(document.getElementById('non_member_count').value) || 0;
    const maxTravel = memberCount + nonMemberCount;
    const travelSelect = document.getElementById('travel_count');
    let oldValue = parseInt(travelSelect.value) || 0;
    travelSelect.innerHTML = '';
    for(let i = 0; i <= maxTravel; i++) {
        let option = document.createElement('option');
        option.value = i;
        option.text = i;
        if(i === oldValue) option.selected = true;
        travelSelect.appendChild(option);
    }
}

function updateTotalPrice() {
    const hidden = document.getElementById('hidden_quota_data');
    const memberPrice = parseFloat(hidden.dataset.memberPrice || 0);
    const nonMemberPrice = parseFloat(hidden.dataset.nonmemberPrice || 0);
    const fanclub = hidden.dataset.fanclub === "1";
    const fanclubPrice = parseFloat(hidden.dataset.fanclubPrice || 0);

    const memberCount = parseInt(document.getElementById('member_count').value) || 0;
    const nonMemberCount = parseInt(document.getElementById('non_member_count').value) || 0;

    let sum = (memberCount * memberPrice) + (nonMemberCount * nonMemberPrice);

    if (fanclub && document.getElementById('fanclub_travel_field').style.display !== 'none') {
        const travelCount = parseInt(document.getElementById('travel_count').value) || 0;
        sum += travelCount * fanclubPrice;
    }

    document.getElementById('total_price').innerText = sum.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}

document.addEventListener('DOMContentLoaded', function() {
    if(document.getElementById('hidden_quota_data')){
        updateDynamicFields();
    }

    // Event-Listener erst nach dem Initialisieren setzen!
    document.getElementById('member_count').addEventListener('change', function(){
        updateTotalPrice();
        if(document.getElementById('fanclub_travel_field').style.display !== 'none'){
            updateTravelOptions();
            updateTotalPrice();
        }
    });
    document.getElementById('non_member_count').addEventListener('change', function(){
        updateTotalPrice();
        if(document.getElementById('fanclub_travel_field').style.display !== 'none'){
            updateTravelOptions();
            updateTotalPrice();
        }
    });
    if(document.getElementById('travel_count')){
        document.getElementById('travel_count').addEventListener('change', updateTotalPrice);
    }
});
</script>
@endsection