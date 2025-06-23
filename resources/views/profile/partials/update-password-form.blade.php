<section x-data="{ open: false }" class="max-w-xl mx-auto mt-10">
    <header @click="open = !open" class="cursor-pointer select-none flex items-center justify-between bg-gray-100 p-4 rounded-md shadow">
        <span class="text-lg font-medium text-gray-900">Passwort ändern</span>
        <svg :class="{ 'rotate-180': open }" class="w-5 h-5 text-gray-600 transform transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </header>

    <div x-show="open" x-transition class="overflow-hidden">
        <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
            @csrf
            @method('put')

            <div>
                <x-input-label for="current_password" value="Aktuelles Passwort" />
                <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full max-w-xl" autocomplete="current-password" />
                <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" value="Neues Passwort" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full max-w-xl" autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" value="Neues Passwort bestätigen" />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full max-w-xl" autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>Passwort speichern</x-primary-button>
                @if (session('status') === 'password-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        class="text-sm text-gray-600"
                    >Gespeichert.</p>
                @endif
            </div>
        </form>
    </div>
</section>