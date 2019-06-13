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
    
    public function insertByEmail($mailaddress, $processing_category, $token) 
    {
        
        $dt = Carbon::now();
        $now = $dt->format('Y-m-d H:i:s');
        $expire = $dt->addHours(config('constant.mailCheck.authExpireHours', self::DEFAULT_EXPIRE_HOURS));
        
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
                        ->update(['flg' => 1]);
        }
        //  データ登録
        $data                        = [];
        $data['maddress']            = $mailaddress;
        $data['ahash']               = $token;
        $data['type']                = 1;
        $data['processing_category'] = $processing_category;
        $data['flg']                 = 0;
        $data['expire']              = $expire;
        self::insert($data);
        
    }
    
}

