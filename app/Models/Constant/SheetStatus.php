<?php

namespace App\Models\Constant;

/**
 * 座席の予約状態
 */
class SheetStatus
{
    /** 予約可 */
    const RESERVABLE = 1;
    /** 予約済み */
    const RESERVED = 2;
    /** お客様の予約されたバイク */
    const RESERVED_CUSTOMER = 3;

    protected static $data = [
        ['id' => self::RESERVABLE, 'name' => '予約可'],
        ['id' => self::RESERVED, 'name' => '予約済み'],
        ['id' => self::RESERVED_CUSTOMER, 'name' => 'お客様の予約されたバイク'],
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