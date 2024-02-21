<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cart extends Model {

    protected $fillable = ['user_id'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function products(): BelongsToMany {
        return $this->belongsToMany(\App\Models\Product::class);
    }

    public function order(): BelongsTo {
        return $this->belongsTo(\App\Models\Order::class);
    }

    public function clear(): void {
        $this->products()->detach();
    }

    public function price_ids() {
        return $this->products()->select('stripe_price_id')->get()
            ->map(fn($product) => $product->stripe_price_id);
    }

}
