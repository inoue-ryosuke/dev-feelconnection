<?php

namespace App\Models\Constant;

/**
 * order_lesson.sb_flgの値一覧
 */
class OrderLessonSbFlg
{
    /** 通常予約 */
    const NORMAL = 0;
    /** キャンセル待ち予約 */
    const CANCEL_WAITING = 1;

    protected static $data = [
        ['id' => self::NORMAL, 'name' => '通常予約'],
        ['id' => self::CANCEL_WAITING, 'name' => 'キャンセル待ち予約'],
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