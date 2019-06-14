<?php namespace App\Libraries;

use App\Http\Controllers\Api\ApiHeaderTrait;
use App\Libraries\Common\Logger as BaseLogger;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Logger extends BaseLogger {

    use ApiHeaderTrait;

    // システムログを記録するログ
    const APP_SYSTEM        = 'system';
    // 例外ログ
    const APP_ERROR         = 'error';
    // メールログ
    const MAIL              = 'mail';
    // メールログ
    const BATCH             = 'batch';
    // モバイルユーザー
    const MOBILE_USER       = 'mobile_user';
    // それ以外の通常アクセス
    const ACCESS            = 'access';
    // APIアクセス
    const API_ACCESS        = 'api_access';
    // 通信ログ
    const COMMUNICATION     = 'communication';
    // ベンチマーク
    const BENCHMARK         = 'bench';
    // ユーザー登録
    const USER              = 'user';

    const LEVEL_DEBUG = 'DEBUG';
    const LEVEL_INFO = 'INFO';
    const LEVEL_WARNING = 'WARNING';
    const LEVEL_ERROR = 'ERROR';
    const LEVEL_FATAL = 'FATAL';
    const LEVEL_NOTICE = 'NOTICE';

    /**
     * ログを出力する。
     * @param string $name
     * @param string $message
     * @param string $dateFormat
     * @param string $splitter
     */
    public static function writeLog($name, $message, $dateFormat = 'Ymd', $splitter = '_') {
        parent::writeLog($name, $message, $dateFormat, $splitter);
    }

    /**
     * バッチログを作成する。
     * @param $message
     */
    public static function writeBatchLog($message) {
        $message = sprintf("%s", $message);
        parent::writeLog(self::BATCH, $message);
    }

    /**
     * ベンチマークログを作成する。
     * @param string $message 概要
     * @param string $type API-XX (API ID)もしくは A～Z-XX (ページID)で記載する。範囲が広い場合は、XXで記載。
     */
    public static function writeBenchmarkLog($message, $type = "") {
        $userId = self::getGuid();
        $message = sprintf("%s:%s\t%s\t%s\t%s\t%s\t%s",
            getmypid(),
            data_get($_SERVER, 'REMOTE_ADDR', '-'),
            data_get($_SERVER, 'REQUEST_METHOD', '-'),
            data_get($_SERVER, 'REQUEST_URI', '-'),
            $type,
            $message,
            $userId
        );
        parent::writeLog(self::BENCHMARK, $message);
    }


    /**
     * メールログを作成する。
     * @param $toAddress
     * @param $subject
     * @param $body
     */
    public static function writeMailLog($toAddress, $subject, $body = "") {
        $message = sprintf("%s\t%s\t%s", $toAddress, $subject, $body);
        parent::writeLog(self::MAIL, $message);
    }


    /**
     * ポイント付与
     * @param $importId
     * @param $record
     * @param $status
     * @return string
     */
    public static function makePointAddLog($importId, $record, $status) {
        $message = sprintf("%s\t%s\t%s\t%s\t%s\t%s",
            $importId,
            $record['email'],
            $record['pin_code'],
            $record['management_code'],
            $record['point'],
            $status
        );
        return $message;
    }

    /**
     * @param $request
     * @param $response
     * @return string
     */
    public static function makeAccessLog($request, $response): string
    {
        // ユーザー特定情報
        $userId = self::getGuid();

        // 共通
        $header = json_encode($request->headers->all());
        $responseHeader = json_encode($response->headers->all());
        $cookies = [];
        foreach ($response->headers->getCookies() as $c) {
            $cookies[$c->getName()] = $c->getValue();
        }
        $cookie = json_encode($cookies);

        $log = sprintf("%s\t%s\t%s\t%s\t%s\t%s\t%s",
            self::LEVEL_INFO,
            data_get($_SERVER, 'REQUEST_METHOD', '-'),
            data_get($_SERVER, 'REQUEST_URI', '-'),
            $userId,
            $header,
            $responseHeader,
            $cookie
        );
        return $log;
    }

    /**
     * API アクセスログを出力
     * @param $request
     * @param $response
     * @return string
     */
    public static function makeApiAccessLog($request, $response): string
    {
        // 各種サービス系のログ
        $userId = self::getGuid();
        $device = self::getDevice();
        $version = self::getApiVersion();
        $message = sprintf("%s\t%s\t%s", $userId, $version, $device);

        // その他共通
        $payload = trim(request()->getContent());
        logger(get_class($response));
        if ($response instanceof BinaryFileResponse) {
            $responseContent = "(Binary)";
        } elseif ($response instanceof StreamedResponse) {
            $responseContent = "(Binary)";
        } else {
            $responseContent = $response->content();
        }
        $header = json_encode($request->headers->all());
        $matches = [];
        $errorCode = preg_match('/"error":"?(\d+)"?,?/', $responseContent, $matches) ? $matches[1] : 0;
//		self::debugLog($responseContent);

        $log = sprintf("%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s",
            self::LEVEL_INFO,
            data_get($_SERVER, 'REQUEST_METHOD', '-'),
            data_get($_SERVER, 'REQUEST_URI', '-'),
            $message,
            $errorCode,
            $responseContent,
            $payload,
            $header
        );
        return $log;
    }

    /**
     * システムログを作成する。
     * @param $code
     * @param $level
     * @param $linePos
     * @param $message
     * @param $param
     * @return string
     */
    public static function makeSystemLog($code, $level, $linePos, $message, $param) {
        if (is_array($param)) {
            $param = json_encode($param);
        }
        // 設定メッセージの内容で上書きできれば上書き
        $configMessage = config($message);
        if (!empty($configMessage) && is_string($configMessage)) {
            $message = $configMessage;
        } else if (!empty($configMessage)) {
            $message = get_class($configMessage);
        }
        return sprintf("%s\t%s\t%s\t%s\t%s", $code, $linePos, $level, $message, $param);
    }

    /**
     * システムログを出力する。
     * @param $level
     * @param $id
     * @param $linePos
     * @param $message
     * @param $param
     */
    public static function writeSystemLog($level, $id, $linePos, $message, $param = []) {
        // ファイルパス部分を容量節約のため落とす
        $linePos = str_replace(base_path(), '', $linePos);
        // 埋め込み用エラーコードに変換
        $code = sprintf("%05d", $id);
        $log = self::makeSystemLog($code, $level, $linePos, $message, $param);
        self::writeLog(Logger::APP_SYSTEM, $log);
        logger($log);
    }


    /**
     * エラーログを出力する
     * @param $message
     */
    public static function writeErrorLog($message) {
        self::writeLog(self::APP_ERROR, $message);
    }

    /**
     * エラーログを出力する
     * @param $pos
     * @param $memo
     * @param $message
     */
    public static function writeCommunicationLog($pos, $memo = "", $message = "") {
        $userId = self::getGuid();
        $message = sprintf("%s:%s\t%s\t%s\t%s\n%s\n-------\n",
            getmypid(),
            data_get($_SERVER, 'REMOTE_ADDR', '-'),
            $pos,
            $userId,
            $memo,
            $message
        );

        self::writeLog(self::COMMUNICATION, $message);
    }

    /**
     * CSVヘッダーを作成する。
     * @param $columns
     * @param $splitter
     * @param $quote
     * @param string $splitter
     * @param $return
     * @return ヘッダー
     */
    public static function makeCsvHeader($columns, $splitter = ',', $quote = '"', $return = "\r\n") {
        $headers = [];
        if ($quote === '"') {
            foreach ($columns as $key => $column) {
                $headers[] = $quote.preg_replace('/"/', '""', $column).$quote;
            }
        } else {
            foreach ($columns as $key => $column) {
                $headers[] = $column;
            }
        }
        return convert_encoding(implode($splitter, $headers), config("constant.csvEncoding"), config("constant.internalEncoding")).$return;
    }

    /**
     * CSレコードを作成する。
     * @param $columns
     * @param $record
     * @param $splitter
     * @param $quote
     * @param $return
     * @param string $splitter
     * @return ヘッダー
     */
    public static function makeCsvRecord($columns, $record, $splitter = ',', $quote = '"', $return = "\r\n") {
        $headers = [];
        if ($quote === '"') {
            foreach ($columns as $key => $column) {
                $headers[] = $quote.preg_replace('/"/', '""', $record[$key]).$quote;
            }
        } else {
            foreach ($columns as $key => $column) {
                $headers[] = $record[$key];
            }
        }
        return convert_encoding(implode($splitter, $headers), config("constant.csvEncoding"), config("constant.internalEncoding")).$return;
    }


    /**
     * エラーがあった場合、デバッグログを残す。
     * @param $responseContent
     */
    public static function debugLog($responseContent) {
        if (preg_match('/"error":"?(\d+)"?,?/', $responseContent, $matches)) {
            $errorCode = $matches[1];
            // エラーが0以上のときはログに残す
            if ($errorCode > 0) {
                logger('errorCode:'.$errorCode);
            }
        }
    }
}