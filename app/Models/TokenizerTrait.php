<?php namespace App\Models;

use App\Exceptions\IllegalParameterException;
use Illuminate\Support\Str;

/**
 * トークン情報を扱うトレイト
 * Class TokenizerTrait
 * @package App
 */
trait TokenizerTrait {

    /**
     * トークンを作成する。
     * @param int $length 長さ
     * @return トークン
     */
    public static function makeInviteCode($length = 32) {
        $count = 0;
        do {
            $token = Str::random($length);
            $count++;
        } while (self::where('invite_code', $token)->count() > 0 && $count < 10);
        return $token;
    }

    /**
     * トークンを作成する。
     * @param int $length 長さ
     * @return トークン
     */
    public static function makeToken($length = 32) {
        $count = 0;
        do {
            $token = Str::random($length);
            $count++;
        } while (self::where('token', $token)->count() > 0 && $count < 10);
        return $token;
    }

    /**
     * 数字トークンを作成する。
     * @param int $length 長さ
     * @return トークン
     */
    public static function makeNumberToken($length = 32) {
        $count = 0;
        do {
            $token = number_random($length);
            $count++;
        } while (self::where('token', $token)->count() > 0 && $count < 10);
        return $token;
    }
    public static function findByEventIds($ids) {
        if (empty($ids)) {
            return null;
        }
        return self::whereIn('id', $ids)->get();
    }

    /**
     * トークンで検索する
     * @param string $token
     * @return トークンで特定されるレコード
     */
    public static function findByToken($token,$lock=false) {
        if (empty($token)) {
            return null;
        }
        if ($lock) {
            return self::where('token', $token)->lockForUpdate()->first();
        }
        return self::where('token', $token)->first();
    }
    /**
     * トークンで検索する
     * @param string $token
     * @return トークンで特定されるレコード
     */
    public static function findByTokenOrFail($token,$lock=false) {
        if (empty($token)) {
            throw new IllegalParameterException(config("error.api.illigalToken"));
        }
        return self::findByToken($token,$lock);
    }

    /**
     * 削除済みも含めてトークンで検索する
     * @param string $token
     * @return トークンで特定されるレコード
     */
    public static function findByTokenWithTrashed($token) {
        if (empty($token)) {
            return null;
        }
        // 削除済みを含めて取得
        return self::where('token', $token)
            ->withTrashed()
            ->first();
    }

    /**
     * トークンを設定する。
     */
    public function setupToken() {
        if (!empty($this->token)) {
            return;
        }
        $this->token = self::makeToken();
    }

    /**
     *数字トークンを設定する。
     */
    public function setupNumberToken() {
        if (!empty($this->token)) {
            return;
        }
        $this->token = self::makeNumberToken(16);
    }

    /**
     * 有効なトークンかチェックする
     * @param $token
     */
    public static function validateToken($token) {
        if(self::isValidToken($token) === false){
            throw new IllegalParameterException(config("error.api.illigalToken"));
        }
    }

    /**
     * 有効なトークンかチェックする
     * @param $token
     * @return boolean
     */
    public static function isValidToken($token)
    {
        if (empty($token)) {
            return false;
        }
        $record = self::findByToken($token);
        if (empty($record)) {
            return false;
        }

        return true;
    }
    /**
     * トークン(array)で検索する
     * @param $token
     * @return NULL|array
     */
    public static function findListByToken(array $token)
    {
        return self::whereIn('token', $token)->get();
    }
}
