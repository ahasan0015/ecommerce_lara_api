<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'variant_id',
        'quantity',
    ];

    /**
     * আইটেমটি কোন কার্টের অংশ।
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * এই আইটেমটি কোন ভেরিয়েন্ট (Size/Color)।
     */
    public function variant(): BelongsTo
    {
        // আপনার টেবিল অনুযায়ী foreign key 'variant_id'
        // মডেলের নাম ProductVariant হলে সেটা দিন
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
