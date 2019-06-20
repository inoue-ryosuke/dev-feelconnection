<?php

namespace App\Models\Constant;

/**
 * order_lesson.cancel_prohibitの値一覧
 */
class OrderLessonCancelProhibit
{
    /** 可 */
    const POSSIBLE = 0;
    /** 不可 */
    const IMPOSSIBLE = 1;

    protected static $data = [
        ['id' => self::POSSIBLE, 'name' => '可'],
        ['id' => self::IMPOSSIBLE, 'name' => '不可'],
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