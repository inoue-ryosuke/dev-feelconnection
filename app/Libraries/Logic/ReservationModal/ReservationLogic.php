<?php

namespace App\Libraries\Logic\ReservationModal;

use App\Libraries\Logger;
use App\Models\OrderLesson;
use Illuminate\Support\Facades\DB;
use App\Models\Constant\OrderLessonFlg;

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

}