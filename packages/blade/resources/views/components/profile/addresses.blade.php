{{-- Addresses section. Variables: $addressGroups (Collection grouped by type). --}}
<section class="persona-profile__section" aria-label="{{ __('Addresses') }}">
    <h3 class="persona-profile__heading">{{ __('Addresses') }}</h3>
    @if($addressGroups->isNotEmpty())
        <div class="persona-profile__addresses">
            @foreach($addressGroups as $type => $group)
                <div class="persona-profile__address-group">
                    <span class="persona-profile__group-label">{{ ucfirst($type) }}</span>
                    <div class="persona-profile__address-items">
                        @foreach($group as $address)
                            @include('persona-blade::components.profile.address-item', ['address' => $address])
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @else
        @include('persona-blade::components.profile.empty-state', [
            'title' => __('No addresses yet'),
            'text'  => __('Add a residence, shipping, or billing address when available.'),
        ])
    @endif
</section>