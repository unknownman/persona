<?php

namespace Persona\Managers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Persona\Events\RelationshipCreated;
use Persona\Models\Relationship;

class RelationshipManager
{
    /**
     * Link two personable models with a relationship type.
     *
     * Symmetric types (spouse, sibling, friend, ...) are stored in a
     * canonical order so that A-friend-B and B-friend-A collapse into a
     * single, deduplicated record instead of violating the unique index.
     */
    public function link(Model $source, Model $target, string $type): Relationship
    {
        [$personable, $relatedPersonable] = $this->canonicalize($source, $target, $type);

        return DB::transaction(function () use ($personable, $relatedPersonable, $source, $target, $type) {
            $relationship = new Relationship(['type' => $type]);

            $relationship->personable_type = $personable->getMorphClass();
            $relationship->personable_id = $personable->getKey();
            $relationship->related_personable_type = $relatedPersonable->getMorphClass();
            $relationship->related_personable_id = $relatedPersonable->getKey();

            $relationship->save();

            RelationshipCreated::dispatch($relationship, $source, $target);

            return $relationship;
        });
    }

    /**
     * Order the two sides canonically for symmetric relation types.
     *
     * The side with the lexicographically smaller morph-class + key
     * signature becomes "personable"; the other becomes
     * "related_personable". Directed types keep their original order.
     *
     * @return array{0: Model, 1: Model}
     */
    protected function canonicalize(Model $source, Model $target, string $type): array
    {
        $symmetric = config('persona.relationships.symmetric', []);

        if (! in_array($type, $symmetric, true)) {
            return [$source, $target];
        }

        $sourceSignature = $source->getMorphClass() . $source->getKey();
        $targetSignature = $target->getMorphClass() . $target->getKey();

        if (strcmp($sourceSignature, $targetSignature) <= 0) {
            return [$source, $target];
        }

        return [$target, $source];
    }
}