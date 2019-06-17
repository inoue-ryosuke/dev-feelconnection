<?php

namespace App\Models\Constant;

/**
 * order_lesson.trial_flgの値一覧
 */
class OrderLessonTrialFlg
{
    /** 体験レッスン(初回レッスン) */
    const TRIAL_LESSON = 1;
    /** 通常レッスン */
    const NORMAL_LESSON = 0;

    protected static $data = [
        ['id' => self::TRIAL_LESSON, 'name' => '体験レッスン(初回レッスン)'],
        ['id' => self::NORMAL_LESSON, 'name' => '通常レッスン'],
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