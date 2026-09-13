<?php

namespace Persona\Api\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * JSON representation of a Persona physical attribute row.
 */
class PhysicalAttributeResource extends JsonResource
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
            'height' => $this->height,
            'weight' => $this->weight,
            'eye_color' => $this->eye_color,
            'hair_color' => $this->hair_color,
            'blood_type' => $this->blood_type,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}