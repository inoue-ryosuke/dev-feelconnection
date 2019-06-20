<?php

namespace App\Libraries\Logic\Authentication;

use App\Exceptions\BadRequestException;
use App\Models\Cust;
use App\Models\PrefMaster;
/**
 *
 *
 */
class StoreLogic
{
    /**
     * 会員情報登録
     * @param $payload
     * @return
     */
    public static function createUser($payload) {

        $response = DB::transaction(function() use($payload) {
            $custinfo = new Cust();
            $custinfo->margeRequest($payload);
            $custinfo->save();
            return ["result_code" => 0];
        });

        return $response;
    }


}