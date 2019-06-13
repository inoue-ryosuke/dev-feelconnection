<?php

namespace App\Libraries\Logic\ReservationModal;

use Illuminate\Support\Facades\Redis;

/**
 * Redisのラッパー
 *
 */
class RedisWrapper
{
    /**
     * HGETALL キー名
     * Redisハッシュを連想配列で取得
     *
     * @param string $keyName
     * @return array
     */
    public static function hGetAll(string $key) {
        return Redis::hgetall($key);
    }

    /**
     * 指定したハッシュの子キーを削除
     *
     * @param string $key ハッシュのキー名
     * @param string $childKey ハッシュの子キー名
     * @return bool
     */
    public static function hDel(string $key, string $childKey) {
        return Redis::hdel($key, $childKey);
    }

}