<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Country;
use App\Models\Service;
use App\Models\SourcingRequest;
use App\Models\SourcingRequestDestination;
use App\Models\Quotation;
use App\Models\SourcingOrder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestingSeeder extends Seeder
{
    public function run(): void
    {
        // Create test client
        $client = User::updateOrCreate(
            ['email' => 'test-client@example.com'],
            [
                'name' => 'Test Client',
                'password' => Hash::make('password'),
                'role' => 'client',
                'email_verified_at' => now(),
                'preferred_locale' => 'eng',
            ]
        );

        // Create test admin
        User::updateOrCreate(
            ['email' => 'test-admin@example.com'],
            [
                'name' => 'Test Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'preferred_locale' => 'eng',
            ]
        );

        // Create test super admin
        User::updateOrCreate(
            ['email' => 'test-superadmin@example.com'],
            [
                'name' => 'Test Super Admin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
                'preferred_locale' => 'eng',
            ]
        );

        // Ensure reference data exists
        $category = Category::first() ?? Category::create(['name' => 'General']);
        $country = Country::first() ?? Country::create(['name' => 'United Arab Emirates', 'code' => 'AE']);
        $service = Service::first() ?? Service::create(['name' => 'DDP']);

        // Create sourcing requests with various statuses
        $statuses = ['pending', 'in_review', 'quoted', 'accepted'];
        $products = [
            ['name' => 'USB-C Cable 2m', 'price' => 120.00, 'unit' => 12.00],
            ['name' => 'Wireless Mouse', 'price' => 250.00, 'unit' => 25.00],
            ['name' => 'Mechanical Keyboard', 'price' => 480.00, 'unit' => 48.00],
        ];

        foreach ($products as $index => $prod) {
            $request = SourcingRequest::create([
                'user_id' => $client->id,
                'product_name' => $prod['name'],
                'product_url' => "https://example.com/product/{$index}",
                'category_id' => $category->id,
                'status' => $statuses[$index % count($statuses)],
                'shipping_method' => 'air',
                'phone_number' => '+971501234567',
                'address' => "Test Address {$index}",
            ]);

            SourcingRequestDestination::create([
                'sourcing_request_id' => $request->id,
                'country_id' => $country->id,
                'service_id' => $service->id,
                'quantity' => 10 + ($index * 5),
                'address' => "Test Address {$index}",
                'label_address' => "Test Label {$index}",
            ]);

            // Create quotation for quoted/accepted requests
            if (in_array($request->status, ['quoted', 'accepted'])) {
                $quotation = Quotation::create([
                    'sourcing_request_id' => $request->id,
                    'amount' => $prod['price'],
                    'currency' => 'USD',
                    'status' => $request->status === 'accepted' ? 'accepted' : 'sent',
                    'unit_price' => $prod['unit'],
                    'commission_service' => 5.00,
                    'delivery_cost_china' => 20.00,
                ]);

                // Create order for accepted requests
                if ($request->status === 'accepted') {
                    SourcingOrder::create([
                        'user_id' => $client->id,
                        'quotation_id' => $quotation->id,
                        'sourcing_request_id' => $request->id,
                        'total_amount' => $prod['price'],
                        'status' => 'paid',
                    ]);
                }
            }
        }

        $this->command->info('E2E test data seeded successfully.');
    }
}
