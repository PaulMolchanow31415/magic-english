<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler {
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Prepare exception for rendering.
     *
     * @param  \Throwable  $e
     *
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $e): JsonResponse|Response {
        $response = parent::render($request, $e);

        if (!app()->environment(['local', 'testing'])
            && in_array($response->status(), [500, 503, 404, 403])
        ) {
            return inertia('Error', ['status' => $response->status()])
                ->toResponse($request)
                ->setStatusCode($response->status());
        } elseif ($response->status() === 419) {
            return back()->with(['message' => 'Страница устарела, попробуйте перезагрузить.']);
        }

        /*return inertia('Error', ['status' => $response->status()])
            ->toResponse($request)
            ->setStatusCode($response->status());*/

        return $response;
    }

}
