<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'hicham.altit2004@gmail.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('Hicham2334'), // You can change the password here
                'role' => 'admin',
            ]
        );
        User::updateOrCreate(
            ['email' => 'Nada.tayebi.28@edu.uiz.ac.ma'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('Nada2334'), // You can change the password here
                'role' => 'admin',
            ]
        );
    }
}
