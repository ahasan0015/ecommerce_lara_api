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

    //order items 
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // order status (Pending/Processing)
    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

    //ShippingAddress relation user_id
    public function shippingAddress()
    {

        return $this->hasOne(ShippingAddress::class, 'user_id', 'user_id')->latest();
    }
}
