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
     * @param array $parameters リクエストパラメータ
     * @return boolean
     */
    public static function validateShiftIdHash(array $parameters) {
        $validator = Validator::make($parameters, [
            'sid' => 'required|string'//|exists:shift_master,shiftid_hash'
        ]);

        return !$validator->fails();
    }
}