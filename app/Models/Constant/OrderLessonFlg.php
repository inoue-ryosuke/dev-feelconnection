<?php

namespace App\Models\Constant;

/**
 * order_lesson.flgの値一覧
 */
class OrderLessonFlg
{
    /** 休講 */
    const NO_LESSON = -1;
    /** キャンセル/削除済み */
    const CANCELD_DELETED = 0;
    /** 予約済み */
    const RESERVED = 1;
    /** 受講済み */
    const ATTENDED = 2;

    protected static $data = [
        ['id' => self::NO_LESSON, 'name' => '休講'],
        ['id' => self::CANCELD_DELETED, 'name' => 'キャンセル/削除済み'],
        ['id' => self::RESERVED, 'name' => '予約済み'],
        ['id' => self::ATTENDED, 'name' => '受講済み'],
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