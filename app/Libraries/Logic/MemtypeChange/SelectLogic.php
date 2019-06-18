<?php

namespace App\Libraries\Logic\MemtypeChange;

use App\Exceptions\IllegalParameterException;
use App\Libraries\Logger;
use App\Libraries\Logic\BaseLogic;
use App\Models\LessonMaster;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\UserMaster;
use App\Models\Cust;
use App\Models\CustMemType;
use App\Models\KessaiMaster;
use App\Models\TenpoMaster;

class SelectLogic extends BaseLogic
{
    /** 入会手続き時 */
    const MODE_NYUKAI        = 1;
    /** 会員種別変更時 */
    const MODE_CHANGE        = 2;
    /** 変更予定の会員種別をキャンセル時 */
    const MODE_CHANGE_CANCEL = 3;

    /**
     * 起動時コンストラクタ
     */
    public function __construct()
    {
    }

    /**
     * 会員種別変更確認画面情報取得
     * 
     * @param
     * @return
     */
    public function getConfirmPage($payload)
    {
        logger('MemtypeChangeSelectLogic SelectLogic getConfirmPage start');

        // 会員情報
        $customerInfo = [
        ];

        $kessaiInfo = [
        ];

        $kessaiList = [
        ];

        // レスポンス
        $response = [
            'result_code' => 0,
            'redirect_url' => "memtype_change/update",
            'mode' => 1,
            'customer_info' => $customerInfo,
            'kessai_info' => $kessaiInfo,
            'kessai_list' => $kessaiList
        ];
        logger('MemtypeChangeSelectLogic SelectLogic getConfirmPage end');

        return $response;
    }
}
