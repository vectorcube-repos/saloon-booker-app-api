<?php

namespace Database\Factories;

use App\Models\Salon;
use App\Models\SalonHour;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SalonHour>
 */
class SalonHourFactory extends Factory
{
    protected $model = SalonHour::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $open = fake()->numberBetween(8, 10);
        $close = fake()->numberBetween(17, 21);

        return [
            'salon_id' => Salon::factory(),
            'day_of_week' => fake()->numberBetween(0, 6),
            'open_time' => sprintf('%02d:00:00', $open),
            'close_time' => sprintf('%02d:00:00', $close),
            'is_closed' => false,
        ];
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_closed' => true,
            'open_time' => '00:00:00',
            'close_time' => '00:00:00',
        ]);
    }
}
