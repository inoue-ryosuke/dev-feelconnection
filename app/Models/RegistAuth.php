<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RegistAuth extends Model
{
    //
    protected $table = 'regist_auth';
    protected $primaryKey = 'aid';
    
    public $timestamps = false;
    
    const IS_REGIST            = 1;     //  アカウント登録
    const IS_PASSWD            = 2;     //  パスワード再設定
    const IS_MAILADDRESS       = 3;     //  メールアドレス変更
    
    const DEFAULT_EXPIRE_HOURS = 24;    //  有効期限の加算時間（configから取れなかった時の初期値）
    
    /**
     * メールアドレス登録
     *
     * @param string $mailaddress
     * @param integer $processing_category
     * @param integer $cid
     * @param string $token
     * @return 
     */
    public function insertByEmail($mailaddress, $processing_category, $cid, $token) 
    {
        
        $dt = Carbon::now();
        $now = $dt->format('Y-m-d H:i:s');
        $expire = $this->getExpireHours($dt, $processing_category);
        
        //  処理分類がアカウント登録で
        //  有効期限内で
        //  登録されてるメールアドレスの数をカウント
        $dataCount = self::where('maddress', $mailaddress)
                        ->where('processing_category', $processing_category)
                        ->exists();
        if($dataCount) {
            //  flg=1（削除）にする
            self::where('maddress', $mailaddress)
                        ->where('processing_category', $processing_category)
                        ->update(['flg' => 0]);
        }
        //  データ登録
        $data                        = [];
        $data['maddress']            = $mailaddress;
        $data['ahash']               = $token;
        //  アカウント登録時以外はIDをセット
        if ($processing_category != self::IS_REGIST) {
            $data['cid']             = $cid;
        }
        $data['type']                = 1;
        $data['processing_category'] = $processing_category;
        $data['flg']                 = 1;
        $data['expire']              = $expire;
        self::insert($data);
        
    }
    
    /**
     * 有効期限加算時間取得
     *
     * @param Carbon obj $dt
     * @param integer $processing_category
     * @return integer $expire
     */
    public function getExpireHours($dt, $processing_category)
    {
        
        $config_key = '';
        switch($processing_category) {
            case self::IS_REGIST:
                $config_key = 'constant.mailCheck.regist.expireHours';
                break;
                
            case self::IS_PASSWD:
                $config_key = 'constant.mailCheck.passwdIssue.expireHours';
                break;
                
            case self::IS_MAILADDRESS:
                $config_key = 'constant.mailCheck.mailReset.expireHours';
                break;
                
        }
        $expire = $dt->addHours(config($config_key, self::DEFAULT_EXPIRE_HOURS));
        
        return $expire;
        
    }
}

