<?php

namespace App\Http\Controllers\Api;
//namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;
//use Log;

//  /**
//  * 会員種別設定ＡＰＩコントローラー
// // * @Middleware({"logger", "ua",  "api.logger", "append", "maintenance"})
//  */
class MemtypeChangeController extends ApiController
{

    use ApiLogicTrait;

    /**
     * API-xx: 会員種別変更確認画面初期表示API
     * 
     * @POST("api/memtype_change/confirm", as="api.memtype_change.confirm")
     * @param $request
     * @return Response
     */
    public function getMemtypeChangeConfirmPage(Request $request)
    {
        logger('initMemtypeChange start');

        $payload = $this->getPayload();
        //\Log::debug($payload);

        // レスポンスを取得
        $response = $this->getMemtypeChangeConfirmPageLogic()->getConfirmPage($payload);

        logger('initMemtypeChange end');
        return response()->json($response);
    }

    /**
     * API-xx: 会員種別設定確認・更新・登録API
     * 
     * @POST("api/memtype_change/update", as="api.memtype_change.update")
     * @param $request
     * @return Response
     */
    public function updateMemtype(Request $request)
    {
        logger('updateMemtype start');

        $payload = $this->getPayload();
        //\Log::debug($payload);

        // レスポンスを取得
        $response = $this->getMemtypeUpdateLogic()->updateMemtype($payload);

        logger('updateMemtype end');
        return response()->json($response);
    }
}
