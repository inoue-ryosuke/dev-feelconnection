<?php namespace App\Libraries\Logic\Music;

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
    * AppleMusic情報取得取得
    * @param
    * @return
    */
    public function getPlayLists()
    {
        logger('Music SelectLogic getPlayLists start');

//        // todo データ取得処置はモデルに移す↓
////         lesson_master.flgが有効、lesson_class1～3がnullではない条件で絞込
//        $query = LessonMaster::where('lesson_master.flg', 1)
//            ->whereNotNull('lesson_master.lesson_class1')
//            ->whereNotNull('lesson_master.lesson_class2')
//            ->whereNotNull('lesson_master.lesson_class3');
//        // 各lesson_classテーブルと結合
//        $query->leftjoin('lesson_class1', 'lesson_master.lesson_class1', 'lesson_class1.id')
//            ->leftjoin('lesson_class2', 'lesson_master.lesson_class2', 'lesson_class2.id')
//            ->leftjoin('lesson_class3', 'lesson_master.lesson_class3', 'lesson_class3.id');
//        // 必要な情報を取得
//        $lessonList = $query->groupBy('lesson_master.lid')->select(
//            'lesson_master.lid',
////            DB::raw('min(lesson_master.path) as path'),
////            DB::raw('min(lesson_master.image_path) as image_path'),
//            DB::raw('min(lesson_class1.name) as lesson_class1_name'),
//            DB::raw('min(lesson_class2.name) as lesson_class2_name'),
//            DB::raw('min(lesson_class3.name) as lesson_class3_name')
//        )->get();
//
//        // todo データ取得処置はモデルに移す↑
//        logger('lessonList');
//        logger($lessonList);
//
//        // 情報を取得できなかった場合は例外
//        if (empty($lessonList)) {
//            throw new IllegalParameterException();
//        }
//        // レスポンスの形式に整形
//        $list = [];
//        foreach($lessonList as $lesson) {
//            $list[$lesson->lesson_class1_name . " " . $lesson->lesson_class2_name] = [
//                "name" => $lesson->lesson_class1_name . " " . $lesson->lesson_class2_name . " " .$lesson->lesson_class3_name,
////                "path" => $lesson->path,
////                "image_path" => $lesson->image_path,
//            ];
//        }
//        $response = [
//            'result_code' => 0,
//            "list" => $list
//        ];

        // スタブレスポンス
        $response = [
            'result_code' => 0,
            "list" => [
                [
                    "BBC HIT" => [
                        [
                            "name"=> "BBC HIT 1",
                            "path"=> "https://music.apple.com/jp/playlist/bb1-dvgt/pl.d1f9e9638c9a41c5ad72e5885e94e6bc",
                            "image_path"=> "https://www.feelcycle.com/feelcycle_hp/img/contents/apple_music/fcam_bb1_hit_03.png"
                        ],
                        [
                            "name"=> "BBC HIT 2",
                            "path"=> "https://music.apple.com/jp/playlist/bb1-hit2/pl.b472b0df577047bb85254a78089ae549",
                            "image_path"=> "https://www.feelcycle.com/feelcycle_hp/img/contents/apple_music/fcam_bb1_hit_02.png"
                        ],
                    ]
                ]
            ]
        ];
        logger('Music SelectLogic getPlayLists end');
        return $response;

    }







}
