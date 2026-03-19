<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalonBookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'salon' => SalonResource::make($this->resource['salon'])->resolve(),
            'staffs' => StaffResource::collection($this->resource['staffs'])->resolve(),
            'services' => ServiceResource::collection($this->resource['services'])->resolve(),
            'dates' => $this->resource['dates'],
            'selected_staff_id' => $this->resource['selected_staff_id'],
            'selected_date' => $this->resource['selected_date'],
            'slots' => $this->resource['slots'],
        ];
    }
}
