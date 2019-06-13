<?php

namespace App\Models\Constant;

/**
 * Redisのキー有効期限一覧(秒)
 */
class RedisKeyLimit
{
    /** バイク枠確保キー(10分)、expireは使用しない */
    const SHEET_LOCK = 600;
    /** shift_master(1ヶ月) */
    const SHIFT_MASTER = 2592000;

    protected static $data = [
        ['id' => self::SHEET_LOCK, 'name' => 'バイク枠確保キー(10分)、expireは使用しない'],
        ['id' => self::SHIFT_MASTER, 'name' => 'shift_master(1ヶ月)'],
    ];

    /**
     * 一覧取得
     */
    public static function getList()
    {
        return self::$data;
    }

    /**
     * 一覧取得(key,value形式)
     */
    public static function getListKV()
    {
        return array_column(self::$data, 'name', 'id');
    }

    /**
     * 名称取得
     *
     * @param int $id
     * @return NULL|mixed
     */
    public static function getName($id)
    {
        $array = self::getListKV();
        return isset($array[(int)$id]) ? $array[(int)$id] : NULL;
    }
}