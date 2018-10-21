<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
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
        if ( $exception instanceof HttpException ) {
            /** @var HttpException $exception */
            if ( $exception->getStatusCode( ) === 404 ) {
                return response( )->json( [
                    'code'              =>  404,
                    'error'             =>  'not_found',
                    'error_description' =>  'The requested resource could not be found',
                ], 404 );
            }
        }

        if ( $exception instanceof ValidationException) {
            /** @var ValidationException $exception */
            return response( )->json( [
                'code'              =>  422,
                'error'             =>  'validation',
                'error_description' =>  'Validation failed',
                'data'              =>  $exception->errors( ),
            ], 422 );
        }

        $response = [
            'code'              =>  500,
            'error'             =>  'server_error',
            'error_description' =>  'A server error has occured',
        ];

        if ( env( 'APP_DEBUG' ) ) {
            $response[ 'exception' ] = [
                'class'         =>  get_class( $exception ),
                'message'       =>  $exception->getMessage( ),
                'filename'      =>  $exception->getFile( ),
                'line'          =>  $exception->getLine( ),
            ];
        }

        return response( )->json( $response, 500 );
    }
}
