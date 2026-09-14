<?php

namespace Persona\Livewire\Http\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Persona\Contracts\SocialActivityResolverContract;
use Persona\Models\Profile;
use Persona\Persona;

/**
 * Atomic, read-only profile overview.
 *
 * Renders an entity's full Persona footprint (identity, contacts,
 * addresses, documents, social accounts, relationships, and physical /
 * legal attributes), sourcing every slice from the Core via
 * `Persona::for($personable)->getFootprint()`:
 *
 *     <livewire:persona.profile-overview :personable="$user" />
 *
 * The component works with ANY Eloquent model; `getFootprint()` hydrates
 * models using the `HasPersona` trait through their eager-loaded relations
 * and falls back to direct morph-pair queries for plain models, so the view
 * layer never touches the database itself.
 *
 * Every section is a small dedicated Blade partial under
 * `resources/views/components/profile/`, so hosts can override any
 * single piece by publishing the view namespace.
 */
class ProfileOverview extends Component
{
    #[Locked]
    public Model $personable;

    public function mount(Model $personable): void
    {
        $this->personable = $personable;
    }

    public function render(): View
    {
        $details = Persona::for($this->personable)->getFootprint();

        $socialActivities = [];
        $resolver = app(SocialActivityResolverContract::class);

        foreach ($details['socialAccounts'] as $account) {
            $socialActivities[$account->getKey()] = $resolver->getRecentActivity($account);
        }

        return view('persona-livewire::components.profile.overview', [
            'personable'        => $this->personable,
            'profile'           => $details['profile'],
            'contacts'          => $details['contacts'],
            'addresses'         => $details['addresses']->groupBy('type'),
            'documents'         => $details['documents'],
            'socialAccounts'    => $details['socialAccounts'],
            'socialActivities'  => $socialActivities,
            'relationships'     => $details['relationships'],
            'physicalAttribute' => $details['physicalAttribute'],
            'legalDetail'       => $details['legalDetail'],
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