<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="FeelConnection API仕様書",
 *     version="1.0"
 * ),
 */

 /**
 * TestＡＰＩコントローラー
 * @Middleware({"logger", "ua",  "web.nocsrf", "api.logger", "append", "auth:api", "maintenance","token"})
 */
class ApiTestController extends ApiController
{
    /**
     * API-xx: テストAPI
     * @OA\POST(
     *     path="/api/get_json_sample",
     *     description="JSONサンプルを表示するためのAPI",
     *     tags={"Sample"},
     *     @OA\RequestBody(
     *          required=false,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="paramater1",type="integer",description="リクエストパラメータ1"),
     *              @OA\Property(property="paramater2",type="integer",description="リクエストパラメータ2"),
     *          )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="OK",
     *          @OA\Header(
     *              header="X-FeelConnection-Required-Ver",
     *              required=true,
     *              @OA\Schema(type="string"),
     *              description="対応バージョン"
     *          ),
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="date", type="date",description="日付（サンプル）"),
     *              @OA\Property(property="name", type="string",description="名前（サンプル）"),
     *              @OA\Property(property="parameters",type="object", description="パラメータ（サンプル）",
     *                  @OA\Property(property="param1",
     *                          type="string", description="オブジェクト内パラメータ1（サンプル）"
     *                  ),
     *                  @OA\Property(property="param2",type="object", description="オブジェクト内オブジェクト2（サンプル）",
     *                          @OA\Property(property="key",type="string", description="オブジェクト2パラメータキー"),
     *                          @OA\Property(property="value",type="string", description="オブジェクト2パラメータ値")
     *                  ),
     *                  @OA\Property(property="param3",
     *                          type="array", description="配列内オブジェクトパラメータ群3（サンプル）",
     *                          @OA\Items(type="object",
     *                              @OA\Property(property="param3_key",type="string", description="オブジェクト3パラメータキー"),
     *                              @OA\Property(property="param3_value",type="string",description="オブジェクト3パラメータ値")
     *                          )
     *                  ),
     *              ),
     *          ),
     *     )
     * )
     * @POST("api/get_json_sample", as="api.get_json_sample.get")
     * @param Request $request
     * @return void
     */
    public function getJsonSample(Request $request)
    {
        $response = [
            'date' => date('Y/m/d h:i:s'),
            'name' => 'sample',
            'parameters' => [
                'param1' => '1',
                'param2' => ['p' => '2'],
                'param3' => [
                        ['param3_key' => 'param3_value'],
                        ['param3_key' => 'param3_value'],
                        ['param3_key' => 'param3_value'],
                ],
            ]
        ];

        return response()->json($response);
    }

}
