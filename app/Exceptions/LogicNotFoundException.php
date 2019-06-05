<?php namespace App\Exceptions;
/**
 * ロジックが見つからない例外
 */
class LogicNotFoundException extends ApplicationException
{
    /**
     * @param $message
     * @param int $code
     * @param \Exception|null $previous
     * 500 内部エラー
     */
    public function __construct($message = "", $code = 500, \Exception $previous = null) {
        // 全てを正しく確実に代入する
        if (empty($message)) {
//            $message = config('error.logic.notFound');
            $message = config('error.systemError');
        }
        parent::__construct($message, $code, $previous);
    }

    // オブジェクトの文字列表現を独自に定義する
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
