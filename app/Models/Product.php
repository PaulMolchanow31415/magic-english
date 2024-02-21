<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model {
    use Searchable;

    protected $fillable = [
        'name',
        'poster_url',
        'content',
        'price',
        'stripe_price_id',
    ];

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class);
    }

    public function carts(): BelongsToMany {
        return $this->belongsToMany(Cart::class);
    }

}
