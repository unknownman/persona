<div class="persona-addresses-list" aria-label="Addresses">
    @if ($addresses->isEmpty())
        <p class="persona-addresses-list__empty">No addresses yet.</p>
    @else
        <ul class="persona-addresses-list__items">
            @foreach ($addresses as $address)
                <li class="persona-address-item">
                    <span class="persona-address-item__line1">{{ $address->line_1 }}</span>
                    @if ($address->line_2)
                        <span class="persona-address-item__line2">{{ $address->line_2 }}</span>
                    @endif
                    @php
                        $locality = collect([
                            $address->city,
                            $address->state,
                            $address->country_code,
                            $address->zip_code,
                        ])->filter()->implode(', ');
                    @endphp
                    @if ($locality)
                        <span class="persona-address-item__locality">{{ $locality }}</span>
                    @endif
                    <span class="persona-address-item__meta">
                        <span class="persona-address-item__type">{{ $address->type }}</span>
                        @if ($address->is_primary)
                            <span class="persona-address-badge persona-address-badge--primary">primary</span>
                        @endif
                    </span>
                </li>
            @endforeach
        </ul>
    @endif
</div>