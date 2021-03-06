<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($this->isHttpException($exception))
        {
            return $this->renderHttpException($exception);
            return $this->renderHttpExceptionWithBlade($exception);
        }

        if ($exception instanceof ModelNotFoundException) {
            return $this->renderHttpExceptionWithBlade(new NotFoundHttpException("Model not found"));
        }

        if (config('app.debug') && !($exception instanceof ValidationException))
        {
            return $this->renderExceptionWithWhoops($exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Render an exception using Whoops.
     *
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    protected function renderExceptionWithWhoops(Exception $exception)
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());

        return new \Illuminate\Http\Response(
            $whoops->handleException($exception),
            $exception->getStatusCode(),
            $exception->getHeaders()
        );
    }

    /**
     * Render an http exception.
     *
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    protected function renderHttpExceptionWithBlade(Exception $exception)
    {
        $status = $exception->getStatusCode();
        $template = (view()->exists("errors::{$status}")) ? "errors::{$status}" : "errors.error";
        return response()->view($template, ['exception' => $exception], $status, $exception->getHeaders());
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
