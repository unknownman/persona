{{-- Contacts section. Variables: $contacts (Collection). --}}
<section class="persona-profile__section" aria-label="{{ __('Contacts') }}">
    <h3 class="persona-profile__heading">{{ __('Contacts') }}</h3>
    <div class="persona-profile__contacts">
        @forelse($contacts as $contact)
            @include('persona-blade::components.profile.contact-item', ['contact' => $contact])
        @empty
            @include('persona-blade::components.profile.empty-state', [
                'title' => __('No contacts yet'),
                'text'  => __('Add an email or phone number when available.'),
            ])
        @endforelse
    </div>
</section>