<?php

namespace Database\Seeders;

use App\Models\Salon;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $globalCatalog = [
            ['name' => 'Haircut', 'description' => 'Classic haircut and styling'],
            ['name' => 'Hair Coloring', 'description' => 'Full color or highlights'],
            ['name' => 'Manicure', 'description' => 'Nail care and polish'],
            ['name' => 'Pedicure', 'description' => 'Foot care and polish'],
            ['name' => 'Facial', 'description' => 'Cleansing facial treatment'],
            ['name' => 'Massage', 'description' => 'Relaxing massage'],
            ['name' => 'Beard Trim', 'description' => 'Beard shaping and grooming'],
            ['name' => 'Blowout', 'description' => 'Blow dry and styling'],
            ['name' => 'Highlights', 'description' => 'Partial or full highlights'],
            ['name' => 'Balayage', 'description' => 'Hand-painted highlights'],
            ['name' => 'Keratin Treatment', 'description' => 'Smoothing keratin treatment'],
            ['name' => 'Deep Conditioning', 'description' => 'Intensive hair treatment'],
        ];

        $globalServices = [];
        foreach ($globalCatalog as $item) {
            $globalServices[] = Service::create([
                'salon_id' => null,
                'name' => $item['name'],
                'description' => $item['description'],
            ]);
        }

        $salons = Salon::all();
        foreach ($salons as $salon) {
            $chosen = fake()->randomElements($globalServices, rand(6, count($globalServices)));
            foreach ($chosen as $service) {
                $salon->services()->attach($service->id, [
                    'duration_minutes' => fake()->randomElement([30, 45, 60, 90, 120]),
                    'rate' => fake()->randomElement([2500, 4500, 6500, 8500, 12000]),
                    'is_active' => fake()->boolean(90),
                ]);
            }

            foreach (range(1, rand(2, 4)) as $j) {
                $privateService = Service::create([
                    'salon_id' => $salon->id,
                    'name' => 'Special ' . fake()->word() . ' ' . fake()->word() . ' Treatment',
                    'description' => fake()->optional(0.7)->sentence(),
                ]);
                $salon->services()->attach($privateService->id, [
                    'duration_minutes' => fake()->randomElement([45, 60, 90]),
                    'rate' => fake()->randomElement([5000, 7500, 10000, 15000]),
                    'is_active' => true,
                ]);
            }
        }
    }
}
