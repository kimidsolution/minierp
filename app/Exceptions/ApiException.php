<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Validation\ValidationException;
use Symfony\Component\ErrorHandler\Error\FatalError;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiException extends Exception
{
    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        $httpCode = (false == method_exists($exception, 'getStatusCode')) ? 500 : $exception->getStatusCode();
        $messageError = $exception->getMessage();

    	if (true == $exception instanceOf ValidationException) {
            $messageError = app('array.helper')->getErrorLaravelFirstKey($exception->errors());
            $httpCode = 400;
        }

        if (true == $exception instanceOf NotFoundHttpException) {
        	$messageError = 'Route not found';
        	$httpCode = 404;
        }
        
        if (200 == $httpCode) {
        	return response()->api(true, [], [], $messageError, $httpCode);
        } else {
        	return response()->api(false, [], [], $messageError, $httpCode);
        }
    }
}
