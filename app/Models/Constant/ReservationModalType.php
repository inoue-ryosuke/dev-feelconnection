<?php

namespace App\Models\Constant;

/**
 * 予約モーダル種別
 */
class ReservationModalType
{
    /** 予約モーダル-1 (通常予約） */
    const MODAL_1 = 10331;
    /** 予約モーダル-2(通常キャンセル、バイク変更) */
    const MODAL_2 = 10332;
    /** 予約モーダル-3(キャンセル待ち登録) */
    const MODAL_3 = 10333;

    protected static $data = [
        ['id' => self::MODAL_1, 'name' => '予約モーダル-1 (通常予約）'],
        ['id' => self::MODAL_2, 'name' => '予約モーダル-2(通常キャンセル、バイク変更)'],
        ['id' => self::MODAL_3, 'name' => '予約モーダル-3(キャンセル待ち登録)'],
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