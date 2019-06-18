<?php

use Illuminate\Database\Seeder;
use App\Models\BaseFormModel;
use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ShiftMaster;
use App\Models\TenpoMaster;
use App\Models\UserMaster;

class ShiftMasterSeeder extends BaseSeeder
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
     * 登録単位でShiftMasterに関係する指定キーのみ登録
     */
    protected function insertRecord($record = []) {

        //店舗情報の取得
        $tenpo = TenpoMaster::first();
        // 店舗情報の主キーを取得
        $tid = $tenpo->getAuthIdentifier();
        // スタッフ情報の取得
        $instructor = UserMaster::leftjoin('user_master_hist', 'user_master.uid', 'user_master_hist.uid')->where('user_master_hist.tid', $tid)->where('user_master.teacher', UserMaster::TEACHER_VALID)->first();
        $dao = new ShiftMaster();
        $dao->shift_tenpoid = $tid;
        $dao->teacher = $instructor->getAuthIdentifier();
        // seeder.develop.shift_masterで設定した値をモデルに設定
        foreach ($record as $key => $value) {
            $dao->$key = $value;
        }
//        $dao->mergeRequest($record);
        $dao->save();
        echo "\n   ---> " . "ShiftMasterSeeder Insert End " . "\n";

    }

}
