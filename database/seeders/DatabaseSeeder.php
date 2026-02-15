<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            PaymentMethodSeeder::class,
            IsoCountrySeeder::class,
            CategorySeeder::class,
            ServiceSeeder::class,
            SocialMediaLinkSeeder::class,
            SuperAdminSeeder::class,
            GoogleSheetSettingSeeder::class,
            DevUserSeeder::class,
            FeatureFlagSeeder::class,
        ]);
    }
}
