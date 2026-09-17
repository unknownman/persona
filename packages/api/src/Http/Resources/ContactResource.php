<?php

namespace Persona\Api\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Persona\Api\Support\PersonaMasker;

/**
 * JSON representation of a Persona contact.
 *
 * The decrypted contact value is exposed ONLY to an authorized owner
 * (`PersonaPolicy@viewSensitive`); every other caller — including anonymous
 * requests and non-owner users — receives a masked surrogate so raw PII never
 * leaks through the API layer. The lookup hash is never included at all.
 */
class ContactResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $canViewSensitive = $request->user()?->can('viewSensitive', $this->resource);

        $value = (string) $this->value;
        if (! $canViewSensitive) {
            $value = PersonaMasker::contact((string) $this->type, $value);
        }

        return [
            'id' => $this->id,
            'personable_type' => $this->personable_type,
            'personable_id' => $this->personable_id,
            'type' => $this->type,
            'value' => $value,
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