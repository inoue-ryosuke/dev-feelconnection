<?php

use Illuminate\Database\Seeder;
use App\Models\BaseFormModel;
use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\LessonMaster;


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
//        DB::statement('TRUNCATE lesson_class1 CASCADE');

        echo "\n" . "ShiftMasterSeeder Start" . "\n";
        DB::transaction(function() {
            // 登録単位ループ
            foreach (config("seeder.develop.shift_master") as $record) {
                $this->insertRecord($record);
            }
        });
        echo "\n" . "UserMasterSeeder Finished" . "\n";
    }
    /**
     * 登録単位でCUSTに関係する指定キーのみ登録
     */
    protected function insertRecord($record = []) {

        //
        $dao = new LessonMaster();
        $dao->mergeRequest($record);
        $dao->save();
        echo "\n   ---> " . "ShiftMasterSeeder Insert End " . "\n";

    }

}
