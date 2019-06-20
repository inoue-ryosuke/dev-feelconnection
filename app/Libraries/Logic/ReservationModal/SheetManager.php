<?php

namespace App\Libraries\Logic\ReservationModal;

use App\Models\OrderLesson;
use App\Models\Constant\SheetStatus;
use App\Models\Constant\ReservationModalType;
use App\Models\Constant\OrderLessonSbFlg;
use App\Models\Constant\RedisKeyLimit;
use App\Models\Constant\SpecialSheetType;
use App\Models\CustMemType;

/**
 * 座席情報、バイク枠確保の管理
 *
 */
class SheetManager
{
    /** 予約しようとしているレッスンスケジュールID */
    private $shiftId;
    /** 予約しようとしている会員ID */
    private $customerId;
    /** 予約しようとしている会員種別 */
    private $memberType;
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
     * @param int $customerId cust_master.cid
     * @param int $memberType cust_master.memtype
     */
    public function __construct(int $shiftId, int $customerId, int $memberType) {
        $this->shiftId = $shiftId;
        $this->customerId = $customerId;
        $this->memberType = $memberType;
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
     * バイク枠確保状態の座席は、予約済みとして取得
     * 会員が枠確保している場合は、空きとして取得
     *
     * @param int $sheetNo 座席番号
     * @return int SheetStatus::RESERVABLE|SheetStatus::RESERVED|SheetStatus::RESERVED_CUSTOMER
     */
    public function getSheetStatus(int $sheetNo) {
        // $this->studio->getSheetStatus($sheetNo);
        $status = $this->studio[$sheetNo]['status'];
        $reservedCustomer = $this->studio[$sheetNo]['status'];

        if ($status === SheetStatus::SHEET_LOCK) {
            if ($reservedCustomer === $this->customerId) {
                // 会員が枠確保している場合は空き
                $status = SheetStatus::RESERVABLE;
            } else {
                // 会員が枠確保していない場合は予約済み
                $status = SheetStatus::RESERVED;
            }
        }

        return $status;
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
            if ($value['status'] === SheetStatus::SHEET_LOCK) {
                // バイク枠確保座席は、予約済みに変更
                $value['status'] = SheetStatus::RESERVED;
            }

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
     * ログインユーザーがバイクを予約済みかどうか
     *
     * @return bool
     */
    public function isCustomerReserved() {
        $filtered = array_filter($this->studio, function ($sheet) {
            return $sheet['status'] === SheetStatus::RESERVED_CUSTOMER;
        });

        return count($filtered) > 0;
    }

    /**
     * 指定されたバイクが、他のユーザーによって枠確保済み・予約済みかどうか
     * 体験レッスンで指定した座席が体験バイクでない場合は、予約済みとなりバリデーションで弾かれる
     *
     * @param int $sheetNo 座席番号
     * @return bool
     */
    public function isSheetReserved(int $sheetNo) {
        // return $this->studio->isSheetReserved();
        return $this->studio[$sheetNo]['status'] === SheetStatus::RESERVED
        || $this->studio[$sheetNo]['status'] === SheetStatus::SHEET_LOCK;
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
                return
                $sheet['status'] === SheetStatus::RESERVED
                   || $sheet['status'] === SheetStatus::RESERVED_CUSTOMER
                   || $sheet['status'] === SheetStatus::SHEET_LOCK;
            });

            return count($filtered) < $capacity;
        } else {
            // 体験定員
            $filtered = array_filter($this->studio, function ($sheet) {
                return in_array(SpecialSheetType::TRIAL, $sheet['special_area_info'], true)
                && (
                    $sheet['status'] === SheetStatus::RESERVED
                    || $sheet['status'] === SheetStatus::RESERVED_CUSTOMER
                    || $sheet['status'] === SheetStatus::SHEET_LOCK);
            });

            return count($filtered) < $trialCapacity;
        }
    }

    /**
     * バイク枠確保している座席情報一覧を取得
     * 会員ID毎
     *
     * @return array バイク枠確保している座席一覧(Redis)
     */
    private function getSecureSheetListBycustomerId() {
        return RedisWrapper::hGetAll(self::SHEET_LOCK_CID_PREFIX . $this->customerId);
    }

    /**
     * バイク枠確保している座席情報一覧を取得
     * レッスンスケジュールID毎
     *
     * @return array バイク枠確保している座席一覧(Redis)
     */
    private function getSecureSheetListByShiftId() {
        return RedisWrapper::hGetAll(self::SHEET_LOCK_SHIFTID_PREFIX . $this->shiftId);
    }

    /**
     * バイクの予約状態をスタジオ情報に登録
     * DB(order_lesson)の値のみ反映
     *
     */
    public function setSheetStatusByOrderLesson() {
        $collection = OrderLesson::getReservedSheetList($this->shiftId);

        // DBから取得した予約状態から、座席予約状態を登録
        foreach ($collection as $model) {
            $this->studio[$model->sheet]['customerId'] = $model->customer_id;

            if ($model->customer_id !== $this->customerId) {
                // 予約済みバイク
                // $this->studio->setSheetStatus();
                $this->studio[$model->sheet]['status'] = SheetStatus::RESERVED;
            } else {
                // お客様の予約されたバイク
                // $this->studio->setSheetStatus();
                $this->studio[$model->sheet]['status'] = SheetStatus::RESERVED_CUSTOMER;
            }
        }
    }

    /**
     * Redisにバイク枠確保レコードを登録・更新
     *
     */
    private function updateSheetLockRedis(int $sheetNo) {
        $currentDateTime = new \DateTime();

        // sheet_lock_shiftid:レッスンスケジュールID 更新
        RedisWrapper::hSet(
            self::SHEET_LOCK_SHIFTID_PREFIX . $this->shiftId,
            $this->customerId,
            $sheetNo . ' ' . $currentDateTime->format('Y/m/d H:i:s')
        );

        // sheet_lock_cid:会員ID 更新
        RedisWrapper::hSet(
            self::SHEET_LOCK_CID_PREFIX . $this->customerId,
            $this->shiftId,
            $sheetNo . ' ' . $currentDateTime->format('Y/m/d H:i:s')
        );
    }

    /**
     * モーダル種別登録
     *
     */
    public function setModalType() {
        // return $this->studio->getModalType();

        // すべて満席の場合、予約モーダル3(キャンセル待ち登録)
        $this->modalType = ReservationModalType::MODAL_3;

        foreach ($this->studio as $sheet) {
            if ($sheet['status'] === SheetStatus::RESERVABLE) {
                // 空き座席がある場合、予約モーダル1(通常予約)
                $this->modalType = ReservationModalType::MODAL_1;
                break;
            } else if ($sheet['status'] === SheetStatus::RESERVED_CUSTOMER) {
                // 会員の予約バイクがある場合、予約モーダル2(バイク変更、キャンセル)
                $this->modalType = ReservationModalType::MODAL_2;
                break;
            } else if ($sheet['status'] === SheetStatus::SHEET_LOCK) {
                // バイク枠確保された座席がありかつ満席の場合、予約モーダル2(バイク変更、キャンセル)
                $this->modalType = ReservationModalType::MODAL_2;
            }
        }
    }

    /**
     * バイクの予約状態をスタジオ情報に登録
     * バイク枠確保情報(Redis)の値のみ反映
     *
     */
    public function setSheetStatusBySheetLock() {
        $secureSheetList = $this->getSecureSheetListByShiftId();

        // 予約しようとしているレッスンの、バイク枠確保済み座席番号、会員ID一覧取得
        $sheetNoCustomerIdList = self::getReservedSheetNoCustomerIdList($secureSheetList);
        foreach ($sheetNoCustomerIdList as $sheetNo => $customerId) {
            // 会員がバイク枠確保していない場合は、予約済みに変更
            if ($this->studio[$sheetNo]['status'] === SheetStatus::RESERVABLE
                && $customerId !== $this->customerId) {
                    $this->studio[$sheetNo]['status'] = SheetStatus::SHEET_LOCK;
                    $this->studio[$sheetNo]['customerId'] = $customerId;
            }
        }
    }

    /**
     * バイク枠確保済み座席番号、会員ID一覧取得
     * 時間切れのバイク枠確保情報を削除
     *
     * @return array [ 座席番号 => 会員ID ]
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

            $results[$sheetNoDateTime['sheet_no']] = $customerIdkey;
        }

        return $results;
    }

    /**
     * ネット・トライアル会員の場合は、体験座席以外を予約済みに変更
     *
     */
    public function fillNotSpecialTrialSheet() {
        // TODO ネット・トライアル会員を判別
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
     * @return bool 延長結果
     */
    public function extendSheetLock(int $sheetNo) {
        $hash = RedisWrapper::hGetAll(self::SHEET_LOCK_SHIFTID_PREFIX . $this->shiftId);

        if (!isset($hash[$this->customerId])) {
            // 会員が指定したレッスンスケジュールでバイク枠確保していない
            return false;
        }

        // 座席番号 タイムスタンプ
        $sheetNoDateTime = $hash[$this->customerId];
        $sheetNoDateTimeArray = CommonLogic::pasrseSheetLockRecord($sheetNoDateTime);

        if ($sheetNoDateTimeArray['sheet_no'] !== $sheetNo) {
            // バイク枠確保している座席と指定座席が異なる
            return false;
        }

        // バイク枠確保延長
        $this->updateSheetLockRedis($sheetNo);

        return true;
    }

    /**
     * 指定されたバイク枠を新規確保、すでに確保済みの場合は延長
     *
     * @param int $sheetNo 座席番号
     * @return bool 確保結果
     */
    public function addSheetLock(int $sheetNo) {
        // 予約しようとしているレッスンの、バイク枠確保済み座席番号、会員ID一覧取得
        $secureSheetList = $this->getSecureSheetListByShiftId();
        $sheetNoCustomerIdList = self::getReservedSheetNoCustomerIdList($secureSheetList);

        // 指定した座席がバイク枠確保されているか
        if (isset($sheetNoCustomerIdList[$sheetNo])) {
            if ($sheetNoCustomerIdList[$sheetNo] !== $this->customerId) {
                // 他の会員によってバイク枠確保済み
                return false;
            } else {
                // 枠確保済みのため延長
                $this->updateSheetLockRedis($sheetNo);

                return true;
            }
        }

        //-------------------------- バイク枠新規確保 --------------------------//
        // ネット予約回数取得
        $reservationCount = CustMemType::getReservationCountForSheetLock($this->memberType);
        // 同時予約数取得
        $simultaneousReservationCount = OrderLesson::getSimultaneousReservationCount($this->customerId);

        if ($simultaneousReservationCount >= $reservationCount) {
            // 「同時予約数 >= ネット予約回数」の場合は枠確保できない
            return false;
        }

        // バイク枠確保している座席一覧取得(会員IDごと)
        $hash = self::getSecureSheetListBycustomerId();

        if (isset($hash[$this->shiftId])) {
            // すでに該当レッスンでバイク枠確保済みのため、削除して新規確保

            // sheet_lock_shiftid:レッスンスケジュールID 会員ID 削除
            RedisWrapper::hDel(self::SHEET_LOCK_SHIFTID_PREFIX . $this->shiftId, $this->customerId);

            // 新規確保
            $this->updateSheetLockRedis($sheetNo);

            return true;
        } else {
            // 新規確保
            $this->updateSheetLockRedis($sheetNo);

            return true;
        }
    }

}