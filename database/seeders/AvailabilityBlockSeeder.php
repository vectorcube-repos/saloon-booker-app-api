<?php

namespace Database\Seeders;

use App\Models\AvailabilityBlock;
use App\Models\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AvailabilityBlockSeeder extends Seeder
{
    public function run(): void
    {
        $providers = ServiceProvider::all();

        foreach ($providers as $provider) {
            $base = Carbon::now()->next('Monday')->setTime(9, 0, 0);

            foreach (range(0, 3) as $weekOffset) {
                foreach ([0, 1, 2, 3, 4] as $day) {
                    $start = (clone $base)->addWeeks($weekOffset)->addDays($day);
                    $end = (clone $start)->addHours(8);

                    AvailabilityBlock::create([
                        'provider_id' => $provider->id,
                        'start_datetime' => $start,
                        'end_datetime' => $end,
                        'is_recurring' => false,
                    ]);
                }
            }
        }
    }
}
