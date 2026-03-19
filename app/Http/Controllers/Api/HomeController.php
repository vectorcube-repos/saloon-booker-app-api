<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\SalonResource;
use App\Http\Resources\ServiceResource;
use App\Models\Appointment;
use App\Models\Salon;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Home payload: global services, 10 active salons, 5 most booked services.
     */
    public function __invoke(Request $request)
    {
        $globalServices = Service::whereNull('salon_id')
            ->with('media')
            ->get();

        $salons = Salon::where('status', 'active')
            ->with('media')
            ->withExists([
                'favoritedByUsers as is_favorite' => fn ($query) => $query->where('users.id', $request->user()->id),
            ])
            ->limit(10)
            ->get();

        $mostBookedServices = Service::withCount('appointments')
            ->with('media')
            ->orderByDesc('appointments_count')
            ->limit(5)
            ->get();

        $latestBooking = null;
        $user = $request->user('sanctum');

        if ($user) {
            $latestBooking = Appointment::query()
                ->where('user_id', $user->id)
                ->with([
                    'salon.media',
                    'service.media',
                    'serviceProvider.media',
                ])
                ->orderByDesc('slot_start')
                ->first();
        }

        return response()->json([
            'message' => 'Home screen data fetched successfully',
            'status' => true,
            'data' => [
                'services' => ServiceResource::collection($globalServices),
                'salons' => SalonResource::collection($salons),
                'most_booked_services' => ServiceResource::collection($mostBookedServices),
                'latest_booking' => $latestBooking ? AppointmentResource::make($latestBooking) : null,
            ],
        ]);
    }
}
