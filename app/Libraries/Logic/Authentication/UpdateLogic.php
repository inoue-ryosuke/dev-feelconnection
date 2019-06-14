<?php

namespace App\Libraries\Logic\Authentication;

use App\Exceptions\BadRequestException;
use App\Models\Cust;
use App\Models\PrefMaster;
use DB;

/**
 * 認証ユーザーの参照・登録・更新
 */
class UpdateLogic
{
    /**
     * 会員情報登録更新用
     */
    public static function updateUser($payload) {
        // TBD:認証情報からCustを特定する
        $cid = Cust::first()->cid;
        $result = DB::transaction(function() use($cid,$payload) {
             $custinfo = Cust::getAuthInfo($cid,true);
             $custinfo->margeRequest($payload);
             $custinfo->save();
             return ["result_code" => 0];
        });
        return $result;        
    }

    /**
     * 
     */
    public static function setDmList($payload) {

        // TBD:認証情報からCustを特定する
        $cid = Cust::first()->cid;
        $result = DB::transaction(function() use($cid,$payload) {
             $dmlist = data_get($payload,'dm_list');
             $pcconf = data_get($payload,'pc_conf');
             $custinfo = Cust::getAuthInfo($cid,true);
             $custinfo->dm_list = implode(",",$dmlist);
             $custinfo->pc_conf = $pcconf;
             $custinfo->save();
             return ["result_code" => 0];
        });
        return $result;

    }

}