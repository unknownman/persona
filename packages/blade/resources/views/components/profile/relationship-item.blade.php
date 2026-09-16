{{-- Single relationship chip. Variables: $relationship, $counterpart, $incoming. --}}
@php
    $relation = $incoming ? 'incoming' : 'outgoing';

    if (method_exists($counterpart, 'persona') && $counterpart->profile) {
        $name = trim(collect([
            $counterpart->profile->first_name,
            $counterpart->profile->last_name,
        ])->filter()->implode(' ')) ?: __('Unnamed');
    } else {
        $name = class_basename($counterpart) . ' #' . $counterpart->getKey();
    }
@endphp
<div
    class="persona-relationship-chip persona-relationship-chip--{{ $relation }}"
    data-direction="{{ $relation }}"
    aria-label="{{ ucfirst($relationship->type) }}: {{ $name }}"
>
    <span class="persona-relationship-chip__type">{{ ucfirst($relationship->type) }}</span>
    <span class="persona-relationship-chip__glyph" aria-hidden="true">
        @if($incoming)
            <svg viewBox="0 0 24 24" width="1em" height="1em" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M11 6l-6 6 6 6"/>
            </svg>
        @else
            <svg viewBox="0 0 24 24" width="1em" height="1em" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14M13 6l6 6-6 6"/>
            </svg>
        @endif
    </span>
    <span class="persona-relationship-chip__name">{{ $name }}</span>
</div>