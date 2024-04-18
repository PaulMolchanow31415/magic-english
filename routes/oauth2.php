<?php

use Inertia\Inertia;
use App\Models\Role;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\PasswordRegenerateMail;
use Laravel\Socialite\Facades\Socialite;

Route::prefix('oauth2/google')->name('oauth2.google.')->group(function () {
    Route::get('/', function () {
        return Inertia::location(
            Socialite::driver('google')->redirect()->getTargetUrl(),
        );
    })->name('redirect');

    Route::get('/callback', function () {
        $googleUser = Socialite::driver('google')->user();

        $password = Str::password(16);

        $user = \App\Models\User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name'               => $googleUser->getName(),
                'email'              => $googleUser->getEmail(),
                'password'           => Hash::make($password),
                'profile_photo_path' => $googleUser->getAvatar(),
                'is_accept_cookies'  => true,
                'role'               => Role::USER,
            ],
        );

        $user->forceFill([
            'social_id'   => $googleUser->getId(),
            'social_type' => 'google',
        ]);

        $user->markEmailAsVerified();

        $user->cart()->save(
            Cart::create(['user_id' => $user->id]),
        );

        Auth::login($user, true);

        Mail::to($user)->send(new PasswordRegenerateMail($user->name, $password));

        return to_route('skills.courses');
    })->name('callback');
});

