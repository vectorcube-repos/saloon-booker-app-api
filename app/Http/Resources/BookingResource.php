<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $slotStart = $this->slot_start instanceof Carbon ? $this->slot_start : Carbon::parse($this->slot_start);
        $slotEnd = $this->slot_end instanceof Carbon ? $this->slot_end : Carbon::parse($this->slot_end);
        $status = $this->resolveStatus($slotEnd);

        return [
            'id' => $this->id,
            'salon_image' => $this->salon?->getFirstMediaUrl('salon_image', 'thumb')
                ? url($this->salon->getFirstMediaUrl('salon_image', 'thumb'))
                : null,
            'salon_name' => $this->salon?->name,
            'service_name' => $this->service?->name,
            'staff_name' => $this->serviceProvider?->display_name,
            'time' => $this->formatBookingTime($slotStart),
            'slot_start' => $slotStart->toIso8601String(),
            'status' => $status,
            'status_label' => ucfirst($status),
        ];
    }

    private function formatBookingTime(Carbon $slotStart): string
    {
        return match (true) {
            $slotStart->isToday() => 'Today, ' . $slotStart->format('g:i A'),
            $slotStart->isTomorrow() => 'Tomorrow, ' . $slotStart->format('g:i A'),
            default => $slotStart->format('M d, g:i A'),
        };
    }

    private function resolveStatus(Carbon $slotEnd): string
    {
        if ($this->status === 'cancelled') {
            return 'cancelled';
        }

        if ($slotEnd->isPast()) {
            return 'completed';
        }

        return $this->status;
    }
}
