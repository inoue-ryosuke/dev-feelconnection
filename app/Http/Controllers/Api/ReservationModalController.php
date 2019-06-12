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
        if (!VaidationLogic::isOpenDateTimePassed($resource->getShitMasterColumn('open_datetime'))) {
            // エラー
            return response()
                ->json([ 'open_datetimeエラー' ])
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return response()->json([ 'resources' => $resource->getAllResource() ]);
    }
}
