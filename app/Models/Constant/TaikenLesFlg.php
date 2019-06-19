<?php

namespace App\Models\Constant;

/**
 * taiken_les_flg(体験予約可)値一覧
 */
class TaikenLesFlg
{
    /** 不可 */
    const IMPOSSIBLE = 0;
    /** 可 */
    const POSSIBLE = 1;

    protected static $data = [
        ['id' => self::IMPOSSIBLE, 'name' => '不可'],
        ['id' => self::POSSIBLE, 'name' => '可'],
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