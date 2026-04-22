<?php

namespace Database\Seeders;

use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gateways = [
            [
                'name' => 'Visa',
                'slug' => 'visa',
                'icon' => 'fab fa-cc-visa',
                'icon_color' => '#1A1F71',
                'description' => 'Visa credit/debit card payments',
                'credentials' => json_encode([]),
                'is_active' => true,
                'is_sandbox' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Mastercard',
                'slug' => 'mastercard',
                'icon' => 'fab fa-cc-mastercard',
                'icon_color' => '#EB001B',
                'description' => 'Mastercard credit/debit card payments',
                'credentials' => json_encode([]),
                'is_active' => true,
                'is_sandbox' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Discover',
                'slug' => 'discover',
                'icon' => 'fab fa-cc-discover',
                'icon_color' => '#FF6000',
                'description' => 'Discover card payments',
                'credentials' => json_encode([]),
                'is_active' => true,
                'is_sandbox' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Amex',
                'slug' => 'amex',
                'icon' => 'fab fa-cc-amex',
                'icon_color' => '#007BC1',
                'description' => 'American Express payments',
                'credentials' => json_encode([]),
                'is_active' => true,
                'is_sandbox' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Google Pay',
                'slug' => 'google_pay',
                'icon' => 'fab fa-google-pay',
                'icon_color' => '#4285F4',
                'description' => 'Google Pay mobile wallet',
                'credentials' => json_encode([]),
                'is_active' => true,
                'is_sandbox' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'EasyPaisa',
                'slug' => 'easypaisa',
                'icon' => 'fas fa-mobile-alt',
                'icon_color' => '#00A650',
                'description' => 'Pakistan EasyPaisa mobile wallet',
                'credentials' => json_encode([]),
                'is_active' => true,
                'is_sandbox' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'JazzCash',
                'slug' => 'jazzcash',
                'icon' => 'fas fa-wallet',
                'icon_color' => '#E2232A',
                'description' => 'Pakistan JazzCash mobile wallet',
                'credentials' => json_encode([]),
                'is_active' => true,
                'is_sandbox' => true,
                'sort_order' => 7,
            ],
        ];

        // First, clear existing gateways to ensure only these 5 remain
        PaymentGateway::truncate();

        foreach ($gateways as $gateway) {
            PaymentGateway::create($gateway);
        }
    }
}
