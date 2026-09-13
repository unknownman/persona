<form class="persona-contacts__form" wire:submit="addContact" aria-label="Add contact">
    <div class="persona-contacts__field">
        <label class="persona-contacts__label" for="persona-type">Type</label>
        <select id="persona-type" class="persona-contacts__input" wire:model="type">
            <option value="email">Email</option>
            <option value="phone">Phone</option>
            <option value="handle">Handle</option>
        </select>
    </div>

    <div class="persona-contacts__field">
        <label class="persona-contacts__label" for="persona-value">Value</label>
        <input
            type="text"
            id="persona-value"
            class="persona-contacts__input"
            wire:model="value"
            placeholder="user@example.com, +1234567890, or @handle"
        >
        @error('value')
            <p class="persona-contacts__error">{{ $message }}</p>
        @enderror
    </div>

    <label class="persona-contacts__check">
        <input type="checkbox" wire:model="isPrimary">
        Make primary
    </label>

    <label class="persona-contacts__check">
        <input type="checkbox" wire:model="isEmergency">
        Emergency contact
    </label>

    <button type="submit" class="persona-contacts__submit">Add contact</button>
</form>