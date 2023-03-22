<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception
     */
    public function report(Exception $exception)
    {
        if (app()->bound('sentry') && $this->shouldReport($exception)){
            app('sentry')->captureException($exception);
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // This will replace our 404 response with
        // a JSON response.
        if ($request->wantsJson()) {
            return $this->getJsonResponse($request, $exception);
        }

        return parent::render($request, $exception);
    }

    protected function getJsonResponse($request, Exception $exception)
    {
        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 401);
        }

        if ($exception instanceof HttpResponseException) {
            return response()->json([
                'products' => '',
                'perPage' => '',
                'page' => '',
                'totalRows' => '',
            ], 200);
        }

        if ($exception instanceof ValidationException) {
            return response()->json([
                'error' => $exception->getMessage(),
                'errors' => $exception->errors(),
            ], 422);
        }

        if ($exception instanceof \ErrorException) {
            dd($exception);
        }

        return response()->json([
            'message' => 'Resource not found'
        ], 404);
    }
}
