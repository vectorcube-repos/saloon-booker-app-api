<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SalonResource;
use App\Models\Salon;
use Illuminate\Http\JsonResponse;

class SalonController extends Controller
{
    /**
     * Return a single salon by id.
     */
    public function show(string $id): JsonResponse
    {
        $salon = Salon::with('media')->find($id);

        if (! $salon) {
            return response()->json([
                'message' => 'Salon not found.',
                'status' => 'error',
            ], 404);
        }

        return response()->json([
            'message' => 'OK',
            'status' => 'success',
            'data' => SalonResource::make($salon)->resolve(),
        ]);
    }
}
