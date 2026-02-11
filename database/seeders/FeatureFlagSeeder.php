<?php

namespace Database\Seeders;

use App\Models\FeatureFlag;
use Illuminate\Database\Seeder;

class FeatureFlagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            [
                'key' => 'tracking',
                'name' => 'Shipment Tracking',
                'status' => 'visible',
                'roles' => [], // Empty roles means public access (within authentication)
            ],
            [
                'key' => 'refunds',
                'name' => 'Refund Claims',
                'status' => 'coming_soon', // Setting to coming_soon to demonstrate the new fallback logic
                'roles' => [],
            ],
        ];

        foreach ($features as $feature) {
            FeatureFlag::updateOrCreate(
                ['key' => $feature['key']],
                [
                    'name' => $feature['name'],
                    'status' => $feature['status'],
                    'roles' => $feature['roles'],
                ]
            );
        }
    }
}
