{{-- Single contact card. Variables: $contact. --}}
@php
    $canCall  = $contact->type === 'phone';
    $canEmail = $contact->type === 'email';
@endphp
<article class="persona-contact-card persona-contact-card--{{ $contact->type }}"
    data-type="{{ $contact->type }}"
    aria-label="{{ $contact->value }}"
>
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

    @if($canCall || $canEmail)
        <nav class="persona-contact-card__actions" aria-label="{{ __('Contact actions') }}">
            @if($canEmail)
                <a
                    href="mailto:{{ $contact->value }}"
                    class="persona-contact-card__action persona-contact-card__action--email"
                    aria-label="{{ __('Send email to :value', ['value' => $contact->value]) }}"
                >
                    <svg viewBox="0 0 24 24" width="1em" height="1em" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="2.5" y="5" width="19" height="14" rx="2.5"/>
                        <path d="m3 6.5 9 7 9-7"/>
                    </svg>
                    <span>{{ __('Email') }}</span>
                </a>
            @endif
            @if($canCall)
                <a
                    href="tel:{{ $contact->value }}"
                    class="persona-contact-card__action persona-contact-card__action--call"
                    aria-label="{{ __('Call :value', ['value' => $contact->value]) }}"
                >
                    <svg viewBox="0 0 24 24" width="1em" height="1em" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M7.5 3H5a2 2 0 0 0-2 2c0 9.4 7.6 17 17 17a2 2 0 0 0 2-2v-2.5a1 1 0 0 0-1.5-.9l-3.2 1.6a.5.5 0 0 1-.6-.1l-5-5a.5.5 0 0 1-.1-.6l1.6-3.2a1 1 0 0 0-.9-1.5H7.5"/>
                    </svg>
                    <span>{{ __('Call') }}</span>
                </a>
                <a
                    href="sms:{{ $contact->value }}"
                    class="persona-contact-card__action persona-contact-card__action--sms"
                    aria-label="{{ __('Send SMS to :value', ['value' => $contact->value]) }}"
                >
                    <svg viewBox="0 0 24 24" width="1em" height="1em" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 4h16a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H9l-5 4v-4H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/>
                        <path d="M8 9h.01M12 9h.01M16 9h.01"/>
                    </svg>
                    <span>{{ __('SMS') }}</span>
                </a>
            @endif
        </nav>
    @endif
</article>