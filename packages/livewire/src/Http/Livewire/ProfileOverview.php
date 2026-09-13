<?php

namespace Persona\Livewire\Http\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Locked;
use Livewire\Component;
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
 * Atomic, read-only profile overview.
 *
 * Renders an entity's full Persona footprint (identity, contacts,
 * addresses, documents, social accounts, relationships, and physical /
 * legal attributes) from a single eager-loaded model:
 *
 *     <livewire:persona.profile-overview :personable="$user" />
 *
 * The component works with ANY Eloquent model. Models using the
 * `HasPersona` trait are hydrated through their eager-loaded relations;
 * plain models fall back to direct morph-pair queries keyed by
 * `personable_type` / `personable_id`, so no trait is required.
 *
 * Every section is a small dedicated Blade partial under
 * `resources/views/components/profile/`, so hosts can override any
 * single piece by publishing the view namespace.
 */
class ProfileOverview extends Component
{
    #[Locked]
    public Model $personable;

    protected ?Profile $profile = null;

    protected Collection $contacts;

    protected Collection $addresses;

    protected Collection $documents;

    protected Collection $socialAccounts;

    protected Collection $relationships;

    protected ?PhysicalAttribute $physicalAttribute = null;

    protected ?LegalDetail $legalDetail = null;

    public function mount(Model $personable): void
    {
        $this->personable = $personable;
    }

    public function render(): View
    {
        if (method_exists($this->personable, 'loadPersonaDetails')) {
            $this->personable->loadPersonaDetails();
            $this->hydrateFromRelations();
        } else {
            $this->loadFallbackDetails();
        }

        $socialActivities = [];
        $resolver = app(SocialActivityResolverContract::class);

        foreach ($this->socialAccounts as $account) {
            $socialActivities[$account->getKey()] = $resolver->getRecentActivity($account);
        }

        return view('persona-livewire::components.profile.overview', [
            'personable' => $this->personable,
            'profile' => $this->profile,
            'contacts' => $this->contacts,
            'addresses' => $this->addresses->groupBy('type'),
            'documents' => $this->documents,
            'socialAccounts' => $this->socialAccounts,
            'socialActivities' => $socialActivities,
            'relationships' => $this->relationships,
            'physicalAttribute' => $this->physicalAttribute,
            'legalDetail' => $this->legalDetail,
            'initials' => $this->computeInitials(),
        ]);
    }

    /**
     * Hydrate the internal slices from the eager-loaded trait relations.
     */
    protected function hydrateFromRelations(): void
    {
        $this->profile = $this->personable->profile;
        $this->contacts = $this->personable->contacts;
        $this->addresses = $this->personable->addresses;
        $this->documents = $this->personable->documents;
        $this->socialAccounts = $this->personable->socialAccounts;
        $this->relationships = $this->personable->loadPersonaRelationships();
        $this->physicalAttribute = $this->personable->physicalAttribute;
        $this->legalDetail = $this->personable->legalDetail;
    }

    /**
     * Hydrate the internal slices by querying the Persona models directly
     * with the polymorphic morph pair — works for any Eloquent model.
     */
    protected function loadFallbackDetails(): void
    {
        $type = $this->personable->getMorphClass();
        $id = $this->personable->getKey();

        $this->profile = Profile::query()
            ->where('personable_type', $type)
            ->where('personable_id', $id)
            ->first();

        $this->contacts = Contact::query()
            ->where('personable_type', $type)
            ->where('personable_id', $id)
            ->get();

        $this->addresses = Address::query()
            ->where('personable_type', $type)
            ->where('personable_id', $id)
            ->get();

        $this->documents = Document::query()
            ->where('personable_type', $type)
            ->where('personable_id', $id)
            ->get();

        $this->socialAccounts = SocialAccount::query()
            ->where('personable_type', $type)
            ->where('personable_id', $id)
            ->get();

        $this->relationships = Relationship::forEntity($this->personable)
            ->with(['personable', 'relatedPersonable'])
            ->get();

        $this->physicalAttribute = PhysicalAttribute::query()
            ->where('personable_type', $type)
            ->where('personable_id', $id)
            ->first();

        $this->legalDetail = LegalDetail::query()
            ->where('personable_type', $type)
            ->where('personable_id', $id)
            ->first();
    }

    /**
     * Avatar fallback initials derived from the profile name.
     */
    protected function computeInitials(): string
    {
        $first = $this->profile?->first_name ?? '';
        $last = $this->profile?->last_name ?? '';

        return strtoupper($first[0] ?? '') . strtoupper($last[0] ?? '');
    }
}