<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'store_name', 'value' => 'S4 LUXURY STORE', 'group' => 'general', 'type' => 'text'],
            ['key' => 'store_logo', 'value' => null, 'group' => 'general', 'type' => 'image'],
            
            // Aesthetics
            ['key' => 'primary_color', 'value' => '#D4AF37', 'group' => 'aesthetics', 'type' => 'color'],
            ['key' => 'font_family', 'value' => "'Outfit', sans-serif", 'group' => 'aesthetics', 'type' => 'text'],
            ['key' => 'base_font_size', 'value' => '16px', 'group' => 'aesthetics', 'type' => 'text'],
            
            // Homepage
            ['key' => 'hero_images', 'value' => json_encode([]), 'group' => 'homepage', 'type' => 'image_gallery'],
            ['key' => 'cat_img_1', 'value' => null, 'group' => 'homepage', 'type' => 'image'],
            ['key' => 'cat_img_2', 'value' => null, 'group' => 'homepage', 'type' => 'image'],
            ['key' => 'cat_img_3', 'value' => null, 'group' => 'homepage', 'type' => 'image'],
            ['key' => 'cat_img_4', 'value' => null, 'group' => 'homepage', 'type' => 'image'],
            ['key' => 'category_grid_images', 'value' => json_encode([]), 'group' => 'homepage', 'type' => 'json'],
            
            // Contact & Social
            ['key' => 'contact_address', 'value' => '123 Fashion Ave, Karachi, Pakistan', 'group' => 'contact', 'type' => 'text'],
            ['key' => 'contact_email', 'value' => 'hello@s4store.com', 'group' => 'contact', 'type' => 'text'],
            ['key' => 'contact_phone', 'value' => '+92 300 1234567', 'group' => 'contact', 'type' => 'text'],
            ['key' => 'contact_phone_2', 'value' => '+92 336 9480148', 'group' => 'contact', 'type' => 'text'],
            ['key' => 'social_links', 'value' => json_encode([
                'facebook' => 'https://facebook.com',
                'instagram' => 'https://instagram.com',
                'whatsapp' => 'https://wa.me/923001234567',
                'tiktok' => 'https://tiktok.com'
            ]), 'group' => 'contact', 'type' => 'json'],

            // Finance & Tax
            ['key' => 'currency_symbol', 'value' => 'Rs.', 'group' => 'finance', 'type' => 'text'],
            ['key' => 'currency_code', 'value' => 'PKR', 'group' => 'finance', 'type' => 'text'],
            ['key' => 'tax_rate', 'value' => '0', 'group' => 'finance', 'type' => 'text'],
            ['key' => 'tax_enabled', 'value' => '0', 'group' => 'finance', 'type' => 'text'],

            // SEO
            ['key' => 'seo_title', 'value' => 'S4 Luxury Store | Premium Luxury Fashion', 'group' => 'seo', 'type' => 'text'],
            ['key' => 'seo_description', 'value' => 'Discover the finest collection of premium luxury fashion at S4 Luxury Store.', 'group' => 'seo', 'type' => 'text'],
            ['key' => 'seo_keywords', 'value' => 'fashion, luxury, clothing, s4 luxury store', 'group' => 'seo', 'type' => 'text'],

            // Notifications
            ['key' => 'notify_email_enabled', 'value' => '1', 'group' => 'notifications', 'type' => 'boolean'],
            ['key' => 'notify_sms_enabled', 'value' => '0', 'group' => 'notifications', 'type' => 'boolean'],
            ['key' => 'notify_whatsapp_enabled', 'value' => '0', 'group' => 'notifications', 'type' => 'boolean'],
            ['key' => 'admin_notify_email', 'value' => 'admin@s4store.com', 'group' => 'notifications', 'type' => 'text'],
            ['key' => 'admin_notify_phone', 'value' => '+923001234567', 'group' => 'notifications', 'type' => 'text'],
            ['key' => 'low_stock_threshold', 'value' => '5', 'group' => 'notifications', 'type' => 'number'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
