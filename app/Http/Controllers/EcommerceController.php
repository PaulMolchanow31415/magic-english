<?php

namespace App\Http\Controllers;

use App\Models\Product;

class EcommerceController extends Controller {

    public function __invoke() {
        $products = null;
        $hasCartProducts = false;

        if (auth()->check()) {
            $user = auth()->user();
            $cartGoods = $user->cart->products();
            $products = Product::select(['id', 'name', 'poster_url', 'price'])->whereNotIn(
                'id',
                $user->products()->allRelatedIds()->merge($cartGoods->allRelatedIds()),
            )->get();
            $hasCartProducts = $cartGoods->count() > 0;
        }

        // todo: fix empty cart
        return inertia('Ecommerce/Index', [
            'lessons'         => $products ?? Product::all(),
            'hasCartProducts' => $hasCartProducts,
            // plans
        ]);
    }
}
