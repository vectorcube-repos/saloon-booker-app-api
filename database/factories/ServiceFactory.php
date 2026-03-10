<?php

namespace Database\Factories;

use App\Models\Salon;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $durations = [30, 45, 60, 90, 120];

        return [
            'salon_id' => Salon::factory(),
            'name' => fake()->randomElement([
                'Haircut',
                'Hair Coloring',
                'Manicure',
                'Pedicure',
                'Facial',
                'Massage',
                'Beard Trim',
                'Blowout',
                'Highlights',
                'Balayage',
            ]) . (fake()->optional(0.3)->randomElement(['', ' (Premium)', ' (Express)']) ?? ''),
            'description' => fake()->optional(0.7)->sentence(),
            'duration_minutes' => fake()->randomElement($durations),
            'price_cents' => fake()->randomElement([2500, 4500, 6500, 8500, 12000, 15000]),
        ];
    }
}
