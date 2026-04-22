<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use Illuminate\Support\Str;

$categories = [
    '2 PIECE',
    '3 PIECE'
];

foreach ($categories as $cat) {
    if (!Category::where('name', $cat)->exists()) {
        Category::create([
            'name' => $cat,
            'slug' => Str::slug($cat)
        ]);
        echo "Created category: $cat\n";
    } else {
        echo "Category already exists: $cat\n";
    }
}
