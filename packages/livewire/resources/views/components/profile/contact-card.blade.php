@props(['contact' => null])
<article class="persona-contact-card">
    <div class="persona-contact-card__main">
        <span class="persona-contact-card__value">{{ $contact->value }}</span>
        <span class="persona-contact-card__type">{{ $contact->type }}</span>

        <span class="persona-contact-card__badges" aria-label="Status">
            @if($contact->is_primary)
                <span class="persona-contact-badge persona-contact-badge--primary" aria-label="Primary contact">Primary</span>
            @endif
            @if($contact->is_verified)
                <span class="persona-contact-badge persona-contact-badge--verified" aria-label="Verified">Verified</span>
            @endif
            @if($contact->is_emergency)
                <span class="persona-contact-badge persona-contact-badge--emergency" aria-label="Emergency contact">Emergency</span>
            @endif
        </span>
    </div>

    <nav class="persona-contact-card__actions" aria-label="Contact actions">
        @if($contact->type === 'email')
            <a href="mailto:{{ $contact->value }}" class="persona-contact-card__action">Email</a>
        @endif
        @if($contact->type === 'phone')
            <a href="tel:{{ $contact->value }}" class="persona-contact-card__action">Call</a>
            <a href="sms:{{ $contact->value }}" class="persona-contact-card__action">SMS</a>
        @endif
    </nav>
</article>