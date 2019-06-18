<?php

use Illuminate\Database\Seeder;
use App\Models\BaseFormModel;
use App\Models\Cust;
use App\Models\UserMaster;
use App\Models\TenpoMaster;
use App\Models\UserMasterHist;
use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Illuminate\Support\Carbon;

class UserMasterHistSeeder extends BaseSeeder
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
        echo "\n" . "UserMasterHistSeeder Start" . "\n";
        DB::transaction(function() {
            // 登録単位ループ
            $custList = UserMaster::take(10)->get();
            foreach ($custList as $record) {
                $this->insertRecord($record);
            }
        });
        echo "\n" . "UserMasterHistSeeder Finished" . "\n";
    }

    protected function insertRecord($record = []) {
            // 設定する店舗情報を取得
            $tenpoList = TenpoMaster::take(10)->get()->random(2);
            foreach($tenpoList as $tenpo) {
                $userMasterHist = new UserMasterHist;
                $userMasterHist->uid = $record->getAuthIdentifier();
                $userMasterHist->tid = $tenpo->getAuthIdentifier();
                $userMasterHist->flg = UserMasterHist::VALID;
                $userMasterHist->start__c = Carbon::now();
                $userMasterHist->save();
            }


    }

}
