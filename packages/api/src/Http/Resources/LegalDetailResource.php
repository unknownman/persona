<?php

namespace Persona\Api\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Persona\Api\Support\PersonaMasker;

/**
 * JSON representation of a Persona legal detail row.
 *
 * The decrypted tax id is exposed ONLY to an authorized owner
 * (`PersonaPolicy@viewSensitive`); every other caller receives a masked
 * surrogate so raw PII never leaks through the API layer. The lookup hash is
 * never included at all.
 */
class LegalDetailResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $canViewSensitive = $request->user()?->can('viewSensitive', $this->resource);

        $taxId = $this->tax_id;
        if (! $canViewSensitive && $taxId !== null) {
            $taxId = PersonaMasker::taxId($taxId);
        }

        return [
            'id' => $this->id,
            'personable_type' => $this->personable_type,
            'personable_id' => $this->personable_id,
            'nationality' => $this->nationality,
            'marital_status' => $this->marital_status,
            'tax_id' => $taxId,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}