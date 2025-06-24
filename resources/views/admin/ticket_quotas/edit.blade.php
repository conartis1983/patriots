@extends('layouts.app')

@section('title', 'Ticketkontingent bearbeiten')

@section('content')
<div class="max-w-md mx-auto mt-10 text-xs">
    <div class="bg-white rounded shadow p-6">
        <h2 class="text-xl font-bold mb-6">Ticketkontingent bearbeiten</h2>

        @if(session('success'))
            <div class="mb-4 p-2 bg-green-200 text-green-800 rounded text-xs text-center">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-2 bg-red-200 text-red-800 rounded text-xs text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.ticket-quotas.update', $ticketQuota) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Event (nicht änderbar) -->
            <div class="mb-4">
                <label class="block text-xs mb-1">Event</label>
                <div class="w-full border px-2 py-1 rounded bg-gray-100 text-gray-600 text-xs">
                    {{ $ticketQuota->event->title ?? '-' }} ({{ \Carbon\Carbon::parse($ticketQuota->event->start)->format('d.m.Y') }})
                </div>
                <input type="hidden" name="event_id" value="{{ $ticketQuota->event_id }}">
            </div>

            <div class="mb-4">
                <label for="total_tickets" class="block text-xs mb-1">Kontingent (Anzahl Tickets)</label>
                <input type="number" name="total_tickets" id="total_tickets" class="w-full border px-2 py-1 rounded text-xs" required min="1" value="{{ old('total_tickets', $ticketQuota->total_tickets) }}">
                @error('total_tickets')
                    <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="price_member" class="block text-xs mb-1">Preis für Mitglieder (€)</label>
                <input type="number" step="0.01" name="price_member" id="price_member" class="w-full border px-2 py-1 rounded text-xs" required value="{{ old('price_member', $ticketQuota->price_member) }}">
                @error('price_member')
                    <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="price_non_member" class="block text-xs mb-1">Preis für Nicht-Mitglieder (€)</label>
                <input type="number" step="0.01" name="price_non_member" id="price_non_member" class="w-full border px-2 py-1 rounded text-xs" required value="{{ old('price_non_member', $ticketQuota->price_non_member) }}">
                @error('price_non_member')
                    <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="inline-flex items-center text-xs">
                    <input type="checkbox" name="fanclub_travel" id="fanclub_travel" class="form-checkbox"
                        {{ old('fanclub_travel', $ticketQuota->fanclub_travel) ? 'checked' : '' }}>
                    <span class="ml-2">Anreise durch Fanclub organisiert</span>
                </label>
            </div>
            <div class="mb-4" id="fanclub_travel_price_container" style="display: {{ old('fanclub_travel', $ticketQuota->fanclub_travel) ? 'block' : 'none' }};">
                <label for="fanclub_travel_price" class="block text-xs mb-1">Preis für Anreise durch Fanclub (€)</label>
                <input type="number" step="0.01" name="fanclub_travel_price" id="fanclub_travel_price" class="w-full border px-2 py-1 rounded text-xs" value="{{ old('fanclub_travel_price', $ticketQuota->fanclub_travel_price) }}">
                @error('fanclub_travel_price')
                    <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex justify-center items-center">
                <x-primary-button type="submit" class="text-xs px-3 py-1 font-bold">
                    Änderungen speichern
                </x-primary-button>
                <a href="{{ route('admin.ticket-quotas.index') }}" class="ml-3 text-neutral-500 hover:underline">Abbrechen</a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('fanclub_travel').addEventListener('change', function() {
    document.getElementById('fanclub_travel_price_container').style.display = this.checked ? '' : 'none';
});
</script>
@endsection