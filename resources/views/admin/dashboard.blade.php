@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="max-w-xl mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-6 text-center">Admin Dashboard</h1>
        <div class="flex flex-col gap-6 mt-10 items-center">
            <a href="{{ route('admin.members.index') }}"
               class="w-full max-w-xs text-center inline-block px-4 py-2 rounded-lg bg-gray-300 text-gray-500 text-sm font-semibold shadow-md transition hover:text-red-700">
                Mitgliederverwaltung
            </a>
            <a href="{{ route('admin.events.index') }}"
               class="w-full max-w-xs text-center inline-block px-4 py-2 rounded-lg bg-gray-300 text-gray-500 text-sm font-semibold shadow-md transition hover:text-red-700">
                Events
            </a>
            <a href="{{ route('admin.ticket-quotas.index') }}"
               class="w-full max-w-xs text-center inline-block px-4 py-2 rounded-lg bg-gray-300 text-gray-500 text-sm font-semibold shadow-md cursor-not-allowed">
                Ticketkontingente verwalten
            </a>
            <a href="#"
               class="w-full max-w-xs text-center inline-block px-4 py-2 rounded-lg bg-gray-300 text-gray-500 text-sm font-semibold shadow-md cursor-not-allowed">
                ...
            </a>
        </div>
    </div>
@endsection