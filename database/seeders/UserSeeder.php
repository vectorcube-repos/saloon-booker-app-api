<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'phone' => '+15551234567',
            'email' => 'admin@example.com',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'role' => 'admin',
            'password_hash' => 'password',
        ]);

        foreach (range(1, 20) as $i) {
            User::create([
                'phone' => '+1555' . str_pad((string) (1000000 + $i), 7, '0'),
                'email' => "owner{$i}@example.com",
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'role' => 'owner',
                'password_hash' => 'password',
            ]);
        }

        foreach (range(1, 40) as $i) {
            User::create([
                'phone' => '+1555' . str_pad((string) (2000000 + $i), 7, '0'),
                'email' => "staff{$i}@example.com",
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'role' => 'staff',
                'password_hash' => 'password',
            ]);
        }

        foreach (range(1, 80) as $i) {
            User::create([
                'phone' => '+1555' . str_pad((string) (3000000 + $i), 7, '0'),
                'email' => "customer{$i}@example.com",
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'role' => 'customer',
                'password_hash' => 'password',
            ]);
        }
    }
}
