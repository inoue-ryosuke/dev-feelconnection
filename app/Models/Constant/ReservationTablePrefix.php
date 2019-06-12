<?php

namespace App\Models\Constant;

/**
 * 予約で使用するテーブル別名のプレフィックス
 */
class ReservationTablePrefix
{
    /** shift_master */
    const SM = 'SM_';
    /** lesson_master */
    const LM = 'LM_';
    /** lesson_class1 */
    const LC1 = 'LC1_';
    /** lesson_class2 */
    const LC2 = 'LC2_';
    /** lesson_class3 */
    const LC3 = 'LC3_';
    /** tenpo_master */
    const TM = 'TM_';
    /** user_master */
    const UM = 'UM_';

    protected static $data = [
        ['id' => self::SM, 'name' => 'shift_master'],
        ['id' => self::LM, 'name' => 'lesson_master'],
        ['id' => self::LC1, 'name' => 'lesson_class1'],
        ['id' => self::LC2, 'name' => 'lesson_class2'],
        ['id' => self::LC3, 'name' => 'lesson_class3'],
        ['id' => self::TM, 'name' => 'tenpo_master'],
        ['id' => self::UM, 'name' => 'user_master'],
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