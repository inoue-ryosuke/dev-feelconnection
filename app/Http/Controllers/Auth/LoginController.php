<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\IllegalParameterException;
use App\Http\Controllers\Controller;
use App\Models\Cust;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Libraries\Logger;


/**
 * 認証コントローラー
 * @Middleware({"guest", "api"})
 */
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, Authenticatable;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     *
     * 仮ログイン(セッション認証を通すだけ) TODO ログインが実装されたあとは削除する
     * @Post("/api/user/login", as="api.user.login")
     * @param $request
     * @return Response
     */
    public function login(Request $request)
    {
        logger('ログイン');
        $payload = $this->getPayload();
        $pcMail = data_get($payload, "pc_mail");

        //ログインIDからユーザの情報を取得
        $pc_mail_key = (new Cust())->convertKey('pc_mail');
        $user = Cust::where($pc_mail_key, $pcMail)->first();

        // ログイン認証(cust_master用)が通ったユーザーをセッションに登録
        auth('customer')->login($user);
        logger('ログイン完了');
        return response()->json($user);

    }

}
