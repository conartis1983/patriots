@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 text-xs">
    <div class="bg-white rounded shadow p-6">
        <h2 class="font-bold text-xl mb-6">Event bearbeiten</h2>
        <form method="POST" action="{{ route('admin.events.update', $event->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="title" class="block text-xs mb-1">Titel</label>
                <input type="text" id="title" name="title" value="{{ old('title', $event->title) }}" class="w-full border rounded px-2 py-1 text-xs" required>
            </div>

            <div class="mb-4">
                <label for="event_category_id" class="block text-xs mb-1">Kategorie</label>
                <select id="event_category_id" name="event_category_id" class="w-full border rounded px-2 py-1 text-xs">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @if(old('event_category_id', $event->event_category_id) == $category->id) selected @endif>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="event_type_id" class="block text-xs mb-1">Typ</label>
                <select id="event_type_id" name="event_type_id" class="w-full border rounded px-2 py-1 text-xs">
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" @if(old('event_type_id', $event->event_type_id) == $type->id) selected @endif>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="start" class="block text-xs mb-1">Start (Datum & Zeit)</label>
                <input type="datetime-local" id="start" name="start"
                       value="{{ old('start', \Carbon\Carbon::parse($event->start)->format('Y-m-d\TH:i')) }}"
                       class="w-full border rounded px-2 py-1 text-xs" required>
            </div>

            <div class="mb-4">
                <label for="location" class="block text-xs mb-1">Ort</label>
                <input type="text" id="location" name="location" value="{{ old('location', $event->location) }}" class="w-full border rounded px-2 py-1 text-xs">
            </div>

            <div class="mb-4">
                <label for="description" class="block text-xs mb-1">Beschreibung</label>
                <textarea id="description" name="description" class="w-full border rounded px-2 py-1 text-xs" rows="4">{{ old('description', $event->description) }}</textarea>
            </div>

            <div class="flex justify-center items-center">
                <x-primary-button type="submit" class="text-xs px-3 py-1 font-bold">Speichern</x-primary-button>
                <a href="{{ route('admin.events.index') }}" class="ml-3 text-neutral-500 hover:underline">Abbrechen</a>
            </div>
        </form>
    </div>
</div>
@endsection