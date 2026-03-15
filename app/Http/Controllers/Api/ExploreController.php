<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    /**
     * List all salons (paginated) and all categories (global services).
     */
    public function __invoke(Request $request): JsonResponse
    {
        $perPage = max(1, min(50, (int) $request->get('per_page', 15)));

        $salons = Salon::where('status', 'active')
            ->orderBy('name')
            ->paginate($perPage, [
                'id', 'name', 'description', 'phone', 'address',
                'city', 'state', 'postal_code', 'latitude', 'longitude', 'status',
            ]);

        $categories = Service::whereNull('salon_id')
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'message' => 'OK',
            'status' => 'success',
            'data' => [
                'salons' => $salons,
                'categories' => $categories,
            ],
        ]);
    }
}
