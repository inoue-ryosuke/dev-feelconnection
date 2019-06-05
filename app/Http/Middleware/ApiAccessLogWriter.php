<?php namespace App\Http\Middleware;

use App\Libraries\Logger;
use Illuminate\Http\Request;


/**
 * APIリクエスト・レスポンスログフィルター
 * Class ApiAccessLogWriter
 * @package App\Http\Middleware
 */
class ApiAccessLogWriter {

	/**
	 * リクエスト・レスポンスログフィルター
	 * @param Request $request
	 * @param \Closure $next
	 * @return mixed
	 */
	public function handle(Request $request, \Closure $next)
	{
		// フィルター前処理
		$response = $next($request);

        // フィルター後処理
        $log = Logger::makeApiAccessLog($request, $response);
        Logger::writeLog(Logger::API_ACCESS, $log);
		return $response;
	}



}
