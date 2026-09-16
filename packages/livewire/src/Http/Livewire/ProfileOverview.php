<?php

namespace Persona\Livewire\Http\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
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
     * Addresses grouped by type, ready for the view.
     *
     * Kept out of the Blade layer so the view stays a dumb renderer and the
     * grouping cost is computed once per Livewire request.
     *
     * @return Collection<string, Collection<int, \Persona\Models\Address>>
     */
    #[Computed]
    public function addressGroups(): Collection
    {
        return $this->footprint['addresses']->groupBy('type');
    }

    /**
     * Physical-attribute rows as label/value pairs (nulls filtered by the view).
     *
     * @return array<string, string|null>
     */
    #[Computed]
    public function physicalItems(): array
    {
        $attribute = $this->footprint['physicalAttribute'];

        if (! $attribute) {
            return [];
        }

        return [
            __('Height')     => $attribute->height ? $attribute->height . ' cm' : null,
            __('Weight')     => $attribute->weight ? $attribute->weight . ' kg' : null,
            __('Eye color')  => $attribute->eye_color,
            __('Hair color') => $attribute->hair_color,
            __('Blood type') => $attribute->blood_type,
        ];
    }

    /**
     * Legal-detail rows as label/value pairs (nulls filtered by the view).
     *
     * @return array<string, string|null>
     */
    #[Computed]
    public function legalItems(): array
    {
        $detail = $this->footprint['legalDetail'];

        if (! $detail) {
            return [];
        }

        return [
            __('Nationality')    => $detail->nationality,
            __('Marital status') => $detail->marital_status,
            __('Tax ID')         => $detail->tax_id,
        ];
    }

    /**
     * The counterpart model (and direction) for every relationship.
     *
     * A relationship row may mount this entity on either side; the resolved
     * counter-model is what gets displayed. Keyed by relationship id.
     *
     * @return array<int, array{counterpart: \Illuminate\Database\Eloquent\Model|null, self: bool}>
     */
    #[Computed]
    public function relationshipCounterparts(): array
    {
        $morph = $this->personable->getMorphClass();
        $key   = (string) $this->personable->getKey();

        $counterparts = [];

        foreach ($this->footprint['relationships'] as $relationship) {
            $isSelf = $relationship->personable_type === $morph
                && (string) $relationship->personable_id === $key;

            $counterparts[$relationship->getKey()] = [
                'counterpart' => $isSelf
                    ? $relationship->relatedPersonable
                    : $relationship->personable,
                'self' => $isSelf,
            ];
        }

        return $counterparts;
    }

    /**
     * Avatar fallback initials derived from the profile name.
     */
    #[Computed]
    public function initials(): string
    {
        $first = $this->footprint['profile']?->first_name ?? '';
        $last  = $this->footprint['profile']?->last_name ?? '';

        return mb_strtoupper(Str::substr($first, 0, 1) . Str::substr($last, 0, 1));
    }
}