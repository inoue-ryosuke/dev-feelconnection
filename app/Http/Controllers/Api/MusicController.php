<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
/**
 * @Middleware({"api"})
 */
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
     *               @OA\Property(property="list", type="array",description="音楽プログラムリスト",
     *                  @OA\Items(type="object",
     *                      @OA\Property(property="{category_name}", type="array",description="BBC HIT,BB1 COMP等カテゴリー毎にグルーピングされたリスト",
     *                         @OA\Items(type="object",
     *                             @OA\Property(property="name", type="string",description="プレイリスト名(表示プレイリスト名　＝　プログラム名 ＋ 音楽ジャンル名 ＋ バージョン
    ex) BB2 HIT 16  = BB2 + HIT + 16)"),
     *                             @OA\Property(property="path", type="string",description="プレイリストパス(URL)(AppleMusicのプレイリスト詳細へのURL )"),
     *                             @OA\Property(property="image_path", type="string",description="プレイリスト画像パス(URL)(AppleMusicのプレイリスト画像パス(URL)"),
     *                         )
     *                      ),
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

        // レスポンスを取得
        $response = $this->getMusicSelectLogic()->getPlayLists();
        logger('response');
        logger($response);
        logger('getMusicPlaylist end');
        return response()->json($response);

    }
    
}
