<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model {
    use Searchable, HasPoster;

    protected $fillable = [
        'name',
        'content',
        'price',
        'stripe_price_id',
    ];

    protected $appends = ['poster_url'];

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class);
    }

    public function carts(): BelongsToMany {
        return $this->belongsToMany(Cart::class);
    }

}
