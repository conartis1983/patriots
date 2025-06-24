@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 text-xs">
    <div class="bg-white rounded shadow p-6">
        <h2 class="font-bold text-xl mb-6">Mitglied bearbeiten</h2>
        <form action="{{ route('admin.members.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-xs mb-1" for="first_name">Vorname</label>
                <input type="text" id="first_name" name="first_name" class="w-full border rounded px-2 py-1 text-xs"
                       value="{{ old('first_name', $user->first_name) }}" required>
            </div>
            <div class="mb-4">
                <label class="block text-xs mb-1" for="last_name">Nachname</label>
                <input type="text" id="last_name" name="last_name" class="w-full border rounded px-2 py-1 text-xs"
                       value="{{ old('last_name', $user->last_name) }}" required>
            </div>
            <div class="mb-4">
                <label class="block text-xs mb-1" for="email">E-Mail</label>
                <input type="email" id="email" name="email" class="w-full border rounded px-2 py-1 text-xs"
                       value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="mb-4">
                <label class="block text-xs mb-1">Rolle</label>
                <select name="is_admin" class="w-full border rounded px-2 py-1 text-xs">
                    <option value="0" @if(!$user->is_admin) selected @endif>Mitglied</option>
                    <option value="1" @if($user->is_admin) selected @endif>Admin</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-xs mb-1">Beitrag bezahlt?</label>
                <select name="fee_status" class="w-full border rounded px-2 py-1 text-xs">
                    <option value="paid"
                        @if(isset($membershipFee) && $membershipFee->paid && !$membershipFee->not_needed)
                            selected
                        @endif
                    >Ja</option>
                    <option value="unpaid"
                        @if(isset($membershipFee) && !$membershipFee->paid && !$membershipFee->not_needed)
                            selected
                        @endif
                    >Nein</option>
                    <option value="not_needed"
                        @if(isset($membershipFee) && $membershipFee->not_needed)
                            selected
                        @endif
                    >Nicht notwendig</option>
                </select>
            </div>

            <div class="flex justify-center items-center">
                <x-primary-button type="submit" class="text-xs px-3 py-1 font-bold">
                    Speichern
                </x-primary-button>
                <a href="{{ route('admin.members.index') }}" class="ml-3 text-neutral-500 hover:underline">Abbrechen</a>
            </div>
        </form>
    </div>
</div>
@endsection