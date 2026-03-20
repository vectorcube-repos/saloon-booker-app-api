<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $thumbUrl = $this->getFirstMediaUrl('service_image', 'thumb');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'duration_minutes' => isset($this->pivot) ? (int) $this->pivot->duration_minutes : null,
            'rate' => isset($this->pivot) ? (int) $this->pivot->rate : null,
            'formatted_rate' => isset($this->pivot) ? number_format($this->pivot->rate / 100, 2) : null,
            'is_active' => isset($this->pivot) ? (bool) $this->pivot->is_active : null,
            'active_salons_count' => $this->active_salons_count ?? null,
            'image' => $thumbUrl ? url($thumbUrl) : null,
        ];
    }
}
