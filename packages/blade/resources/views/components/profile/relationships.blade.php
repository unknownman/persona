{{-- Relationships section.
     Variables: $relationships (Collection), $counterparts (keyed by relationship id,
     each an array of ["counterpart" => Model|null, "self" => bool]). --}}
<section class="persona-profile__section" aria-label="{{ __('Relationships') }}">
    <h3 class="persona-profile__heading">{{ __('Relationships') }}</h3>
    <div class="persona-profile__relationships">
        @forelse($relationships as $relationship)
            @php
                $entry = $counterparts[$relationship->getKey()] ?? null;
            @endphp
            @if($entry && $entry['counterpart'])
                @include('persona-blade::components.profile.relationship-item', [
                    'relationship' => $relationship,
                    'counterpart'  => $entry['counterpart'],
                    'incoming'     => $entry['self'],
                ])
            @endif
        @empty
            @include('persona-blade::components.profile.empty-state', [
                'title' => __('No relationships yet'),
                'text'  => __('Linked people and organizations will appear here.'),
            ])
        @endforelse
    </div>
</section>