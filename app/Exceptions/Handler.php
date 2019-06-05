<?php

namespace App\Exceptions;

use App\Libraries\Logger;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Session\TokenMismatchException;
use App\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        // ログの出力
        $debug_backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 20);
        logger()->error($debug_backtrace);
        $prevItem = collect([]);
        $error_log = collect($debug_backtrace)->keyBy(function($item) use ($prevItem) {

            $err_class    = isset($item['class'])    ? $item['class'] : '(unknown class)';
            $err_function = isset($item['function']) ? $item['function'] : '(unknown function)';
            $err_file     = isset($item['file'])     ? $item['file'] : '(unknown file)';
            $err_line     = isset($item['line'])     ? $item['file'] : '(unknown line)';

            $message = "at ".$err_class.".".$err_function." (".basename($err_file).":".$err_line.")";
            return $message;

        })->keys();
        $userId = $request->headers->get('X-FeelConnection-GuardUID');
        $device = $request->headers->get('X-FeelConnection-Device');
        $version = $request->headers->get('X-FeelConnection-Version');
//        $method = array_selector('REQUEST_METHOD', $_SERVER, '-');
//        $uri = array_selector('REQUEST_URI', $_SERVER, '-');
        $method = array_get($_SERVER, 'REQUEST_METHOD', '-');
        $uri = array_get($_SERVER,'REQUEST_URI', '-');
        $ua = $request->headers->get('USER_AGENT');
        $code = $this->isHttpException($exception)?$exception->getStatusCode():$exception->getCode();
        $message = $exception->getMessage();
        $className = get_class($exception);

        $serverAddr = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '127.0.0.1';
        $errorId = sprintf("%03d%s", preg_replace('/^\d+\.\d+\.\d+\./', '', $serverAddr), time());
        $access = sprintf("%s\t%s\t%s\t%s\t%s\t%s\t%s", $userId, $device, $version, $method, $uri, $ua, json_encode($error_log));

        // error.log
        Logger::writeErrorLog(
            $errorId . "\t" . $code. "\t" . $className . "\t" . $message."\t".
            $exception->getFile().":".$exception->getLine()."\t".
            $exception->getMessage()."\t".
            $access);

        // laravel.log
        logger()->debug($exception->getMessage());
        logger()->error("Error occured.\n".$className.":".$code.":".$message."\n    ".$error_log->implode("\n    "));

        // 404 NotFound の場合
        if (!isApiRequest() && $exception instanceof NotFoundHttpException) {
            return redirect()->route("admin.top.get");
        }
        // API向けの出力
        if(isApiRequest()){
            $response =  [
                'error'     => 1,
                'errorId'   => $errorId,
                'code'      => $code,
                'message'   => $message
            ];

            $return_code = $this->getReturnCode($exception);
            return new JSONResponse($response,$return_code);
        }
        // その他例外の場合
        return redirect()->route("admin.top.get");
        // 描画
        //return parent::render($request, $exception);
    }

    /**
     * Render an exception to the console.
     *
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @param  \Exception                                        $e
     *
     * @return void
     */
    public function renderForConsole( $output, Exception $e ) {
        echo $e->getMessage();
    }

    /**
     * ステータスコード返却
     * TokenMismatchException：200で返す
     * それ以外は400番台
     * @param $e
     * @return number
     */
    public function getReturnCode($e)
    {

        if( ($e instanceof TokenMismatchException)){
            return 200;
        }

        //HttpException継承している例外はgetStatusCode
        //それ以外は400で返す
        if($this->isHttpException($e)){
            return $e->getStatusCode();
        }else{
            return 400;
        }

    }

}
