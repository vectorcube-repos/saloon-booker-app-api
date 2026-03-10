<?php

namespace Database\Factories;

use App\Models\AvailabilityBlock;
use App\Models\ServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AvailabilityBlock>
 */
class AvailabilityBlockFactory extends Factory
{
    protected $model = AvailabilityBlock::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('next Monday', 'next Friday');
        $start->setTime((int) fake()->numberBetween(9, 16), 0, 0);
        $end = clone $start;
        $end->modify('+8 hours');

        return [
            'provider_id' => ServiceProvider::factory(),
            'start_datetime' => $start,
            'end_datetime' => $end,
            'is_recurring' => false,
            'recurrence_pattern' => null,
        ];
    }

    public function recurring(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_recurring' => true,
            'recurrence_pattern' => 'WEEKLY',
        ]);
    }
}
