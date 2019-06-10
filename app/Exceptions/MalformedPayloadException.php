<?php namespace App\Exceptions;
/**
 * ペイロード不正の例外
 */
class MalformedPayloadException extends ApplicationException
{
    /**
     * @param $message
     * @param int $code
     * @param \Exception|null $previous
     * 406: Not Accepatble
     */
    public function __construct($message = "", $code = 406, \Exception $previous = null) {
        // 全てを正しく確実に代入する
        parent::__construct($message, $code, $previous);
    }

    // オブジェクトの文字列表現を独自に定義する
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
