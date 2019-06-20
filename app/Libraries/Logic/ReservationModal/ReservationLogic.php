<?php

namespace App\Libraries\Logic\ReservationModal;

use App\Libraries\Logger;
use App\Models\OrderLesson;
use Illuminate\Support\Facades\DB;
use App\Models\Constant\OrderLessonFlg;
use App\Models\Constant\OrderLessonSbFlg;

/**
 * 予約登録、バイク位置変更、予約キャンセル ロジック
 *
 */
class ReservationLogic
{

    /**
     * バイク位置変更
     *
     * @param int $shiftId レッスンスケジュールID
     * @param int $sheetNo 変更先座席番号
     * @param int $customerId 会員ID
     * @return bool
     */
    public static function changeSheetNo(int $shiftId, int $sheetNo, int $customerId) {
        try {
            DB::transaction(function () use ($shiftId, $sheetNo, $customerId) {
                $model = OrderLesson::where('sid', '=', $shiftId)
                    ->where('customer_id', '=', $customerId)
                    ->where('flg', '=', OrderLessonFlg::RESERVED)
                    ->where('sb_flg', '=', OrderLessonSbFlg::NORMAL)
                    ->lockForUpdate()
                    ->first();

                $model->fill([ 'sheet' => $sheetNo ]);
                $model->save();
            });
        } catch (\Exception $e) {
            Logger::writeErrorLog($e->getMessage());

            return false;
        }

        return true;
    }

    /**
     * 予約済みレッスン情報取得
     *
     * @param int $shiftId レッスンスケジュールID
     * @param int $customerId 会員ID
     * @return array|null
     */
    public static function getReservedLesson(int $shiftId, int $customerId) {
        $model = OrderLesson::getReservedOrderLesson($shiftId, $customerId);

        if (is_null($model)) {
            // 予約済みでない
            return null;
        }

        return $model->toArray();
    }

}