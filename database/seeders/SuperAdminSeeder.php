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
        // Delete the old super admin if exists
        \App\Models\User::where('email', 'mehdi@gmail.com')->delete();

        // Add new super admins
        \App\Models\User::updateOrCreate(
            ['email' => 'fastsourcingbrothers2@gmail.com'],
            [
                'name' => 'Super Admin 1',
                'password' => \Illuminate\Support\Facades\Hash::make('MOHAMMED12345'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
            ]
        );

        \App\Models\User::updateOrCreate(
            ['email' => 'fastsourcingbrothers1@gmail.com'],
            [
                'name' => 'Super Admin 2',
                'password' => \Illuminate\Support\Facades\Hash::make('MOHAMMED12345'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
            ]
        );

        \App\Models\User::updateOrCreate(
            ['email' => 'contact@fastsourcingbrothers.com'],
            [
                'name' => 'Super Admin Contact',
                'password' => \Illuminate\Support\Facades\Hash::make('contact@fastsourcingbrothers.com'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
