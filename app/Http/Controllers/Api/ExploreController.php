<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SalonListResource;
use App\Http\Resources\ServiceResource;
use App\Models\Salon;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ExploreController extends Controller
{
    /**
     * List all salons (paginated) and all categories (global services).
     */
    public function __invoke(Request $request): JsonResponse
    {

        $salonsQuery = Salon::where('status', 'active')
            ->with('media')
            ->orderBy('name');

        if ($request->filled('service_id')) {
            $salonsQuery->whereHas('services', fn ($q) => $q->where('services.id', $request->service_id));
        }

        $salons = $salonsQuery->paginate(6);

        $data = [
            'status' => true,
            'message' => 'Explore screen data fetched successfully',
            'data' => [
                'salons' => new SalonListResource($salons),
            ],
        ];

        if ($request->has('initial') && $request->initial == '1') {
            $categories = Service::whereNull('salon_id')
                ->with('media')
                ->orderBy('name')
                ->get();

            $data['data']['services'] = ServiceResource::collection($categories);
        }

        return response()->json($data, 200);
    }
}
