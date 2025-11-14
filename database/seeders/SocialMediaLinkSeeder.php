<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SocialMediaLink; // Import the SocialMediaLink model

class SocialMediaLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SocialMediaLink::updateOrCreate(
            [], // Unique identifier (empty array for a single record)
            [
                'facebook_url' => 'https://facebook.com',
                'instagram_url' => 'https://instagram.com',
                'linkedin_url' => 'https://linkedin.com',
                'twitter_url' => 'https://twitter.com',
                'whatsapp_number' => '+1234567890', // Placeholder number
            ]
        );
    }
}
