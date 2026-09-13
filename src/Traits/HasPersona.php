<?php

namespace Persona\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Persona\Managers\PersonaManager;
use Persona\Models\Address;
use Persona\Models\Contact;
use Persona\Models\Document;
use Persona\Models\LegalDetail;
use Persona\Models\PhysicalAttribute;
use Persona\Models\Profile;
use Persona\Models\Relationship;
use Persona\Models\SocialAccount;
use Persona\Persona;

trait HasPersona
{
    /**
     * Get all persona profiles for the model.
     */
    public function profiles(): MorphMany
    {
        return $this->morphMany(
            config('persona.models.profile', Profile::class),
            'personable'
        );
    }

    /**
     * Get the primary/default persona profile for the model.
     */
    public function profile(): MorphOne
    {
        return $this->morphOne(
            config('persona.models.profile', Profile::class),
            'personable'
        );
    }

    /**
     * Get all persona contacts for the model.
     */
    public function contacts(): MorphMany
    {
        return $this->morphMany(
            config('persona.models.contact', Contact::class),
            'personable'
        );
    }

    /**
     * Get all persona addresses for the model.
     */
    public function addresses(): MorphMany
    {
        return $this->morphMany(
            config('persona.models.address', Address::class),
            'personable'
        );
    }

    /**
     * Get all persona documents for the model.
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(
            config('persona.models.document', Document::class),
            'personable'
        );
    }

    /**
     * Get all persona social accounts for the model.
     */
    public function socialAccounts(): MorphMany
    {
        return $this->morphMany(
            config('persona.models.social_account', SocialAccount::class),
            'personable'
        );
    }

    /**
     * Get all persona relationships where the model is the source.
     */
    public function relationships(): MorphMany
    {
        return $this->morphMany(
            config('persona.models.relationship', Relationship::class),
            'personable'
        );
    }

    /**
     * Get all persona physical attributes for the model.
     */
    public function physicalAttributes(): MorphMany
    {
        return $this->morphMany(
            config('persona.models.physical_attribute', PhysicalAttribute::class),
            'personable'
        );
    }

    /**
     * Get the single persona physical attributes record for the model.
     */
    public function physicalAttribute(): MorphOne
    {
        return $this->morphOne(
            config('persona.models.physical_attribute', PhysicalAttribute::class),
            'personable'
        );
    }

    /**
     * Get all persona legal details for the model.
     */
    public function legalDetails(): MorphMany
    {
        return $this->morphMany(
            config('persona.models.legal_detail', LegalDetail::class),
            'personable'
        );
    }

    /**
     * Get the single persona legal detail record for the model.
     */
    public function legalDetail(): MorphOne
    {
        return $this->morphOne(
            config('persona.models.legal_detail', LegalDetail::class),
            'personable'
        );
    }

    /**
     * Get the Persona manager scoped to this model.
     */
    public function persona(): PersonaManager
    {
        return Persona::for($this);
    }
}
