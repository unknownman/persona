@props(['relationship' => null, 'counterpart' => null])
<div class="persona-relationship-chip" aria-label="{{ __('Relationship') }}: {{ $relationship->type }}">
    <span class="persona-relationship-chip__type">{{ ucfirst($relationship->type) }}</span>
    <span class="persona-relationship-chip__name">
        @if(method_exists($counterpart, 'persona') && $counterpart->profile)
            {{ trim(collect([
                $counterpart->profile->first_name,
                $counterpart->profile->last_name,
            ])->filter()->implode(' ')) ?: __('Unnamed') }}
        @else
            {{ class_basename($counterpart) }} #{{ $counterpart->getKey() }}
        @endif
    </span>
</div>