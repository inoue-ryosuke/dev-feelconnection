<?php

namespace App\Libraries\Logic\ReservationModal;

use Illuminate\Support\Facades\Validator;
use App\Models\Constant\TaikenMess;
use App\Models\Constant\TaikenLesFlg;
use App\Models\Constant\ShiftMasterFlg;

use App\Models\OrderLesson;

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
            'sid' => 'required|string|exists:shift_master,shiftid_hash,flg,' . ShiftMasterFlg::VALID
        ]);

        return !$validator->fails();
    }

    /**
     * レッスンスケジュールIDハッシュ(shift_master.shiftid_hash)、座席番号のバリデーション
     *
     * @param array $params リクエストパラメータ
     * @return bool
     */
    public static function validateShiftIdHashAndSheetNo(array $params) {
        $validator = Validator::make($params, [
            'sid' => 'required|string|exists:shift_master,shiftid_hash,flg,' . ShiftMasterFlg::VALID,
            'sheet_no' => 'required|integer|min:1'
        ]);

        return !$validator->fails();
    }

    /**
     * ネット予約公開日時が過去の日付かどうか
     *
     * @param string $openDateTime ネット予約公開日時 yyyy/mm/dd hh:ii:ss
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
     * @param int $taikenLessonFlag 体験予約可(0:不可, 1:可)
     * @return bool
     */
    public static function canReserveByNetTrialMember(int $memberType, int $taikenLessonFlag) {
        // TODO:会員種別ID(cust_memtype.mid)を渡して、ネット・トライアル会員を判別
        if (false) {
            if ($taikenLessonFlag === TaikenLesFlg::IMPOSSIBLE) {
                return false;
            }
        }

        return true;
    }

    /**
     * 体験予約可かどうか
     *
     * @param int $taikenLessonFlag 体験予約可(0:不可, 1:可)
     * @return bool
     */
    public static function canTrialReservation(int $taikenLessonFlag) {
        return $taikenLessonFlag === TaikenLesFlg::POSSIBLE;
    }

    /**
     * 体験レッスン受講済みでないかつ予約済みの場合は、予約した体験レッスンより前の日時のレッスンは選択不可
     * ※体験レッスン予約時にマンスリー会員で入会して、体験レッスンと通常レッスンを同時に予約。
     *
     * @param int $memberType 会員種別(cust_memtype.mid)
     * @param string $shiftDateTime 予約予定レッスン開催日時 yyyy/mm/dd hh:ii:ss
     * @param string $reservedDateTime 予約済みレッスン終了日時 yyyy/mm/dd hh:ii:ss
     * @return bool
     */
    public static function validateTrialReservationDate(int $memberType, string $shiftDateTime, string $reservedDateTime) {
        // TODO: 会員種別を渡してマンスリー会員か判別
        if (false) {
            // マンスリー会員でない

            return false;
        }

        $shiftDateTime = new \DateTime($shiftDateTime);
        $reservedDateTime = new \DateTime($reservedDateTime);

        return $shiftDateTime > $reservedDateTime;
    }

    /**
     * 予約受付時間内かどうか
     * 過去の日付のレッスンを指定した場合も、このバリデーションで弾かれる
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

    /**
     * 体験レッスン受講済みでないかつ予約済みでない場合
     * 体験レッスン受講済みでないかつ予約済みの場合
     * それぞれのバリデーション
     *
     * @param array $shiftMaster
     * @param array $custMaster
     * @return array [ result => バリデーション結果, 'error_message' => エラーメッセージ, 'trial_flag' => 体験レッスン受講状態(true=受講済み、false=未受講) ]
     */
    public static function validateNotTakenTrialLesson(array &$shiftMaster, array &$custMaster) {
        // 体験レッスン受講済み状態、体験レッスン予約済み状態取得
        $trialLessonStatus = OrderLesson::getTrialLessonStatus($custMaster['cid']);

        if (!$trialLessonStatus[0]) {
            // 体験レッスン受講済みでない

            // 体験制限非表示かどうか
            if ($shiftMaster['taiken_mess'] === TaikenMess::NO_DISPLAY) {
                return [ 'result' => false, 'error_message' => '体験制限のレッスンです。', 'trial_flag' => $trialLessonStatus[0] ];
            }

            if (!$trialLessonStatus[1]) {
                // 体験レッスン受講済みでないかつ予約済みでない
                if (!VaidationLogic::canTrialReservation($shiftMaster['taiken_les_flg'])) {
                    // 体験予約不可のレッスンを指定
                    return [ 'result' => false, 'error_message' => '体験予約不可のレッスンが指定されました。', 'trial_flag' => $trialLessonStatus[0] ];
                }
            } else {
                // 体験レッスン受講済みでないかつ予約済み
                $shiftDateTime = $shiftMaster['shift_date'] . $shiftMaster['ls_et'];
                if (!VaidationLogic::validateTrialReservationDate($custMaster['memtype'], $shiftDateTime, $trialLessonStatus[2])) {
                    // 予約済みの体験レッスンより前の日付のレッスンを指定
                    return [ 'result' => false, 'error_message' => '予約済みの体験レッスンより後の日付を指定してください。', 'trial_flag' => $trialLessonStatus[0] ];
                }
            }
        }

        return [ 'result' => true, 'error_message' => '', 'trial_flag' => $trialLessonStatus[0] ];
    }
}