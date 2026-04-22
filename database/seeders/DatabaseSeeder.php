<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Categories
        $categories = [
            [
                'name' => 'Men\'s Clothing',
                'slug' => 'mens-clothing',
                'description' => 'Premium men\'s fashion collection',
                'is_active' => true,
            ],
            [
                'name' => 'Women\'s Clothing',
                'slug' => 'womens-clothing',
                'description' => 'Elegant women\'s fashion collection',
                'is_active' => true,
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'description' => 'Fashion accessories and more',
                'is_active' => true,
            ],
            [
                'name' => 'Kids Clothing',
                'slug' => 'kids-clothing',
                'description' => 'Comfortable and stylish kids wear',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create Products for Men's Clothing
        $mensProducts = [
            [
                'name' => 'Classic White Dress Shirt',
                'slug' => 'classic-white-dress-shirt',
                'description' => 'Premium cotton dress shirt perfect for formal occasions. Features a classic collar and button-down front.',
                'short_description' => 'Premium cotton dress shirt',
                'price' => 4999.00,
                'sale_price' => 3999.00,
                'sku' => 'MEN-SHIRT-001',
                'stock_quantity' => 50,
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
                'colors' => json_encode(['White', 'Blue', 'Black']),
                'material' => 'Cotton',
                'brand' => 'Premium Threads',
                'is_featured' => true,
                'is_new' => true,
                'category_id' => 1,
            ],
            [
                'name' => 'Slim Fit Denim Jeans',
                'slug' => 'slim-fit-denim-jeans',
                'description' => 'Modern slim fit jeans made from premium denim. Comfortable and stylish for everyday wear.',
                'short_description' => 'Slim fit premium denim jeans',
                'price' => 5999.00,
                'sale_price' => null,
                'sku' => 'MEN-JEANS-001',
                'stock_quantity' => 40,
                'sizes' => json_encode(['28', '30', '32', '34', '36']),
                'colors' => json_encode(['Blue', 'Black', 'Grey']),
                'material' => 'Denim',
                'brand' => 'Urban Fit',
                'is_featured' => true,
                'is_new' => false,
                'category_id' => 1,
            ],
            [
                'name' => 'Casual Cotton T-Shirt',
                'slug' => 'casual-cotton-tshirt',
                'description' => 'Comfortable cotton t-shirt perfect for casual wear. Soft fabric and modern fit.',
                'short_description' => 'Comfortable cotton t-shirt',
                'price' => 1999.00,
                'sale_price' => 1499.00,
                'sku' => 'MEN-TSHIRT-001',
                'stock_quantity' => 100,
                'sizes' => json_encode(['S', 'M', 'L', 'XL']),
                'colors' => json_encode(['White', 'Black', 'Navy', 'Grey']),
                'material' => 'Cotton',
                'brand' => 'Comfort Wear',
                'is_featured' => false,
                'is_new' => true,
                'category_id' => 1,
            ],
        ];

        // Create Products for Women's Clothing
        $womensProducts = [
            [
                'name' => 'Elegant Floral Dress',
                'slug' => 'elegant-floral-dress',
                'description' => 'Beautiful floral print dress perfect for summer occasions. Lightweight and comfortable.',
                'short_description' => 'Floral print summer dress',
                'price' => 6999.00,
                'sale_price' => 5499.00,
                'sku' => 'WOM-DRESS-001',
                'stock_quantity' => 30,
                'sizes' => json_encode(['XS', 'S', 'M', 'L', 'XL']),
                'colors' => json_encode(['Pink', 'Blue', 'Yellow']),
                'material' => 'Chiffon',
                'brand' => 'Elegant Style',
                'is_featured' => true,
                'is_new' => true,
                'category_id' => 2,
            ],
            [
                'name' => 'Professional Blazer',
                'slug' => 'professional-blazer',
                'description' => 'Tailored blazer perfect for professional settings. Classic design with modern fit.',
                'short_description' => 'Professional tailored blazer',
                'price' => 8999.00,
                'sale_price' => null,
                'sku' => 'WOM-BLAZER-001',
                'stock_quantity' => 25,
                'sizes' => json_encode(['S', 'M', 'L', 'XL']),
                'colors' => json_encode(['Black', 'Navy', 'Grey']),
                'material' => 'Polyester Blend',
                'brand' => 'Executive Wear',
                'is_featured' => true,
                'is_new' => false,
                'category_id' => 2,
            ],
        ];

        // Create Products for Accessories
        $accessoriesProducts = [
            [
                'name' => 'Leather Belt',
                'slug' => 'leather-belt',
                'description' => 'Genuine leather belt with classic buckle. Perfect accessory for any outfit.',
                'short_description' => 'Genuine leather belt',
                'price' => 2499.00,
                'sale_price' => 1999.00,
                'sku' => 'ACC-BELT-001',
                'stock_quantity' => 60,
                'sizes' => json_encode(['S', 'M', 'L', 'XL']),
                'colors' => json_encode(['Brown', 'Black']),
                'material' => 'Leather',
                'brand' => 'Classic Accessories',
                'is_featured' => false,
                'is_new' => false,
                'category_id' => 3,
            ],
        ];

        $allProducts = array_merge($mensProducts, $womensProducts, $accessoriesProducts);

        foreach ($allProducts as $productData) {
            Product::create($productData);
        }

        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create a test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'user',
        ]);
    }
}
