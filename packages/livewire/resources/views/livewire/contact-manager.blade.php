<div class="persona-contacts">
    <h2 class="persona-contacts__title">{{ __('Contacts') }}</h2>

    @if ($message)
        <p class="persona-contacts__message" role="status">{{ $message }}</p>
    @endif

    <ul class="persona-contacts__list">
        @forelse ($this->contacts as $contact)
            @include('persona-livewire::livewire.partials.contact-item', ['contact' => $contact])
        @empty
            <li class="persona-contacts__empty">{{ __('No contacts yet.') }}</li>
        @endforelse
    </ul>

    @include('persona-livewire::livewire.partials.contact-form')
</div>