<?php namespace App\Http\Middleware;

use App\Libraries\Logger;
use DB;
use Input;
use Response;
use Request;
use Closure;
use Config;
use Log;


/**
 * リクエスト・レスポンスログフィルター
 * Class AccessLogWriter
 * @package App\Http\Middleware
 */
class AccessLogWriter {

	/**
	 * リクエスト・レスポンスログフィルター
	 * @param $request
	 * @param Closure $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
	    Logger::writeBenchmarkLog("*** Logger start.");
		// フィルター前処理
		$response = $next($request);

        $log = Logger::makeAccessLog($request, $response);
        Logger::writeLog(Logger::ACCESS, $log);

        Logger::writeBenchmarkLog("*** Logger end.");
		return $response;
	}


}
