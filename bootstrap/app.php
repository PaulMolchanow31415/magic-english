<?php

use App\Http\Middleware\CheckRole;
use Illuminate\Foundation\Application;
use App\Http\Middleware\VerifyRecaptcha;
use App\Http\Middleware\EnsureUserIsUnlocked;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ])->alias([
            'recaptcha' => VerifyRecaptcha::class,
            'hasRole'   => CheckRole::class,
            'unlocked'  => EnsureUserIsUnlocked::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        if (!app()->environment(['local', 'testing'])) {
            $exceptions->respond(function (Response $response) {
                $status = $response->getStatusCode();

                if (in_array($status, [500, 503, 404, 403])) {
                    return inertia('Error', ['status' => $status])
                        ->toResponse(request())
                        ->setStatusCode($status);
                } elseif ($status === 419) {
                    return back()->with('message', 'Страница устарела, попробуйте перезагрузить.');
                }
            });
        }
    })->create();
