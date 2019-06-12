<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Libraries\Logic\ReservationModal\VaidationLogic;
use App\Libraries\Logic\ReservationModal\ReservationModalMasterResource;

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
            return response()
                    ->json([ 'sidエラー' ])
                    ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        // 予約モーダルで必要なマスターデータ取得
        $resource = new ReservationModalMasterResource($sid);
        if(!$resource->createRedisResource()) {
            // Redisキャッシュの取得に失敗
            $resource->createDBResource();
        }

        // ネット予約公開日時が過去の日付かどうか
        $openDateTime = $resource->getShitMasterColumn('open_datetime');
        if (!VaidationLogic::isOpenDateTimePassed($openDateTime)) {
            // エラー
            return response()
                ->json([ 'open_datetimeエラー' ])
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        // ネット・トライアル会員が体験予約不可のレッスンを指定した場合エラー
        $memberType = $resource->getCustMasterColumn('memtype');
        $taikenLessonFlag = $resource->getShitMasterColumn('taiken_les_flg');
        if (!VaidationLogic::canReserveByNetTrialMember($memberType, $taikenLessonFlag)) {
            // エラー
            return response()
            ->json([ 'ネット・トライアル会員予約不可エラー' ])
            ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        // 予約受付時間内かどうか
        $shiftDate = $resource->getShitMasterColumn('shift_date');
        $startTime = $resource->getShitMasterColumn('ls_st');
        $timeLimit = $resource->getShitMasterColumn('tlimit');
        if (!VaidationLogic::validateTimeLimit($shiftDate, $startTime, $timeLimit)) {
            // エラー
            return response()
            ->json([ '予約受付時間外エラー' ])
            ->setStatusCode(Response::HTTP_CONFLICT);
        }

        return response()->json([ 'resources' => $resource->getAllResource() ]);
    }
}
