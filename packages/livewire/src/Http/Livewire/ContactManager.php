<?php

namespace Persona\Livewire\Http\Livewire;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Persona\Managers\PersonaManager;
use Persona\Models\Contact;
use Persona\Rules\PersonaUniqueContactValue;

class ContactManager extends Component
{
    #[Locked]
    public string $personableType;

    #[Locked]
    public int|string $personableId;

    #[Validate('required|string', as: 'type')]
    public string $type = 'email';

    #[Validate(['required', 'string', 'max:255'], as: 'value')]
    public string $value = '';

    public bool $isPrimary = false;

    public bool $isEmergency = false;

    public ?string $message = null;

    protected PersonaManager $personaManager;

    public function boot(PersonaManager $personaManager): void
    {
        $this->personaManager = $personaManager;
    }

    public function mount(string $personableType, int|string $personableId): void
    {
        $this->personableType = $personableType;
        $this->personableId = $personableId;
    }

    public function addContact(): void
    {
        $this->validate([
            'type' => ['required', 'string'],
            'value' => ['required', 'string', 'max:255', new PersonaUniqueContactValue($this->type)],
        ]);

        $contact = $this->personaManager
            ->contacts()
            ->add(
                $this->personable(),
                $this->type,
                $this->value,
                isPrimary: $this->isPrimary,
                isEmergency: $this->isEmergency,
            );

        $this->reset('value', 'isPrimary', 'isEmergency');
        $this->message = "{$contact->value} was added as a {$contact->type} contact.";
    }

    public function setAsPrimary(int|string $contactId): void
    {
        $personable = $this->personable();
        $contact = $this->resolveContact($contactId);

        $this->personaManager->contacts()->makePrimary($personable, $contact);

        $this->message = 'Primary contact updated.';
    }

    public function deleteContact(int|string $contactId): void
    {
        $personable = $this->personable();
        $contact = $this->resolveContact($contactId);

        $this->personaManager->contacts()->delete($personable, $contact);

        $this->message = 'Contact removed.';
    }

    /**
     * Resolve a contact owned by this component's personable scope directly
     * through the Contact model.
     *
     * The query is scoped on the morph pair (personable_type / personable_id)
     * instead of `$personable->contacts()`, so the host model does NOT need to
     * use the HasPersona trait or define any relation methods for this to work.
     */
    protected function resolveContact(int|string $contactId): Contact
    {
        return Contact::query()
            ->where('personable_type', $this->personableType)
            ->where('personable_id', $this->personableId)
            ->findOrFail($contactId);
    }

    public function getContactsProperty(): Collection
    {
        return Contact::query()
            ->where('personable_type', $this->personableType)
            ->where('personable_id', $this->personableId)
            ->orderByDesc('is_primary')
            ->orderBy('created_at')
            ->get();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('persona-livewire::livewire.contact-manager');
    }

    protected function personable(): Model
    {
        // Resolve morph-map aliases (e.g. 'user' => App\Models\User) through
        // the canonical Relation resolver so UUID models and morph maps work.
        $class = Relation::getMorphedModel($this->personableType) ?? $this->personableType;

        return app($class)->query()->findOrFail($this->personableId);
    }
}