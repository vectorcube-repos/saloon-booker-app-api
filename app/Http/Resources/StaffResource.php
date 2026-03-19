<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $thumbUrl = $this->getFirstMediaUrl('photo', 'thumb');

        return [
            'id' => $this->id,
            'display_name' => $this->display_name,
            'bio' => $this->bio,
            'skill_tags' => $this->skill_tags ?? [],
            'active' => (bool) $this->active,
            'image' => $thumbUrl ? url($thumbUrl) : null,
        ];
    }
}
