<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStatus extends Model
{
    protected $fillable = ['name'];

    // Status has many Products
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
