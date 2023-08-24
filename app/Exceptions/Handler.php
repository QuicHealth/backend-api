<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use ErrorException;
use App\Traits\HasResponse;
use BadMethodCallException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    use HasResponse;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            $report = [
                "message" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine(),
                "code" => $e->getCode(),
            ];
        });
    }

    /**
     * Handles all throwable exceptions
     *
     * @param $request
     * @param \Throwable $e
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request, Throwable $e)
    {

        if ($e instanceof ModelNotFoundException && $request->wantsJson()) {
            return response()->json(
                [
                    'error' => 'Resource not found'
                ],
                404
            );
        }
        if ($e instanceof MethodNotAllowedHttpException) {

            return $this->notAllowedResponse(__('errors.method_not_allowed'));
        }

        if ($e instanceof NotFoundHttpException) {
            return $this->notFoundResponse($e->getMessage() ? $e->getMessage() : __('errors.not_found'));
        }

        if ($e instanceof ValidationException) {
            return $this->formValidationResponse(__('errors.validation_failed'), $e->errors());
        }

        if ($e instanceof AuthenticationException) {
            // return $this->notAllowedResponse($e->getMessage());
            if (!$request->expectsJson()) {
                return response()->json(['message' => 'Not Authenticated'], 403);
            }
        }

        if ($e instanceof AuthorizationException) {
            return $this->notAllowedResponse($e->getMessage());
        }

        if ($e instanceof AuthorizationException) {
            return $this->serverErrorResponse($e->getMessage());
        }

        // instanceof server error
        if ($e instanceof ErrorException || $e->getCode() == 0) {
            return $this->serverErrorResponse(__('errors.server_error'), new Exception($e->getMessage()));
        }

        if ($e instanceof BadMethodCallException) {

            return $this->serverErrorResponse(__('errors.server_error'), new Exception($e->getMessage()));
        }
        //default laravel response
        return parent::render($request, $e);
    }
}
