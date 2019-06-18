<?php

namespace App\Libraries\Logic\ReservationModal;

use Illuminate\Support\Facades\Redis;

/**
 * Redisのラッパー
 * 各Redisコマンドは、Redisファサードを使用して下記で呼び出し可能
 * Redis::コマンド名(パラメータ)
 *
 * @var \Illuminate\Redis\Connections\PredisConnection
 * @var \Illuminate\Redis\Connections\Connection
 * command($method, array $parameters = [])
 * $this->client->{$method}(...$parameters);
 *
 * @var \Predis\Client
 * public function __call($commandID, $arguments)
 *   {
 *       return $this->executeCommand(
 *           $this->createCommand($commandID, $arguments)
 *        );
 *   }
 *
 */
class RedisWrapper
{
    /**
     * HGETALL
     * Redisハッシュを連想配列で取得
     *
     * @param string $keyName ハッシュのキー名
     * @return array 取得できなかった場合は空の配列
     */
    public static function hGetAll(string $key) {
        return Redis::hgetall($key);
    }

    /**
     * HDEL
     * 指定したハッシュの子キーを削除
     *
     * @param string $key ハッシュのキー名
     * @param string $childKey ハッシュの子キー名
     * @return int 1:削除成功, 0:失敗
     */
    public static function hDel(string $key, string $childKey) {
        return Redis::hdel($key, $childKey);
    }

    /**
     * HMSET
     * 指定したキーのハッシュ生成
     *
     * @param string $key ハッシュのキー名
     * @param array $hashArray ハッシュのキー・値の連想配列
     * @return bool
     */
    public static function hmSet(string $key, array $hashArray) {
        return Redis::hmset($key, $hashArray);
    }

    /**
     * HSET
     * 指定したハッシュの子キー作成
     *
     * @param string $key ハッシュのキー名
     * @param string $childKey ハッシュの子キー名
     * @param string $childValue ハッシュの子キー・値
     * @return int 1:キーを新しく生成, 0:既存のキーを更新、
     */
    public static function hSet(string $key, string $childKey, string $childValue) {
        return Redis::hset($key, $childKey, $childValue);
    }

}