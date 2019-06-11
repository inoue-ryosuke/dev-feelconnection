<?php

namespace App\Http\Controllers\Api;

//use App\Libraries\Logic\Loader;  実開発の場合はロジック層にて実装
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Libraries\Logic\Authentication\ValidationLogic;   // バリデーション
use App\Libraries\Logic\Authentication\DataManagerLogic;  // 情報取得･更新系

///**
// * 認証コントローラー
//// * @Middleware({"logger", "ua", "append", "maintenance", "api"})
// */
class AuthController extends ApiController
{

    use ApiLogicTrait;

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
    public function getUserInfo(Request $request) {

        logger("getUserInfo() Start");

        // ▼実実装用
        $payload = $request->getContent();
        $payload = json_decode($payload, true);
        if (!VaidationLogic::validateAuthInfo($parameters)) {
            // エラー
            //throw new AuthenticationErrorException(); Handler経由レスポンスの場合
            // 手動レスポンス形成
            return response()
                    ->json([ 'エラー' ])
                    ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        // TBD:認証情報からIDを特定する
        $id = 1;
        $custinfo = DataManagerLogic::getAuthInfo($id);
        if (!$custinfo) {
            // エラー
            //throw new AuthenticationErrorException(); Handler経由レスポンスの場合
            // 手動レスポンス形成
            return response()
                    ->json([ 'エラー' ])
                    ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        $response = DataManagerLogic::getUserInfo($cid);

        // ▼stub用
        /*
        $response = [
	        "result_code" => 0,
	        "name" => "鈴木太郎",
	        "kana" => "スズキタロウ",
	        "pc_mail" => "you@feelcycle.com",
	        "b_birthday" => "昭和55年1月1日",
	        "b_year" => 1980,
	        "b_month" => 1,
	        "b_day" => 1,
 	        "sex" => "1",
            "h_zip" => "104-0061",
            "h_pref" => "東京都",
            "h_addr" => "中央区銀座 GINZA SIX 10F",
            "h_tel" => "0363161005",
	        "memtype_name" => "マンスリーメンバー",
            "store_name" => "銀座（GNZ）、自由が丘（JYO）",
            "dm_list" => "1,,,,",
   	        "pc_conf" => 1,
	        "gmo_credit" => "XXXXXXXXXXX",
        ];*/
        logger("getUserInfo() End");
        return response()->json($response);

    }

}
