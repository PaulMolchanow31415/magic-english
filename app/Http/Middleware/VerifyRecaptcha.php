<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class VerifyRecaptcha {
    public function handle(Request $request, Closure $next): Response {
        $request->validate(['recaptcha_token' => 'required|string',]);

        $response = Http::get(config('recaptcha.url'), [
            'secret'   => config('recaptcha.secret'),
            'response' => $request['recaptcha_token'],
            'remoteip' => $request->ip(),
        ]);

        /* USER
          "success" => true
          "challenge_ts" => "2023-12-26T16:11:40Z"
          "hostname" => "127.0.0.1"
          "score" => 0.9
          "action" => "login"
         */

        if ($response->successful()
            and $response->json("success")
                and $response->json("score") > 0.5
        ) {
            return $next($request);
        }

        return redirect()->back()->withErrors([
            'recaptcha_token' => 'Ошибка! Похоже на то, что вы робот:)',
        ]);
    }
}
