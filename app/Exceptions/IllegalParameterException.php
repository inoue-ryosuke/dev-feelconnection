<?php namespace App\Exceptions;
/**
 * パラメーター不正の例外
 */
class IllegalParameterException extends ApplicationException
{
    /**
     * @param $message
     * @param int $code
     * @param \Exception|null $previous
     * 406: NotAcceptable
     */
    public function __construct($message = "", $code = 406, \Exception $previous = null) {
        if (empty($message)) {
            $message = config('error.api.illegalParameter');
        }
        // 全てを正しく確実に代入する
        parent::__construct($message, $code, $previous);
    }

    // オブジェクトの文字列表現を独自に定義する
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
