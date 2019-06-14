<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
/**
 * @Middleware({"api", "guest"})
 */
class InviteController extends ApiController
{
    /**
     * API-xx: 紹介URL有効性確認API
     * @OA\GET(
     *     path="/api/invite/{invite_code}",
     *     description="対象の紹介URLに埋め込まれた{invite_code}の有効性を確認する。",
     *     tags={"Invite"},
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
     *              @OA\Property(property="lid", type="integer",description="レッスンID"),
     *              @OA\Property(property="lesson_flag", type="integer",description="レッスン受講可能判定(0:不可　1:可(申し込んだレッスンが受講可能か判定。受講可能の場合は1:可))"),
     *              @OA\Property(property="friend_flag", type="integer",description="友人紹介判定(0:偽　1:真(アカウント登録か体験レッスン申込か判定。アカウント登録の場合は1:真))"),
     *          ),
     *     )
     * )
     *
     * @GET("api/invite/{invite_code}", as="api.invite.get")
     * @param Request $request
     * @return
     */
    public function validateInviteCode($inviteCode, Request $request)
    {
        logger('validateInviteCode start');
        if (empty($inviteCode)) {
            logger($inviteCode);
            $inviteCode = 'asdf1321asfa3s21asf';
        }
        // レスポンスを取得
        $response = $this->getInviteSelectLogic()->validateInviteCode($inviteCode);
        logger('response');
        logger($response);
        logger('validateInviteCode end');
        return response()->json($response)->Cookie('_ic', $inviteCode);

    }
    
}
