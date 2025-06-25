@extends('layouts.app')

@section('title', 'Bestellung bearbeiten')

@section('content')
<div class="max-w-md mx-auto mt-10 text-xs">
    <div class="bg-white rounded shadow p-6">
        <h2 class="text-xl font-bold mb-6">Bestellung bearbeiten</h2>

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

        <form method="POST" action="{{ route('ticket-orders.update', $order) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block mb-1 text-xs font-bold">Event/Kontingent</label>
                <div class="p-2 bg-gray-100 rounded border text-xs">
                    {{ $ticketQuota->event->title ?? $ticketQuota->name ?? 'Event' }}
                </div>
            </div>

            <div class="mb-4">
                <label class="block mb-1 text-xs font-bold">Mitgliedertickets ({{ number_format($ticketQuota->price_member, 2, ',', '.') }} € pro Stück)</label>
                <select name="member_count" id="member_count" class="w-full border px-2 py-1 rounded text-xs" required>
                    @php
                        $ordered = $ticketQuota->ticketOrders->where('id', '!=', $order->id)->sum(fn($o) => $o->member_count + $o->non_member_count);
                        $remaining = $ticketQuota->total_tickets - $ordered;
                        $max_member = min(10, $remaining + $order->member_count + $order->non_member_count);
                    @endphp
                    @for($i = 1; $i <= $max_member; $i++)
                        <option value="{{ $i }}" {{ old('member_count', $order->member_count) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                @error('member_count')
                    <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block mb-1 text-xs font-bold">Nicht-Mitgliedertickets ({{ number_format($ticketQuota->price_non_member, 2, ',', '.') }} € pro Stück)</label>
                <select name="non_member_count" id="non_member_count" class="w-full border px-2 py-1 rounded text-xs" required>
                    @for($i = 0; $i <= $max_member; $i++)
                        <option value="{{ $i }}" {{ old('non_member_count', $order->non_member_count) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                @error('non_member_count')
                    <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            @if($ticketQuota->fanclub_travel)
            <div class="mb-4">
                <label class="block mb-1 text-xs font-bold">Fanclub-Anreise ({{ number_format($ticketQuota->fanclub_travel_price, 2, ',', '.') }} € pro Person)</label>
                <select name="travel_count" id="travel_count" class="w-full border px-2 py-1 rounded text-xs">
                    @php
                        $max_travel = (old('member_count', $order->member_count) + old('non_member_count', $order->non_member_count));
                    @endphp
                    @for($i = 0; $i <= $max_travel; $i++)
                        <option value="{{ $i }}" {{ old('travel_count', $order->travel_count ?? 0) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                @error('travel_count')
                    <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
            @endif

            <div class="flex justify-center items-center mt-4">
                <x-primary-button type="submit" class="text-xs px-3 py-1 font-bold">
                    Speichern
                </x-primary-button>
                <a href="{{ route('ticket-orders.index') }}" class="ml-3 text-neutral-500 hover:underline">Abbrechen</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateTravelOptions() {
        @if($ticketQuota->fanclub_travel)
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
        @endif
    }

    document.getElementById('member_count').addEventListener('change', updateTravelOptions);
    document.getElementById('non_member_count').addEventListener('change', updateTravelOptions);
    updateTravelOptions();
});
</script>
@endsection