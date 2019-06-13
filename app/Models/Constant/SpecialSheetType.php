<?php

namespace App\Models\Constant;

/**
 * 座席の特別エリア種別
 */
class SpecialSheetType
{
    /** トライアル優先席 */
    const TRIAL = 1;

    protected static $data = [
        ['id' => self::TRIAL, 'name' => 'トライアル優先席'],
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