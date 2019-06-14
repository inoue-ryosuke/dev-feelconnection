<?php namespace App\Libraries\Logic\MailCheck;

use App\Exceptions\IllegalParameterException;
use App\Libraries\Logger;
use App\Libraries\Logic\BaseLogic;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Mail\RegistMail;

use App\Models\RegistAuth;


class SelectLogic extends BaseLogic
{

    //  config/validation配下のファイルを指定
    const VALIDATE_KEY_PREFIX = "mailcheck";

    private $_rules;

    /**
     * 起動時コンストラクタ
     */
    public function __construct()
    {
//        logger('Category Logic construct');
    }

    /**
    * メールアドレスの有効性判定
    * @param $payload
    * @return
    */
    public function validateRegistMailAddress($payload)
    {
        logger(__CLASS__.':'.__METHOD__.' start');
        
        logger($payload);
        
        $result_code = 0;
        $redirect_url = url('api/registmailcomplete');
        $errors = [];
        
        $key = self::getValidationKey($payload);
        
        //  バリデーションチェック
        $validator = validator(
            $payload,
            config('validation.'.self::VALIDATE_KEY_PREFIX.'.'.$key.'.rules', []),
            config('validation.common.errors'),
            config('validation.'.self::VALIDATE_KEY_PREFIX.'.'.$key.'.attributes')
        );
        if ($validator->fails()) {
            logger()->debug($validator->errors());
            $errors = json_encode($validator->errors(), JSON_UNESCAPED_UNICODE);
            $result_code = 1;
            $redirect_url = '';
            return [
                    'result_code'   => $result_code,
                    'redirect_url'  => $redirect_url,
                    'error_message' => $errors
            ];
        }
        
        //  バリデーションOKなら認証テーブルに登録してメール送信
        DB::transaction(function () use($payload) {
            $mailaddress = data_get($payload, 'mail_address', null);
            //  認証用ハッシュ生成
            $token = Str::random(64);
            (new RegistAuth)->insertByEmail($mailaddress, RegistAuth::IS_REGIST, $token);
            //  メール送信
            Mail::to($mailaddress)->send(new RegistMail($token));
        });
        
        logger([$result_code, $redirect_url, $errors]);
        
        logger(__CLASS__.':'.__METHOD__.' end');
        
        return [
                'result_code'   => $result_code,
                'redirect_url'  => $redirect_url,
                'error_message' => $errors
        ];

    }

    /**
    * タイプ別バリデーションキー取得
    * @param $payload
    * @return $key
    */
    private function getValidationKey($payload)
    {
        logger(__CLASS__.':'.__METHOD__.' start');
        
        $type = data_get($payload, 'type', null);
        
        $key = '';
        switch ($type) {
            case RegistAuth::IS_REGIST:
                //  登録
                $key = 'accountRegist';
                break;
            
            case RegistAuth::IS_PASSWD:
                //  パスワード再設定
                $key = 'passwdReset';
                break;
            
            case RegistAuth::IS_MAILADDRESS:
                //  メールアドレス変更
                $key = 'mailaddressChange';
                break;
            
            default:
                $key = '';
                break;
        }
        
        logger(__CLASS__.':'.__METHOD__.' end');
        
        return $key;
        
    }
    
}
