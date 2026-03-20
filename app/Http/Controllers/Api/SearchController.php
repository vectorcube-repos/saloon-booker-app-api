<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SalonResource;
use App\Http\Resources\ServiceResource;
use App\Models\Salon;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'max:120'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $query = trim($validated['q']);
        $limit = $validated['limit'] ?? 10;
        $user = $request->user();

        if (mb_strlen($query) < 2) {
            return response()->json([
                'message' => 'OK',
                'status' => 'success',
                'data' => [
                    'query' => $query,
                    'salons' => [],
                    'services' => [],
                ],
            ]);
        }

        $salons = Salon::query()
            ->where('status', 'active')
            ->where(function ($builder) use ($query) {
                $builder
                    ->where('name', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%')
                    ->orWhere('city', 'like', '%' . $query . '%')
                    ->orWhere('state', 'like', '%' . $query . '%')
                    ->orWhere('address', 'like', '%' . $query . '%');
            })
            ->with('media')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->withExists([
                'favoritedByUsers as is_favorite' => fn ($builder) => $builder->where('users.id', $user->id),
            ])
            ->orderBy('name')
            ->limit($limit)
            ->get();

        $services = Service::query()
            ->where(function ($builder) use ($query) {
                $builder
                    ->where('name', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%');
            })
            ->whereHas('salons', fn ($builder) => $builder->where('salon_service.is_active', true))
            ->with('media')
            ->withCount([
                'salons as active_salons_count' => fn ($builder) => $builder->where('salon_service.is_active', true),
            ])
            ->orderBy('name')
            ->limit($limit)
            ->get();

        return response()->json([
            'message' => 'OK',
            'status' => 'success',
            'data' => [
                'query' => $query,
                'salons' => SalonResource::collection($salons)->resolve(),
                'services' => ServiceResource::collection($services)->resolve(),
            ],
        ]);
    }
}
