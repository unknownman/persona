{{-- Single document item. Variables: $document. --}}
@php
    $isExpired = $document->isExpired();
@endphp
<article class="persona-document-item persona-document-item--{{ $document->status }}"
    data-status="{{ $document->status }}"
    aria-label="{{ ucwords(str_replace('_', ' ', $document->type)) }} {{ $document->status }}"
>
    <div class="persona-document-item__main">
        <span class="persona-document-item__type">{{ ucwords(str_replace('_', ' ', $document->type)) }}</span>
        <span class="persona-document-item__number">
            @if($document->number)
                &bull;&bull;&bull;&bull; {{ substr($document->number, -4) }}
            @else
                &mdash;
            @endif
        </span>

        <span class="persona-document-item__meta">
            @if($document->country_code)
                <span class="persona-chip">{{ $document->country_code }}</span>
            @endif
            @if($document->issued_at)
                <span class="persona-document-item__issued">
                    {{ __('Issued :date', ['date' => $document->issued_at->format('M j, Y')]) }}
                </span>
            @endif
            @if($document->expires_at)
                <span
                    class="persona-document-item__expiry @if($isExpired) persona-document-item__expiry--expired @endif"
                    aria-label="{{ $isExpired ? __('Expired') : __('Expires') }} {{ $document->expires_at->format('M j, Y') }}"
                >
                    {{ $isExpired ? __('Expired') : __('Expires') }} {{ $document->expires_at->format('M j, Y') }}
                </span>
            @endif
        </span>
    </div>

    <span
        class="persona-document-status persona-document-status--{{ $document->status }}"
        aria-label="{{ __('Status') }}: {{ $document->status }}"
    >
        {{ ucfirst($document->status) }}
    </span>
</article>