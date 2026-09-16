<div class="persona-contacts-list" aria-label="{{ __('Contacts') }}">
    @if($contacts->isEmpty())
        @include('persona-blade::components.profile.empty-state', [
            'title' => __('No contacts yet'),
            'text'  => __('Add an email or phone number when available.'),
        ])
    @else
        <ul class="persona-contacts-list__items">
            @foreach($contacts as $contact)
                <li class="persona-contacts-list__item">
                    @include('persona-blade::components.profile.contact-item', ['contact' => $contact])
                </li>
            @endforeach
        </ul>
    @endif
</div>