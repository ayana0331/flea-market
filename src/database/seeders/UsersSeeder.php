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
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'テストユーザー',
                'password' => bcrypt('password'),
                'postal_code' => '1234567',
                'address' => '東京都新宿区1-1-1',
                'building' => 'テストビル',
                'profile_image' => 'profiles/test.png',
            ]
        );

        User::updateOrCreate(
            ['email' => 'test2@example.com'],
            [
                'name' => 'テストユーザー2',
                'password' => bcrypt('password'),
                'postal_code' => '9876543',
                'address' => '大阪府大阪市北区2-2-2',
                'building' => 'マンション202',
                'profile_image' => 'profiles/test2.png',
            ]
        );

        User::updateOrCreate(
            ['email' => 'test3@example.com'],
            [
                'name' => 'テストユーザー3',
                'password' => bcrypt('password'),
                'postal_code' => '1230000',
                'address' => '大阪府大阪市北区3',
                'building' => 'マンション303',
                'profile_image' => 'profiles/test3.png',
            ]
        );
    }
}
