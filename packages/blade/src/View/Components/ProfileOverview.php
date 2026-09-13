<?php

namespace Persona\Blade\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;
use Persona\Contracts\SocialActivityResolverContract;
use Persona\Models\Address;
use Persona\Models\Contact;
use Persona\Models\Document;
use Persona\Models\LegalDetail;
use Persona\Models\PhysicalAttribute;
use Persona\Models\Profile;
use Persona\Models\Relationship;
use Persona\Models\SocialAccount;

/**
 * Read-only profile overview for developers who use plain Blade
 * without Livewire.
 *
 *     <x-persona-blade-profile-overview :personable="$user" />
 *
 * Works with ANY Eloquent model. Models using the `HasPersona` trait
 * are hydrated through their eager-loaded relations; plain models
 * fall back to direct morph-pair queries.
 */
class ProfileOverview extends Component
{
    public function __construct(
        public readonly Model $personable,
    ) {}

    public function render(): View
    {
        $details = $this->resolveDetails();

        $socialActivities = [];
        $resolver = app(SocialActivityResolverContract::class);

        foreach ($details['socialAccounts'] as $account) {
            $socialActivities[$account->getKey()] = $resolver->getRecentActivity($account);
        }

        return view('persona-blade::components.profile-overview', [
            'personable'       => $this->personable,
            'profile'          => $details['profile'],
            'contacts'         => $details['contacts'],
            'addresses'        => $details['addresses'],
            'documents'        => $details['documents'],
            'socialAccounts'   => $details['socialAccounts'],
            'relationships'    => $details['relationships'],
            'physicalAttribute' => $details['physicalAttribute'],
            'legalDetail'      => $details['legalDetail'],
            'socialActivities' => $socialActivities,
            'initials'         => $this->computeInitials($details['profile']),
        ]);
    }

    /**
     * Resolve the full Persona footprint for the given model.
     *
     * Models with HasPersona are hydrated from eager-loaded relations;
     * plain models query the morph-pair directly.
     */
    protected function resolveDetails(): array
    {
        if (method_exists($this->personable, 'loadPersonaDetails')) {
            $this->personable->loadPersonaDetails();

            return [
                'profile'          => $this->personable->profile,
                'contacts'         => $this->personable->contacts,
                'addresses'        => $this->personable->addresses,
                'documents'        => $this->personable->documents,
                'socialAccounts'   => $this->personable->socialAccounts,
                'relationships'    => $this->personable->loadPersonaRelationships(),
                'physicalAttribute' => $this->personable->physicalAttribute,
                'legalDetail'      => $this->personable->legalDetail,
            ];
        }

        return $this->resolveFromMorphPair();
    }

    protected function resolveFromMorphPair(): array
    {
        $type = $this->personable->getMorphClass();
        $id   = $this->personable->getKey();

        return [
            'profile'          => Profile::query()->where('personable_type', $type)->where('personable_id', $id)->first(),
            'contacts'         => Contact::query()->where('personable_type', $type)->where('personable_id', $id)->get(),
            'addresses'        => Address::query()->where('personable_type', $type)->where('personable_id', $id)->get(),
            'documents'        => Document::query()->where('personable_type', $type)->where('personable_id', $id)->get(),
            'socialAccounts'   => SocialAccount::query()->where('personable_type', $type)->where('personable_id', $id)->get(),
            'relationships'    => Relationship::forEntity($this->personable)->with(['personable', 'relatedPersonable'])->get(),
            'physicalAttribute' => PhysicalAttribute::query()->where('personable_type', $type)->where('personable_id', $id)->first(),
            'legalDetail'      => LegalDetail::query()->where('personable_type', $type)->where('personable_id', $id)->first(),
        ];
    }

    protected function computeInitials(?Profile $profile): string
    {
        $first = $profile?->first_name ?? '';
        $last  = $profile?->last_name  ?? '';

        return strtoupper($first[0] ?? '') . strtoupper($last[0] ?? '');
    }
}
