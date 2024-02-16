<?php

namespace App\Http\Controllers;

class CookieController extends Controller {
    public function __invoke() {
        auth()->check() and auth()->user()->update(['is_accept_cookies' => 1]);

        session(['is_accept_cookies' => true]);
    }
}
