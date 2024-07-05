<?php

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Symfony\Component\HttpFoundation\Response;

function user(): User|Authenticatable|null {
    return auth()->user();
}

function forbidden(): void {
    abort(Response::HTTP_FORBIDDEN);
}
