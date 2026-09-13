<?php

namespace Persona\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Relationship extends Model
{
    protected $guarded = [];

    /**
     * Resolve the table name from the package configuration.
     */
    public function getTable(): string
    {
        return config('persona.tables.relationships', 'persona_relationships');
    }

    public function personable(): MorphTo
    {
        return $this->morphTo();
    }

    public function relatedPersonable(): MorphTo
    {
        return $this->morphTo('related_personable', 'rel_personable_type', 'rel_personable_id');
    }
}