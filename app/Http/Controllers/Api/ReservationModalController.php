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
                    ->json([ 'エラー' ])
                    ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        /*
         * shift_master、lesson_master、tenpo_master、cust_master、user_master、
         * lesson_class1、lesson_class2、lesson_class3
         * あたりの更新頻度が低くて管理画面からのみ更新がかかりそうなマスタデータをRedisにキャッシュする
         * 「lesson_master:ID」のようにキーを指定・Hashで登録して、O(1)で取得できるようにする
         *
         */

        // 予約モーダルで必要なマスターデータ取得
        $resource = new ReservationModalMasterResource($sid);
        if(!$resource->createRedisResource()) {
            // Redisキャッシュの取得に失敗
            $resource->createDBResource();
        }

        return response()->json([ 'shift_master' => $resource->getShitMasterResource() ]);
    }
}
