<?php

namespace Persona\Blade\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;
use Persona\Contracts\SocialActivityResolverContract;
use Persona\Models\Profile;
use Persona\Persona;

/**
 * Read-only profile overview for developers who use plain Blade
 * without Livewire.
 *
 *     <x-persona-blade-profile-overview :personable="$user" />
 *
 * Sourcing every slice from the Core via `Persona::for($personable)->getFootprint()`,
 * the component works with ANY Eloquent model, regardless of the `HasPersona` trait.
 */
class ProfileOverview extends Component
{
    public function __construct(
        public readonly Model $personable,
    ) {}

    public function render(): View
    {
        $details = Persona::for($this->personable)->getFootprint();

        $socialActivities = [];
        $resolver = app(SocialActivityResolverContract::class);

        foreach ($details['socialAccounts'] as $account) {
            $socialActivities[$account->getKey()] = $resolver->getRecentActivity($account);
        }

        return view('persona-blade::components.profile-overview', [
            'personable'        => $this->personable,
            'profile'           => $details['profile'],
            'contacts'          => $details['contacts'],
            'addresses'         => $details['addresses'],
            'documents'         => $details['documents'],
            'socialAccounts'    => $details['socialAccounts'],
            'relationships'     => $details['relationships'],
            'physicalAttribute' => $details['physicalAttribute'],
            'legalDetail'       => $details['legalDetail'],
            'socialActivities'  => $socialActivities,
            'initials'          => $this->computeInitials($details['profile']),
        ]);
    }

    /**
     * Avatar fallback initials derived from the profile name.
     */
    protected function computeInitials(?Profile $profile): string
    {
        $first = $profile?->first_name ?? '';
        $last  = $profile?->last_name ?? '';

        return strtoupper($first[0] ?? '') . strtoupper($last[0] ?? '');
    }
}