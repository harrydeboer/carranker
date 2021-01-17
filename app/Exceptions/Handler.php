<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Contracts\View\Factory;

class Handler extends ExceptionHandler
{
    public function __construct(
        Container $container,
        private Factory $viewFactory,
    ) {
        parent::__construct($container);
    }

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
        /** The error views need a message, controller (for css and js) and a title. */
        $this->viewFactory->share('message', $e->getMessage());
        $this->viewFactory->share('controller', 'error');

        try {
            if ($e->getStatusCode() === 404) {
                $this->viewFactory->share('title', 'Not Found');
            }  else {
                $this->viewFactory->share('title', 'Error');
            }
        } catch (Throwable $exceptionNot404) {
            $this->viewFactory->share('title', 'Error');
        }

        return parent::render($request, $e);
    }
}
