<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Laravel\Passport\Exceptions\OAuthServerException;
use Illuminate\Support\Facades\Lang;
use App\Constants\Constants;

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
     * Report or log an exception.
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
        if ($exception instanceof OAuthServerException) { 

            $description = Lang::get('auth.validator.unauthorized'); 
            $error = $exception->getMessage();

            switch ($error) {
                case  "Client authentication failed":
                    $description = Lang::get('auth.validator.client');

                case  "The authorization grant type is not supported by the authorization server.":
                    $description = Lang::get('auth.validator.grant_type');
            };

            return $this->render_response($description);
        }

        return parent::render($request, $exception);
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
            $description = Lang::get('auth.validator.description'); 
            return $this->render_response( $description);
        }

        return redirect()->guest('login');
    }

    /**
     * Helper para renderizar response error
     */
    private function render_response( $description)
    {
        $status = Constants::UNAUTHORIZED_REQUEST;
        $message = Lang::get('auth.validator.message');

        $response = [
            "code" => $status,
            "message" => $message,
            "description" => $description
        ];
        return response()->json($response, $status);
    }
}
