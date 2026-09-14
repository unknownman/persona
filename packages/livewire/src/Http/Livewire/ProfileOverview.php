<?php

namespace Persona\Livewire\Http\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Persona\Contracts\SocialActivityResolverContract;
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
 * The heavy aggregation is exposed through {@see footprint()} instead of
 * `render()` so it only executes once per Livewire request — the computed
 * property is memoized and shared with the view and with the other computed
 * properties ({@see socialActivities()}, {@see initials()}).
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
        return view('persona-livewire::components.profile.overview');
    }

    /**
     * The full Persona footprint for the personable.
     *
     * @return array{
     *     profile: \Persona\Models\Profile|null,
     *     contacts: \Illuminate\Database\Eloquent\Collection,
     *     addresses: \Illuminate\Database\Eloquent\Collection,
     *     documents: \Illuminate\Database\Eloquent\Collection,
     *     socialAccounts: \Illuminate\Database\Eloquent\Collection,
     *     relationships: \Illuminate\Database\Eloquent\Collection,
     *     physicalAttribute: \Persona\Models\PhysicalAttribute|null,
     *     legalDetail: \Persona\Models\LegalDetail|null,
     * }
     */
    #[Computed]
    public function footprint(): array
    {
        return Persona::for($this->personable)->getFootprint();
    }

    /**
     * Recent activity resolved per social account, keyed by account id.
     */
    #[Computed]
    public function socialActivities(): array
    {
        $resolver = app(SocialActivityResolverContract::class);

        $activities = [];

        foreach ($this->footprint['socialAccounts'] as $account) {
            $activities[$account->getKey()] = $resolver->getRecentActivity($account);
        }

        return $activities;
    }

    /**
     * Avatar fallback initials derived from the profile name.
     */
    #[Computed]
    public function initials(): string
    {
        $first = $this->footprint['profile']?->first_name ?? '';
        $last  = $this->footprint['profile']?->last_name ?? '';

        return strtoupper($first[0] ?? '') . strtoupper($last[0] ?? '');
    }
}