<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'address_id',
        'order_number',
        'subtotal',
        'delivery_fee',
        'total',
        'payment_method',
        'payment_status',
        'order_status',
        'tracking_number',
        'notes',
        'paid_at',
        'shipped_at',
        'delivered_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    public function payments()
{
    return $this->hasMany(Payment::class);
}
    public function latestPayment()
	{
	    return $this->hasOne(Payment::class)->latestOfMany();
	}
}
