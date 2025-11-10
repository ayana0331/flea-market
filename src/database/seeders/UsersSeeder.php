<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'postal_code' => '1234567',
                'address' => '東京都新宿区1-1-1',
                'building' => 'テストビル',
                'remember_token' => Str::random(10),
            ]
        );
    }
}
