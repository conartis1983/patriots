<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TicketQuota;
use Illuminate\Http\Request;

class TicketQuotaController extends Controller
{
    public function index()
    {
        $ticketQuotas = TicketQuota::with('event')->get();
        return view('admin.ticket_quotas.index', compact('ticketQuotas'));
    }

    public function create()
    {
        // Nur aktive Events mit Kategorie "Ländermatch",
        // für die es noch KEIN Ticketkontingent gibt
        $events = Event::whereHas('category', function($q) {
                $q->where('name', 'Ländermatch');
            })
            ->whereDate('start', '>=', now())
            ->whereDoesntHave('ticketQuotas')
            ->get();

        return view('admin.ticket_quotas.create', compact('events'));
    }

    public function store(Request $request)
    {
        // Checkboxen benötigen eine Konvertierung!
        $request->merge([
            'fanclub_travel' => $request->has('fanclub_travel') ? 1 : 0,
        ]);

        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
            'total_tickets' => 'required|integer|min:1',
            'expires_at' => 'required|date|after_or_equal:today',
            'price_member' => 'required|numeric|min:0',
            'price_non_member' => 'required|numeric|min:0',
            'fanclub_travel' => 'nullable|boolean',
            'fanclub_travel_price' => 'required_if:fanclub_travel,1|nullable|numeric|min:0',
        ], [
            // Individuelle Fehlermeldungen auf Deutsch
            'expires_at.after_or_equal' => 'Das Bestellschluss-Datum muss heute oder in der Zukunft liegen.',
            'expires_at.required' => 'Bitte gib ein Bestellschluss-Datum an.',
            'expires_at.date' => 'Das Bestellschluss-Datum ist ungültig.',
        ]);

        // Zusätzliche Prüfung: Bestellschluss < Event-Datum
        $event = Event::find($data['event_id']);
        if ($event && \Carbon\Carbon::parse($data['expires_at'])->gte(\Carbon\Carbon::parse($event->start))) {
            return back()
                ->withInput()
                ->withErrors([
                    'expires_at' => 'Der Bestellschluss muss vor dem Event-Datum liegen (' . \Carbon\Carbon::parse($event->start)->format('d.m.Y') . ').'
                ]);
        }

        if (!$data['fanclub_travel']) {
            $data['fanclub_travel_price'] = null;
        }

        TicketQuota::create($data);

        return redirect()->route('admin.ticket-quotas.index')->with('success', 'Ticketkontingent erfolgreich angelegt!');
    }
    
    public function edit(TicketQuota $ticketQuota)
    {
        // Nur aktive Events mit Kategorie "Ländermatch",
        // für die es noch KEIN Ticketkontingent gibt ODER das aktuelle Event des Kontingents
        $events = Event::whereHas('category', function($q) {
                $q->where('name', 'Ländermatch');
            })
            ->whereDate('start', '>=', now())
            ->where(function($query) use ($ticketQuota) {
                $query->whereDoesntHave('ticketQuotas')
                      ->orWhere('id', $ticketQuota->event_id);
            })
            ->get();

        return view('admin.ticket_quotas.edit', compact('ticketQuota', 'events'));
    }

    public function update(Request $request, TicketQuota $ticketQuota)
    {
        // Checkboxen benötigen eine Konvertierung!
        $request->merge([
            'fanclub_travel' => $request->has('fanclub_travel') ? 1 : 0,
        ]);

        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
            'total_tickets' => 'required|integer|min:1',
            'expires_at' => 'required|date|after_or_equal:today',
            'price_member' => 'required|numeric|min:0',
            'price_non_member' => 'required|numeric|min:0',
            'fanclub_travel' => 'nullable|boolean',
            'fanclub_travel_price' => 'required_if:fanclub_travel,1|nullable|numeric|min:0',
        ], [
            // Individuelle Fehlermeldungen auf Deutsch
            'expires_at.after_or_equal' => 'Das Bestellschluss-Datum muss heute oder in der Zukunft liegen.',
            'expires_at.required' => 'Bitte gib ein Bestellschluss-Datum an.',
            'expires_at.date' => 'Das Bestellschluss-Datum ist ungültig.',
        ]);

        // Zusätzliche Prüfung: Bestellschluss < Event-Datum
        $event = Event::find($data['event_id']);
        if ($event && \Carbon\Carbon::parse($data['expires_at'])->gte(\Carbon\Carbon::parse($event->start))) {
            return back()
                ->withInput()
                ->withErrors([
                    'expires_at' => 'Der Bestellschluss muss vor dem Event-Datum liegen (' . \Carbon\Carbon::parse($event->start)->format('d.m.Y') . ').'
                ]);
        }

        if (!$data['fanclub_travel']) {
            $data['fanclub_travel_price'] = null;
        }

        $ticketQuota->update($data);

        return redirect()->route('admin.ticket-quotas.index')->with('success', 'Ticketkontingent erfolgreich aktualisiert!');
    }

    public function destroy(TicketQuota $ticketQuota)
    {
        $ticketQuota->delete();
        return redirect()->route('admin.ticket-quotas.index')->with('success', 'Ticketkontingent wurde gelöscht!');
    }
}