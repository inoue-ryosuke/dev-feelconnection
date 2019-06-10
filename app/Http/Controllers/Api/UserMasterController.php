<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserMasterController extends ApiController
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
     *              @OA\Property(property="search",type="string",description="検索ワード(インストラクター名、店舗名を入力して検索)"),
     *              @OA\Property(property="limit",type="integer",description="取得上限数(データ取得数を指定)"),
     *              @OA\Property(property="offset",type="integer",description="オフセット数(取得オフセット数を指定)"),
     *              @OA\Property(property="sort",type="integer", description="ソート対象項目(ソート対象項目を指定(1：名前順 / 2：デビュー年順 / 3：所属店舗順))"),
     *         )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="OK",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="result_code", type="integer",description="結果コード(0:正常 1:エラー)"),
     *               @OA\Property(property="list", type="array",description="インストラクター一覧リスト",
     *                  @OA\Items(type="object",
     *                      @OA\Property(property="uid", type="integer",description="スタッフID(インストラクターID。インストラクター詳細情報取得用。)"),
     *                      @OA\Property(property="name", type="integer",description="スタッフ名(インストラクター名)"),
     *                      @OA\Property(property="assigned_at", type="date",description="インストラクターのデビュー年月日"),
     *                      @OA\Property(property="tenpo_list", type="array",description="インストラクター所属店舗リスト",
     *                  　　    @OA\Items(type="object",
     *                              @OA\Property(property="tid", type="integer",description="店舗ID"),
     *                              @OA\Property(property="tenpo_name", type="integer",description="店舗名"),
     *                              @OA\Property(property="prefecture_number", type="integer",description="都道府県ナンバー"),
     *                          ),
     *                      ),
     *                      @OA\Property(property="image_path", type="string",description="インストラクターの表示画像パス(画像URL)"),
     *                  ),
     *              ),
     *              @OA\Property(property="total_count", type="integer",description="データ全件数"),
     *              @OA\Property(property="list_count", type="integer",description="取得したリストのデータ件数"),
     *              @OA\Property(property="limit", type="integer",description="取得上限数(リクエストで指定された取得数)"),
     *              @OA\Property(property="offset", type="integer",description="オフセット数(リクエストで指定された取得オフセット数)"),
     *              @OA\Property(property="sort", type="integer",description="ソート対象項目(リクエストで指定されたソート対象)"),
     *              @OA\Property(property="order", type="integer",description="取得順(リクエストで指定されたソート対象の取得順)"),
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

        $response = [
            'result_code' => 0,
            "list" => [
                [
                    "uid" => 151,
                     "name" => "AAAA太郎",
                     "assigned_at" => "2019/06/10",
                     "tenpo_list" => [
                           ["tid" => 50, "prefecture_number" => 13, "tenpo_name" =>  "六本木店"],
                           ["tid" => 52, "prefecture_number" => 13, "tenpo_name" =>  "渋谷店"],
                     ],
                     "image_path" => "/images/aaaa_tarou.jpg"
                ],
                [
                    "uid" => 152,
                     "name" => "AAAA太郎坊",
                     "tenpo_list" => [["tid" => 51,"tenpo_name" =>  "自由が丘店",]],
                     "image_path" => "/images/aaaa_taroubou.jpg"
                ]
            ],
            "total_count" => 2,
            "list_count" => 2,
            "limit" => 100,
            "offset" => 0,
            "sort" => 1,
        "order" => 1
        ];
        logger('getInstructors end');
        return response()->json($response);

    }
    
}
