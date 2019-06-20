<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
/**
 * @Middleware({"api", "guest"})
 */
class ZipCodeController extends ApiController
{
    /**
     * API-xx: 郵便番号住所取得API
     * @OA\POST(
     *     path="/api/zip_code",
     *     description="リクエストされた郵便番号から対応する住所を取得する",
     *     tags={"ZipCode"},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="zip_code",type="string",description="郵便番号(7桁)"),
     *         )
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
     *              @OA\Property(property="result_code", type="integer",description="結果コード(0:正常 1:エラー)"),
     *              @OA\Property(property="zip_code", type="string",description="郵便番号"),
     *              @OA\Property(property="prefecture", type="string",description="都道府県名"),
     *              @OA\Property(property="address", type="string",description="住所"),
     *          ),
     *     )
     * )
     *
     * @POST("/api/zip_code", as="api.zip_code.get")
     * @param Request $request
     * @return
     */
    public function getAddressByZipCode(Request $request)
    {
        logger('getAddressByZipCode start');
        $payload = $this->getPayload();
        logger('payload');
        logger($payload);
        // バリデーションチェック
        $this->validateApiPayload('zipCode.search', $payload);
        // レスポンスを取得
        $response = $this->getZipCodeSelectLogic()->getAddressByZipCode($payload);
        logger('response');
        logger($response);
        logger('getAddressByZipCode end');
        return response()->json($response);

    }
    
}
