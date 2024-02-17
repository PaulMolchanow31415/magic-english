<?php

namespace App\Http\Controllers;

class CookieController extends Controller {
    public function __invoke() {
        auth()->check() and auth()->user()->update(['is_accept_cookies' => true]);

        session(['is_accept_cookies' => true]);
    }
}
