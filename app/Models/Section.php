<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'title',
        'is_active',
        'scroll_type',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_section');
    }
}
