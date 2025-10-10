<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'name' => 'Cash',
                'code' => 'cash',
                'description' => 'Cash payment',
                'transaction_charge' => 0,
                'status' => 'active',
            ],
            [
                'name' => 'bKash',
                'code' => 'bkash',
                'description' => 'bKash mobile banking',
                'account_number' => '01XXXXXXXXX',
                'gateway_config' => [
                    'api_key' => 'your_bkash_api_key',
                    'api_secret' => 'your_bkash_api_secret',
                ],
                'transaction_charge' => 1.85,
                'status' => 'active',
            ],
            [
                'name' => 'Nagad',
                'code' => 'nagad',
                'description' => 'Nagad mobile banking',
                'account_number' => '01XXXXXXXXX',
                'gateway_config' => [
                    'api_key' => 'your_nagad_api_key',
                    'api_secret' => 'your_nagad_api_secret',
                ],
                'transaction_charge' => 1.49,
                'status' => 'active',
            ],
            [
                'name' => 'Bank Transfer',
                'code' => 'bank',
                'description' => 'Direct bank transfer',
                'account_number' => '1234567890',
                'transaction_charge' => 0,
                'status' => 'active',
            ],
            [
                'name' => 'Cheque',
                'code' => 'cheque',
                'description' => 'Payment by cheque',
                'transaction_charge' => 0,
                'status' => 'active',
            ],
            [
                'name' => 'Credit/Debit Card',
                'code' => 'card',
                'description' => 'Card payment',
                'gateway_config' => [
                    'gateway' => 'stripe', // or sslcommerz
                    'api_key' => 'your_stripe_api_key',
                ],
                'transaction_charge' => 2.5,
                'status' => 'active',
            ],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::create($method);
        }
    }
}
