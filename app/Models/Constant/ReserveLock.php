<?php

namespace App\Models\Constant;

/**
 * レッスン予約・キャンセル排他ロック
 * cust_master.reserve_lock, shift_master.reserve_lock
 */
class ReserveLock
{
    /** ロック中 */
    const LOCK = 'Y';
    /** ロック中でない */
    const UNLOCK = 'N';

    protected static $data = [
        ['id' => self::LOCK, 'name' => 'ロック中'],
        ['id' => self::UNLOCK, 'name' => 'ロック中でない'],
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