<?php

namespace App\Models\Constant;

/**
 * shift_master.flgの値一覧
 */
class ShiftMasterFlg
{
    /** 有効 */
    const VALID = 'Y';
    /** 削除済み */
    const DELETED = 'N';
    /** 休講 */
    const NO_LESSON = 'C';

    protected static $data = [
        ['id' => self::VALID, 'name' => '有効'],
        ['id' => self::DELETED, 'name' => '削除済み'],
        ['id' => self::NO_LESSON, 'name' => '休講'],
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