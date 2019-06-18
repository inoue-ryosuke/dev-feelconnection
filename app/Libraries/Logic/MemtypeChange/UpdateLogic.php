<?php

namespace App\Libraries\Logic\MemtypeChange;

use App\Exceptions\BadRequestException;
use App\Models\Cust;
use DB;

/**
 * 認証で使用するバリデーション
 *
 */
class UpdateLogic
{
    
    /**
     * 会員種別変更確認
     * @param
     * @return
     */
    public function updateMemtype($payload)
    {
        logger('MemtypeChangeSelectLogic UpdateLogic updateMemtype start');
        logger('payload');
        logger($payload);

        // レスポンス
        $response = [
            'result_code' => 0,
            'redirect_url' => "http:///XXX/YYY"
        ];

        logger('MemtypeChangeSelectLogic UpdateLogic updateMemtype end');

        return $response;
    }

}