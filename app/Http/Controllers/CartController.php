<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\OrderStatus;
use App\Models\Product;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CartController extends Controller {

    protected function cartItems(): BelongsToMany {
        return auth()->user()->cart->products();
    }

    public function addProduct(Product $product) {
        $this->cartItems()->syncWithoutDetaching($product);
    }

    public function show() {
        return inertia('Cart', [
            'products' => $this->cartItems()
                ->select(['name', 'poster_url', 'price'])->get(),
        ]);
    }

    public function removeProduct(Product $product) {
        $this->cartItems()->detach($product);

        if ($this->cartItems()->count() < 1) {
            return to_route('ecommerce');
        }

        return back();
    }

    public function clearCart(): RedirectResponse {
        $this->cartItems()->detach();

        return to_route('ecommerce');
    }

    public function checkout() {
        $cart = auth()->user()->cart;

        $order = \App\Models\Order::create([
            'cart_id'   => $cart->id,
            'price_ids' => $cart->price_ids(),
            'status'    => OrderStatus::INCOMPLETE,
        ]);

        $url = auth()->user()->checkout($order->price_ids, [
            'success_url' => route('cart.checkout.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('cart.show'),
            'metadata'    => ['order_id' => $order->id],
        ])->url;

        return Inertia::location($url);
    }

    /**
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function handleSuccessCheckout(Request $request): RedirectResponse {
        $sessionId = $request->get('session_id');

        if (empty($sessionId)) {
            return to_route('cart.show');
        }

        $session = Cashier::stripe()->checkout->sessions->retrieve($sessionId);

        if ($session->payment_status !== 'paid') {
            return to_route('cart.show');
        }

        $orderId = $session['metadata']['order_id'] ?? null;
        $order = \App\Models\Order::findOrFail($orderId);
        $order->update(['status' => OrderStatus::COMPLETED]);
        auth()->user()->products()->syncWithoutDetaching($this->cartItems()->get());
        auth()->user()->cart->clear();

        return to_route('purchased.lesson.all');
    }

}
