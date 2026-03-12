<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    // ১. ফিলবল প্রপার্টি সেট করা (যাতে store মেথডে এরর না আসে)
    protected $fillable = [
        'product_id',
        'size_id',
        'color_id',
        'status_id',
        'sale_price',
        'sku',
        'stock'
    ];

    // ২. রিলেশনশিপ: এই ভেরিয়েন্টটি কোন প্রোডাক্টের?
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // ৩. রিলেশনশিপ: এই ভেরিয়েন্টের সাইজ কী?
    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    // ৪. রিলেশনশিপ: এই ভেরিয়েন্টের কালার কী?
    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    // ৫. রিলেশনশিপ: ভেরিয়েন্টের স্ট্যাটাস (Active/Inactive)
    public function status()
    {
        return $this->belongsTo(VariantStatus::class, 'status_id');
    }
}