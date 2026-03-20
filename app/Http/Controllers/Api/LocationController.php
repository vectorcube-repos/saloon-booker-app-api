<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocationDetailsResource;
use App\Http\Resources\LocationSearchResultResource;
use App\Services\LocationSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class LocationController extends Controller
{
    public function __construct(
        private readonly LocationSearchService $locationSearchService,
    ) {}

    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'max:120'],
            'country' => ['nullable', 'string', 'size:2'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:10'],
            'session_token' => ['nullable', 'string', 'max:36'],
        ]);

        if (mb_strlen(trim($validated['q'])) < 4) {
            return response()->json([
                'message' => 'OK',
                'status' => 'success',
                'data' => [],
            ]);
        }

        try {
            $locations = $this->locationSearchService->search(
                query: $validated['q'],
                country: isset($validated['country']) ? strtoupper($validated['country']) : null,
                latitude: isset($validated['lat']) ? (float) $validated['lat'] : null,
                longitude: isset($validated['lng']) ? (float) $validated['lng'] : null,
                limit: $validated['limit'] ?? 8,
                sessionToken: $validated['session_token'] ?? null,
            );
        } catch (RuntimeException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => 'error',
                'data' => [],
            ], 503);
        }

        return response()->json([
            'message' => 'OK',
            'status' => 'success',
            'data' => LocationSearchResultResource::collection(collect($locations))->resolve(),
        ]);
    }

    public function show(Request $request, string $placeId): JsonResponse
    {
        $validated = $request->validate([
            'session_token' => ['nullable', 'string', 'max:36'],
        ]);

        try {
            $location = $this->locationSearchService->details(
                placeId: $placeId,
                sessionToken: $validated['session_token'] ?? null,
            );
        } catch (RuntimeException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => 'error',
                'data' => null,
            ], 503);
        }

        return response()->json([
            'message' => 'OK',
            'status' => 'success',
            'data' => LocationDetailsResource::make($location)->resolve(),
        ]);
    }

    public function reverse(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        try {
            $location = $this->locationSearchService->reverseGeocode(
                latitude: (float) $validated['latitude'],
                longitude: (float) $validated['longitude'],
            );
        } catch (RuntimeException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => 'error',
                'data' => null,
            ], 503);
        }

        return response()->json([
            'message' => 'OK',
            'status' => 'success',
            'data' => LocationDetailsResource::make($location)->resolve(),
        ]);
    }
}
