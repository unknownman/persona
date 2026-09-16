{{-- Physical attributes section. Variables: $physicalItems (array). --}}
<section class="persona-profile__section" aria-label="{{ __('Physical attributes') }}">
    <h3 class="persona-profile__heading">{{ __('Physical attributes') }}</h3>
    @if($physicalItems)
        @include('persona-blade::components.profile.attributes-table', ['items' => $physicalItems])
    @else
        @include('persona-blade::components.profile.empty-state', [
            'title' => __('No physical attributes'),
            'text'  => __('Height, weight, and other attributes will show up here.'),
        ])
    @endif
</section>