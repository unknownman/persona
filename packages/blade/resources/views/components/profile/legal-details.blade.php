{{-- Legal details section. Variables: $legalItems (array). --}}
<section class="persona-profile__section" aria-label="{{ __('Legal details') }}">
    <h3 class="persona-profile__heading">{{ __('Legal details') }}</h3>
    @if($legalItems)
        @include('persona-blade::components.profile.attributes-table', ['items' => $legalItems])
    @else
        @include('persona-blade::components.profile.empty-state', [
            'title' => __('No legal details'),
            'text'  => __('Nationality, marital status, and tax details appear here.'),
        ])
    @endif
</section>