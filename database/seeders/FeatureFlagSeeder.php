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
                'roles' => [],
            ],
            [
                'key' => 'refunds',
                'name' => 'Refund Claims',
                'status' => 'coming_soon',
                'roles' => [],
            ],
            [
                'key' => 'shipping_fees_popup',
                'name' => 'Shipping Fees Pop-up',
                'status' => 'visible',
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
