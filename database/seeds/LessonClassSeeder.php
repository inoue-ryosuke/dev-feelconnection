<?php

use Illuminate\Database\Seeder;
use App\Models\BaseFormModel;
use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\UserMaster;
use App\Models\TenpoMaster as TenpoMaster;
use App\Models\LessonClass1;
use App\Models\LessonClass2;
use App\Models\LessonClass3;
use \Illuminate\Support\Carbon;

class LessonClassSeeder extends BaseSeeder
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
//        DB::statement('TRUNCATE lesson_class1 CASCADE');
//        DB::statement('TRUNCATE lesson_class2 CASCADE');
//        DB::statement('TRUNCATE lesson_class3 CASCADE');

        echo "\n" . "LessonClassSeeder Start" . "\n";
        DB::transaction(function() {
            // 登録単位ループ
            // lesson_class1
            foreach (config("seeder.develop.lesson_class1") as $record) {
                $dao = new LessonClass1();
                $this->insertRecord($record,$dao);
            }
            // lesson_class2
            foreach (config("seeder.develop.lesson_class2") as $record) {
                $dao = new LessonClass2();
                $this->insertRecord($record,$dao);
            }
            // lesson_class3
            foreach (config("seeder.develop.lesson_class3") as $record) {
                $dao = new LessonClass3();
                $this->insertRecord($record,$dao);
            }
        });
        echo "\n" . "UserMasterSeeder Finished" . "\n";
    }
    /**
     * 登録単位でCUSTに関係する指定キーのみ登録
     */
    protected function insertRecord($record = [], $dao) {

        //
        $dao->mergeRequest($record);
        $dao->created_at = Carbon::now();
        $dao->save();
        echo "\n   ---> " . "LessonClassSeeder Insert End " . "\n";

    }

}
