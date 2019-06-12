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
     * @return bool
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
     * @return bool
     */
    public static function isOpenDateTimePassed(string $openDateTime) {
        $currentDateTime = new \DateTime();
        $openDateTime = new \DateTime($openDateTime);

        return $currentDateTime >= $openDateTime;
    }

    /**
     * ネット・トライアル会員が体験予約不可のレッスンを指定しているかどうか
     *
     * @param int $memberType 会員種別(cust_memtype.mid)
     * @param bool $taikenLessonFlag 体験予約可(0:不可, 1:可)
     * @return bool
     */
    public static function canReserveByNetTrialMember(int $memberType, bool $taikenLessonFlag) {
        // TODO:会員種別ID(cust_memtype.mid)を渡して、ネット・トライアル会員を判別
        if (false) {
            if (!$taikenLessonFlag) {
                return false;
            }
        }

        return true;
    }

    /**
     * 予約受付時間内かどうか
     *
     * @param string $shiftDate レッスン日(yyyy/mm/dd)
     * @param string $startTime 開始時間(hh:ii:ss)
     * @param string $timeLimit 予約受付時間(分前まで)
     * @return bool
     */
    public static function validateTimeLimit(string $shiftDate, string $startTime, string $timeLimit) {
        $currentDateTime = new \DateTime();
        $timeLimitDateTime = new \DateTime("{$shiftDate} {$startTime}");

        $timeLimitDateTime->modify("-{$timeLimit} minute");

        return $currentDateTime <= $timeLimitDateTime;
    }
}