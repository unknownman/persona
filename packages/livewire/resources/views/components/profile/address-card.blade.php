{{-- Props: $address (Persona\Models\Address) --}}
<article class="persona-address-card">
    <div class="persona-address-card__main">
        <span class="persona-address-card__line1">{{ $address->line_1 }}</span>
        @if($address->line_2)
            <span class="persona-address-card__line2">{{ $address->line_2 }}</span>
        @endif
        @php
            $locality = collect([
                $address->city,
                $address->state,
                $address->country_code,
                $address->zip_code,
            ])->filter()->implode(', ');
        @endphp
        @if($locality)
            <span class="persona-address-card__locality">{{ $locality }}</span>
        @endif
    </div>

    @if($address->is_primary)
        <span class="persona-address-badge persona-address-badge--primary" aria-label="Primary address">Primary</span>
    @endif
</article>