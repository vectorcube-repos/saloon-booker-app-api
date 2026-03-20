<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class LocationSearchService
{
    public function search(
        string $query,
        ?string $country = null,
        ?float $latitude = null,
        ?float $longitude = null,
        int $limit = 8,
        ?string $sessionToken = null,
    ): array
    {
        $apiKey = config('services.google_maps.key');

        if (! $apiKey) {
            throw new RuntimeException('Google Maps API key is not configured.');
        }

        if (mb_strlen(trim($query)) < 4) {
            return [];
        }

        $payload = [
            'input' => $query,
            'includeQueryPredictions' => false,
            'languageCode' => 'en',
            'includedRegionCodes' => $country ? [strtolower($country)] : [],
        ];

        if ($sessionToken) {
            $payload['sessionToken'] = $sessionToken;
        }

        if ($latitude !== null && $longitude !== null) {
            $payload['locationBias'] = [
                'circle' => [
                    'center' => [
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                    ],
                    'radius' => 50000,
                ],
            ];
        }

        try {
            $response = Http::timeout(10)
                ->acceptJson()
                ->withHeaders([
                    'X-Goog-Api-Key' => $apiKey,
                    'X-Goog-FieldMask' => 'suggestions.placePrediction.placeId,suggestions.placePrediction.text.text,suggestions.placePrediction.structuredFormat.mainText.text,suggestions.placePrediction.structuredFormat.secondaryText.text,suggestions.placePrediction.types',
                ])
                ->post(config('services.google_maps.autocomplete_url'), $payload)
                ->throw();
        } catch (RequestException $exception) {
            $providerMessage = Arr::get($exception->response?->json(), 'error.message')
                ?? Arr::get($exception->response?->json(), 'error.status')
                ?? $exception->response?->body()
                ?? 'Location search provider request failed.';

            throw new RuntimeException('Location search provider request failed: ' . $providerMessage, previous: $exception);
        }

        return collect($response->json('suggestions', []))
            ->take(max(1, min($limit, 10)))
            ->map(function (array $suggestion): ?array {
                $prediction = $suggestion['placePrediction'] ?? null;

                if (! $prediction || empty($prediction['placeId'])) {
                    return null;
                }

                $mainText = data_get($prediction, 'structuredFormat.mainText.text')
                    ?? data_get($prediction, 'text.text');
                $secondaryText = data_get($prediction, 'structuredFormat.secondaryText.text');
                $fullText = data_get($prediction, 'text.text');

                return [
                    'place_id' => $prediction['placeId'],
                    'name' => $mainText ?: $fullText,
                    'address' => $secondaryText ?: ($fullText ?: $mainText),
                    'main_text' => $mainText ?: $fullText,
                    'secondary_text' => $secondaryText,
                    'latitude' => null,
                    'longitude' => null,
                    'types' => $prediction['types'] ?? [],
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    public function details(string $placeId, ?string $sessionToken = null): array
    {
        $apiKey = config('services.google_maps.key');

        if (! $apiKey) {
            throw new RuntimeException('Google Maps API key is not configured.');
        }

        try {
            $request = Http::timeout(10)
                ->acceptJson()
                ->withHeaders([
                    'X-Goog-Api-Key' => $apiKey,
                    'X-Goog-FieldMask' => 'id,displayName.text,formattedAddress,location,types',
                ]);

            if ($sessionToken) {
                $request = $request->withHeaders([
                    'X-Goog-Session-Token' => $sessionToken,
                ]);
            }

            $response = $request
                ->get(rtrim(config('services.google_maps.place_details_url'), '/') . '/' . urlencode($placeId))
                ->throw();
        } catch (RequestException $exception) {
            $providerMessage = Arr::get($exception->response?->json(), 'error.message')
                ?? Arr::get($exception->response?->json(), 'error.status')
                ?? $exception->response?->body()
                ?? 'Location details provider request failed.';

            throw new RuntimeException('Location details provider request failed: ' . $providerMessage, previous: $exception);
        }

        return [
            'place_id' => $response->json('id'),
            'name' => $response->json('displayName.text'),
            'address' => $response->json('formattedAddress'),
            'latitude' => $response->json('location.latitude'),
            'longitude' => $response->json('location.longitude'),
            'main_text' => $response->json('displayName.text'),
            'secondary_text' => $response->json('formattedAddress'),
            'types' => $response->json('types', []),
        ];
    }

    public function reverseGeocode(float $latitude, float $longitude): array
    {
        $apiKey = config('services.google_maps.key');

        if (! $apiKey) {
            throw new RuntimeException('Google Maps API key is not configured.');
        }

        try {
            $response = Http::timeout(10)
                ->acceptJson()
                ->get(config('services.google_maps.geocoding_url'), [
                    'latlng' => $latitude . ',' . $longitude,
                    'key' => $apiKey,
                ])
                ->throw();
        } catch (RequestException $exception) {
            $providerMessage = Arr::get($exception->response?->json(), 'error_message')
                ?? Arr::get($exception->response?->json(), 'status')
                ?? $exception->response?->body()
                ?? 'Reverse geocoding provider request failed.';

            throw new RuntimeException('Reverse geocoding provider request failed: ' . $providerMessage, previous: $exception);
        }

        $payload = $response->json();
        $status = $payload['status'] ?? null;

        if (! in_array($status, ['OK', 'ZERO_RESULTS'], true)) {
            throw new RuntimeException('Reverse geocoding provider returned an error: ' . $status);
        }

        $result = collect($payload['results'] ?? [])->first();

        if (! $result) {
            return [
                'place_id' => null,
                'name' => null,
                'address' => null,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'main_text' => null,
                'secondary_text' => null,
                'types' => [],
            ];
        }

        $formattedAddress = $result['formatted_address'] ?? null;
        $mainText = $formattedAddress ? trim((string) explode(',', $formattedAddress)[0]) : null;

        return [
            'place_id' => $result['place_id'] ?? null,
            'name' => $mainText,
            'address' => $formattedAddress,
            'latitude' => data_get($result, 'geometry.location.lat', $latitude),
            'longitude' => data_get($result, 'geometry.location.lng', $longitude),
            'main_text' => $mainText,
            'secondary_text' => $formattedAddress,
            'types' => $result['types'] ?? [],
        ];
    }
}
