<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SalonResource;
use App\Http\Resources\ServiceResource;
use App\Models\Salon;
use App\Models\Service;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    /**
     * Home payload: global services, 10 active salons, 5 most booked services.
     */
    public function __invoke(): JsonResponse
    {
        $globalServices = Service::whereNull('salon_id')
            ->with('media')
            ->get();

        $salons = Salon::where('status', 'active')
            ->with('media')
            ->limit(10)
            ->get();

        $mostBookedServices = Service::withCount('appointments')
            ->with('media')
            ->orderByDesc('appointments_count')
            ->limit(5)
            ->get();

        return response()->json([
            'message' => 'Home screen data fetched successfully',
            'status' => true,
            'data' => [
                'services' => ServiceResource::collection($globalServices),
                'salons' => SalonResource::collection($salons), 
                'most_booked_services' => ServiceResource::collection($mostBookedServices)
            ],
        ]);
    }
}
