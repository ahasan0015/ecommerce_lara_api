<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'brand_id',
        'name',
        'slug',
        'status_id'
    ];
    // public function brands(){
    //     return $this->belongsToMany(Brand::class);
    // }
}
