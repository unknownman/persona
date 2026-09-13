<?php

namespace Persona\Managers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Persona\Events\PersonaDataWiped;

class PersonaManager
{
    /**
     * The tables holding data keyed to "personable" morph columns.
     */
    protected const PERSONABLE_TABLES = [
        'profiles',
        'social_accounts',
        'contacts',
        'addresses',
        'documents',
        'physical_attributes',
        'legal_details',
    ];

    public function __construct(
        protected ContactManager $contacts,
        protected DocumentManager $documents,
        protected RelationshipManager $relationships,
    ) {}

    public function contacts(): ContactManager
    {
        return $this->contacts;
    }

    public function documents(): DocumentManager
    {
        return $this->documents;
    }

    public function relationships(): RelationshipManager
    {
        return $this->relationships;
    }

    /**
     * Wipe the complete Persona footprint for a personable model.
     *
     * Deletes every row across the package's tables that references the
     * given model, either as the owner ("personable") or as the target of
     * a relationship ("related_personable"). Document files are removed
     * through their foreign-key cascade from the documents table.
     */
    public function forgetAll(Model $personable): void
    {
        DB::transaction(function () use ($personable) {
            $type = $personable->getMorphClass();
            $id = $personable->getKey();

            $tables = config('persona.tables', []);

            foreach (self::PERSONABLE_TABLES as $key) {
                if (isset($tables[$key])) {
                    DB::table($tables[$key])
                        ->where('personable_type', $type)
                        ->where('personable_id', $id)
                        ->delete();
                }
            }

            if (isset($tables['relationships'])) {
                $relationships = $tables['relationships'];

                DB::table($relationships)
                    ->where(function ($query) use ($type, $id) {
                        $query->where('personable_type', $type)
                            ->where('personable_id', $id);
                    })
                    ->orWhere(function ($query) use ($type, $id) {
                        $query->where('rel_personable_type', $type)
                            ->where('rel_personable_id', $id);
                    })
                    ->delete();
            }

            PersonaDataWiped::dispatch($personable);
        });
    }
}