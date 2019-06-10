<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MusicController extends ApiController
{
    /**
     * API-xx: AppleMusic情報取得API
     * @OA\GET(
     *     path="/api/apple_music",
     *     description="AppleMusicの情報を取得する",
     *     tags={"Music"},
     *     @OA\Response(
     *          response="200",
     *          description="OK",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="result_code", type="integer",description="結果コード(0:正常 1:エラー)"),
     *               @OA\Property(property="list", type="array",description="インストラクター一覧リスト",
     *                  @OA\Items(type="object",
     *                      @OA\Property(property="{category_name}", type="integer",description="BBC HIT,BB1 COMP等カテゴリー毎にグルーピングされたリスト"),
     *                      @OA\Property(property="name", type="integer",description="プレイリスト名(表示プレイリスト名　＝　プログラム名 ＋ 音楽ジャンル名 ＋ バージョン
    ex) BB2 HIT 16  = BB2 + HIT + 16)"),
     *                      @OA\Property(property="path", type="date",description="プレイリストパス(URL)(AppleMusicのプレイリスト詳細へのURL )"),
     *                      @OA\Property(property="image_path", type="date",description="プレイリスト画像パス(URL)(AppleMusicのプレイリスト画像パス(URL)"),
     *                  ),
     *              ),
     *          ),
     *     )
     * )
     *
     * @GET("api/apple_music", as="api.apple_music.get")
     * @param Request $request
     * @return
     */
    public function getMusicPlaylist(Request $request)
    {
        logger('getMusicPlaylist start');

        $response = [
            'result_code' => 0,
            "list" => [
                [
                    "BBC HIT" => [
                        [
                            "name"=> "BBC HIT 1",
                            "path"=> "https://music.apple.com/jp/playlist/bb1-dvgt/pl.d1f9e9638c9a41c5ad72e5885e94e6bc",
                            "image_path"=> "https://www.feelcycle.com/feelcycle_hp/img/contents/apple_music/fcam_bb1_hit_03.png"
                        ],
                        [
                            "name"=> "BBC HIT 2",
                            "path"=> "https://music.apple.com/jp/playlist/bb1-hit2/pl.b472b0df577047bb85254a78089ae549",
                            "image_path"=> "https://www.feelcycle.com/feelcycle_hp/img/contents/apple_music/fcam_bb1_hit_02.png"
                        ],
                    ]
                ]
            ]
        ];
        logger('getMusicPlaylist end');
        return response()->json($response);

    }
    
}
