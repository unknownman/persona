{{-- Social accounts section.
     Variables: $socialAccounts (Collection), $socialActivities (array keyed by account id). --}}
<section class="persona-profile__section" aria-label="{{ __('Social accounts') }}">
    <h3 class="persona-profile__heading">{{ __('Social accounts') }}</h3>
    <div class="persona-profile__socials">
        @forelse($socialAccounts as $account)
            @include('persona-blade::components.profile.social-item', [
                'account'    => $account,
                'activities' => $socialActivities[$account->getKey()] ?? [],
            ])
        @empty
            @include('persona-blade::components.profile.empty-state', [
                'title' => __('No social accounts yet'),
                'text'  => __('Connected social profiles will appear here.'),
            ])
        @endforelse
    </div>
</section>