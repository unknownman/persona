{{-- Documents section. Variables: $documents (Collection). --}}
<section class="persona-profile__section" aria-label="{{ __('Documents') }}">
    <h3 class="persona-profile__heading">{{ __('Documents') }}</h3>
    <div class="persona-profile__documents">
        @forelse($documents as $document)
            @include('persona-blade::components.profile.document-item', ['document' => $document])
        @empty
            @include('persona-blade::components.profile.empty-state', [
                'title' => __('No documents yet'),
                'text'  => __('Verified identity documents will show up here.'),
            ])
        @endforelse
    </div>
</section>