<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
            ->get(['id', 'name', 'description']);

        $salons = Salon::where('status', 'active')
            ->limit(10)
            ->get(['id', 'name', 'description', 'phone', 'address', 'city', 'state', 'postal_code', 'latitude', 'longitude']);

        $mostBookedServices = Service::withCount('appointments')
            ->orderByDesc('appointments_count')
            ->limit(5)
            ->get(['id', 'name', 'description', 'salon_id']);

        return response()->json([
            'message' => 'Welcome to the Saloon Booker App API!',
            'version' => '1.0.0',
            'status' => 'success',
            'data' => [
                'global_services' => $globalServices,
                'salons' => $salons,
                'most_booked_services' => $mostBookedServices,
            ],
        ]);
    }
}
