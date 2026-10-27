<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'chef_id',
        'subtotal',
        'delivery_fee',
        'total',
        'address',
        'phone',
        'processing_time',
        'delivery_time',
        'status',
        'payment_method',
        'payment_status',
    ];

    protected static function booted(): void
    {
        static::saved(function ($order) {
            \App\Services\OrderStats::clearCaches();
        });

        static::deleted(function ($order) {
            \App\Services\OrderStats::clearCaches();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chef()
    {
        return $this->belongsTo(User::class, 'chef_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    //
}
