<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Salon;
use App\Models\Service;
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

        foreach (range(1, 15) as $i) {
            $salon = $salons->random();
            $service = $salon->services->random();
            $provider = $salon->serviceProviders->isNotEmpty()
                ? $salon->serviceProviders->random()
                : null;

            $slotStart = Carbon::now()->addDays(rand(1, 14))->setTime(rand(9, 16), 0, 0);
            $slotEnd = (clone $slotStart)->addMinutes($service->duration_minutes);

            Appointment::create([
                'user_id' => $customers->random()->id,
                'salon_id' => $salon->id,
                'service_id' => $service->id,
                'provider_id' => $provider?->id,
                'slot_start' => $slotStart,
                'slot_end' => $slotEnd,
                'status' => fake()->randomElement(['pending', 'confirmed', 'confirmed', 'completed']),
                'notes' => fake()->optional(0.2)->sentence(),
            ]);
        }
    }
}
