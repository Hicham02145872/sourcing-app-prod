<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Hicham Altit',
            'email' => 'mehdi@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('Hicham2334'),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);
    }
}
