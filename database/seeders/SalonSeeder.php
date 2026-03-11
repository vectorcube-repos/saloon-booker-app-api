<?php

namespace Database\Seeders;

use App\Models\Salon;
use App\Models\User;
use Illuminate\Database\Seeder;

class SalonSeeder extends Seeder
{
    public function run(): void
    {
        $owners = User::where('role', 'owner')->get();

        foreach ($owners as $index => $owner) {
            Salon::create([
                'owner_id' => $owner->id,
                'name' => fake()->company() . ' Salon',
                'description' => fake()->optional(0.8)->paragraph(),
                'phone' => fake()->phoneNumber(),
                'address' => fake()->streetAddress(),
                'city' => fake()->city(),
                'state' => fake()->stateAbbr(),
                'postal_code' => fake()->postcode(),
                'latitude' => fake()->latitude(),
                'longitude' => fake()->longitude(),
                'status' => fake()->randomElement(['active', 'active', 'closed']),
            ]);
        }
    }
}
