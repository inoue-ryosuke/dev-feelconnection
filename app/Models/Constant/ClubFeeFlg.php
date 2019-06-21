<?php

namespace App\Models\Constant;

/**
 * club_fee.flgの値一覧
 */
class ClubFeeFlg
{
    /** 削除済み */
    const DELETED = 0;
    /** 有効 */
    const VALID = 1;

    protected static $data = [
        ['id' => self::DELETED, 'name' => '休講'],
        ['id' => self::VALID, 'name' => 'キャンセル/削除済み'],
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