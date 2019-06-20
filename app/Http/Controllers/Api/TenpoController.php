<?php

namespace App\Http\Controllers\Api;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Exceptions\IllegalParameterException;
use App\Models\TenpoMaster;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Exceptions\ForbiddenException;

/**
 * 認証コントローラー
 * @Middleware({"api", "auth:customer"})
 */
class TenpoController extends ApiController
{

    use ApiLogicTrait;

    /**
     * API-101: 店舗情報表示
     * 
     * @POST("api/studio", as="api.studio.get")
     * @param $request
     * @return Response
     */
    public function getTenpoInfo(Request $request) {

        logger("getTenpoInfo() Start");
        $payload = $this->getPayload();

        $this->validateApiPayload('tenpo.info', $payload);
        $tid = data_get($payload,(new TenpoMaster)->cKey("tid"));
        $response = $this->getTenpoSelectLogic()->getTenpoInfo($tid);
        logger("getTenpoInfo() End");
        return response()->json($response,200);
    }
}