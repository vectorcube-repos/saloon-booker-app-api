<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Salon;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $salons = Salon::with(['services', 'serviceProviders'])->get();

        if ($customers->isEmpty() || $salons->isEmpty()) {
            return;
        }

        foreach ($salons as $salon) {
            $services = $salon->services->filter(fn ($s) => $s->pivot->is_active);
            if ($services->isEmpty()) {
                continue;
            }

            $appointmentsPerSalon = rand(8, 25);
            for ($i = 0; $i < $appointmentsPerSalon; $i++) {
                $service = $services->random();
                $provider = $salon->serviceProviders->isNotEmpty()
                    ? $salon->serviceProviders->random()
                    : null;

                $daysOffset = rand(-30, 30);
                $minute = [0, 15, 30, 45][array_rand([0, 15, 30, 45])];
                $slotStart = Carbon::now()->addDays($daysOffset)->setTime(rand(9, 16), $minute, 0);
                $slotEnd = (clone $slotStart)->addMinutes($service->pivot->duration_minutes);

                $status = $daysOffset < 0
                    ? fake()->randomElement(['completed', 'completed', 'completed', 'cancelled'])
                    : fake()->randomElement(['pending', 'confirmed', 'confirmed', 'confirmed']);

                Appointment::create([
                    'user_id' => $customers->random()->id,
                    'salon_id' => $salon->id,
                    'service_id' => $service->id,
                    'provider_id' => $provider?->id,
                    'slot_start' => $slotStart,
                    'slot_end' => $slotEnd,
                    'status' => $status,
                    'notes' => fake()->optional(0.2)->sentence(),
                ]);
            }
        }
    }
}
