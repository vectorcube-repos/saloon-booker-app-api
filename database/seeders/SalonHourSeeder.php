<?php

namespace Database\Seeders;

use App\Models\Salon;
use App\Models\SalonHour;
use Illuminate\Database\Seeder;

class SalonHourSeeder extends Seeder
{
    public function run(): void
    {
        $salons = Salon::all();

        foreach ($salons as $salon) {
            foreach (range(0, 6) as $dayOfWeek) {
                SalonHour::create([
                    'salon_id' => $salon->id,
                    'day_of_week' => $dayOfWeek,
                    'open_time' => $dayOfWeek === 0 ? '00:00:00' : '09:00:00',
                    'close_time' => $dayOfWeek === 0 ? '00:00:00' : '18:00:00',
                    'is_closed' => $dayOfWeek === 0,
                ]);
            }
        }
    }
}
