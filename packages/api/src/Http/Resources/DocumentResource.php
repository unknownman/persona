<?php

namespace Persona\Api\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Persona\Api\Support\PersonaMasker;

/**
 * JSON representation of a Persona identity document.
 *
 * The decrypted document number is exposed ONLY to an authorized owner
 * (`PersonaPolicy@viewSensitive`); every other caller receives a masked
 * surrogate so raw PII never leaks through the API layer. The lookup hash is
 * never included at all.
 */
class DocumentResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $canViewSensitive = $request->user()?->can('viewSensitive', $this->resource);

        $number = $this->number;
        if (! $canViewSensitive && $number !== null) {
            $number = PersonaMasker::documentNumber((string) $number);
        }

        return [
            'id' => $this->id,
            'personable_type' => $this->personable_type,
            'personable_id' => $this->personable_id,
            'type' => $this->type,
            'number' => $number,
            'country_code' => $this->country_code,
            'issued_at' => $this->issued_at?->toDateString(),
            'expires_at' => $this->expires_at?->toDateString(),
            'status' => $this->status,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'deleted_at' => $this->deleted_at?->toIso8601String(),
        ];
    }
}