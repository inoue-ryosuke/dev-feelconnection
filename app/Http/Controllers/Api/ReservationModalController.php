<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;
use App\Libraries\Logic\ReservationModal\VaidationLogic;

/**
 * 予約モーダルコントローラー
 *
 */
class ReservationModalController extends Controller
{

    /**
     * 予約モーダルAPI
     *
     * @POST("api/reservation_modal", as="api.reservation_modal.post")
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reservationModalApi(Request $request)
    {
        $parameters = $request->all();

        // レッスンスケジュールIDハッシュのバリデーション
        if (!VaidationLogic::validateShiftIdHash($parameters)) {
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
        Redis::set('shiftid_hash:' . $parameters['sid'], 'テスト');
        $value = Redis::get('shiftid_hash:' . $parameters['sid']);

        return response()->json([ 'redis' => $value ]);
    }
}
