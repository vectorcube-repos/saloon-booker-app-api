<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['place_id'],
            'place_id' => $this['place_id'],
            'name' => $this['name'],
            'address' => $this['address'],
            'latitude' => $this['latitude'],
            'longitude' => $this['longitude'],
            'main_text' => $this['main_text'] ?? $this['name'],
            'secondary_text' => $this['secondary_text'] ?? $this['address'],
            'types' => $this['types'] ?? [],
        ];
    }
}
