{{-- Full read-only profile overview for non-Livewire Blade hosts.
     Props: $personable (Model), $profile, $fullName, $contacts, $addressGroups,
     $documents, $socialAccounts, $socialActivities, $relationships,
     $counterparts, $physicalItems, $legalItems, $initials.
     Composes small per-section partials under components/profile/; hosts
     override any single piece by publishing the view namespace. --}}
<section class="persona-profile" aria-label="{{ __('Profile overview') }}">
    @if($profile)
        @include('persona-blade::components.profile.header')
    @else
        @include('persona-blade::components.profile.empty-state', [
            'variant' => 'page',
            'title'   => __('No profile yet'),
            'text'    => __('This entity has not added a profile yet.'),
        ])
    @endif

    @include('persona-blade::components.profile.contacts')
    @include('persona-blade::components.profile.addresses')
    @include('persona-blade::components.profile.documents')
    @include('persona-blade::components.profile.socials')
    @include('persona-blade::components.profile.relationships')
    @include('persona-blade::components.profile.physical-attributes')
    @include('persona-blade::components.profile.legal-details')
</section>