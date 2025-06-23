<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Profilinformationen bearbeiten</h2>
        <p class="mt-1 text-sm text-gray-600">Aktualisiere deine Profildaten.</p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6 max-w-xl">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="first_name" value="Vorname" />
            <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full max-w-xl" required autofocus autocomplete="given-name" value="{{ old('first_name', Auth::user()->first_name) }}" />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="last_name" value="Nachname" />
            <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full max-w-xl" required autocomplete="family-name" value="{{ old('last_name', Auth::user()->last_name) }}" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="phone" value="Telefonnummer" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full max-w-xl" autocomplete="tel" value="{{ old('phone', Auth::user()->phone) }}" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="birthdate" value="Geburtsdatum" />
            <x-text-input id="birthdate" name="birthdate" type="date" class="mt-1 block w-full max-w-xl" required value="{{ old('birthdate', Auth::user()->birthdate) }}" />
            <x-input-error :messages="$errors->get('birthdate')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" value="E-Mail" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full max-w-xl" required autocomplete="username" value="{{ old('email', Auth::user()->email) }}" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Speichern</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    class="text-sm text-gray-600"
                >Gespeichert.</p>
            @endif
        </div>
    </form>
</section>