<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_variant_id', 'image', 'is_main'];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
