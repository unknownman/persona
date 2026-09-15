<?php

namespace Persona\Livewire\Http\Livewire;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Attributes\Computed;
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

    protected ?Model $resolvedPersonable = null;

    public function boot(PersonaManager $personaManager): void
    {
        $this->personaManager = $personaManager;
    }

    public function mount(
        ?Model $personable = null,
        ?string $personableType = null,
        int|string|null $personableId = null,
    ): void {
        if ($personable) {
            $this->personableType = $personable->getMorphClass();
            $this->personableId = $personable->getKey();
            $this->resolvedPersonable = $personable;
        } else {
            $this->personableType = (string) $personableType;
            $this->personableId = $personableId;
        }
    }

    /**
     * Contacts scoped to this component's personable morph pair.
     *
     * Delegates to the model's `contacts()` relation (HasPersona trait) when
     * available; otherwise falls back to a direct Contact query on the morph
     * pair columns so the host model does NOT need the trait.
     */
    #[Computed]
    public function contacts(): Collection
    {
        $personable = $this->personable();

        return method_exists($personable, 'contacts')
            ? $personable->contacts()->orderByDesc('is_primary')->orderBy('created_at')->get()
            : Contact::query()
                ->where('personable_type', $this->personableType)
                ->where('personable_id', $this->personableId)
                ->orderByDesc('is_primary')
                ->orderBy('created_at')
                ->get();
    }

    public function addContact(): void
    {
        $personable = $this->personable();

        $this->validate([
            'type' => ['required', 'string'],
            'value' => ['required', 'string', 'max:255', new PersonaUniqueContactValue($this->type, $personable)],
        ]);

        $contact = $this->personaManager
            ->contacts()
            ->add(
                $personable,
                $this->type,
                $this->value,
                isPrimary: $this->isPrimary,
                isEmergency: $this->isEmergency,
            );

        $this->resetComputed('contacts');
        $this->reset('value', 'isPrimary', 'isEmergency');
        $this->message = __(':value was added as a :type contact.', [
            'value' => $contact->value,
            'type' => $contact->type,
        ]);
    }

    public function setAsPrimary(int|string $contactId): void
    {
        $personable = $this->personable();
        $contact = $this->resolveContact($contactId);

        $this->personaManager->contacts()->makePrimary($personable, $contact);

        $this->resetComputed('contacts');
        $this->message = __('Primary contact updated.');
    }

    public function deleteContact(int|string $contactId): void
    {
        $personable = $this->personable();
        $contact = $this->resolveContact($contactId);

        $this->personaManager->contacts()->delete($personable, $contact);

        $this->resetComputed('contacts');
        $this->message = __('Contact removed.');
    }

    /**
     * Resolve a single contact owned by this component's personable scope.
     *
     * Queries the Contact model directly on the morph pair columns so the host
     * model does NOT need the HasPersona trait for individual lookups.
     */
    protected function resolveContact(int|string $contactId): Contact
    {
        return Contact::query()
            ->where('personable_type', $this->personableType)
            ->where('personable_id', $this->personableId)
            ->findOrFail($contactId);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('persona-livewire::livewire.contact-manager');
    }

    /**
     * Resolve the personable model for this component's morph pair.
     *
     * Uses the class from the morph map (or falls back to the raw type)
     * and caches the result for the duration of the request lifecycle.
     */
    protected function personable(): Model
    {
        if ($this->resolvedPersonable) {
            return $this->resolvedPersonable;
        }

        $class = Relation::getMorphedModel($this->personableType) ?? $this->personableType;

        $this->resolvedPersonable = app($class)->query()->findOrFail($this->personableId);

        return $this->resolvedPersonable;
    }
}
