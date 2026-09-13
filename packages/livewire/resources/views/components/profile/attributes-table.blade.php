{{-- Props: $items (array of label => value; null values are collapsed) --}}
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