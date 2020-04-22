<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $e)
    {
        $viewFactory = app('Illuminate\Contracts\View\Factory');
        $viewFactory->share('message', $e->getMessage());
        $viewFactory->share('controller', 'error');

        try {
            if ($e->getStatusCode() === 404) {
                $viewFactory->share('title', 'Not Found');
            }  else {
                $viewFactory->share('title', 'Error');
            }
        } catch (Throwable $exceptionNot404) {
            $viewFactory->share('title', 'Error');
        }

        return parent::render($request, $e);
    }
}
