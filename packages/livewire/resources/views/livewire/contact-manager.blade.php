<div class="persona-contacts" wire:poll.5s>
    <h2 class="persona-contacts__title">Contacts</h2>

    @if ($message)
        <p class="persona-contacts__message" role="status">{{ $message }}</p>
    @endif

    <ul class="persona-contacts__list">
        @forelse ($this->contacts as $contact)
            <li class="persona-contacts__item">
                <span class="persona-contacts__value">{{ $contact->value }}</span>
                <span class="persona-contacts__meta">
                    {{ $contact->type }}
                    @if ($contact->is_primary)
                        <span class="persona-contacts__badge persona-contacts__badge--primary">primary</span>
                    @endif
                    @if ($contact->is_emergency)
                        <span class="persona-contacts__badge persona-contacts__badge--emergency">emergency</span>
                    @endif
                    @if ($contact->is_verified)
                        <span class="persona-contacts__badge persona-contacts__badge--verified">verified</span>
                    @endif
                </span>
                <span class="persona-contacts__actions">
                    @unless ($contact->is_primary)
                        <button
                            type="button"
                            class="persona-contacts__button"
                            wire:click="setAsPrimary('{{ $contact->id }}')"
                        >
                            Make primary
                        </button>
                    @endunless
                    <button
                        type="button"
                        class="persona-contacts__button persona-contacts__button--danger"
                        wire:click="deleteContact('{{ $contact->id }}')"
                        wire:confirm="Remove this contact?"
                    >
                        Remove
                    </button>
                </span>
            </li>
        @empty
            <li class="persona-contacts__empty">No contacts yet.</li>
        @endforelse
    </ul>

    <form class="persona-contacts__form" wire:submit="addContact">
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
</div>