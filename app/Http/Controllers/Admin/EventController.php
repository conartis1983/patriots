<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventType;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::with(['category', 'type'])->orderBy('start', 'desc')->get();
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = EventCategory::all();
        $types = EventType::all();
        return view('admin.events.create', compact('categories', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_category_id' => 'required|exists:event_categories,id',
            'event_type_id' => 'required|exists:event_types,id',
            'start' => 'required|date',
            'location' => 'nullable|string|max:255',
        ]);

        Event::create($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = Event::with(['category', 'type'])->findOrFail($id);
        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $event = Event::with(['category', 'type'])->findOrFail($id);
        $categories = EventCategory::all();
        $types = EventType::all();
        return view('admin.events.edit', compact('event', 'categories', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_category_id' => 'required|exists:event_categories,id',
            'event_type_id' => 'required|exists:event_types,id',
            'start' => 'required|date',
            'location' => 'nullable|string|max:255',
        ]);

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event erfolgreich aktualisiert.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event erfolgreich gelöscht.');
    }
}
