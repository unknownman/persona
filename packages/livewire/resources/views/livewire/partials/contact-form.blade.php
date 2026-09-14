<form class="persona-contacts__form" wire:submit="addContact" aria-label="{{ __('Add contact') }}">
    <div class="persona-contacts__field">
        <label class="persona-contacts__label" for="persona-type">{{ __('Type') }}</label>
        <select id="persona-type" class="persona-contacts__input" wire:model="type">
            <option value="email">{{ __('Email') }}</option>
            <option value="phone">{{ __('Phone') }}</option>
            <option value="handle">{{ __('Handle') }}</option>
        </select>
    </div>

    <div class="persona-contacts__field">
        <label class="persona-contacts__label" for="persona-value">{{ __('Value') }}</label>
        <input
            type="text"
            id="persona-value"
            class="persona-contacts__input"
            wire:model="value"
            placeholder="{{ __('user@example.com, +1234567890, or @handle') }}"
        >
        @error('value')
            <p class="persona-contacts__error">{{ $message }}</p>
        @enderror
    </div>

    <label class="persona-contacts__check">
        <input type="checkbox" wire:model="isPrimary">
        {{ __('Make primary') }}
    </label>

    <label class="persona-contacts__check">
        <input type="checkbox" wire:model="isEmergency">
        {{ __('Emergency contact') }}
    </label>

    <button type="submit" class="persona-contacts__submit">{{ __('Add contact') }}</button>
</form>