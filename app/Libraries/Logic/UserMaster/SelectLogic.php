<?php namespace App\Libraries\Logic\UserMaster;

use App\Libraries\Logger;
use App\Libraries\Logic\BaseLogic;
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
    * インストラクター一覧取得
    * @param $payload
    * @return
    */
    public function getInstructors($payload)
    {
        logger('UserMaster SelectLogic getInstructors start');
        logger('payload');
        logger($payload);
        // パラメーターを取得
        $freeWord = data_get($payload, 'freeword', null);
        $limit = data_get($payload, 'limit', 100);
        $offset = data_get($payload, 'offset', 0);
        $type = data_get($payload, 'type', UserMaster::SORT_TYPE_NAME);
        // インストラクター一覧を取得
//        $instructorList = UserMaster::getInstructors($limit, $offset);
//        $sortResults =
        $response = [
            'result_code' => 0,
            "list" => [
                [
                    "uid" => 151,
                    "name" => "AAAA太郎",
                    "assigned_at" => "2019/06/10",
                    "comment" =>  "AAAA太郎の自己紹介文",
                    "image_path" => "/images/aaaa_tarou.jpg"
                ],
                [
                    "uid" => 152,
                    "name" => "BBBB太郎",
                    "assigned_at" => "2019/06/11",
                    "comment" =>  "BBBB太郎の自己紹介文",
                    "image_path" => "/images/bbbb_tarou.jpg"
                ]
            ],
            "sort_results" => [
                [
                    "name" => "銀座(GNZ)",
                    "instructors" => [
                        [ "uid" => 151 ],
                        [ "uid" => 152 ]
                    ]
                ],
                [
                    "name" => "渋谷(SBY)",
                    "instructors" => [
                        [ "uid" => 151 ]
                    ]
                ]
            ],
            "total_count" => 2,
            "list_count" => 2,
            "limit" => 100,
            "offset" => 0,
            "type" => 1
        ];
        logger('UserMaster SelectLogic getInstructors start');
        return $response;

    }







}
