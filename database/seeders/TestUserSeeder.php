<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Country;
use App\Models\Service;
use App\Models\SourcingRequest;
use App\Models\SourcingRequestDestination;
use App\Models\Quotation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Test Client User
        $client = User::updateOrCreate(
            ['email' => 'client.test@example.com'],
            [
                'name' => 'Test Client',
                'password' => Hash::make('password123'),
                'role' => 'client',
                'email_verified_at' => now(),
            ]
        );

        // Seed Test Super Admin User
        User::updateOrCreate(
            ['email' => 'superadmin.test@example.com'],
            [
                'name' => 'Test Super Admin',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
            ]
        );

        // Seed Sourcing Requests & Quotations if they don't exist yet
        $category = Category::first() ?? Category::create(['name' => 'General']);
        $country = Country::first() ?? Country::create(['name' => 'United States', 'code' => 'US']);
        $service = Service::first() ?? Service::create(['name' => 'DDP']);

        // Check if client already has pending test requests
        $existingCount = SourcingRequest::where('user_id', $client->id)
            ->whereHas('quotation', function($query) {
                $query->where('status', '!=', 'accepted');
            })
            ->count();

        if ($existingCount < 3) {
            $products = [
                ['name' => 'Test Product A', 'price' => 150.00, 'unit' => 15.00],
                ['name' => 'Test Product B', 'price' => 320.00, 'unit' => 32.00],
                ['name' => 'Test Product C', 'price' => 450.00, 'unit' => 45.00],
            ];

            foreach ($products as $index => $prod) {
                $request = SourcingRequest::create([
                    'user_id' => $client->id,
                    'product_name' => $prod['name'],
                    'product_url' => 'https://example.com/product',
                    'category_id' => $category->id,
                    'status' => 'quoted',
                    'shipping_method' => 'air',
                    'phone_number' => '123456789',
                    'address' => 'Test Address ' . ($index + 1),
                ]);

                // Create destination
                SourcingRequestDestination::create([
                    'sourcing_request_id' => $request->id,
                    'country_id' => $country->id,
                    'service_id' => $service->id,
                    'quantity' => 10,
                    'address' => 'Test Address ' . ($index + 1),
                    'label_address' => 'Test Label Address ' . ($index + 1),
                ]);

                // Create quotation
                Quotation::create([
                    'sourcing_request_id' => $request->id,
                    'amount' => $prod['price'],
                    'currency' => 'USD',
                    'status' => 'sent',
                    'unit_price' => $prod['unit'],
                    'commission_service' => 5.00,
                    'unit_weight' => 1.5,
                    'delivery_cost_china' => 20.00,
                ]);
            }
        }
    }
}
