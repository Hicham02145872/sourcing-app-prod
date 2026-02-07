<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::create([
            'name' => 'Wise',
            'logo_path' => null,
            'details' => [
                'Account Type' => 'Checking',
                'Account Holder' => 'ayoub mamouni',
                'Routing Number' => '084009519',
                'Account Number' => '9600002009308313',
                'Country' => 'United States',
                'Email' => 'Mamouni.ayoub@gmail.com',
                'Address' => '30 W. 26th Street, Sixth Floor New York NY 10010',
            ],
            'is_active' => true,
        ]);

        PaymentMethod::create([
            'name' => 'CIH',
            'logo_path' => null,
            'details' => [
                'Account Holder' => 'MONSIEUR AYOUB MAMOUNI',
                'Account Number' => '230 815 4881038211005300 27',
                'IBAN' => 'MA64 2308 1548 8103 8211 0053 0027',
                'SWIFT Code' => 'CIHMMAMC',
            ],
            'is_active' => true,
        ]);
    }
}
