<?php

namespace App\Http\Controllers\Api;

use App\Libraries\Logic\Loader;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class MailCheckController extends ApiController
{
    //
    use ApiLogicTrait;
    
    /**
     * @POST("api/mailcheck", as="api.mailcheck.post")
     * @param Request $request
     * @return
     */
    public function chkMail(Request $request)
    {
    
        logger("chkMail() Start");
        logger($request->all());

        $type = $request->input('type');
        $payload = $request->getContent();
        $payload = json_decode($payload, true);
        //  処理分岐
        if ($type == 1) {
            //  登録
//            $this->validateApiPayload('mailcheck.accountRegist', $payload);
            $response = $this->getAccountRegistSelectLogic()->validateMailAddress($payload);
        }
        elseif ($type == 2) {
            //  パスワード再設定
            $result = self::passwd($request);
        }
        elseif ($type == 3) {
            //  メールドレス再設定
            $result = self::mail($request);
        }
        else {
            //  例外
            //  400エラーにする
            return response()
                    ->json([ 'パラメータエラー' ])
                    ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        
        logger("chkMail() End");
        return response()->json($response);
    }
    
    /**
     * 
     * アカウント登録開始処理
     * 
     * @param Request $request
     * @return
     */
    private function regist(Request $request)
    {
        
//        logger("chkMail() Start");
        
        $error_messages = [];
        $result_code = 0;
        $redirect_url = ""; //  TODO    遷移先決まったら入れる
        
        $redirect_url = '';
        //  メールアドレス存在バリデーション
        $validator = Validator::make($request->all(), [
            'mail_address' => 'required|email|unique:cust_master,pc_mail',
        ]);
        
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->has('mail_address')) {
                $error_messages['mail_address'] = $errors->first('mail_address');
            }
            //  エラーの時はリダイレクトURLをクリアにする
            $result_code = 1;
            $redirect_url = "";
        }
        else{
            //  認証テーブルに追加及び認証付きメール送信
        }
        
//        logger("getUserInfo() End");
        return response()->json([$result_code, $redirect_url, $error_messages]);
        
    }
    
    /**
     * 
     * パスワード再発行処理
     * 
     * @param Request $request
     * @return
     */
    private function passwd(Request $request)
    {
        
        $error_messages = [];
        $result_code = 0;
        $redirect_url = ""; //  TODO    遷移先決まったら入れる
        
        $redirect_url = '';
        //  メールアドレス存在バリデーション
        $validator = Validator::make($request, [
            'mail_address' => [
                'required', 'email', 'exists:cust_master, pc_mail',],
        ]);
        
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->has('mail_address')) {
                $error_messages['mail_address'] = $errors->first('mail_address');
            }
            //  エラーの時はリダイレクトURLをクリアにする
            $result_code = 1;
            $redirect_url = "";
        }
        else{
            //  認証テーブルに追加及び認証付きメール送信
        }
        
//        logger("getUserInfo() End");
        return response()->json([$result_code, $redirect_url, $error_messages]);
        
    }
    
    /**
     * 
     * メールアドレス再設定処理
     * 
     * @param Request $request
     * @return
     */
    private function mail(Request $request)
    {
        
        $error_messages = [];
        $result_code = 0;
        $redirect_url = ""; //  TODO    遷移先決まったら入れる
        
        $redirect_url = '';
        //  メールアドレス存在バリデーション
        $validator = Validator::make($request, [
            'mail_address' => [
                'required', 'email', 'exists:cust_master, pc_mail',],
        ]);
        
        //  メールアドレスし設定の場合はエラーになっても正常終了にする
        //  但し、認証URL付きメールの送信は行わない
        if (!$validator->fails()) {
            //  認証テーブルに追加及び認証付きメール送信
        }
        
//        logger("getUserInfo() End");
        return response()->json([$result_code, $redirect_url, $error_messages]);
        
    }
    
}
