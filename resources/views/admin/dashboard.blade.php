<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                <h3>Willkommen im Admin-Bereich, {{ Auth::user()->first_name }}!</h3>
                <!-- Hier kommen Admin-Features -->
            </div>
        </div>
    </div>
</x-app-layout>