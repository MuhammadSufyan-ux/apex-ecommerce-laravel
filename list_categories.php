<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$categories = App\Models\Category::all()->pluck('name')->toArray();
echo "List of Categories:\n";
foreach ($categories as $category) {
    echo $category . "\n";
}
