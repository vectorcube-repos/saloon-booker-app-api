<?php

namespace Database\Seeders;

use App\Models\Salon;
use App\Models\ServiceProvider;
use App\Models\User;
use Illuminate\Database\Seeder;

class ServiceProviderSeeder extends Seeder
{
    public function run(): void
    {
        $salons = Salon::with('services')->get();
        $staffUsers = User::where('role', 'staff')->get();

        foreach ($salons as $salon) {
            $count = rand(4, 8);
            $services = $salon->services;

            for ($i = 0; $i < $count; $i++) {
                $staff = $staffUsers->isNotEmpty() ? $staffUsers->random() : null;
                $provider = ServiceProvider::create([
                    'salon_id' => $salon->id,
                    'user_id' => $staff?->id,
                    'display_name' => fake()->firstName() . ' ' . fake()->lastName(),
                    'bio' => fake()->optional(0.7)->paragraph(),
                    'skill_tags' => fake()->randomElements(['hair', 'nails', 'skincare', 'massage', 'color', 'styling', 'mens', 'bridal'], rand(2, 5)),
                    'active' => fake()->boolean(95),
                ]);

                if ($services->isNotEmpty()) {
                    $provider->services()->attach(
                        $services->random(rand(2, min(6, $services->count())))->pluck('id')
                    );
                }
            }
        }
    }
}
