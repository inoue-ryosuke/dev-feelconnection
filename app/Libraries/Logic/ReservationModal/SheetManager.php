<?php

namespace App\Libraries\Logic\ReservationModal;

use App\Models\OrderLesson;
use App\Models\Constant\SheetStatus;
use App\Models\Constant\ReservationModalType;
use App\Models\Constant\OrderLessonSbFlg;
use App\Models\Constant\RedisKeyLimit;
use App\Models\Constant\SpecialSheetType;

/**
 * 座席情報、バイク枠確保の管理
 *
 */
class SheetManager
{
    /** レッスンスケジュールID */
    private $shiftId;
    /** スタジオ情報 */
    private $studio;
    /** 予約モーダル種別 */
    private $modalType;

    /**
     *
     * @param int $shiftId shift_master.shiftid
     */
    public function __construct(int $shiftId) {
        $this->shiftId = $shiftId;
    }

    /**
     * スタジオ情報の初期化
     */
    public function initStudio() {
        // スタジオ情報([座席番号, x, y, 座席ステータス, 特別エリア情報]のオブジェクト配列)の初期化
        // $this->studio = new Studio($this->shiftId);
        // 仮データ
        $this->studio = array(
            1 => [ 'x' => 1, 'y' => 1, 'status' => SheetStatus::RESERVABLE, 'special_area_info' => [ SpecialSheetType::TRIAL ] ],
            2 => [ 'x' => 2, 'y' => 1, 'status' => SheetStatus::RESERVABLE, 'special_area_info' => [ SpecialSheetType::TRIAL ] ],
            3 => [ 'x' => 2, 'y' => 2, 'status' => SheetStatus::RESERVABLE, 'special_area_info' => [ SpecialSheetType::TRIAL ] ],
            4 => [ 'x' => 2, 'y' => 3, 'status' => SheetStatus::RESERVABLE, 'special_area_info' => [ SpecialSheetType::TRIAL ] ],
            5 => [ 'x' => 5, 'y' => 1, 'status' => SheetStatus::RESERVABLE, 'special_area_info' => [] ]
        );

        // 予約モーダル-1 (通常予約）
        $this->modalType = ReservationModalType::MODAL_1;
    }

    /**
     * バイクの予約状態をスタジオ情報に登録、予約モーダル種別を登録
     *
     * @param int $customerId cust_master.cid
     */
    public function setSheetStatusAndModalType(int $customerId) {
        $collection = OrderLesson::getReservedSheetList($this->shiftId);
        $secureSheetList = $this->getSecureSheetList();

        // 予約済み座席数 == スタジオ座席数の場合は、予約モーダル-3(キャンセル待ち登録)で登録
        // $this->studio->getSheetCount();
        if (count($secureSheetList) !== 0 && count($secureSheetList) === count($this->studio)) {
            $this->modalType = ReservationModalType::MODAL_3;
        }

        // DBから取得した予約状態から、座席予約状態を登録
        foreach ($collection as $model) {
            if ($model->customer_id !== $customerId) {
                // 予約済みバイク
                // $this->studio->setSheetStatus();
                $this->studio[$model->sheet]['status'] = SheetStatus::RESERVED;
            } else {
                // お客様の予約されたバイク
                // $this->studio->setSheetStatus();
                $this->studio[$model->sheet]['status'] = SheetStatus::RESERVED_CUSTOMER;

                if ($model->sb_flg === OrderLessonSbFlg::NORMAL) {
                    // 通常予約している場合は、予約モーダル-2(通常キャンセル、バイク変更)
                    $this->modalType = ReservationModalType::MODAL_2;
                } else if ($model->sb_flg === OrderLessonSbFlg::CANCEL_WAITING) {
                    // キャンセル待ち予約している場合は、予約モーダル-3(キャンセル待ち登録)
                    $this->modalType = ReservationModalType::MODAL_3;
                }
            }
        }

        // Redisから取得したバイク枠確保情報から、座席予約状態を登録
        $keyLimt = RedisKeyLimit::SHEET_LOCK;
        $shiftIdHashKey = 'sheet_lock_shiftid:' . $this->shiftId;
        $currentDateTime = new \DateTime();
        foreach ($secureSheetList as $customerIdkey => $value) {
            $sheetNoDateTime = CommonLogic::pasrseSheetLockRecord($value);

            $recordDateTime = new \DateTime($sheetNoDateTime[1]);
            $recordDateTime->modify("+{$keyLimt} second");

            // キーの有効期限(10分)を過ぎているかどうか
            if ($currentDateTime > $recordDateTime) {
                // レッスンスケジュールごとのバイク枠確保キーを削除
                RedisWrapper::hDel($shiftIdHashKey, $customerIdkey);
                // 会員IDごとのバイク枠確保キーを削除
                RedisWrapper::hDel("shift_lock_cid:{$customerIdkey}", $this->shiftId);

                continue;
            }

            // ログインユーザーがバイク枠確保していない場合は、予約済みに変更
            if ($customerIdkey !== $customerId) {
                $this->studio[$sheetNoDateTime[0]]['status'] = SheetStatus::RESERVED;
            }
        }
    }

    /**
     * バイク枠確保している座席情報を取得
     *
     * @return array バイク枠確保している座席一覧(Redis)
     */
    private function getSecureSheetList() {
        return RedisWrapper::hGetAll('sheet_lock_shiftid:' . $this->shiftId);
    }

    /**
     * ネット・トライアル会員の場合は、体験座席以外を予約済みに変更
     *
     * @param int $customerType cust_master.memtype
     */
    public function fillNotSpecialSheetTrial(int $customerType) {
        // TODO 会員種別を受け取って、ネット・トライアル会員を判別
        if (true) {
            // $sheetNoSpecialAreaInfo = $this->studio->getSheetNoSpecialAreaInfo();
            // $this->studio->setSheetStatus();
            foreach ($this->studio as $key => &$value) {
                if (!in_array(SpecialSheetType::TRIAL, $value['special_area_info'], true)) {
                    // 予約済みに変更
                    $value['status'] = SheetStatus::RESERVED;
                }
            }
        }
    }

    /**
     * 予約モーダル種別を取得
     *
     * @return int 予約モーダル種別
     */
    public function getReservationModalType() {
        return $this->modalType;
    }

    /**
     * レスポンスとして渡す座席情報配列取得
     *
     * @return array 座席情報
     */
    public function getResponseSheetsArray() {
        //return $this->studio->getSheetsArray();

        $sheets = array();

        foreach ($this->studio as $key => $value) {
            $sheets[] = array_merge([ 'sheet_no' => $key], $value);
        }

        return $sheets;
    }
}