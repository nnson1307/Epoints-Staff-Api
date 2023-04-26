<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use MyCore\Http\Response\ResponseFormatTrait;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
{
    use ResponseFormatTrait;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
        ValidationException::class,
        UnauthorizedHttpException::class
        //UnauthorizedHttpException
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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        }
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
//        $telemetry = app()->get(\DaiDP\AppInsights\TelemetryClient::class);

        if ($exception instanceof ValidationException) {
            $errs = $exception->validator->errors()->toArray();
            $msg  = $this->getValidMsg($errs);
            if (is_array($msg)) {
                $msg = current($msg);
            }

            // log ra kibana
//            $telemetry->trackException($exception, $errs);
//            $telemetry->flush();
            Log::error($msg, $errs);

            return $this->responseJson(CODE_ERROR, $msg, $errs);
        };

        // Log ra kibana
//        $telemetry->trackException($exception);
//        $telemetry->flush();
        Log::error($exception->getMessage());

        if ($exception instanceof UnauthorizedHttpException) {
            return $this->responseJson(CODE_UNAUTHORIZED, $exception->getMessage())
                ->setStatusCode(Response::HTTP_UNAUTHORIZED);
        };

        if ($exception instanceof QueryException) {
            //return $this->responseJson(CODE_ERROR, 'Dữ liệu không đúng.');
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }

        //return $this->responseJson(CODE_ERROR, $exception->getMessage());
        return parent::render($request, $exception);
    }

    /**
     * Lấy thông báo lỗi đầu tiên
     *
     * @param $errData
     * @return mixed|string
     */
    protected function getValidMsg($errData)
    {
        if (empty($errData)) {
            return 'Dữ liệu không đúng.';
        }

        return current($errData);
    }
}
