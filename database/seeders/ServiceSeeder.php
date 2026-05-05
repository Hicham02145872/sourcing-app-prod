<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

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
