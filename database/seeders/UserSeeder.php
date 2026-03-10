<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->admin()->create([
            'phone' => '+15551234567',
            'email' => 'admin@example.com',
            'first_name' => 'Admin',
            'last_name' => 'User',
        ]);

        User::factory()->owner()->count(3)->create();

        User::factory()->staff()->count(5)->create();

        User::factory()->customer()->count(20)->create();
    }
}
