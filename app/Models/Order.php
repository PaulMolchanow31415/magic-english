<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model {

    protected $fillable = [
        'cart_id',
        'price_ids',
        'status',
    ];

    protected $casts = [
        'price_ids' => 'array',
        'status'    => OrderStatus::class,
    ];

    public function carts(): HasMany {
        return $this->hasMany(Cart::class);
    }

}
