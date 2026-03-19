<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SalonBookingResource;
use App\Models\Appointment;
use App\Models\Salon;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SalonController extends Controller
{
    /**
     * Return a single salon booking payload by id.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $user = $request->user();

        $salon = Salon::with([
            'media',
            'salonHours',
            'serviceProviders' => fn ($query) => $query
                ->with('media')
                ->where('active', true)
                ->orderBy('display_name'),
            'services' => fn ($query) => $query
                ->with('media')
                ->wherePivot('is_active', true)
                ->orderBy('name'),
        ])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->withExists([
                'favoritedByUsers as is_favorite' => fn ($query) => $query->where('users.id', $user->id),
            ])
            ->find($id);

        if (! $salon) {
            return response()->json([
                'message' => 'Salon not found.',
                'status' => 'error',
            ], 404);
        }

        $staffs = $salon->serviceProviders->values();
        $services = $salon->services->values();
        $dates = $this->buildDateOptions($salon);
        $selectedStaff = $staffs->firstWhere('id', (int) $request->integer('staff_id')) ?? $staffs->first();
        $selectedDate = $this->resolveSelectedDate($request->query('date'), $dates);

        return response()->json([
            'message' => 'OK',
            'status' => 'success',
            'data' => SalonBookingResource::make([
                'salon' => $salon,
                'staffs' => $staffs,
                'services' => $services,
                'dates' => $dates,
                'selected_staff_id' => $selectedStaff?->id,
                'selected_date' => $selectedDate->toDateString(),
                'slots' => $this->buildSlots($salon, $selectedDate, $selectedStaff?->id),
            ])->resolve(),
        ]);
    }

    private function buildDateOptions(Salon $salon): Collection
    {
        $hoursByDay = $salon->salonHours->keyBy('day_of_week');
        $today = Carbon::today();

        return collect(range(0, 5))
            ->map(function (int $offset) use ($hoursByDay, $today) {
                $date = $today->copy()->addDays($offset);
                $hours = $hoursByDay->get($date->dayOfWeek);
                $isClosed = ! $hours || $hours->is_closed;

                return [
                    'date' => $date->toDateString(),
                    'label' => $date->isToday() ? 'Today' : $date->format('D, d M'),
                    'day' => $date->format('D'),
                    'day_name' => $date->format('l'),
                    'day_number' => $date->format('d'),
                    'month' => $date->format('M'),
                    'is_today' => $date->isToday(),
                    'is_closed' => $isClosed,
                    'opening_time' => $isClosed ? null : Carbon::parse($hours->open_time)->format('g:i A'),
                    'closing_time' => $isClosed ? null : Carbon::parse($hours->close_time)->format('g:i A'),
                ];
            })
            ->values();
    }

    private function resolveSelectedDate(?string $date, Collection $dates): Carbon
    {
        if ($date && $dates->contains(fn (array $item) => $item['date'] === $date)) {
            return Carbon::parse($date);
        }

        return Carbon::parse($dates->first()['date']);
    }

    private function buildSlots(Salon $salon, Carbon $selectedDate, ?int $staffId): array
    {
        $hours = $salon->salonHours->firstWhere('day_of_week', $selectedDate->dayOfWeek);

        if (! $hours || $hours->is_closed) {
            return [];
        }

        $openAt = Carbon::parse($selectedDate->toDateString() . ' ' . $hours->open_time);
        $closeAt = Carbon::parse($selectedDate->toDateString() . ' ' . $hours->close_time);
        $now = Carbon::now();

        $appointments = Appointment::query()
            ->where('salon_id', $salon->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereDate('slot_start', $selectedDate->toDateString())
            ->when($staffId, fn ($query) => $query->where('provider_id', $staffId))
            ->orderBy('slot_start')
            ->get(['slot_start', 'slot_end']);

        $slots = [];
        $cursor = $openAt->copy();

        while ($cursor->lt($closeAt)) {
            $slotEnd = $cursor->copy()->addMinutes(30);

            if ($slotEnd->gt($closeAt)) {
                break;
            }

            $isPast = $selectedDate->isToday() && $slotEnd->lte($now);
            $isBooked = $appointments->contains(
                fn (Appointment $appointment) => $cursor->lt($appointment->slot_end) && $slotEnd->gt($appointment->slot_start)
            );

            if (! $isPast && ! $isBooked) {
                $slots[] = [
                    'start' => $cursor->toIso8601String(),
                    'end' => $slotEnd->toIso8601String(),
                    'label' => $cursor->format('g:i A'),
                ];
            }

            $cursor->addMinutes(30);
        }

        return $slots;
    }
}
