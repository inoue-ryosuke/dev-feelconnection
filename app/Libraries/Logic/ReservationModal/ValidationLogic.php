<?php

namespace App\Libraries\Logic\ReservationModal;

use Illuminate\Support\Facades\Validator;

/**
 * 予約モーダルで使用するバリデーション
 *
 */
class VaidationLogic
{
    /**
     * レッスンスケジュールIDハッシュ(shift_master.shiftid_hash)のバリデーション
     *
     * @param array $params リクエストパラメータ
     * @return boolean
     */
    public static function validateShiftIdHash(array $params) {
        $validator = Validator::make($params, [
            'sid' => 'required|string|exists:shift_master,shiftid_hash'
        ]);

        return !$validator->fails();
    }

    /**
     * ネット予約公開日時が過去の日付かどうか
     *
     * @param string $openDateTime ネット予約公開日時
     * @return boolean
     */
    public static function isOpenDateTimePassed(string $openDateTime) {
        $currentDate = new \DateTime();
        $openDateTimeDate = new \DateTime($openDateTime);

        return $currentDate >= $openDateTimeDate;
    }
}