<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistAuth extends Model
{
    //
    protected $table = 'regist_auth';
    protected $primaryKey = 'aid';
    
//    const CREATED_AT = 'reg_date';
    
    public function insertByEmail($mailaddress, $processing_category, $token) 
    {
        
        //  処理分類がアカウント登録で
        //  有効期限内で
        //  登録されてるメールアドレスの数をカウント
        $dataCount = self::where('maddress', $mailaddress)
                        ->where('type', 1)
                        ->where('reg_date', '>=', DB::row('NOW()'))
                        ->count();
        if($dataCount > 0) {
            //  flg=1（削除）にする
            self::where('maddress', $mailaddress)
                        ->where('type', 1)
                        ->where('reg_date', '>=', DB::row('NOW()'))
                        ->update(['flg' => 1]);
        }
        //  データ登録
        $data                        = array();
        $data['maddress']            = $mailaddress;
        $data['ahash']               = $token;
        $data['reg_date']            = DB::raw("NOW()");
        $data['processing_category'] = $processing_category;
        self::insert($data);
        
    }
    
}
