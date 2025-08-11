<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();

            if ($statusCode === 403) {
                return response()->view('errors.403', [], 403);
            }
            if ($statusCode === 404) {
                return response()->view('errors.404', [], 404);
            }
            if ($statusCode === 500) {
                return response()->view('errors.500', [], 500);
            }
            if ($statusCode === 503) {
                return response()->view('errors.503', [], 503);
            }
        }

        return parent::render($request, $exception);
    }
}