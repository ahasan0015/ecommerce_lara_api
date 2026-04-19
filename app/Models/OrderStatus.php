<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // এই স্ট্যাটাসের অধীনে কতগুলো অর্ডার আছে তা দেখতে চাইলে
    public function orders()
    {
        return $this->hasMany(Order::class, 'order_status_id');
    }
}