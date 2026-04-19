<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'order_status_id',
        'order_number',
        'subtotal',
        'discount',
        'total',
        'payment_method',
        'payment_status_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ২. এই অর্ডারের অধীনে কি কি আইটেম কেনা হয়েছে
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ৩. অর্ডারের বর্তমান স্ট্যাটাস কি (Pending/Processing ইত্যাদি)
    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }
}
