<?php

namespace App\Http\Controllers;

use App\Models\Product;

class EcommerceController extends Controller {

    public function __invoke() {
        $products = null;
        $hasCartProducts = false;

        if (auth()->check()) {
            $buyer = user();
            $goods = $buyer->cartItems();
            $mergedIds = $buyer->products()->allRelatedIds()->merge($goods->allRelatedIds());
            $products = Product::select(['id', 'name', 'poster_url', 'price'])
                ->whereNotIn('id', $mergedIds)->get();
            $hasCartProducts = $goods->count() > 0;
        }

        return inertia('Ecommerce/Index', [
            'lessons'         => $products ?? Product::all(),
            'hasCartProducts' => $hasCartProducts,
        ]);
    }
}
