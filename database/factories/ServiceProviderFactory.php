<?php

namespace Database\Factories;

use App\Models\Salon;
use App\Models\ServiceProvider;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceProvider>
 */
class ServiceProviderFactory extends Factory
{
    protected $model = ServiceProvider::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tags = ['hair', 'nails', 'skincare', 'massage', 'color', 'styling', 'mens', 'bridal'];

        return [
            'salon_id' => Salon::factory(),
            'user_id' => null,
            'display_name' => fake()->firstName() . ' ' . fake()->lastName(),
            'bio' => fake()->optional(0.7)->paragraph(),
            'skill_tags' => fake()->randomElements($tags, fake()->numberBetween(1, 4)),
            'active' => true,
        ];
    }

    public function withUser(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => User::factory()->staff(),
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => ['active' => false]);
    }
}
