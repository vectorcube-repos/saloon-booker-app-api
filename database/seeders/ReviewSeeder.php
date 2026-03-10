<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Review;
use App\Models\Salon;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $salons = Salon::all();
        $customers = User::where('role', 'customer')->get();

        if ($salons->isEmpty() || $customers->isEmpty()) {
            return;
        }

        $completedAppointments = Appointment::where('status', 'completed')
            ->with(['salon', 'user'])
            ->get();

        foreach ($completedAppointments->take(10) as $appointment) {
            Review::create([
                'salon_id' => $appointment->salon_id,
                'user_id' => $appointment->user_id,
                'appointment_id' => $appointment->id,
                'rating' => fake()->numberBetween(1, 5),
                'comment' => fake()->optional(0.8)->paragraph(),
            ]);
        }

        foreach (range(1, 10) as $i) {
            Review::create([
                'salon_id' => $salons->random()->id,
                'user_id' => $customers->random()->id,
                'appointment_id' => null,
                'rating' => fake()->numberBetween(1, 5),
                'comment' => fake()->optional(0.8)->paragraph(),
            ]);
        }
    }
}
