<?php

use Illuminate\Database\Seeder;
use App\Models\BaseFormModel;
use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ShiftMaster;
use App\Models\TenpoMaster;
use App\Models\UserMaster;
use App\Models\Cust;
use App\Models\LessonMaster;
use \Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

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
//        DB::statement('TRUNCATE shift_master CASCADE');
        // 本シーダー(ShiftMasterSeeder)は、tenpo_master、user_master、cust_master、Lesson_master、user_master_histにレコードがある前提で作成しています。
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
        //cust情報の取得
        $custList = Cust::take(5)->get();
        $custIds = $custList->implode("cid",",");
        //レッスン情報の取得
        $lessonMaster = LessonMaster::first();

        /** ShiftMasterへ登録開始 */
        $dao = new ShiftMaster();
        // 店舗IDを設定
        $dao->shift_tenpoid = $tid;
        // 担当インストラクターのIDを設定
        $dao->teacher = $instructor->getAuthIdentifier();
        // 担当インストラクターのIDを設定
        $dao->ls_menu = $lessonMaster->getAuthIdentifier();
        // 予約会員 cust_master.cid ※カンマ区切りで複数
        $dao->cstid = $custIds;
        // 受講会員（消化含む）cust_master.cid ※カンマ区切りで複数。予約回数増加チケット使用時は、予約登録時点で登録
        $dao->regid = $custIds;
        // 消化会員 cust_master.cid ※カンマ区切りで複数。予約回数増加チケット使用時は、予約登録時点で登録
        $dao->canid = $custIds;
        // レッスン日 例:2019/5/27
        $dao->shift_date = Carbon::tomorrow()->format('Y/m/d');
        // レッスン開始時間 例:18:30:00
        $dao->ls_st = Carbon::tomorrow()->format('H:i:s');
        // レッスン終了時間 例:19:15:00
        $dao->ls_et = Carbon::tomorrow()->addMinute(45)->format('H:i:s');
        // ネット予約公開日時 例:2019/5/3  20:00:00
        $dao->open_datetime = Carbon::now();

        // seeder.develop.shift_masterで設定した値をモデルに設定
        foreach ($record as $key => $value) {
            $dao->$key = $value;
        }

        $dao->save();
        // モデルの再取得
        $freshDao = $dao->fresh();
        // ネット予約公開日時 例:2019/5/3  20:00:00
        $freshDao->shiftid_hash = Hash::make($freshDao->shiftid);
        $freshDao->save();

        /** ShiftMasterへ登録終了 */
        echo "\n   ---> " . "ShiftMasterSeeder Insert End " . "\n";

    }

}
