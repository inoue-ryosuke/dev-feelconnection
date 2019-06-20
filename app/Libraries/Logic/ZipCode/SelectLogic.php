<?php namespace App\Libraries\Logic\ZipCode;

use App\Exceptions\IllegalParameterException;
use App\Libraries\Logger;
use App\Libraries\Logic\BaseLogic;
use App\Models\ZipCode;
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
//        $response = $this->getStub();
//        // パラメーターを取得
        $zipCode = data_get($payload, 'zip_code', null);

        if (ZipCode::isExistsZipCode($zipCode)) {
            $record = ZipCode::findByCode((integer)$zipCode);
        } else {
            throw new IllegalParameterException('存在しない郵便番号です');
        }

        // 住所を結合
        $address = null;
        if (!empty($record->address2) && !is_null($record->address2)) {
            $address = $record->address2;
        }

        if (!empty($record->address3) && !is_null($record->address3)) {
            // address3の中身が「以下に掲載がない場合」を削除
            if (preg_match("/^以下に掲載がない場合$/",$record->address3)) {
                $record->address3 = "";
            }
            // （次のビルを除く）（地階・階層不明）（３~６丁目）等を削除
            if (preg_match("/（.*\）$/",$record->address3)) {
                $match = preg_replace("/（.*\）$/","",$record->address3);
                 $record->address3 = $match;
            }
            $address = $address.$record->address3;
        }

        $response = [
            'result_code' => 0,
            "zip_code" => (string)$record->code,
            "prefecture" => $record->address1,
            "address" => $address,
        ];

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
