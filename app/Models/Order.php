<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_code',
        'customer_name',
        'customer_phone',
        'customer_email',
        'shipping_address',
        'notes',
        'payment_method',
        'payment_status',
        'status',
        'subtotal',
        'discount_amount',
        'shipping_fee',
        'total_price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function review() {
        return $this->hasOne(OrderReview::class);
    }

    public function productReviews() {
        return $this->hasMany(ProductReview::class);
    }

    public function cancellation() {
        return $this->hasOne(\App\Models\OrderCancellation::class);
    }

    public function returnRequest() {
        return $this->hasOne(\App\Models\ReturnRequest::class);
    }
}