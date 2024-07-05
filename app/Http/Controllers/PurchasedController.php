<?php

namespace App\Http\Controllers;

use App\Models\Product;

class PurchasedController extends Controller {

    public function lessons() {
        return inertia('Purchased/Lesson/Index', [
            'lessons' => user()->products()->select(['name', 'content', 'poster_url'])->get(),
        ]);
    }

    public function index() {
        //
    }

    public function show(Product $product) {
        //
    }

    public function destroy(Product $product) {
        //
    }
}
