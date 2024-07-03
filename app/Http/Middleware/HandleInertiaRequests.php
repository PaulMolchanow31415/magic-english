<?php

namespace App\Http\Middleware;

use App\Complexity;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;
use Illuminate\Http\Request;
use App\Http\Controllers\LearnableFilter;

class HandleInertiaRequests extends Middleware {
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array {
        $user = auth()->user();

        return [
            'complexities'            => Complexity::cases(),
            'learnableFilters'        => LearnableFilter::cases(),
            'isAcceptCookies'         => session('is_accept_cookies', $user?->is_accept_cookies),
            'cartItemsAmount'         => $user ? $user->cart->products->count() : 0,
            'purchasedProductsAmount' => $user ? $user->products()->count() : 0,
            ...parent::share($request),
            'ziggy'                   => fn() => [
                ...(new Ziggy())->toArray(),
                'location' => $request->url(),
            ],
        ];
    }
}
