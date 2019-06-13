<?php

namespace App\Libraries\Logic\ReservationModal;

use App\Models\OrderLesson;
use Illuminate\Support\Facades\Redis;
use App\Models\Constant\SheetStatus;
use App\Models\Constant\ReservationModalType;
use App\Models\Constant\OrderLessonSbFlg;

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

        // スタジオ情報([座席番号, x, y, 座席ステータス, 特別エリア情報]のオブジェクト配列)の初期化
        // $this->studio = $studio;
        // 仮データ
        $this->studio = array(
            1 => [ 'x' => 1, 'y' => 1, 'status' => SheetStatus::RESERVABLE, 'special_area_info' => [1, 2] ],
            2 => [ 'x' => 2, 'y' => 1, 'status' => SheetStatus::RESERVABLE, 'special_area_info' => [] ],
            3 => [ 'x' => 2, 'y' => 2, 'status' => SheetStatus::RESERVABLE, 'special_area_info' => [] ],
            4 => [ 'x' => 2, 'y' => 3, 'status' => SheetStatus::RESERVABLE, 'special_area_info' => [] ],
            5 => [ 'x' => 1, 'y' => 1, 'status' => SheetStatus::RESERVABLE, 'special_area_info' => [] ],
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

        foreach ($collection as $model) {
            if ($model->customer_id !== $customerId) {
                // 予約済みバイク
                // $this->studio->setSheetStatus();
                $this->studio[$model->sheet_no]['status'] = SheetStatus::RESERVED;
            } else {
                // お客様の予約されたバイク
                // $this->studio->setSheetStatus();
                $this->studio[$model->sheet_no]['status'] = SheetStatus::RESERVED_CUSTOMER;

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
     * バイク枠確保している座席情報を取得
     *
     * @return array バイク枠確保している座席一覧(Redis)
     */
    private function getSecureSheetList() {
        return Redis::hgetall('sheet_lock_shiftid:' . $this->shiftId);;
    }

    /**
     * ネット・トライアル会員の場合は、体験座席以外を予約済みに変更
     *
     * @param int $customerId cust_master.cid
     */
    public function setNetTrialMemberSheet(int $customerId) {

    }

    /**
     * 予約モーダル種別を取得
     *
     * @return int 予約モーダル種別
     */
    public function getReservationModalType() {
        return $this->modalType;
    }
}