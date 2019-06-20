<?php

namespace App\Http\Controllers\Api;

//use App\Libraries\Logic\Loader;  実開発の場合はロジック層にて実装
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Exceptions\IllegalParameterException;
use App\Models\Cust;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Exceptions\ForbiddenException;

/**
 * 認証コントローラー
 * @Middleware({"api", "auth:customer"})
 */
class AuthController extends ApiController
{

    use ApiLogicTrait;
    /**
     * API インデックス (APIパスか判定するためのダミーAPI)
     * @Get("api", as="api.get")
     * @param $request
     * @return Response
     */
    public function index(Request $request) {
        return $this->getUrl($request);
    }

    /**
     * API-01: 認証情報の取得
     * @OA\POST(
     *     path="/api/auth",
     *     description="認証されている会員情報を取得",
     *     tags={"Auth"},
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
     *              @OA\Property(property="result_code", type="integer",description="0:正常 1:エラー(フロント側のダイアログ制御仕分け用)"),
     *              @OA\Property(property="name", type="string", description="認証ユーザー名称"),
     *              @OA\Property(property="kana", type="string", description="認証ユーザー名フリガナ"),
     *              @OA\Property(property="mail", type="string",description="PCメールアドレス"),
     *              @OA\Property(property="b_birthday", type="string",description="生年月日（和暦）"),
     *              @OA\Property(property="b_year", type="integer",description="生年月日（西暦：年）"),
     *              @OA\Property(property="b_month", type="integer",description="生年月日（西暦：月）"),
     *              @OA\Property(property="b_day", type="integer",description="生年月日（西暦：日）"),
     *              @OA\Property(property="sex", type="integer",description="性別（1:男性 2:女性 3:未設定）"),
     *              @OA\Property(property="h_zip", type="string",description="郵便番号（ハイフンを含む）"),
     *              @OA\Property(property="h_pref", type="string",description="都道府県"),
     *              @OA\Property(property="h_addr", type="string",description="番地、建物名"),
     *              @OA\Property(property="h_tel", type="string",description="電話番号（ハイフンなし）"),
     *              @OA\Property(property="memtype_name", type="string",description="会員種別名（変更がある場合は（変更登録あり）の文言が末尾に追加）"),
     *              @OA\Property(property="store_name", type="string",description="所属店名（複数想定：「、」区切り）"),
     *              @OA\Property(property="dm_list", type="string",description="案内メール(現行では1:PCメールのみ想定)  チェックON= 1:PCメールのみON  チェックOFF=5:拒否のみON"),
     *              @OA\Property(property="pc_conf", type="integer",description="予約確認メール（0：予約確認メールしない　1:予約確認メールする）"),
     *              @OA\Property(property="gmo_credit", type="string",description="GMO関連の手続き処理（外部URL）に指定するパラメータ"),
     *          ),
     *     ),
     *     @OA\Response(
     *          response="403",
     *          description="NG（403:認証失敗時）",
     *          @OA\Header(
     *              header="X-FeelConnection-Required-Ver",
     *              required=true,
     *              @OA\Schema(type="string"),
     *              description="対応バージョン"
     *          ),
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="result_code", type="integer", description="1:エラー(フロント側のダイアログ制御仕分け用)"),
     *              @OA\Property(property="error_id", type="string", description="サーバ返却エラーID（通常はHTTPレスポンスコード）"),
     *              @OA\Property(property="error_code", type="string", description="サーバ返却エラーコード"),
     *              @OA\Property(property="message", type="string", description="サーバ返却エラーメッセージ"),
     *          ),
     *     )
     * )
     *
     * @POST("api/auth", as="api.auth.get")
     * @param $request
     * @return Response
     */
    public function getAuthInfo(Request $request) {

        logger("getAuthUserInfo() Start");
        // TBD:認証はヘッダーパラメータ（Bearerトークン）でやる想定。ペイロード処理はなし
        //$payload = $this->getPayload();
        //logger('payload');
        //logger($payload);
        // ペイロードバリデーション
        //$this->validateApiPayload('cust.auth', $payload);
        
        // TBD:認証情報からIDを特定する
        $response = $this->getAuthSelectLogic()->getAuthInfo();
        logger("getAuthUserInfo() End");
        return response()->json($response);

    }
    /**
     * API-02: 個人情報表示
     * 
     * @POST("api/auth/user", as="api.auth.user.get")
     * @param $request
     * @return Response
     */
    public function getUserInfo(Request $request) {
        logger("getUserInfo() Start");

        // TBD:認証はヘッダーパラメータ（Bearerトークン）でやる想定。ペイロード処理はなし
        $payload = $this->getPayload();
        logger('payload');
        logger($payload);
        // ペイロードバリデーション
        $this->validateApiPayload('cust.auth_user', $payload);
        $cid = data_get($payload,"cid",null);

        // TBD:認証情報からIDを特定する
        $response = $this->getAuthSelectLogic()->getUserInfo($cid);
        logger("getUserInfo() End");
        return response()->json($response);
    }
    /**
     * API-03: 受け取りメール設定表示更新
     * 
     * @POST("api/auth/user/dm_list/update", as="api.auth.user.dm_list.update")
     * @param $request
     * @return Response
     */
    public function updateUserDmList(Request $request) {

        logger("updateUserDmList() Start");

        // TBD:認証はヘッダーパラメータ（Bearerトークン）でやる想定。ペイロード処理はなし
        $payload = $this->getPayload();
        logger('payload');
        logger($payload);
        // ペイロードバリデーション
        $this->validateApiPayload('cust.dm_update', $payload);

        // TBD:認証情報からIDを特定する
        $response = $this->getAuthUpdateLogic()->setDmList($payload);
        logger("getUserInfo() End");
        return response()->json($response);

    }
    /**
     * API-04: 認証会員情報更新
     * 
     * @POST("api/auth/user/update", as="api.auth.user.update")
     * @param $request
     * @return Response
     */
    public function updateUser(Request $request) {

        logger("updateUserDmList() Start");

        // TBD:認証はヘッダーパラメータ（Bearerトークン）でやる想定。ペイロード処理はなし
        $payload = $this->getPayload();
        logger('payload');
        logger($payload);
        // ペイロードバリデーション
        $this->validateApiPayload('cust.dm_update', $payload);

        // TBD:認証情報からIDを特定する
        $response = $this->getAuthUpdateLogic()->updateUser($payload);
        logger("getUserInfo() End");
        return response()->json($response);

    }

    /**
     * API-05: 会員情報登録
     *
     * @POST("api/auth/user/store", as="api.auth.user.store")
     * @param $request
     * @return Response
     */
    public function storeUser(Request $request) {

        logger("storeUser() Start");

        $payload = $this->getPayload();
        logger('payload');
        logger($payload);
        // ペイロードバリデーション
        $this->validateApiPayload('cust.store', $payload);

        // TBD:認証情報からIDを特定する
        $response = $this->getAuthStoreLogic()->createUser($payload);
        logger("storeUser() End");
        return response()->json($response);

    }

}