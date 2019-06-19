<?php

use App\Models\LessonClass1;
use Illuminate\Database\Seeder;
use App\Models\BaseFormModel;
use App\Models\Cust;
use App\Models\Invite;
use App\Models\LessonMaster;
use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\PrefMaster as PrefMaster;

class InviteSeeder extends BaseSeeder
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
//        DB::statement('TRUNCATE user_master_hist CASCADE');
        // 本シーダー(InviteSeeder)は、cust_masterにレコードがある前提で作成しています
        echo "\n" . "InviteSeeder Start" . "\n";
        DB::transaction(function() {
            // 登録単位ループ
            $custList = Cust::take(10)->get();
            foreach ($custList as $record) {
                $this->insertRecord($record);
            }
        });
        echo "\n" . "InviteSeeder Finished" . "\n";
    }

    protected function insertRecord($record = []) {
            // 体験予約可能なレッスンIDを設定
            //lesson_master情報の取得 TODO custに紐づくtenpo_master,shift_masterからlesson_masterを取得するように要調整
            $lessonList = LessonMaster::get();
            $lessonMater = $lessonList->random();
            $record->lid = $lessonMater->getAuthIdentifier();
            $invite = new Invite;
            $invite->mergeRequest($record);

            if (Invite::isExistsInviteCode('jIU0DztiPyr7yndZdYlC31Lkacc4Kgr2')) {
                // ランダムで紹介コードを設定
                $invite->invite_code = $invite::makeInviteCode();
            } else {
                // テスト用に仮で固定紹介コードを設定
                $invite->invite_code = 'jIU0DztiPyr7yndZdYlC31Lkacc4Kgr2';
            }

            $invite->save();

    }

}
