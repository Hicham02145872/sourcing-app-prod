<?php

namespace Database\Seeders;

use App\Models\GoogleSheetSetting;
use Illuminate\Database\Seeder;

class GoogleSheetSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GoogleSheetSetting::firstOrCreate(
            ['id' => 1],
            [
                'sheet_id' => null,
                'sheet_name' => 'sourcing',
            ]
        );
    }
}
