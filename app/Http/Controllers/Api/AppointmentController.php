<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\Salon;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'salon_id' => ['required', 'integer', 'exists:salons,id'],
            'service_id' => ['required', 'integer'],
            'staff_id' => ['required', 'integer'],
            'slot_start' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $salon = Salon::with([
            'salonHours',
            'media',
            'services' => fn ($query) => $query
                ->with('media')
                ->wherePivot('is_active', true),
            'serviceProviders' => fn ($query) => $query
                ->with('media')
                ->where('active', true),
        ])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->findOrFail($validated['salon_id']);

        $service = $salon->services->firstWhere('id', (int) $validated['service_id']);
        $staff = $salon->serviceProviders->firstWhere('id', (int) $validated['staff_id']);

        if (! $service) {
            return response()->json([
                'message' => 'Selected service is not available for this salon.',
                'status' => 'error',
            ], 422);
        }

        if (! $staff) {
            return response()->json([
                'message' => 'Selected staff is not available for this salon.',
                'status' => 'error',
            ], 422);
        }

        $slotStart = Carbon::parse($validated['slot_start']);

        if ($slotStart->isPast()) {
            return response()->json([
                'message' => 'Please choose a future slot.',
                'status' => 'error',
            ], 422);
        }

        if (((int) $slotStart->format('i')) % 30 !== 0 || $slotStart->second !== 0) {
            return response()->json([
                'message' => 'Appointments must start on a 30-minute boundary.',
                'status' => 'error',
            ], 422);
        }

        $hours = $salon->salonHours->firstWhere('day_of_week', $slotStart->dayOfWeek);

        if (! $hours || $hours->is_closed) {
            return response()->json([
                'message' => 'Salon is closed on the selected date.',
                'status' => 'error',
            ], 422);
        }

        $openAt = Carbon::parse($slotStart->toDateString() . ' ' . $hours->open_time);
        $closeAt = Carbon::parse($slotStart->toDateString() . ' ' . $hours->close_time);
        $slotEnd = $slotStart->copy()->addMinutes((int) $service->pivot->duration_minutes);

        if ($slotStart->lt($openAt) || $slotEnd->gt($closeAt)) {
            return response()->json([
                'message' => 'Selected slot is outside salon opening hours.',
                'status' => 'error',
            ], 422);
        }

        $hasConflict = Appointment::query()
            ->where('salon_id', $salon->id)
            ->where('provider_id', $staff->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($slotStart, $slotEnd) {
                $query
                    ->where('slot_start', '<', $slotEnd)
                    ->where('slot_end', '>', $slotStart);
            })
            ->exists();

        if ($hasConflict) {
            return response()->json([
                'message' => 'Selected slot is no longer available.',
                'status' => 'error',
            ], 422);
        }

        $appointment = Appointment::create([
            'user_id' => $request->user()?->id,
            'salon_id' => $salon->id,
            'service_id' => $service->id,
            'provider_id' => $staff->id,
            'slot_start' => $slotStart,
            'slot_end' => $slotEnd,
            'status' => 'confirmed',
            'notes' => $validated['notes'] ?? null,
        ])->load(['salon.media', 'service.media', 'serviceProvider.media', 'user']);

        return response()->json([
            'message' => 'Appointment booked successfully.',
            'status' => 'success',
            'data' => AppointmentResource::make($appointment)->resolve(),
        ], 201);
    }
}
