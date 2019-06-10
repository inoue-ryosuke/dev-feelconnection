<?php namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * アプリケーション例外
 */
class ApplicationException extends HttpException
{
    /**
     * @param $message
     * @param int $code
     * @param \Exception|null $previous
     */
    // 例外を再定義し、メッセージをオプションではなくする
    public function __construct($message = "", $code = 0, \Exception $previous = null) {
        // なんらかのコード

        // 全てを正しく確実に代入する
        parent::__construct($code, $message, $previous);
    }

    // オブジェクトの文字列表現を独自に定義する
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}
