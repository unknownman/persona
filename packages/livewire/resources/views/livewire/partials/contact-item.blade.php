<li class="persona-contacts__item">
    <span class="persona-contacts__value">{{ $contact->value }}</span>
    <span class="persona-contacts__meta">
        {{ $contact->type }}
        @if ($contact->is_primary)
            <span class="persona-contacts__badge persona-contacts__badge--primary">{{ __('primary') }}</span>
        @endif
        @if ($contact->is_emergency)
            <span class="persona-contacts__badge persona-contacts__badge--emergency">{{ __('emergency') }}</span>
        @endif
        @if ($contact->is_verified)
            <span class="persona-contacts__badge persona-contacts__badge--verified">{{ __('verified') }}</span>
        @endif
    </span>
    <span class="persona-contacts__actions">
        @unless ($contact->is_primary)
            <button
                type="button"
                class="persona-contacts__button"
                wire:click="setAsPrimary('{{ $contact->id }}')"
            >
                {{ __('Make primary') }}
            </button>
        @endunless
        <button
            type="button"
            class="persona-contacts__button persona-contacts__button--danger"
            wire:click="deleteContact('{{ $contact->id }}')"
            wire:confirm="{{ __('Remove this contact?') }}"
        >
            {{ __('Remove') }}
        </button>
    </span>
</li>