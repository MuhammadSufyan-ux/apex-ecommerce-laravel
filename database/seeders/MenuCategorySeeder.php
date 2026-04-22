<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class MenuCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'SALE',
            'MAN',
            'WOMEN',
            'KIDS',
            'ACCESSORIES',
            'NEW',
            'SUMMER',
            'WINTER'
        ];

        // Clear existing categories to ensure only requested ones remain
        Category::query()->update(['parent_id' => null]); 

        foreach ($categories as $catName) {
            Category::updateOrCreate(
                ['name' => $catName],
                [
                    'slug' => Str::slug($catName),
                    'is_active' => true,
                    'show_in_menu' => true,
                    'parent_id' => null,
                ]
            );
        }
    }
}
