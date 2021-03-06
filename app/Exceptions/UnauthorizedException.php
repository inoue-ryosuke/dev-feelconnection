<?php namespace App\Exceptions;

/**
 * 認証エラー系操作の例外
 */
class UnauthorizedException extends ApplicationException
{
    /**
     * @param $message
     * @param int $code
     * @param \Exception|null $previous
     * 406: NotAcceptable
     */
    public function __construct($message = "", $code = 403, \Exception $previous = null) {
        if (empty($message)) {
            $message = config('error.api.unauthorized');
        }
        // 全てを正しく確実に代入する
        parent::__construct($message, $code, $previous);
    }

    // オブジェクトの文字列表現を独自に定義する
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
