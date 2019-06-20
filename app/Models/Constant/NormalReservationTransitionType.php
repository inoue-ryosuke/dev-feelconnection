<?php

namespace App\Models\Constant;

/**
 * 予約モーダルAPIで指定する、遷移先種別
 */
class NormalReservationTransitionType
{
    /** 予約確定(遷移は無し) */
    const RESERVATION_CONFIRMATION = 1;
    /** 予約モーダルリロード説明モーダル */
    const EXPLANATION_MODAL = 3005;
    /** 他店舗チケット・1回券選択モーダル */
    const OTHER_STORES_ONE_TIME_TICKET_SELECTION_MODAL = 1042;
    /** マンスリー・リミテッド1回券選択モーダル */
    const ONE_TIME_TICKET_SELECTION_MODAL = 1043;
    /** 他店舗チケット使用確認ダイアログ_3010 */
    const CONFIRM_DIALOG_3010 = 3010;
    /** 他店舗チケット支払い方法選択画面 */
    const PAYMENT_METHOD_SELECTION = 1104;
    /** 予約振替モーダル */
    const RESERVATION_TRANSFER_MODAL = 1024;
    /** チケット使用確認ダイアログ_3006 */
    const CONFIRM_DIALOG_3006 = 3006;
    /** 追加チケット購入モーダル */
    const BUY_ADDITIONAL_TICKET_MODAL = 1091;

    protected static $data = [
        ['id' => self::RESERVATION_CONFIRMATION, 'name' => '予約確定(遷移は無し)'],
        ['id' => self::EXPLANATION_MODAL, 'name' => '予約モーダルリロード説明モーダル'],
        ['id' => self::OTHER_STORES_ONE_TIME_TICKET_SELECTION_MODAL, 'name' => '他店舗チケット・1回券選択モーダル'],
        ['id' => self::ONE_TIME_TICKET_SELECTION_MODAL, 'name' => 'マンスリー・リミテッド1回券選択モーダル'],
        ['id' => self::CONFIRM_DIALOG_3010, 'name' => '他店舗チケット使用確認ダイアログ_3010'],
        ['id' => self::PAYMENT_METHOD_SELECTION, 'name' => '他店舗チケット支払い方法選択画面'],
        ['id' => self::RESERVATION_TRANSFER_MODAL, 'name' => '予約振替モーダル'],
        ['id' => self::CONFIRM_DIALOG_3006, 'name' => 'チケット使用確認ダイアログ_3006'],
        ['id' => self::BUY_ADDITIONAL_TICKET_MODAL, 'name' => '追加チケット購入モーダル'],
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