<?php

namespace Database\Seeders;

use App\Models\Salon;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $salons = Salon::all();

        $serviceTemplates = [
            ['name' => 'Haircut', 'duration_minutes' => 30, 'price_cents' => 3500],
            ['name' => 'Hair Coloring', 'duration_minutes' => 90, 'price_cents' => 8500],
            ['name' => 'Manicure', 'duration_minutes' => 45, 'price_cents' => 4500],
            ['name' => 'Pedicure', 'duration_minutes' => 60, 'price_cents' => 5500],
            ['name' => 'Facial', 'duration_minutes' => 60, 'price_cents' => 7500],
            ['name' => 'Massage', 'duration_minutes' => 60, 'price_cents' => 8000],
            ['name' => 'Beard Trim', 'duration_minutes' => 20, 'price_cents' => 2500],
            ['name' => 'Blowout', 'duration_minutes' => 45, 'price_cents' => 5000],
            ['name' => 'Highlights', 'duration_minutes' => 120, 'price_cents' => 12000],
        ];

        foreach ($salons as $salon) {
            $used = [];
            foreach (fake()->randomElements($serviceTemplates, rand(4, count($serviceTemplates))) as $template) {
                $name = $template['name'];
                if (in_array($name, $used, true)) {
                    $name .= ' (Premium)';
                }
                $used[] = $template['name'];
                Service::create([
                    'salon_id' => $salon->id,
                    'name' => $name,
                    'description' => fake()->optional(0.6)->sentence(),
                    'duration_minutes' => $template['duration_minutes'],
                    'price_cents' => $template['price_cents'] + fake()->randomElement([0, 500, 1000]),
                ]);
            }
        }
    }
}
