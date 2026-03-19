<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Profile fetched successfully.',
            'status' => 'success',
            'data' => ProfileResource::make($request->user())->resolve(),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'phone')->ignore($user->id),
            ],
        ]);

        [$firstName, $lastName] = $this->splitName($validated['name']);

        $user->update([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $validated['phone'],
        ]);

        return response()->json([
            'message' => 'Profile updated successfully.',
            'status' => 'success',
            'data' => ProfileResource::make($user->fresh())->resolve(),
        ]);
    }

    private function splitName(string $name): array
    {
        $name = trim(Str::squish($name));
        $parts = explode(' ', $name, 2);

        return [
            $parts[0] ?? '',
            $parts[1] ?? null,
        ];
    }
}
