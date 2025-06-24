<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $users = User::with('membershipFees')->get();
        return view('admin.members.index', compact('users'));
    }

    public function edit(User $user)
    {
        $year = now()->year;
        $membershipFee = $user->membershipFees()->where('year', $year)->first();
        return view('admin.members.edit', compact('user', 'membershipFee'));
    }

    public function update(Request $request, User $user)
    {
        $year = now()->year;

        $validated = $request->validate([
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'email'       => 'required|email|max:255|unique:users,email,' . $user->id,
            'is_admin'    => 'required|in:0,1',
            'fee_status'  => 'required|in:paid,unpaid,not_needed',
        ]);

        $user->first_name = $validated['first_name'];
        $user->last_name  = $validated['last_name'];
        $user->email      = $validated['email'];
        $user->is_admin   = $validated['is_admin'];
        $user->save();

        // Fee für aktuelles Jahr aktualisieren oder anlegen
        $membershipFee = $user->membershipFees()->firstOrCreate(
            ['year' => $year],
            ['paid' => false, 'not_needed' => false]
        );

        // Logik für das neue Dropdown
        if ($validated['fee_status'] === 'paid') {
            $membershipFee->paid = true;
            $membershipFee->not_needed = false;
            $membershipFee->paid_at = now();
        } elseif ($validated['fee_status'] === 'unpaid') {
            $membershipFee->paid = false;
            $membershipFee->not_needed = false;
            $membershipFee->paid_at = null;
        } else { // not_needed
            $membershipFee->paid = false;
            $membershipFee->not_needed = true;
            $membershipFee->paid_at = null;
        }

        $membershipFee->save();

        return redirect()->route('admin.members.index')->with('success', 'Mitglied erfolgreich aktualisiert.');
    }
}