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

    /** レッスンスケジュールIDごとのバイク枠確保キープレフィックス */
    const SHEET_LOCK_SHIFTID_PREFIX = 'sheet_lock_shiftid:';
    /** 会員IDごとのバイク枠確保キープレフィックス */
    const SHEET_LOCK_CID_PREFIX = 'sheet_lock_cid:';

    /** 未予約状態の座席の会員ID */
    const RESERVABLE_SHEET_CUSTOMER_ID = 0;

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
        // スタジオ情報([座席番号, x, y, 座席ステータス, 特別エリア情報, 予約した会員のID]のオブジェクト配列)の初期化
        // $this->studio = new Studio($this->shiftId);
        // 仮データ
        $this->studio = array(
            1 => [ 'x' => 1, 'y' => 1, 'status' => SheetStatus::RESERVABLE, 'special_area_info' => [ SpecialSheetType::TRIAL ], 'customerId' => self::RESERVABLE_SHEET_CUSTOMER_ID ],
            2 => [ 'x' => 2, 'y' => 1, 'status' => SheetStatus::RESERVABLE, 'special_area_info' => [ SpecialSheetType::TRIAL ], 'customerId' => self::RESERVABLE_SHEET_CUSTOMER_ID ],
            3 => [ 'x' => 2, 'y' => 2, 'status' => SheetStatus::RESERVABLE, 'special_area_info' => [ SpecialSheetType::TRIAL ], 'customerId' => self::RESERVABLE_SHEET_CUSTOMER_ID ],
            4 => [ 'x' => 2, 'y' => 3, 'status' => SheetStatus::RESERVABLE, 'special_area_info' => [ SpecialSheetType::TRIAL ], 'customerId' => self::RESERVABLE_SHEET_CUSTOMER_ID ],
            5 => [ 'x' => 5, 'y' => 1, 'status' => SheetStatus::RESERVABLE, 'special_area_info' => [], 'customerId' => self::RESERVABLE_SHEET_CUSTOMER_ID ]
        );

        // 予約モーダル-1 (通常予約）
        $this->modalType = ReservationModalType::MODAL_1;
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
     * 座席予約状態取得
     *
     * @param int $sheetNo 座席番号
     * @return int
     */
    public function getSheetStatus(int $sheetNo) {
        // $this->studio->getSheetStatus($sheetNo);
        return $this->studio[$sheetNo]['status'];
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

    /**
     * 座席番号が該当スタジオで有効か
     *
     * @param int $sheetNo 座席番号
     * @return bool
     */
    public function isSheetNoValid(int $sheetNo) {
        // $this->studio->isSheetNoValid($sheetNo);
        // 仮
        return $sheetNo >= 1 && $sheetNo <= 5;
    }

    /**
     * 指定されたバイクが、ログインユーザー or 他のユーザーによって枠確保済み・予約済みかどうか
     * 体験レッスンで指定した座席が体験バイクでない場合は、予約済みとなりバリデーションで弾かれる
     *
     * @param int $sheetNo 座席番号
     * @return bool
     */
    public function isSheetReserved(int $sheetNo) {
        // return $this->studio->isSheetReserved();
        return $this->studio[$sheetNo]['status'] === SheetStatus::RESERVED
        || $this->studio[$sheetNo]['status'] === SheetStatus::RESERVED_CUSTOMER;
    }

    /**
     * 定員、体験定員が満席かどうか
     *
     * @param bool $trialFlag 体験レッスン受講済み状態
     * @param int $capacity 定員
     * @param int $trialCapacity 体験定員
     * @return bool
     */
    public function isStudioFull(bool $trialFlag, int $capacity, int $trialCapacity) {
        // return $this->studio->isFull();

        if ($trialFlag) {
            // 定員
            $filtered = array_filter($this->studio, function ($sheet) {
                return $sheet['status'] === SheetStatus::RESERVED || $sheet['status'] === SheetStatus::RESERVED_CUSTOMER;
            });

            return count($filtered) < $capacity;
        } else {
            // 体験定員
            $filtered = array_filter($this->studio, function ($sheet) {
                return in_array(SpecialSheetType::TRIAL, $sheet['special_area_info'], true)
                && ($sheet['status'] === SheetStatus::RESERVED || $sheet['status'] === SheetStatus::RESERVED_CUSTOMER);
            });

            return count($filtered) < $trialCapacity;
        }
    }

    /**
     * バイク枠確保している座席情報一覧を取得
     *
     * @return array バイク枠確保している座席一覧(Redis)
     */
    private function getSecureSheetList() {
        return RedisWrapper::hGetAll(self::SHEET_LOCK_SHIFTID_PREFIX . $this->shiftId);
    }

    /**
     * バイクの予約状態をスタジオ情報に登録、予約モーダル種別を登録
     * DB(order_lesson)の値のみ反映
     *
     * @param int $customerId cust_master.cid
     */
    public function setSheetStatusAndModalTypeByOrderLesson(int $customerId) {
        $collection = OrderLesson::getReservedSheetList($this->shiftId);

        // 予約済み座席数 == スタジオ座席数の場合は、予約モーダル-3(キャンセル待ち登録)で登録
        // $this->studio->getSheetCount();
        if ($collection->count() !== 0 && $collection->count() === count($this->studio)) {
            $this->modalType = ReservationModalType::MODAL_3;
        }

        // DBから取得した予約状態から、座席予約状態を登録
        foreach ($collection as $model) {
            $this->studio[$model->sheet]['customerId'] = $model->customerId;

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
    }

    /**
     * バイクの予約状態をスタジオ情報に登録、予約モーダル種別を登録
     * バイク枠確保情報(Redis)の値のみ反映
     *
     * @param int $customerId cust_master.cid
     */
    public function setSheetStatusAndModalTypeBySheetLock(int $customerId) {
        $secureSheetList = $this->getSecureSheetList();

        // バイク枠確保済み座席番号、会員ID一覧取得
        $sheetNoCustomerIdList = self::getReservedSheetNoCustomerIdList($secureSheetList);
        foreach ($sheetNoCustomerIdList as $sheetNoCustomerId) {
            // ログインユーザーがバイク枠確保していない場合は、予約済みに変更
            if ($sheetNoCustomerId['cid'] !== $customerId) {
                $this->studio[$sheetNoCustomerId['sheet_no']]['status'] = SheetStatus::RESERVED;
            }
        }
    }

    /**
     * バイク枠確保済み座席番号、会員ID一覧取得
     * 時間切れのバイク枠確保情報を削除
     *
     * @return array [ [ 'sheet_no' => 座席番号, 'customer_id' => 会員ID ] ]
     */
    private function getReservedSheetNoCustomerIdList(array $secureSheetList) {
        $keyLimt = RedisKeyLimit::SHEET_LOCK;
        $shiftIdHashKey = self::SHEET_LOCK_SHIFTID_PREFIX . $this->shiftId;
        $currentDateTime = new \DateTime();
        $results = array();

        foreach ($secureSheetList as $customerIdkey => $value) {
            $sheetNoDateTime = CommonLogic::pasrseSheetLockRecord($value);

            $recordDateTime = new \DateTime($sheetNoDateTime['timestamp']);
            $recordDateTime->modify("+{$keyLimt} second");

            // キーの有効期限(10分)を過ぎているかどうか
            if ($currentDateTime > $recordDateTime) {
                // レッスンスケジュールごとのバイク枠確保キーを削除
                RedisWrapper::hDel($shiftIdHashKey, $customerIdkey);
                // 会員IDごとのバイク枠確保キーを削除
                RedisWrapper::hDel(self::SHEET_LOCK_CID_PREFIX . $customerIdkey, $this->shiftId);

                continue;
            }

            $results[] = array('sheet_no' => $sheetNoDateTime['sheet_no'], 'cid' => $customerIdkey);
        }

        return $results;
    }

    /**
     * ネット・トライアル会員の場合は、体験座席以外を予約済みに変更
     *
     * @param int $customerType cust_master.memtype
     */
    public function fillNotSpecialTrialSheet(int $customerType) {
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
     * 指定されたバイク枠を延長、バイク枠確保していない場合は失敗
     *
     * @param int $sheetNo 座席番号
     * @param int $customerId 会員ID
     * @return bool 延長結果
     */
    public function extendSheetLock(int $sheetNo, int $customerId) {
        $hash = RedisWrapper::hGetAll(self::SHEET_LOCK_SHIFTID_PREFIX . $this->shiftId);

        if (!isset($hash[$customerId])) {
            // 会員が指定したレッスンスケジュールでバイク枠確保していない
            return false;
        }

        // 座席番号 タイムスタンプ
        $sheetNoDateTime = $hash[$customerId];
        $sheetNoDateTimeArray = CommonLogic::pasrseSheetLockRecord($sheetNoDateTime);

        if ($sheetNoDateTimeArray['sheet_no'] !== $sheetNo) {
            // バイク枠確保している座席と指定座席が異なる
            return false;
        }

        // バイク枠確保延長
        $currentDateTime = new \DateTime();

        // sheet_lock_shiftid:ID 更新
        RedisWrapper::hSet(
            self::SHEET_LOCK_SHIFTID_PREFIX . $this->shiftId,
            $customerId,
            $sheetNo . ' ' . $currentDateTime->format('Y/m/d H:i:s')
        );

        // sheet_lock_cid:ID 更新
        RedisWrapper::hSet(
            self::SHEET_LOCK_CID_PREFIX . $customerId,
            $this->shiftId,
            $sheetNo . ' ' . $currentDateTime->format('Y/m/d H:i:s')
        );

        return true;
    }

}