<?php

namespace App\Models\Constant;

/**
 * taiken_mess_flg(体験制限)値一覧
 * shift_master
 * lesson_master_tenpo
 */
class TaikenMess
{
    /** 非表示 */
    const NO_DISPLAY = 0;
    /** 表示 */
    const DISPLAY = 1;

    protected static $data = [
        ['id' => self::NO_DISPLAY, 'name' => '非表示'],
        ['id' => self::DISPLAY, 'name' => '表示'],
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