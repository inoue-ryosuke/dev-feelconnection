<?php

namespace App\Libraries\Logic\Authentication;

use App\Exceptions\BadRequestException;
use App\Models\Cust;
use App\Models\PrefMaster;
use DB;

/**
 * 認証で使用するバリデーション
 *
 */
class UpdateLogic
{
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