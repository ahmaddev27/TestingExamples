<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\JsonResponseBuilder;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Exception;

class Handler extends ExceptionHandler
{
    use JsonResponseBuilder;


    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {

            if ($exception instanceof ModelNotFoundException) {
                return $this->errorResponse('Resource not found', null, 404);
            }

            if ($exception instanceof NotFoundHttpException) {
                return $this->errorResponse('Resource not found', null, 404);
            }

            if ($exception instanceof MethodNotAllowedHttpException) {
                return $this->errorResponse('Method not allowed', 405);
            }

            if ($exception instanceof AuthorizationException) {
                return $this->errorResponse('You are not authorized to perform this action.', 403);
            }

            if ($exception instanceof AuthenticationException) {
                return $this->errorResponse('Unauthenticated.', 401);
            }

            // Debugging information if app.debug is true
            if (config('app.debug')) {
                return response()->json([
                    'status' => 'error',
                    'message' => $exception->getMessage(),
                    'data' => null,
                    'code' => $exception->getCode() ?: 500,
                    'trace' => $exception->getTrace(),
                ], 500);
            }

            return $this->errorResponse('Server Error', 500);
        }

        return parent::render($request, $exception);
    }
}
