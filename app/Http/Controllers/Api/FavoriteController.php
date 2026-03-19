<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SalonResource;
use App\Models\Salon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $salons = $user->favoriteSalons()
            ->with('media')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->withExists([
                'favoritedByUsers as is_favorite' => fn ($query) => $query->where('users.id', $user->id),
            ])
            ->orderByPivot('created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'Favorite salons fetched successfully.',
            'status' => 'success',
            'data' => SalonResource::collection($salons)->resolve(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'salon_id' => ['required', 'integer', 'exists:salons,id'],
        ]);

        $user = $request->user();

        $user->favoriteSalons()->syncWithoutDetaching([$validated['salon_id']]);

        $salon = Salon::query()
            ->with('media')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->withExists([
                'favoritedByUsers as is_favorite' => fn ($query) => $query->where('users.id', $user->id),
            ])
            ->findOrFail($validated['salon_id']);

        return response()->json([
            'message' => 'Salon marked as favorite.',
            'status' => 'success',
            'data' => SalonResource::make($salon)->resolve(),
        ], 201);
    }

    public function destroy(Request $request, Salon $salon): JsonResponse
    {
        $user = $request->user();

        $user->favoriteSalons()->detach($salon->id);

        $salon->load('media');
        $salon->loadCount('reviews');
        $salon->loadAvg('reviews', 'rating');
        $salon->setAttribute('is_favorite', false);

        return response()->json([
            'message' => 'Salon removed from favorites.',
            'status' => 'success',
            'data' => SalonResource::make($salon)->resolve(),
        ]);
    }
}
