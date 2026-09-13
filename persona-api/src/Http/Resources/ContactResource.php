<?php

namespace Persona\Api\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * JSON representation of a Persona contact.
 *
 * The decrypted contact value is exposed for the frontend, but the
 * lookup hash used for uniqueness checks is deliberately NOT included.
 */
class ContactResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'personable_type' => $this->personable_type,
            'personable_id' => $this->personable_id,
            'type' => $this->type,
            'value' => $this->value,
            'is_primary' => (bool) $this->is_primary,
            'is_verified' => (bool) $this->is_verified,
            'verified_at' => $this->verified_at?->toIso8601String(),
            'is_emergency' => (bool) $this->is_emergency,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'deleted_at' => $this->deleted_at?->toIso8601String(),
        ];
    }
}