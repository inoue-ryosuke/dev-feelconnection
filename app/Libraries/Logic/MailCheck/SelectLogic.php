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
use App\Mail\PasswdIssueMail;
use App\Mail\MailResetMail;

use App\Models\RegistAuth;

class SelectLogic extends BaseLogic
{

    private $_type;
    private $_mail_address;

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
    public function validateMailAddress($type, $payload)
    {
        logger(__CLASS__.':'.__METHOD__.' start');
        
        logger($payload);
        
        $this->_type = $type;
        $this->_mail_address = data_get($payload, 'mail_address', null);
        
        $result_code = 0;
        $errors = [];
        //  処理区分別リダイレクトURL取得
        $redirect_url = $this->getRedirectURL();
        
        //  処理区分別バリデーションキー取得
        $key = self::getValidationKey();
        //  バリデーションチェック
        logger('validation.mailcheck.'.$key.'.attributes');
        $validator = validator(
            $payload,
            config('validation.mailcheck.'.$key.'.rules', []),
            config('validation.common.errors'),
            config('validation.mailcheck.'.$key.'.attributes')
        );
        //  バリデーションエラー処理
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
        
        //  TODO    認証が通ったら正規の取得方法に変える
        //$user = auth('customer')->user();
        $cid = 999;
        //  バリデーションOKなら認証テーブルに登録してメール送信
        DB::transaction(function () use($cid) {
            //  認証用ハッシュ生成
            $token = Str::random(64);
            (new RegistAuth)->insertByEmail($this->_mail_address, $this->_type, $cid, $token);
            //  メール送信
            Mail::to($this->_mail_address)->send($this->getMailObject($token));
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
    * 処理区分別リダイレクトURL取得
    * @param $payload
    * @return $key
    */
    private function getRedirectURL()
    {
        logger(__CLASS__.':'.__METHOD__.' start');
        
        $url = '';
        switch ($this->_type) {
            case RegistAuth::IS_REGIST:
                //  登録
                $url = url('api/registmailcomplete');
                break;
            
            case RegistAuth::IS_PASSWD:
                //  パスワード再設定
                $url = url('api/passwdissuecomplete');
                break;
            
            case RegistAuth::IS_MAILADDRESS:
                //  メールアドレス変更
                $url = url('api/changemailcomplete');
                break;
            
            default:
                $url = '';
                break;
        }
        
        logger(__CLASS__.':'.__METHOD__.' end');
        
        return $url;
        
    }
    
    /**
    * 処理区分別バリデーションキー取得
    * @param $payload
    * @return $key
    */
    private function getValidationKey()
    {
        logger(__CLASS__.':'.__METHOD__.' start');
        
        $key = '';
        switch ($this->_type) {
            case RegistAuth::IS_REGIST:
                //  登録
                $key = 'accountRegist';
                break;
            
            case RegistAuth::IS_PASSWD:
                //  パスワード再設定
                $key = 'passwdIssue';
                break;
            
            case RegistAuth::IS_MAILADDRESS:
                //  メールアドレス変更
                $key = 'mailaddressReset';
                break;
            
            default:
                $key = '';
                break;
        }
        
        logger(__CLASS__.':'.__METHOD__.' end');
        
        return $key;
        
    }
    
    /**
    * 処理区分別バリデーションキー取得
    * @param $payload
    * @return $key
    */
    private function getMailObject($token)
    {
        logger(__CLASS__.':'.__METHOD__.' start');
        
        $obj = '';
        switch ($this->_type) {
            case RegistAuth::IS_REGIST:
                //  登録
                $obj = new RegistMail($token);
                break;
            
            case RegistAuth::IS_PASSWD:
                //  パスワード再設定
                $obj = new PasswdIssueMail($token);
                break;
            
            case RegistAuth::IS_MAILADDRESS:
                //  メールアドレス変更
                $obj = new MailResetMail($token);
                break;
            
            default:
                $obj = '';
                break;
        }
        
        logger(__CLASS__.':'.__METHOD__.' end');
        
        return $obj;
        
    }
    
    
}
