<?php

namespace App\Libraries\Logic\Authentication;

use App\Exceptions\BadRequestException;
use App\Models\Cust;
use App\Models\Invite;
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
            /******* Cust登録 START *******/
            $custinfo = new Cust();
            $custinfo->margeRequest($payload);
            $custinfo->save();
            $freshCust = $custinfo->fresh();
            /******* Cust登録 END *******/

            /******* Cust登録 START *******/
            $invite = new Invite;
            $invite->cidKey = $freshCust->getAuthIdentifier();
            $invite->invite_code = $invite::makeInviteCode(16);
            $invite->save();
            /******* Cust登録 END *******/
            return ["result_code" => 0];
        });

        return $response;
    }


}