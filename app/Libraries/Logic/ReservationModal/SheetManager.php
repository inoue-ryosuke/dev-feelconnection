<?php

namespace App\Libraries\Logic\ReservationModal;

use App\Models\OrderLesson;

/**
 * 座席情報の管理、バイク枠確保
 *
 */
class SheetManager
{
    /** レッスンスケジュールID */
    private $shiftId;
    /** スタジオ情報 */
    private $studio;

    /**
     *
     * @param int $shiftId shift_master.shiftid
     * @param Studio $studio
     */
    public function __construct(int $shiftId/** ,Studio $studio */) {
        $this->shiftId = $shiftId;

        // スタジオ情報([座席番号, x, y, 特別エリア情報]のオブジェクト配列)の初期化
        // $this->studio = $studio;
        // 仮データ
        $this->studio = array(
            [ 'sheet_no' => 1, 'x' => 1, 'y' => 1, [1, 2] ],
            [ 'sheet_no' => 2, 'x' => 2, 'y' => 1, [] ],
            [ 'sheet_no' => 3, 'x' => 2, 'y' => 2, [] ],
            [ 'sheet_no' => 4, 'x' => 2, 'y' => 3, [] ],
            [ 'sheet_no' => 5, 'x' => 1, 'y' => 1, [] ],
        );
    }

    /**
     * バイクの予約状態を登録
     *
     * @param int $customerId cust_master.cid
     */
    public function setReservationStatus(int $customerId) {
        OrderLesson::getReservedSheetsList($this->shiftId);
    }

    /**
     * ネット・トライアル会員の場合は、体験座席以外を予約済みに変更
     *
     * @param int $customerId cust_master.cid
     */
    public function setNetTrialMemberSheet(int $customerId) {

    }

    /**
     * モーダル種別を取得
     *
     * @return \App\Models\Constant\ReservationModalType
     */
    public function getReservationModalType() {

    }
}