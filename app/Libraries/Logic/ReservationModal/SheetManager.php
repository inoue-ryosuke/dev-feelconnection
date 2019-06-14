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
     * バイク枠確保している座席情報一覧を取得
     *
     * @return array バイク枠確保している座席一覧(Redis)
     */
    private function getSecureSheetList() {
        return RedisWrapper::hGetAll('sheet_lock_shiftid:' . $this->shiftId);
    }

    /**
     * バイク枠確保済み座席番号、会員ID一覧取得
     * 時間切れのバイク枠確保情報を削除
     *
     * @return array [ [ 'sheet_no' => 座席番号, 'customer_id' => 会員ID ] ]
     */
    private function getReservedSheetNoCustomerIdList(array $secureSheetList) {
        $keyLimt = RedisKeyLimit::SHEET_LOCK;
        $shiftIdHashKey = 'sheet_lock_shiftid:' . $this->shiftId;
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
                RedisWrapper::hDel("sheet_lock_cid:{$customerIdkey}", $this->shiftId);

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

    /**
     * 座席番号が該当スタジオで有効か
     *
     * @param int $sheetNo 座席番号
     * @return bool
     */
    public function isSheetNoValid(int $sheetNo) {
        return true;
    }

    /**
     * 指定された座席が体験バイクかどうか
     *
     * @param int $sheetNo 座席番号
     * @return bool
     */
    public function isSheetSpecialTrial(int $sheetNo) {
        // return $this->studio->isSpecialSheetTrial();
        return $this->studio[$sheetNo]['special_area_info'] === SpecialSheetType::TRIAL;
    }

    /**
     * 座席予約状態取得
     *
     * @param int $sheetNo 座席番号
     * @param int $customerId 会員ID
     * @return int
     */
    public function getSheetStatus(int $sheetNo, int $customerId, int $memberType) {
        $model = OrderLesson::getReservedSheet($this->shiftId, $sheetNo);

        if (!is_null($model)) {
            // 座席が予約済み
            if ($model->customer_id !== $customerId) {
                // 予約済み
                return SheetStatus::RESERVED;
            } else {
                // お客様の予約されたバイク
                return SheetStatus::RESERVED_CUSTOMER;
            }
        } else {
            // 座席が予約済みでない
            $secureSheetList = $this->getSecureSheetList();

            // TODO 会員種別を受け取って、ネット・トライアル会員を判別
            if (true) { // $this->studio->getSheet($sheetNo)
                if (!in_array(SpecialSheetType::TRIAL, $this->studio[$sheetNo]['special_area_info'], true)) {
                    // 体験予約席でない場合は予約済み
                    return SheetStatus::RESERVED;
                }
            }

            // バイク枠確保済み座席番号、会員ID一覧取得
            $sheetNoCustomerIdList = self::getReservedSheetNoCustomerIdList($secureSheetList);

            foreach ($sheetNoCustomerIdList as $sheetNoCustomerId) {
                // 指定された座席でログインユーザーが確保していない場合、予約済み
                if ($sheetNoCustomerId['sheet_no'] === $sheetNo) {
                    if ($sheetNoCustomerId['cid'] !==$customerId ) {
                        return SheetStatus::RESERVED;
                    }
                }
            }

            return SheetStatus::RESERVABLE;
        }
    }
}