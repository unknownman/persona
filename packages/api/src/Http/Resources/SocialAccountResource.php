<?php

namespace Persona\Api\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * JSON representation of a Persona social account.
 */
class SocialAccountResource extends JsonResource
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
            'platform' => $this->platform,
            'username' => $this->username,
            'url' => $this->url,
            'is_primary' => (bool) $this->is_primary,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}