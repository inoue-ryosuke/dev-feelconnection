<?php namespace App\Libraries\Common;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;

class Logger {

    /**
     * Write a log message using Monolog.
     *
     * @param string  $name
     * @param string  $message
	 * @param string  $dateFormat
	 * @param string  $splitter ファイル名のスプリッター
     * @return void
     */
	public static function writeLog($name, $message, $dateFormat = 'Ymd', $splitter = '_')
	{
//		$logMaxFiles = config('constant.app.logMaxFiles');
//		if (empty($logMaxFiles)) {
//			$logMaxFiles = 365;
//		}
		$level = MonologLogger::INFO;
		$log = new MonologLogger($level);
		if (empty($dateFormat)) {
			$filename = storage_path().'/logs/'.$name.'.log';
		} else {
			$filename = storage_path().'/logs/'.$name.$splitter.date($dateFormat).'.log';
		}
		$handler = new StreamHandler(
			$filename,
			$level,
			true,
			0777
		);
		$log->pushHandler($handler);
		list($usec, $sec) = explode(" ", microtime());
		$formatter = new LineFormatter(time2datetime($sec).substr($usec, 1)."\t%message%\n", null, true, true);
		$handler->setFormatter($formatter);
		$log->addInfo($message);
	}
}
