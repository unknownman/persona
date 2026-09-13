<?php

namespace Persona\Api\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * JSON representation of a Persona profile.
 *
 * Only presentation-safe fields are exposed. No internal identifiers,
 * hashes, or raw storage payloads are ever leaked.
 */
class ProfileResource extends JsonResource
{
    protected function personableType(): ?string
    {
        return $this->resource?->personable_type;
    }

    protected function personableId(): ?int
    {
        return $this->resource?->personable_id;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'personable_type' => $this->personableType(),
            'personable_id' => $this->personableId(),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'middle_name' => $this->middle_name,
            'gender' => $this->when($this->gender !== null, $this->gender),
            'birth_date' => $this->when(
                $this->birth_date !== null,
                $this->birth_date?->toDateString()
            ),
            'locale' => $this->locale,
            'timezone' => $this->timezone,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}