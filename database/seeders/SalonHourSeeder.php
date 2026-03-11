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
            foreach (range(0, 6) as $day) {
                $isClosed = $day === 0; // Sunday closed
                SalonHour::create([
                    'salon_id' => $salon->id,
                    'day_of_week' => $day,
                    'open_time' => $isClosed ? '09:00' : '09:00',
                    'close_time' => $isClosed ? '17:00' : '18:00',
                    'is_closed' => $isClosed,
                ]);
            }
        }
    }
}
