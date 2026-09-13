<?php

namespace Persona\Api\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * JSON representation of a Persona legal detail row.
 *
 * The decrypted tax id is exposed for the frontend, but the lookup hash
 * used for uniqueness checks is deliberately NOT included.
 */
class LegalDetailResource extends JsonResource
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
            'nationality' => $this->nationality,
            'marital_status' => $this->marital_status,
            'tax_id' => $this->tax_id,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}