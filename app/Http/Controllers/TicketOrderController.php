<?php

namespace App\Http\Controllers;

use App\Models\TicketQuota;
use App\Models\TicketOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketOrderController extends Controller
{
    // Übersicht: Verfügbare Tickets, Aktuelle Bestellungen, Vergangene Bestellungen
    public function index()
    {
        $user = Auth::user();

        // Alle aktiven Kontingente
        $activeQuotas = TicketQuota::with('event')
            ->whereDate('expires_at', '>=', now()->toDateString())
            ->orderBy('expires_at')
            ->get();

        // IDs, zu denen der User schon eine Bestellung hat
        $orderedQuotaIds = $user->ticketOrders()->pluck('ticket_quota_id')->toArray();

        // Verfügbare Kontingente (zu denen noch keine Bestellung existiert)
        $availableQuotas = $activeQuotas->filter(function($quota) use ($orderedQuotaIds) {
            return !in_array($quota->id, $orderedQuotaIds);
        });

        // Aktuelle Bestellungen (eigene Bestellungen für noch offene Kontingente)
        $currentOrders = $user->ticketOrders()
            ->with(['ticketQuota.event'])
            ->whereHas('ticketQuota', function($q) {
                $q->whereDate('expires_at', '>=', now()->toDateString());
            })
            ->latest()
            ->get();

        // Vergangene Bestellungen (eigene Bestellungen für abgelaufene Kontingente)
        $pastOrders = $user->ticketOrders()
            ->with(['ticketQuota.event'])
            ->whereHas('ticketQuota', function($q) {
                $q->where('expires_at', '<', now());
            })
            ->latest()
            ->get();

        return view('ticket_orders.index', [
            'availableQuotas' => $availableQuotas,
            'currentOrders' => $currentOrders,
            'pastOrders' => $pastOrders,
        ]);
    }

    // Bestellformular für neue Bestellung (zeigt nur noch offene Kontingente)
    public function create(Request $request)
    {
        $user = Auth::user();

        // Alle aktiven Kontingente
        $ticketQuotas = TicketQuota::with('event')
            ->where('expires_at', '>=', now())
            ->orderBy('expires_at')
            ->get();

        // IDs aller Kontingente, für die der User schon bestellt hat
        $userOrderQuotaIds = TicketOrder::where('user_id', $user->id)
            ->whereIn('ticket_quota_id', $ticketQuotas->pluck('id'))
            ->pluck('ticket_quota_id')
            ->toArray();

        // Nur Kontingente, für die der User noch NICHT bestellt hat
        $availableQuotas = $ticketQuotas->filter(function($quota) use ($userOrderQuotaIds) {
            return !in_array($quota->id, $userOrderQuotaIds);
        });

        $showForm = $availableQuotas->count() > 0;
        $firstExistingOrder = null;
        $firstExistingQuota = null;

        if (!$showForm && count($userOrderQuotaIds) > 0) {
            $firstExistingOrder = TicketOrder::where('user_id', $user->id)
                ->whereIn('ticket_quota_id', $userOrderQuotaIds)
                ->with('ticketQuota.event')
                ->first();
            $firstExistingQuota = $firstExistingOrder?->ticketQuota;
        }

        // Vorauswahl für das Kontingent, falls per Link übergeben
        $preselectedQuota = null;
        if ($request->has('ticket_quota_id')) {
            $preselectedQuota = $availableQuotas->first(fn($q) => $q->id == $request->ticket_quota_id);
        }

        return view('ticket_orders.create', [
            'ticketQuotas' => $availableQuotas,
            'showForm' => $showForm,
            'existingOrder' => $firstExistingOrder,
            'existingQuota' => $firstExistingQuota,
            'preselectedQuota' => $preselectedQuota,
        ]);
    }

    // Speichern einer neuen Bestellung
    public function store(Request $request)
    {
        $request->validate([
            'ticket_quota_id'   => ['required', 'exists:ticket_quotas,id'],
            'member_count'      => ['required', 'integer', 'min:1'],
            'non_member_count'  => ['required', 'integer', 'min:0'],
            'travel_count'      => ['nullable', 'integer', 'min:0'],
        ]);

        $ticketQuota = TicketQuota::with('event', 'ticketOrders')->findOrFail($request->ticket_quota_id);

        // Prüfen, ob der User bereits für dieses Kontingent bestellt hat
        $alreadyExists = TicketOrder::where('ticket_quota_id', $ticketQuota->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($alreadyExists) {
            return back()->withInput()->withErrors([
                'member_count' => 'Du hast für dieses Kontingent bereits eine Bestellung aufgegeben.'
            ]);
        }

        $member_count = (int) $request->input('member_count', 0);
        $non_member_count = (int) $request->input('non_member_count', 0);
        $travel_count = (int) $request->input('travel_count', 0);

        $total_requested = $member_count + $non_member_count;

        $already_ordered = $ticketQuota->ticketOrders->sum(fn($o) => $o->member_count + $o->non_member_count);
        $remaining = $ticketQuota->total_tickets - $already_ordered;

        $expiresAt = \Carbon\Carbon::parse($ticketQuota->expires_at);
        if ($remaining < $total_requested) {
            return back()->withInput()->withErrors(['member_count' => 'Nicht mehr genug Tickets verfügbar!']);
        }
        if (now()->gt($expiresAt)) {
            return back()->withInput()->withErrors(['member_count' => 'Der Bestellschluss ist erreicht!']);
        }

        $total_price = ($member_count * $ticketQuota->price_member) +
                       ($non_member_count * $ticketQuota->price_non_member);

        if ($ticketQuota->fanclub_travel && $travel_count > 0) {
            $total_price += $travel_count * $ticketQuota->fanclub_travel_price;
        }

        $order = TicketOrder::create([
            'ticket_quota_id' => $ticketQuota->id,
            'user_id' => Auth::id(),
            'member_count' => $member_count,
            'non_member_count' => $non_member_count,
            'travel_count' => $ticketQuota->fanclub_travel ? $travel_count : null,
            'total_price' => $total_price,
            'confirmed' => false,
        ]);

        return redirect()->route('ticket-orders.index')->with('success', 'Deine Bestellung wurde gespeichert!');
    }

    // Einzelne Bestellung bearbeiten
    public function edit(TicketOrder $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        $ticketQuota = $order->ticketQuota;
        return view('ticket_orders.edit', compact('order', 'ticketQuota'));
    }

    // Update Bestellung
    public function update(Request $request, TicketOrder $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'member_count'      => ['required', 'integer', 'min:1'],
            'non_member_count'  => ['required', 'integer', 'min:0'],
            'travel_count'      => ['nullable', 'integer', 'min:0'],
        ]);

        $member_count = (int) $request->input('member_count', 0);
        $non_member_count = (int) $request->input('non_member_count', 0);
        $travel_count = (int) $request->input('travel_count', 0);

        $ticketQuota = $order->ticketQuota;
        $total_requested = $member_count + $non_member_count;

        $already_ordered = $ticketQuota->ticketOrders()
            ->where('id', '!=', $order->id)
            ->sum(\DB::raw('member_count + non_member_count'));
        $remaining = $ticketQuota->total_tickets - $already_ordered;

        $expiresAt = \Carbon\Carbon::parse($ticketQuota->expires_at);
        if ($remaining < $total_requested) {
            return back()->withInput()->withErrors(['member_count' => 'Nicht mehr genug Tickets verfügbar!']);
        }
        if (now()->gt($expiresAt)) {
            return back()->withInput()->withErrors(['member_count' => 'Der Bestellschluss ist erreicht!']);
        }

        $total_price = ($member_count * $ticketQuota->price_member) +
                       ($non_member_count * $ticketQuota->price_non_member);

        if ($ticketQuota->fanclub_travel && $travel_count > 0) {
            $total_price += $travel_count * $ticketQuota->fanclub_travel_price;
        }

        $order->update([
            'member_count' => $member_count,
            'non_member_count' => $non_member_count,
            'travel_count' => $ticketQuota->fanclub_travel ? $travel_count : null,
            'total_price' => $total_price,
        ]);

        return redirect()->route('ticket-orders.index')->with('success', 'Deine Bestellung wurde gespeichert!');
    }
}