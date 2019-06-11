<?php

namespace App\Libraries\Logic\Authentication;

use Illuminate\Support\Facades\Validator;

/**
 * 認証で使用するバリデーション
 *
 */
class VaidationLogic
{
    /**
     *  とりあえず正常を返す
     */
    public static function validateAuthInfo(array $parameters) {
        // TBD:ペイロードのバリデーション処理をする
        /*
        $validator = Validator::make($parameters, [
            'sid' => 'required|string'//|exists:shift_master,shiftid_hash'
        ]);
        return !$validator->fails();
        */
        return true;
    }
}