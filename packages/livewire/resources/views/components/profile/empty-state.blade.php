{{-- Reusable empty state for any profile section.
     Props: $title (string), $text (string|null), $variant ("section"|"page"). --}}
@props([
    'title' => __('Nothing here yet.'),
    'text' => null,
    'variant' => 'section',
])
<div class="persona-empty-state persona-empty-state--{{ $variant }}" role="status" aria-label="{{ $title }}">
    <span class="persona-empty-state__icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" width="1.375em" height="1.375em" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 10.5h.01M15 10.5h.01M8.5 14.5a3.6 3.6 0 0 0 7 0"/>
            <circle cx="12" cy="12" r="9.5"/>
        </svg>
    </span>
    <p class="persona-empty-state__title">{{ $title }}</p>
    @if($text)
        <p class="persona-empty-state__text">{{ $text }}</p>
    @endif
</div>