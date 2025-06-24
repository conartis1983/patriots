@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-8 bg-white rounded shadow p-6">
    <h2 class="font-bold text-lg mb-4">Neues Event erstellen</h2>
    <form method="POST" action="{{ route('admin.events.store') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm mb-1">Titel</label>
            <input name="title" class="w-full border rounded px-2 py-1 text-sm" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm mb-1">Beschreibung</label>
            <textarea name="description" class="w-full border rounded px-2 py-1 text-sm"></textarea>
        </div>
        <div class="mb-4">
            <label class="block text-sm mb-1">Kategorie</label>
            <select name="event_category_id" id="category" class="w-full border rounded px-2 py-1 text-sm" required>
                <option value="">Bitte wählen…</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm mb-1">Typ</label>
            <select name="event_type_id" id="type" class="w-full border rounded px-2 py-1 text-sm" required>
                <option value="">Bitte zuerst Kategorie wählen…</option>
                @foreach($categories as $cat)
                    @foreach($cat->types as $type)
                        <option value="{{ $type->id }}" data-category="{{ $cat->id }}">{{ $type->name }}</option>
                    @endforeach
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm mb-1">Beginn (Datum und Uhrzeit)</label>
            <input type="datetime-local" name="start" class="w-full border rounded px-2 py-1 text-sm" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm mb-1">Ort</label>
            <input name="location" class="w-full border rounded px-2 py-1 text-sm">
        </div>
        <div class="flex justify-end gap-4 mt-6">
            <x-primary-button type="submit">Speichern</x-primary-button>
            <a href="{{ route('admin.events.index') }}" class="text-neutral-500 hover:underline">Abbrechen</a>
        </div>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const catSel = document.getElementById('category');
    const typeSel = document.getElementById('type');
    function updateTypes() {
        const cat = catSel.value;
        Array.from(typeSel.options).forEach(opt => {
            if (!opt.value) return;
            opt.style.display = (opt.getAttribute('data-category') === cat) ? '' : 'none';
        });
        typeSel.value = '';
    }
    catSel.addEventListener('change', updateTypes);
    updateTypes();
});
</script>
@endsection