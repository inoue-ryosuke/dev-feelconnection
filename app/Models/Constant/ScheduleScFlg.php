<?php

namespace App\Models\Constant;

/**
 * schedule.sc_flg値一覧
 *
 */
class ScheduleScFlg
{
    /** 削除済み */
    const DELETED = 0;
    /** スケジュール実行前 */
    const BEFORE_EXECUTION = 1;
    /** スケジュール実行済み */
    const EXECUTED = 2;

    protected static $data = [
        ['id' => self::DELETED, 'name' => '削除済み'],
        ['id' => self::BEFORE_EXECUTION, 'name' => 'スケジュール実行前'],
        ['id' => self::EXECUTED, 'name' => 'スケジュール実行前'],
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