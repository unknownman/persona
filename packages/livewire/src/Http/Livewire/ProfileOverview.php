<?php

namespace Persona\Livewire\Http\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Persona\Contracts\SocialActivityResolverContract;

/**
 * Atomic, read-only profile overview.
 *
 * Renders an entity's full Persona footprint (identity, contacts,
 * addresses, documents, social accounts, relationships, and physical /
 * legal attributes) from a single eager-loaded model:
 *
 *     <livewire:persona.profile-overview :personable="$user" />
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
        $this->personable->loadPersonaDetails();

        $socialActivities = [];
        $resolver = app(SocialActivityResolverContract::class);

        foreach ($this->personable->socialAccounts as $account) {
            $socialActivities[$account->getKey()] = $resolver->getRecentActivity($account);
        }

        return view('persona-livewire::components.profile.overview', [
            'personable' => $this->personable,
            'profile' => $this->personable->profile,
            'contacts' => $this->personable->contacts,
            'addresses' => $this->personable->addresses->groupBy('type'),
            'documents' => $this->personable->documents,
            'socialAccounts' => $this->personable->socialAccounts,
            'socialActivities' => $socialActivities,
            'relationships' => $this->personable->loadPersonaRelationships(),
            'physicalAttribute' => $this->personable->physicalAttribute,
            'legalDetail' => $this->personable->legalDetail,
            'initials' => $this->computeInitials(),
        ]);
    }

    /**
     * Avatar fallback initials derived from the profile name.
     */
    protected function computeInitials(): string
    {
        $first = $this->personable->profile?->first_name ?? '';
        $last = $this->personable->profile?->last_name ?? '';

        return strtoupper($first[0] ?? '') . strtoupper($last[0] ?? '');
    }
}