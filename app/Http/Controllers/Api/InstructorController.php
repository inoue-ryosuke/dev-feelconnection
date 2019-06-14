<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
/**
 * @Middleware({"api"})
 */
class InstructorController extends ApiController
{
    /**
     * API-xx: インストラクター紹介一覧取得API
     * @OA\POST(
     *     path="/api/instructors",
     *     description="インストラクター紹介画面に表示するインストラクターの一覧を取得する",
     *     tags={"UserMaster"},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="freeword",type="string",description="検索ワード(インストラクター名、店舗名を入力して検索)"),
     *              @OA\Property(property="limit",type="integer",description="取得上限数(データ取得数を指定)"),
     *              @OA\Property(property="offset",type="integer",description="オフセット数(取得オフセット数を指定)"),
     *              @OA\Property(property="type",type="integer", description="ソート対象項目(ソート対象項目を指定(1：所属店舗順(北から) / 2：デビュー年順(古い在籍順) / 3：名前順(ABC順)))"),
     *         )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="OK",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="result_code", type="integer",description="結果コード(0:正常 1:エラー)"),
     *               @OA\Property(property="instructors", type="array",description="インストラクター一覧リスト",
     *                  @OA\Items(type="object",
     *                      @OA\Property(property="uid", type="integer",description="スタッフID(インストラクターID。インストラクター詳細情報取得用。)"),
     *                      @OA\Property(property="name", type="string",description="スタッフ名(インストラクター名)"),
     *                      @OA\Property(property="assigned_at", type="date",description="インストラクターのデビュー年月日"),
     *                      @OA\Property(property="comment", type="string",description="インストラクターの自己紹介文"),
     *                      @OA\Property(property="image_path", type="string",description="インストラクターの表示画像パス(画像URL)"),
     *                  ),
     *              ),
     *              @OA\Property(property="sort_results", type="array",description="ソート店舗リスト",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="name", type="string",description="店舗名"),
     *                     @OA\Property(property="instructors", type="array",description="店舗所属インストラクターリスト",
     *                        @OA\Items(type="object",
     *                            @OA\Property(property="uid", type="integer",description="スタッフID(インストラクターID)"),
     *                        ),
     *                     ),
     *                 ),
     *              ),
     *              @OA\Property(property="total_count", type="integer",description="データ全件数"),
     *              @OA\Property(property="list_count", type="integer",description="取得したリストのデータ件数"),
     *              @OA\Property(property="limit", type="integer",description="取得上限数(リクエストで指定された取得数)"),
     *              @OA\Property(property="offset", type="integer",description="オフセット数(リクエストで指定された取得オフセット数)"),
     *              @OA\Property(property="type", type="integer",description="ソート対象項目(リクエストで指定されたソート対象)")
     *          ),
     *     )
     * )
     *
     * @POST("api/instructors", as="api.instructors.get")
     * @param Request $request
     * @return
     */
    public function getInstructors(Request $request)
    {
        logger('getInstructors start');
        $payload = $this->getPayload();
        logger('payload');
        logger($payload);
        // バリデーションチェック
        $this->validateApiPayload('instructors.search', $payload);
        // レスポンスを取得
        $response = $this->getInstructorSelectLogic()->getInstructors($payload);
        logger('response');
        logger($response);
        logger('getInstructors end');
        return response()->json($response);

    }
    
}
