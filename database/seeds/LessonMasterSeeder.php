<?php

use Illuminate\Database\Seeder;
use App\Models\BaseFormModel;
use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\LessonMaster;
use App\Models\LessonClass1;
use App\Models\LessonClass2;
use App\Models\LessonClass3;
use App\Models\CustMemType;

class LessonMasterSeeder extends BaseSeeder
{

    public function __construct()
    {
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 削除
//        DB::statement('TRUNCATE lesson_master CASCADE');
        // 本シーダー(LessonMasterSeeder)は、lesson_class1、lesson_class2、lesson_class3とcust_memtypeにレコードがある前提で作成しています
        echo "\n" . "LessonMasterSeeder Start" . "\n";
        DB::transaction(function() {
            // 登録単位ループ
            foreach (config("seeder.develop.lesson_master") as $record) {
                $this->insertRecord($record);
            }
        });
        echo "\n" . "UserMasterSeeder Finished" . "\n";
    }
    /**
     * 登録単位でCUSTに関係する指定キーのみ登録
     */
    protected function insertRecord($record = []) {

        //lesson_class情報の取得
        $lessonClass1List = LessonClass1::get();
        $lessonClass1 = $lessonClass1List->random();
        $lessonClass2List = LessonClass2::get();
        $lessonClass2 = $lessonClass2List->random();
        //会員種別(cust_memtype)情報取得
        $custMemTypeList = CustMemType::get();
        $custMemTypeIds = $custMemTypeList->implode("mid",",");
        /** LessonMasterへ登録開始 */
        $dao = new LessonMaster();
        // seeder.develop.lesson_masterで設定した値をモデルに設定
        foreach ($record as $key => $value) {
            $dao->$key = $value;
        }
        // レッスン分類1 ID
        $dao->lesson_class1 = $lessonClass1->getAuthIdentifier();
        // レッスン分類2 ID
        $dao->lesson_class2 = $lessonClass2->getAuthIdentifier();
        // TODO lesson_class2が季節特集やアーティスト特集の場合は、lesson_class3に0以外の数値が入るように要調整
        // レッスン分類3 ID
        $dao->lesson_class3 = 0;
        // レッスン名
        $dao->lessonname = $lessonClass1->name . " " . $lessonClass2->name ." 1";
        // アイコン名(プログラム名の取得先)
        $dao->iname = $dao->lessonname;
        // 予約可能会員種別ID cust_memtype.mid ※カンマ区切りで複数
        $dao->memtype = $custMemTypeIds;
        $dao->save();
        /** LessonMasterへ登録終了 */
        echo "\n   ---> " . "LessonMasterSeeder Insert End " . "\n";

    }

}
