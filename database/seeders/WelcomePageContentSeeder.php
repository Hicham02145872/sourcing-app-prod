<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WelcomePageContent;

class WelcomePageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WelcomePageContent::truncate();

        $contents = [
            ['key' => 'hero_badge', 'value' => 'Trusted by 500+ Businesses'],
            ['key' => 'hero_title', 'value' => "Your Sourcing,\nFinally Simplified"],
            ['key' => 'hero_subtitle', 'value' => 'Submit your request in just a few clicks, receive competitive quotes from our experts, and track your order progress in real-time. Free yourself from the complexity of procurement.'],
            ['key' => 'hero_button', 'value' => 'Start My First Request Free'],
            ['key' => 'how_it_works_title', 'value' => 'A Simple 3-Step Process'],
            ['key' => 'how_it_works_subtitle', 'value' => "From request to delivery, we've streamlined every step to make sourcing effortless"],
            ['key' => 'step1_title', 'value' => 'Describe Your Needs'],
            ['key' => 'step1_description', 'value' => "Fill out our smart form to tell us exactly what product you're looking for, quantities, and your specifications. It takes just minutes."],
            ['key' => 'step2_title', 'value' => 'Receive & Compare Quotes'],
            ['key' => 'step2_description', 'value' => 'Our team of experts activates their network of qualified suppliers and sends you a selection of competitive quotes directly to your dashboard.'],
            ['key' => 'step3_title', 'value' => 'Validate & Track Your Order'],
            ['key' => 'step3_description', 'value' => 'Accept the quote that suits you best. We transform your quote into an order and you can track its status until final delivery with real-time updates.'],
            ['key' => 'benefits_title', 'value' => 'Take Control of Your Sourcing'],
            ['key' => 'benefits_subtitle', 'value' => 'Experience the advantages of a centralized, transparent, and expert-driven sourcing platform'],
            ['key' => 'benefit1_title', 'value' => 'Massive Time Savings'],
            ['key' => 'benefit1_description', 'value' => 'Stop wasting hours searching for and contacting suppliers. We do it for you, freeing up your time to focus on growing your business.'],
            ['key' => 'benefit2_title', 'value' => 'Everything Centralized'],
            ['key' => 'benefit2_description', 'value' => 'No more scattered emails and Excel files. Manage all your requests, communications, and documents from one single, organized platform.'],
            ['key' => 'benefit3_title', 'value' => 'Total Transparency'],
            ['key' => 'benefit3_description', 'value' => 'With our real-time tracking system and notifications, you always know exactly where your request stands. No more uncertainty.'],
            ['key' => 'benefit4_title', 'value' => 'Expert Service'],
            ['key' => 'benefit4_description', 'value' => 'Access a team of sourcing professionals who negotiate for you and ensure supplier quality. Benefit from their expertise and network.'],
            ['key' => 'testimonials_title', 'value' => 'They Trust Us'],
            ['key' => 'testimonials_subtitle', 'value' => 'Discover how SourceHub has helped businesses like yours succeed in their sourcing journey'],
            ['key' => 'testimonial1_text', 'value' => '"The platform has transformed our sourcing approach. We\'ve saved 20% on costs and gained precious time. The team is professional and responsive."'],
            ['key' => 'testimonial2_text', 'value' => '"Exceptional service from start to finish. The quality controls are rigorous and communication is excellent. I highly recommend!"'],
            ['key' => 'testimonial3_text', 'value' => '"Thanks to SourceHub, we found reliable suppliers and saved tremendously. Their market expertise is invaluable for our business."'],
            ['key' => 'cta_title', 'value' => 'Ready to Optimize Your Sourcing?'],
            ['key' => 'cta_subtitle', 'value' => 'Join 500+ Client that have already trusted FastSourcingBrothers for their procurement needs.'],
            ['key' => 'cta_button', 'value' => 'Create My Account'],
        ];

        foreach ($contents as $content) {
            WelcomePageContent::create($content);
        }
    }
}
