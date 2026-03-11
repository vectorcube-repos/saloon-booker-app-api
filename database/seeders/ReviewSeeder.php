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
        $completedAppointments = Appointment::with('salon')
            ->where('status', 'completed')
            ->get();

        $customers = User::where('role', 'customer')->get();
        $reviewed = [];

        foreach ($completedAppointments->random(min(80, $completedAppointments->count())) as $appointment) {
            $key = "{$appointment->id}";
            if (isset($reviewed[$key])) {
                continue;
            }
            $reviewed[$key] = true;

            Review::create([
                'salon_id' => $appointment->salon_id,
                'user_id' => $appointment->user_id ?? $customers->random()->id,
                'appointment_id' => $appointment->id,
                'rating' => fake()->numberBetween(3, 5),
                'comment' => fake()->optional(0.7)->paragraph(),
            ]);
        }

        $salons = Salon::all();
        foreach ($salons as $salon) {
            $reviewsToAdd = rand(3, 8);
            for ($i = 0; $i < $reviewsToAdd; $i++) {
                $customer = $customers->random();
                if (! Review::where('salon_id', $salon->id)->where('user_id', $customer->id)->exists()) {
                    Review::create([
                        'salon_id' => $salon->id,
                        'user_id' => $customer->id,
                        'appointment_id' => null,
                        'rating' => fake()->numberBetween(1, 5),
                        'comment' => fake()->optional(0.7)->paragraph(),
                    ]);
                }
            }
        }
    }
}
