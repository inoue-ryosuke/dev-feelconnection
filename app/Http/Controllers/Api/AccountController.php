<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\IllegalParameterException;
use App\Exceptions\UnauthorizedException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cust;

/**
 * @Middleware({"api"})
 */
class AccountController extends ApiController
{
    /**
     * API-xx: 個人情報入力確認・更新・登録API
     * @OA\POST(
     *     path="/api/account",
     *     description="入力・確認時は、各データのバリデーションチェックを行う。
    変更時は、各データのバリデーションチェックを行い、DBを更新する。
    登録時は、各データのバリデーションチェックを行い、DBへ登録し、登録完了メールを送信する。",
     *     tags={"Account"},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="type",type="integer",description="1:入力・確認、２：変更、３：登録"),
     *              @OA\Property(property="name",type="string",description="名前（漢字）"),
     *              @OA\Property(property="kana",type="string",description="名前(カタカナ)"),
     *              @OA\Property(property="b_year",type="integer", description="生年月日(年)"),
     *              @OA\Property(property="b_month",type="integer", description="生年月日(月)"),
     *              @OA\Property(property="b_day",type="integer", description="生年月日(日)"),
     *              @OA\Property(property="sex",type="integer", description="性別(1:男性、2:女性)"),
     *              @OA\Property(property="h_zip",type="string", description="郵便番号"),
     *              @OA\Property(property="h_pref",type="string", description="都道府県"),
     *              @OA\Property(property="h_addr",type="string", description="番地、建物名"),
     *              @OA\Property(property="h_tel",type="string", description="電話番号・市外局番"),
     *              @OA\Property(property="dm",type="integer", description="ご案内メール受信許可(0:可　1:不可)"),
     *              @OA\Property(property="pc_conf",type="integer", description="事前予約案内メール受信許可(0:可　1:不可)"),
     *              @OA\Property(property="login_pass",type="string", description="パスワード"),
     *              @OA\Property(property="login_pass_confirmation",type="string", description="パスワード（確認）"),
     *              @OA\Property(property="campaign_code",type="string", description="キャンペーンコード"),
     *              @OA\Property(property="pc_mail",type="string", description="メールアドレス"),
     *         )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="OK",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="result_code", type="integer",description="結果コード(0:正常 1:エラー)"),
     *          ),
     *     )
     * )
     *
     * @POST("api/account", as="api.account.post")
     * @param Request $request
     * @return
     */
    public function validateAccount(Request $request)
    {
        logger('validateAccount start');
        $payload = $this->getPayload();
        logger('payload');
        logger($payload);
        // バリデーションチェック
        $this->validateApiPayload('cust.store', $payload);
        // 処理タイプを取得
        $type = data_get($payload, 'type', 1);
        logger('type');
        logger($type);
        // 処理タイプが更新の場合
        if ($this->isAuthUpdate($type)) {
            logger('update user');
            $response = $this->getAuthUpdateLogic()->updateUser($payload);
            return response()->json($response);
        }
        // 入力確認・アカウント登録
        //年齢確認
        $year = data_get($payload, 'b_year', 0);
        $month = data_get($payload, 'b_month', 0);
        $day = data_get($payload, 'b_day', 0);
        // 年齢を取得
        $age = Cust::getUserAge($year, $month, $day);

        // 入会可能な年齢の場合
        if (Cust::validateUserAge($age)) {
            if ($type === Cust::PROCESS_VALIDATE) {
                logger('validate user');
                // 処理タイプが入力・確認
                $response = ["result_code" => 0];
            } else {
                logger('create user');
                // 処理タイプが登録
                $response = $this->getAuthStoreLogic()->createUser($payload);
            }

            // レスポンスを取得
            logger('response');
            logger($response);
            logger('validateAccount end');
            return response()->json($response);
        } else {
            throw new IllegalParameterException();
        }

    }

    /**
     * 個人情報入力確認・更新・登録APIで処理タイプが更新の場合に認証済みか確認
     * @param $type
     * @return bool
     */
    private function isAuthUpdate($type): bool
    {
        //処理タイプが更新の場合
        if ($type === Cust::PROCESS_UPDATE) {
            // 認証が通ってなければ例外
            if (!auth('customer')->check()) {
                throw new UnauthorizedException();
            } else {
                // 認証済みの場合
                return true;
            }
        } else {
            // 処理タイプが更新じゃない場合
            return false;
        }

    }

}
