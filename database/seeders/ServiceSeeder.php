<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            ['name' => 'Aramex'],
            ['name' => 'FedEx'],
            ['name' => 'DHL'],
            ['name' => 'Chronopost'],
            ['name' => 'UPS'],
            ['name' => 'Jumia Express'],
            ['name' => 'Amana (Poste Maroc)'],
            ['name' => 'Tashil Express'],
            ['name' => 'STG Maroc'],
            ['name' => 'Ameex'],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate($service);
        }
    }
}
