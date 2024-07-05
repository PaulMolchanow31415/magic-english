<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;

class CartController extends Controller {

    public function addProduct(Product $product) {
        user()->cartItems()->syncWithoutDetaching($product);
    }

    public function show() {
        return inertia('Cart', [
            'products' => user()->cartItems()->select(['name', 'poster_url', 'price'])->get(),
        ]);
    }

    public function removeProduct(Product $product) {
        $items = user()->cartItems();
        $items->detach($product);

        if ($items->count() < 1) {
            return to_route('ecommerce');
        }

        return back();
    }

    public function clearCart() {
        user()->cartItems()->detach();

        return to_route('ecommerce');
    }

    public function checkout() {
        $cart = user()->cart;

        $order = Order::create([
            'cart_id'   => $cart->id,
            'price_ids' => $cart->stripePriceIds(),
            'status'    => OrderStatus::INCOMPLETE,
        ]);

        $url = user()->checkout($order->price_ids, [
            'success_url' => route('cart.checkout.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('cart.show'),
            'metadata'    => ['order_id' => $order->id],
        ])->url;

        return Inertia::location($url);
    }

    /**
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function handleSuccessCheckout(Request $request) {
        $sessionId = $request->get('session_id');

        if (empty($sessionId)) {
            return to_route('cart.show');
        }

        $session = Cashier::stripe()->checkout->sessions->retrieve($sessionId);

        if ($session->payment_status !== 'paid') {
            return to_route('cart.show');
        }

        $orderId = $session['metadata']['order_id'] ?? null;
        $order = Order::findOrFail($orderId);
        $order->update(['status' => OrderStatus::COMPLETED]);
        user()->products()->syncWithoutDetaching(user()->cartItems()->get());
        user()->cart->clear();

        return to_route('purchased.lesson.all');
    }

}
