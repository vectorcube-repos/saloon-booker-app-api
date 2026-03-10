<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Salon;
use App\Models\Service;
use App\Models\ServiceProvider;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $slotStart = fake()->dateTimeBetween('next week', '+1 month');
        $slotStart->setTime((int) fake()->numberBetween(9, 17), 0, 0);
        $slotEnd = clone $slotStart;
        $slotEnd->modify('+1 hour');

        return [
            'user_id' => User::factory()->customer(),
            'salon_id' => Salon::factory(),
            'service_id' => Service::factory(),
            'provider_id' => ServiceProvider::factory(),
            'slot_start' => $slotStart,
            'slot_end' => $slotEnd,
            'status' => fake()->randomElement(['pending', 'confirmed', 'cancelled', 'completed']),
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'confirmed']);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'completed']);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'cancelled']);
    }
}
