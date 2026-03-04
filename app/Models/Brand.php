<?php

namespace App\Models;

use App\Http\Controllers\Api\BrandController;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'status_id'
    ];

    /**
     * রিলেশনশিপ: এই ব্র্যান্ডের আন্ডারে কতগুলো প্রোডাক্ট আছে
     */
    // public function categories()
    // {
    //     return $this->hasMany(Category::class, 'brand_id');
    // }

    // public function status()
    // {
    //     // আপনার স্কিমা অনুযায়ী product_statuses টেবিলের সাথে সম্পর্ক
    //     return $this->belongsTo(ProductStatus::class, 'status_id');
    // }
}
