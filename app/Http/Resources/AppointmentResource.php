<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $serviceProvider = $this->whenLoaded('serviceProvider');

        $providerImageUrl = $serviceProvider ? $serviceProvider->getFirstMediaUrl('photo') : null;
        $providerThumbUrl = $serviceProvider ? $serviceProvider->getFirstMediaUrl('photo', 'thumb') : null;

        return [
            'id' => $this->id,
            'status' => $this->status,
            'notes' => $this->notes,
            'slot_start' => $this->slot_start?->toIso8601String(),
            'slot_end' => $this->slot_end?->toIso8601String(),
            'salon' => $this->whenLoaded('salon', fn () => SalonResource::make($this->salon)),
            'service' => $this->whenLoaded('service', fn () => ServiceResource::make($this->service)),
            'service_provider' => $serviceProvider ? [
                'id' => $serviceProvider->id,
                'display_name' => $serviceProvider->display_name,
                'bio' => $serviceProvider->bio,
                'active' => (bool) $serviceProvider->active,
                'image' => $providerImageUrl ? url($providerImageUrl) : null,
                'image_thumb' => $providerThumbUrl ? url($providerThumbUrl) : null,
            ] : null,
        ];
    }
}
