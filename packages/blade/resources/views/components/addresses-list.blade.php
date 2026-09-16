<div class="persona-addresses-list" aria-label="{{ __('Addresses') }}">
    @if($addresses->isEmpty())
        @include('persona-blade::components.profile.empty-state', [
            'title' => __('No addresses yet'),
            'text'  => __('Add a residence, shipping, or billing address when available.'),
        ])
    @else
        <ul class="persona-addresses-list__items">
            @foreach($addresses as $address)
                <li class="persona-addresses-list__item">
                    @include('persona-blade::components.profile.address-item', ['address' => $address])
                </li>
            @endforeach
        </ul>
    @endif
</div>