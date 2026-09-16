<?php

namespace Persona\Blade\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;
use Persona\Contracts\SocialActivityResolverContract;
use Persona\Models\LegalDetail;
use Persona\Models\PhysicalAttribute;
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

        $socialActivities = $this->resolveSocialActivities($details['socialAccounts']);

        return view('persona-blade::components.profile-overview', [
            'personable'        => $this->personable,
            'profile'           => $details['profile'],
            'fullName'          => $this->fullName($details['profile']),
            'contacts'          => $details['contacts'],
            'addresses'         => $details['addresses'],
            'addressGroups'     => $details['addresses']->groupBy('type'),
            'documents'         => $details['documents'],
            'socialAccounts'    => $details['socialAccounts'],
            'relationships'     => $details['relationships'],
            'physicalAttribute' => $details['physicalAttribute'],
            'physicalItems'     => $this->physicalItems($details['physicalAttribute']),
            'legalDetail'       => $details['legalDetail'],
            'legalItems'        => $this->legalItems($details['legalDetail']),
            'counterparts'      => $this->relationshipCounterparts($details['relationships']),
            'socialActivities'  => $socialActivities,
            'initials'          => $this->computeInitials($details['profile']),
        ]);
    }

    /**
     * Resolve recent activity for a set of social accounts, keyed by id.
     *
     * @param  \Illuminate\Database\Eloquent\Collection<int, \Persona\Models\SocialAccount>  $accounts
     * @return array<int, array>
     */
    protected function resolveSocialActivities($accounts): array
    {
        $resolver = app(SocialActivityResolverContract::class);

        $activities = [];

        foreach ($accounts as $account) {
            $activities[$account->getKey()] = $resolver->getRecentActivity($account);
        }

        return $activities;
    }

    /**
     * Display name assembled from the profile name parts.
     */
    protected function fullName(?Profile $profile): string
    {
        return trim(collect([
            $profile?->first_name,
            $profile?->middle_name,
            $profile?->last_name,
        ])->filter()->implode(' '));
    }

    /**
     * Physical-attribute rows as label/value pairs (nulls filtered by the view).
     *
     * @return array<string, string|null>
     */
    protected function physicalItems(?PhysicalAttribute $attribute): array
    {
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
    protected function legalItems(?LegalDetail $detail): array
    {
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
     * The counterpart model (and direction) for every relationship, keyed by id.
     *
     * @param  \Illuminate\Database\Eloquent\Collection<int, \Persona\Models\Relationship>  $relationships
     * @return array<int, array{counterpart: \Illuminate\Database\Eloquent\Model|null, self: bool}>
     */
    protected function relationshipCounterparts($relationships): array
    {
        $morph = $this->personable->getMorphClass();
        $key   = (string) $this->personable->getKey();

        $counterparts = [];

        foreach ($relationships as $relationship) {
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
    protected function computeInitials(?Profile $profile): string
    {
        $first = $profile?->first_name ?? '';
        $last  = $profile?->last_name ?? '';

        return strtoupper($first[0] ?? '') . strtoupper($last[0] ?? '');
    }
}