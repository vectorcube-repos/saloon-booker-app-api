<?php

namespace Database\Seeders;

use App\Models\Salon;
use App\Models\User;
use Illuminate\Database\Seeder;

class SalonSeeder extends Seeder
{
    public function run(): void
    {
        $owners = User::where('role', 'owner')->get();
        if ($owners->isEmpty()) {
            $owners = User::factory()->owner()->count(3)->create();
        }

        foreach ($owners as $owner) {
            Salon::factory()->active()->count(rand(1, 2))->create([
                'owner_id' => $owner->id,
            ]);
        }
    }
}
