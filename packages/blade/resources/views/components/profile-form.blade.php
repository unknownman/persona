<form action="{{ $action }}" method="post" class="persona-profile-form" novalidate>
    @csrf
    @if (! in_array($method, ['GET', 'POST'], true))
        @method($method)
    @endif

    <div class="persona-profile-form__field">
        <label for="first_name" class="persona-profile-form__label">First name</label>
        <input
            type="text"
            id="first_name"
            name="first_name"
            value="{{ old('first_name', $profile?->first_name) }}"
            class="persona-profile-form__input"
        >
        @error('first_name')
            <p class="persona-profile-form__error">{{ $message }}</p>
        @enderror
    </div>

    <div class="persona-profile-form__field">
        <label for="last_name" class="persona-profile-form__label">Last name</label>
        <input
            type="text"
            id="last_name"
            name="last_name"
            value="{{ old('last_name', $profile?->last_name) }}"
            class="persona-profile-form__input"
        >
        @error('last_name')
            <p class="persona-profile-form__error">{{ $message }}</p>
        @enderror
    </div>

    <div class="persona-profile-form__field">
        <label for="middle_name" class="persona-profile-form__label">Middle name</label>
        <input
            type="text"
            id="middle_name"
            name="middle_name"
            value="{{ old('middle_name', $profile?->middle_name) }}"
            class="persona-profile-form__input"
        >
    </div>

    <div class="persona-profile-form__field">
        <label for="gender" class="persona-profile-form__label">Gender</label>
        <input
            type="text"
            id="gender"
            name="gender"
            value="{{ old('gender', $profile?->gender) }}"
            class="persona-profile-form__input"
        >
    </div>

    <div class="persona-profile-form__field">
        <label for="birth_date" class="persona-profile-form__label">Birth date</label>
        <input
            type="date"
            id="birth_date"
            name="birth_date"
            value="{{ old('birth_date', $profile?->birth_date?->format('Y-m-d')) }}"
            class="persona-profile-form__input"
        >
    </div>

    <div class="persona-profile-form__field">
        <label for="locale" class="persona-profile-form__label">Locale</label>
        <input
            type="text"
            id="locale"
            name="locale"
            value="{{ old('locale', $profile?->locale) }}"
            class="persona-profile-form__input"
        >
    </div>

    <div class="persona-profile-form__field">
        <label for="timezone" class="persona-profile-form__label">Timezone</label>
        <input
            type="text"
            id="timezone"
            name="timezone"
            value="{{ old('timezone', $profile?->timezone) }}"
            class="persona-profile-form__input"
        >
    </div>

    <div class="persona-profile-form__actions">
        <button type="submit" class="persona-profile-form__submit">
            {{ $profile ? 'Save changes' : 'Create profile' }}
        </button>
    </div>
</form>