<?php namespace App\Libraries\Logic\ZipCode;

use App\Exceptions\IllegalParameterException;
use App\Libraries\Logger;
use App\Libraries\Logic\BaseLogic;
use App\Models\LessonMaster;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\UserMaster;

class SelectLogic extends BaseLogic
{

    /**
     * 起動時コンストラクタ
     */
    public function __construct()
    {
//        logger('Category Logic construct');
    }

    /**
    * 郵便番号住所取得
    * @param $payload
    * @return
    */
    public function getAddressByZipCode($payload)
    {
        logger('ZipCode SelectLogic getAddressByZipCode start');

        // スタブレスポンス
        $response = $this->getStub();
        logger('ZipCode SelectLogic getAddressByZipCode end');
        return $response;

    }

    /**
     * @return array
     */
    private function getStub(): array
    {
        $response = [
            'result_code' => 0,
            "zip_code" => '0640941',
            "prefecture" => '北海道',
            "address" => '札幌市中央区旭ケ丘',
        ];
        return $response;
    }


}
