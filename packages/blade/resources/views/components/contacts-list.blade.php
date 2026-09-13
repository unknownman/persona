<div class="persona-contacts-list" aria-label="Contacts">
    @if ($contacts->isEmpty())
        <p class="persona-contacts-list__empty">No contacts yet.</p>
    @else
        <ul class="persona-contacts-list__items">
            @foreach ($contacts as $contact)
                <li class="persona-contact-item">
                    <span class="persona-contact-item__value">{{ $contact->value }}</span>
                    <span class="persona-contact-item__type">{{ $contact->type }}</span>
                    <span class="persona-contact-item__badges">
                        @if ($contact->is_primary)
                            <span class="persona-contact-badge persona-contact-badge--primary">primary</span>
                        @endif
                        @if ($contact->is_verified)
                            <span class="persona-contact-badge persona-contact-badge--verified">verified</span>
                        @endif
                        @if ($contact->is_emergency)
                            <span class="persona-contact-badge persona-contact-badge--emergency">emergency</span>
                        @endif
                    </span>
                </li>
            @endforeach
        </ul>
    @endif
</div>