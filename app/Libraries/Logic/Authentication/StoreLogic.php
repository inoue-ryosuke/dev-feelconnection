<?php

namespace App\Libraries\Logic\Authentication;

use App\Exceptions\BadRequestException;
use App\Models\Cust;
use App\Models\BaseModel;
use App\Models\Invite;
use DB;

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
        logger('Auth StoreLogic createUser start');
        $response = DB::transaction(function() use($payload) {
            /******* Cust登録 START *******/
            $custinfo = new Cust();
            $custinfo->mergeRequest($payload);
            $custinfo->webmem = 'Y';
            $custinfo->gmo_credit = '';
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
        logger('Auth StoreLogic createUser end');
        return $response;
    }


}