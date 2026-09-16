{{-- Shared attributes table. Variables: $items (array<string, string|null>). --}}
@if($items)
    <dl class="persona-attributes-table">
        @foreach($items as $label => $value)
            @if($value)
                <div class="persona-attributes-table__row">
                    <dt class="persona-attributes-table__key">{{ $label }}</dt>
                    <dd class="persona-attributes-table__value">{{ $value }}</dd>
                </div>
            @endif
        @endforeach
    </dl>
@else
    @include('persona-blade::components.profile.empty-state', [
        'title' => __('Nothing here yet.'),
    ])
@endif