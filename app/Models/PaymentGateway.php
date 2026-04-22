<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PaymentGateway extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'icon_color',
        'description',
        'credentials',
        'is_active',
        'is_sandbox',
        'sort_order',
    ];

    protected $casts = [
        'credentials' => 'array',
        'is_active' => 'boolean',
        'is_sandbox' => 'boolean',
    ];

    /**
     * Get only active gateways for the checkout page.
     */
    public static function getActiveGateways()
    {
        return self::where('is_active', true)->orderBy('sort_order')->get();
    }

    /**
     * Get a specific credential value.
     */
    public function getCredential($key, $default = null)
    {
        $creds = $this->credentials ?? [];
        return $creds[$key] ?? $default;
    }

    /**
     * Check if credentials are configured.
     */
    public function hasCredentials()
    {
        $creds = $this->credentials;
        
        // Handle case where cast failed or data is a string
        if (is_string($creds)) {
            $creds = json_decode($creds, true);
        }
        
        // Ensure it is an iterable array
        if (!is_array($creds)) {
            $creds = [];
        }

        // Check if at least one credential has a non-empty value
        foreach ($creds as $value) {
            if (!empty($value)) return true;
        }
        return false;
    }

    /**
     * Get the default credential fields for known gateway types.
     */
    public static function getDefaultFields($slug)
    {
        $defaults = [
            'stripe' => [
                ['key' => 'publishable_key', 'label' => 'Publishable Key', 'type' => 'text', 'placeholder' => 'pk_test_...'],
                ['key' => 'secret_key', 'label' => 'Secret Key', 'type' => 'password', 'placeholder' => 'sk_test_...'],
                ['key' => 'webhook_secret', 'label' => 'Webhook Secret', 'type' => 'password', 'placeholder' => 'whsec_...'],
            ],
            'paypal' => [
                ['key' => 'client_id', 'label' => 'Client ID', 'type' => 'text', 'placeholder' => 'Your PayPal Client ID'],
                ['key' => 'client_secret', 'label' => 'Client Secret', 'type' => 'password', 'placeholder' => 'Your PayPal Client Secret'],
                ['key' => 'webhook_id', 'label' => 'Webhook ID', 'type' => 'text', 'placeholder' => 'Optional webhook ID'],
            ],
            'google_pay' => [
                ['key' => 'merchant_id', 'label' => 'Merchant ID', 'type' => 'text', 'placeholder' => 'Your Google Pay Merchant ID'],
                ['key' => 'merchant_name', 'label' => 'Merchant Name', 'type' => 'text', 'placeholder' => 'Your Business Name'],
                ['key' => 'environment', 'label' => 'Environment', 'type' => 'text', 'placeholder' => 'TEST or PRODUCTION'],
            ],
            'jazzcash' => [
                ['key' => 'merchant_id', 'label' => 'Merchant ID', 'type' => 'text', 'placeholder' => 'JazzCash Merchant ID'],
                ['key' => 'password', 'label' => 'Password', 'type' => 'password', 'placeholder' => 'JazzCash Password'],
                ['key' => 'integrity_salt', 'label' => 'Integrity Salt', 'type' => 'password', 'placeholder' => 'JazzCash Integrity Salt'],
                ['key' => 'return_url', 'label' => 'Return URL', 'type' => 'text', 'placeholder' => 'https://yoursite.com/payment/callback'],
            ],
            'easypaisa' => [
                ['key' => 'store_id', 'label' => 'Store ID', 'type' => 'text', 'placeholder' => 'EasyPaisa Store ID'],
                ['key' => 'store_password', 'label' => 'Store Password', 'type' => 'password', 'placeholder' => 'EasyPaisa Store Password'],
                ['key' => 'account_number', 'label' => 'Account Number', 'type' => 'text', 'placeholder' => 'Your EasyPaisa Account'],
            ],
            'hbl' => [
                ['key' => 'merchant_id', 'label' => 'Merchant ID', 'type' => 'text', 'placeholder' => 'HBL Merchant ID'],
                ['key' => 'api_key', 'label' => 'API Key', 'type' => 'password', 'placeholder' => 'HBL API Key'],
                ['key' => 'secret_key', 'label' => 'Secret Key', 'type' => 'password', 'placeholder' => 'HBL Secret Key'],
            ],
            'ubl' => [
                ['key' => 'merchant_id', 'label' => 'Merchant ID', 'type' => 'text', 'placeholder' => 'UBL Merchant ID'],
                ['key' => 'api_key', 'label' => 'API Key', 'type' => 'password', 'placeholder' => 'UBL API Key'],
                ['key' => 'secret_key', 'label' => 'Secret Key', 'type' => 'password', 'placeholder' => 'UBL Secret Key'],
            ],
            'credit_card' => [
                ['key' => 'gateway_provider', 'label' => 'Gateway Provider', 'type' => 'text', 'placeholder' => 'e.g. Stripe, Square, etc.'],
                ['key' => 'api_key', 'label' => 'API Key', 'type' => 'password', 'placeholder' => 'Card Gateway API Key'],
                ['key' => 'secret_key', 'label' => 'Secret Key', 'type' => 'password', 'placeholder' => 'Card Gateway Secret Key'],
            ],
            'debit_card' => [
                ['key' => 'gateway_provider', 'label' => 'Gateway Provider', 'type' => 'text', 'placeholder' => 'e.g. Stripe, Square, etc.'],
                ['key' => 'api_key', 'label' => 'API Key', 'type' => 'password', 'placeholder' => 'Card Gateway API Key'],
                ['key' => 'secret_key', 'label' => 'Secret Key', 'type' => 'password', 'placeholder' => 'Card Gateway Secret Key'],
            ],
            'cod' => [
                ['key' => 'instructions', 'label' => 'Customer Instructions', 'type' => 'text', 'placeholder' => 'Pay cash upon delivery'],
                ['key' => 'max_order_amount', 'label' => 'Max COD Amount', 'type' => 'text', 'placeholder' => 'Maximum order value for COD'],
            ],
        ];

        return $defaults[$slug] ?? [
            ['key' => 'api_key', 'label' => 'API Key', 'type' => 'password', 'placeholder' => 'Enter your API Key'],
            ['key' => 'secret_key', 'label' => 'Secret Key', 'type' => 'password', 'placeholder' => 'Enter your Secret Key'],
            ['key' => 'merchant_id', 'label' => 'Merchant ID', 'type' => 'text', 'placeholder' => 'Enter Merchant ID'],
        ];
    }
}
