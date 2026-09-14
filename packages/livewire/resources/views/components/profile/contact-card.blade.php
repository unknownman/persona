@props(['contact' => null])
<article class="persona-contact-card">
    <div class="persona-contact-card__main">
        <span class="persona-contact-card__value">{{ $contact->value }}</span>
        <span class="persona-contact-card__type">{{ $contact->type }}</span>

        <span class="persona-contact-card__badges" aria-label="{{ __('Status') }}">
            @if($contact->is_primary)
                <span class="persona-contact-badge persona-contact-badge--primary" aria-label="{{ __('Primary contact') }}">{{ __('Primary') }}</span>
            @endif
            @if($contact->is_verified)
                <span class="persona-contact-badge persona-contact-badge--verified" aria-label="{{ __('Verified') }}">{{ __('Verified') }}</span>
            @endif
            @if($contact->is_emergency)
                <span class="persona-contact-badge persona-contact-badge--emergency" aria-label="{{ __('Emergency contact') }}">{{ __('Emergency') }}</span>
            @endif
        </span>
    </div>

    <nav class="persona-contact-card__actions" aria-label="{{ __('Contact actions') }}">
        @if($contact->type === 'email')
            <a href="mailto:{{ $contact->value }}" class="persona-contact-card__action">{{ __('Email') }}</a>
        @endif
        @if($contact->type === 'phone')
            <a href="tel:{{ $contact->value }}" class="persona-contact-card__action">{{ __('Call') }}</a>
            <a href="sms:{{ $contact->value }}" class="persona-contact-card__action">{{ __('SMS') }}</a>
        @endif
    </nav>
</article>