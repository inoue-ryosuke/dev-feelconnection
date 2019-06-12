<?php namespace App\Libraries\Logic\MailCheck;

use App\Exceptions\IllegalParameterException;
use App\Libraries\Logger;
use App\Libraries\Logic\BaseLogic;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Invite;
use App\Mail\RegistMail;
use Illuminate\Support\Facades\Mail;

class SelectLogic extends BaseLogic
{

    //  config/validation配下のファイルを指定
    const VALIDATE_KEY = "mailcheck.accountRegist";

    private $_rules;

    /**
     * 起動時コンストラクタ
     */
    public function __construct()
    {
//        logger('Category Logic construct');
        
        //  バリデーションルール取得
        $this->_rules = config('validation.'.self::VALIDATE_KEY.'.rules', []);
    }

    /**
    * メールアドレスの有効性判定
    * @param $inviteCode
    * @return
    */
    public function validateMailAddress($payload)
    {
        logger(__CLASS__.':'__METHOD__.' start');
        
        logger($payload);
        
        $result_code = 0;
        $redirect_url = ''; //  TODO    遷移先決まったら設定する
        $errors = [];
        
        //  バリデーションチェック
        $validator = validator(
            $payload,
            $this->_rules,
            config('validation.common.errors'),
            config('validation.'.self::VALIDATE_KEY.'.attributes')
        );
        if ($validator->fails()) {
            logger()->debug($validator->errors());
            $errors = json_encode($validator->errors(), JSON_UNESCAPED_UNICODE);
            $result_code = 1;
            $redirect_url = '';
            return [$result_code, $redirect_url, $errors];
        }
        
        //  バリデーションOKなら認証テーブルに登録してメール送信
        DB::transaction(function () use($payload) {
            $mailaddress = data_get($payload, 'mail_address', null);
            //  認証用ハッシュ生成   TODO
            $token = Str::random(60);
            (new RegistAuth)->insertByEmail($mailaddress, 1, $token);
            //  メール送信
            Mail::to($mailaddress)->send(new RegistMail($token));
        });
        
        logger(__CLASS__.':'__METHOD__.' end');
        
        return $response;

    }

}
