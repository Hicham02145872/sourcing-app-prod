<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DevUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'hichamaltit@gmail.com'],
            [
                'name' => 'Hicham Dev',
                'password' => Hash::make('Hicham2334'),
                'role' => 'developer',
                'email_verified_at' => now(),
            ]
        );
    }
}
