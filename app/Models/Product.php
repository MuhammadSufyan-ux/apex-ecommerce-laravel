<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'cost_price',
        'sale_price',
        'sku',
        'stock_quantity',
        'sizes',
        'colors',
        'material',
        'brand',
        'is_featured',
        'is_new',
        'is_active',
        'meta_title',
        'meta_description',
        'care_instructions',
        'fabric_details',
        'return_policy',
        'duppata_image',
        'shalwar_image',
        'bazoo_image',
        'dynamic_details',
        'is_coming_soon',
        'is_on_sale',
        'discount_badge',
        'section',
        'section',
        'is_popup',
        'popup_start_date',
        'popup_end_date',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'sizes' => 'array',
        'colors' => 'array',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_active' => 'boolean',
        'is_coming_soon' => 'boolean',
        'is_on_sale' => 'boolean',
        'is_popup' => 'boolean',
        'popup_start_date' => 'datetime',
        'popup_end_date' => 'datetime',
        'dynamic_details' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'product_section');
    }

    public function getCurrentPrice()
    {
        return $this->sale_price ?? $this->price;
    }

    public function hasDiscount()
    {
        return $this->sale_price && $this->sale_price < $this->price;
    }

    public function getDiscountPercentage()
    {
        if (!$this->hasDiscount()) {
            return 0;
        }
        return round((($this->price - $this->sale_price) / $this->price) * 100);
    }
}
