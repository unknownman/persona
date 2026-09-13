<?php

namespace Persona\Managers;

use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Persona\Contracts\EmailNormalizerContract;
use Persona\Contracts\HandleNormalizerContract;
use Persona\Contracts\PhoneNormalizerContract;
use Persona\Events\ContactAdded;
use Persona\Models\Contact;

class ContactManager
{
    public function __construct(
        protected Container $app,
    ) {}

    /**
     * Add a contact value for a personable model.
     *
     * Raw string values are passed to the model; the encryption and
     * hashing casts take care of storage and unique lookups.
     *
     * @param  bool  $isPrimary  When true, this contact becomes the single
     *                           primary contact for the given type.
     * @param  bool  $isEmergency  Marks the contact as an emergency line.
     */
    public function add(
        Model $personable,
        string $type,
        string $value,
        bool $isPrimary = false,
        bool $isEmergency = false,
    ): Contact {
        $value = $this->normalize($type, $value);

        return DB::transaction(function () use ($personable, $type, $value, $isPrimary, $isEmergency) {
            if ($isPrimary) {
                Contact::query()
                    ->where('personable_type', $personable->getMorphClass())
                    ->where('personable_id', $personable->getKey())
                    ->where('type', $type)
                    ->where('is_primary', true)
                    ->update(['is_primary' => false]);
            }

            $contact = new Contact([
                'type' => $type,
                'value' => $value,
                'value_hash' => $value,
                'is_primary' => $isPrimary,
                'is_emergency' => $isEmergency,
            ]);

            $contact->personable_type = $personable->getMorphClass();
            $contact->personable_id = $personable->getKey();
            $contact->save();

            ContactAdded::dispatch($contact);

            return $contact;
        });
    }

    /**
     * Normalize the given value through the bound normalizer for its type.
     */
    protected function normalize(string $type, string $value): string
    {
        $contract = match ($type) {
            'email' => EmailNormalizerContract::class,
            'phone' => PhoneNormalizerContract::class,
            'handle', 'username' => HandleNormalizerContract::class,
            default => null,
        };

        if ($contract !== null && $this->app->bound($contract)) {
            return $this->app->make($contract)->normalize($value);
        }

        return $value;
    }
}