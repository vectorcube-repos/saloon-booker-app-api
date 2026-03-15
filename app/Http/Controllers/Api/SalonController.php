<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use Illuminate\Http\JsonResponse;

class SalonController extends Controller
{
    /**
     * Return a single salon by id.
     */
    public function show(string $id): JsonResponse
    {
        $salon = Salon::find($id);

        if (! $salon) {
            return response()->json([
                'message' => 'Salon not found.',
                'status' => 'error',
            ], 404);
        }

        return response()->json([
            'message' => 'OK',
            'status' => 'success',
            'data' => [
                'product' => 12,
                'id' => $salon->id,
                'owner_id' => $salon->owner_id,
                'name' => $salon->name,
                'description' => $salon->description,
                'phone' => $salon->phone,
                'address' => $salon->address,
                'city' => $salon->city,
                'state' => $salon->state,
                'postal_code' => $salon->postal_code,
                'latitude' => $salon->latitude,
                'longitude' => $salon->longitude,
                'status' => $salon->status,
            ],
        ]);
    }
}
