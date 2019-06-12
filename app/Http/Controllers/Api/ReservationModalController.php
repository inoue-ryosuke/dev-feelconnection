<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Libraries\Logic\ReservationModal\VaidationLogic;
use App\Libraries\Logic\ReservationModal\ReservationModalMasterResource;
use App\Models\OrderLesson;

use App\Exceptions\BadRequestException;
use App\Exceptions\IllegalParameterException;

/**
 * 予約モーダルコントローラー
 *
 */
class ReservationModalController extends Controller
{
    /**
     * 予約モーダルAPI
     *
     * @GET("api/reservation_modal/{sid}", as="api.reservation_modal.get")
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reservationModalApi(Request $request, $sid)
    {
        $params = array('sid' => $sid);

        // レッスンスケジュールIDハッシュのバリデーション
        if (!VaidationLogic::validateShiftIdHash($params)) {
            // エラー
            throw new BadRequestException('レッスンスケジュールIDが不正です。');
        }

        // 予約モーダルで必要なマスターデータ取得
        $resource = new ReservationModalMasterResource($sid);
        if(!$resource->createRedisResource()) {
            // Redisキャッシュの取得に失敗
            $resource->createDBResource();
        }

        $shiftMaster = $resource->getShitMasterResource();
        $lessonMaster = $resource->getLessonMasterResource();
        $lessonClass1 = $resource->getLessonClass1Resource();
        $lessonClass2 = $resource->getLessonClass2Resource();
        $lessonClass3 = $resource->getLessonClass3Resource();
        $custMaster = $resource->getCustMasterResource();
        $tenpoMaster = $resource->getTenpoMasterResource();
        $userMaster = $resource->getUserMasterResource();

        // ネット予約公開日時が過去の日付かどうか
        if (!VaidationLogic::isOpenDateTimePassed($shiftMaster['open_datetime'])) {
            // ネット予約公開日時が未来
            throw new BadRequestException('未公開のレッスンです。');
        }

        // レッスンスケジュールフラグ(shift_master.flg)が有効(Y)かどうか
        if (!VaidationLogic::isShiftMasterFlgValid($shiftMaster['flg'])) {
            throw new BadRequestException('無効なレッスンです。');
        }

        // レッスンスケジュールレッスン開催日時(shift_master.shift_date, shift_master.ls_st)が未来の日付かどうか
        if (!VaidationLogic::isShiftDateTimeComing($shiftMaster['shift_date'], $shiftMaster['ls_st'])) {
            throw new BadRequestException('無効なレッスンです。');
        }

        // ネット・トライアル会員が体験予約不可のレッスンを指定した場合エラー
        if (!VaidationLogic::canReserveByNetTrialMember($custMaster['memtype'], $shiftMaster['taiken_les_flg'])) {
            // 体験予約不可のレッスンを指定
            throw new BadRequestException('ネット・トライアル会員が体験予約不可のレッスンを指定しました。');
        }

        // 体験レッスン受講済み状態、体験レッスン予約済み状態取得
        $trialLessonStatus = OrderLesson::getTrialLessonStatus($custMaster['cid']);

        if (!$trialLessonStatus[0]) {
            // 体験レッスン受講済みでない
            if (!$trialLessonStatus[1]) {
                // 体験レッスン受講済みでないかつ予約済みでない
                if (!VaidationLogic::canTrialReservation($shiftMaster['taiken_les_flg'])) {
                    // 体験予約不可のレッスンを指定
                    throw new BadRequestException('体験予約不可のレッスンが指定されました。');
                }
            } else {
                // 体験レッスン受講済みでないかつ予約済み
                $shiftDateTime = $shiftMaster['shift_date'] . $shiftMaster['ls_et'];
                if (!VaidationLogic::validateTrialReservationDate($custMaster['memtype'], $shiftDateTime, $trialLessonStatus[2])) {
                    // 予約済みの体験レッスンより前の日付のレッスンを指定
                    throw new BadRequestException('予約済みの体験レッスンより後の日付を指定してください。');
                }
            }
        }

        // 予約受付時間内かどうか
        if (!VaidationLogic::validateTimeLimit($shiftMaster['shift_date'], $shiftMaster['ls_st'], $shiftMaster['tlimit'])) {
            // 予約受付時間外
            throw new IllegalParameterException('予約受付時間外です。');
        }

        return response()->json([ 'resources' => $resource->getAllResource() ]);
    }
}
