<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class PurchasedController extends Controller {

    protected function myProducts() {
        return auth()->user()->products();
    }

    // plans

    public function lessons() {
        return inertia('Purchased/Lesson/Index', [
            'lessons' => $this->myProducts()->select(['name', 'content', 'poster_url'])->get(),
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
