{{-- Single address card. Variables: $address. --}}
@php
    $locality = trim(collect([
        $address->city,
        $address->state,
        $address->country_code,
        $address->zip_code,
    ])->filter()->implode(', '));
@endphp
<article class="persona-address-card persona-address-card--{{ $address->type }}"
    data-type="{{ $address->type }}"
    aria-label="{{ $address->line_1 }}"
>
    <div class="persona-address-card__main">
        <span class="persona-address-card__line1">{{ $address->line_1 }}</span>
        @if($address->line_2)
            <span class="persona-address-card__line2">{{ $address->line_2 }}</span>
        @endif
        @if($locality)
            <span class="persona-address-card__locality">{{ $locality }}</span>
        @endif
    </div>

    <span class="persona-address-card__meta">
        <span class="persona-chip">{{ $address->type }}</span>
        @if($address->is_primary)
            <span class="persona-address-badge persona-address-badge--primary" aria-label="{{ __('Primary address') }}">{{ __('Primary') }}</span>
        @endif
    </span>
</article>