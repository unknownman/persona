@props(['document' => null])
<article class="persona-document-item">
    <div class="persona-document-item__main">
        <span class="persona-document-item__type">{{ ucwords(str_replace('_', ' ', $document->type)) }}</span>
        <span class="persona-document-item__number">
            @if($document->number)
                •••• {{ substr($document->number, -4) }}
            @else
                —
            @endif
        </span>
        @if($document->expires_at)
            <span
                class="persona-document-item__expiry @if($document->isExpired()) persona-document-item__expiry--expired @endif"
                aria-label="{{ $document->isExpired() ? __('Expired') : __('Expires') }} {{ $document->expires_at->format('M j, Y') }}"
            >
                {{ $document->isExpired() ? __('Expired') : __('Expires') }} {{ $document->expires_at->format('M j, Y') }}
            </span>
        @endif
    </div>

    <span
        class="persona-document-status persona-document-status--{{ $document->status }}"
        aria-label="{{ __('Status') }}: {{ $document->status }}"
    >
        {{ ucfirst($document->status) }}
    </span>
</article>