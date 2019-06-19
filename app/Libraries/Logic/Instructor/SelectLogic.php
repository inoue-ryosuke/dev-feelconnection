<?php namespace App\Libraries\Logic\Instructor;

use App\Libraries\Logger;
use App\Libraries\Logic\BaseLogic;
use App\Models\TenpoMaster;
use App\Models\BelongTenpoHist;
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
        logger('Instructor SelectLogic getInstructors start');
        logger('payload');
        logger($payload);
        // スタブレスポンス
//        return $this->getStub();

        // パラメーターを取得
        $freeWord = data_get($payload, 'freeword', null);
        $limit = data_get($payload, 'limit', 100);
        $offset = data_get($payload, 'offset', 0);
        $type = data_get($payload, 'type', UserMaster::SORT_TYPE_NAME);

        // インストラクター一覧を取得
        $instructorList = UserMaster::findInstructors($limit, $offset, $type, $freeWord);

        // インストラクター全件数を取得
        $totalCount = $instructorList['count'];

        // レスポンス用に整形
        $list = [];
        foreach($instructorList['record'] as $instructor) {
            $list[] = [
                "uid" => $instructor->uid,
                "name" => $instructor->name,
                "assigned_at" => $instructor->assigned_at,
//                "comment" =>  $instructor->self_introduction,
//                "image_path" => $instructor->image_path,
            ];
        }
        // 取得件数
        $listCount = count($list);
        $response = [
            'result_code' => 0,
            'instructors' => $list,
            "total_count" => $totalCount,
            "list_count" => $listCount,
            "limit" => $limit,
            "offset" => $offset,
            "type" => $type
        ];

        if ($type == UserMaster::SORT_TYPE_SHOP) {
            //所属店舗順(北から)の場合
            // 所属店舗情報を取得
            $instructorIds = $instructorList['record']->pluck('uid')->unique();
            $shopList = TenpoMaster::findInstructorsShops($instructorIds);
            // レスポンス用に整形
            $formatedShopList = [];
            if (!empty($shopList)) {
                // 店舗名でグルーピング
                $groupedShopList = $shopList->groupBy('tenpo_name');
                foreach($groupedShopList as $name => $shop) {
                    // コレクションからuidのキーバリューを抽出
                    $uidList = $shop->map(function ($item) {
                        return [ 'uid' => $item[BelongTenpoHist::UID] ];
                    });
                    $formatedShopList[] = [
                        'name' => $name,
                        'instructors' => $uidList
                    ];
                }
                $response['sort_results'] = $formatedShopList;
            }
        }

        logger('Instructor SelectLogic getInstructors end');
        return $response;

    }

    /**
     * @return array
     */
    private function getStub(): array
    {
        return $response = [
            'result_code' => 0,
            "instructors" => [
                [
                    "uid" => 151,
                    "name" => "AAAA太郎",
                    "assigned_at" => "2019/06/10",
                    "comment" => "AAAA太郎の自己紹介文",
                    "image_path" => "/images/aaaa_tarou.jpg"
                ],
                [
                    "uid" => 152,
                    "name" => "BBBB太郎",
                    "assigned_at" => "2019/06/11",
                    "comment" => "BBBB太郎の自己紹介文",
                    "image_path" => "/images/bbbb_tarou.jpg"
                ]
            ],
            "sort_results" => [
                [
                    "name" => "銀座(GNZ)",
                    "instructors" => [
                        ["uid" => 151],
                        ["uid" => 152]
                    ]
                ],
                [
                    "name" => "渋谷(SBY)",
                    "instructors" => [
                        ["uid" => 151]
                    ]
                ]
            ],
            "total_count" => 2,
            "list_count" => 2,
            "limit" => 100,
            "offset" => 0,
            "type" => 1
        ];
    }


}
