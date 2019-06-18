<?php

use Illuminate\Database\Seeder;
use App\Models\BaseFormModel;
use App\Models\Cust;
use App\Models\UserMaster;
use App\Models\TenpoMaster;
use App\Models\BelongTenpoHist;
use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Illuminate\Support\Carbon;

class BelongTenpoHistSeeder extends BaseSeeder
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
//        DB::statement('TRUNCATE belong_tenpo_hist__c CASCADE');
        // 本シーダー(BelongTenpoHistSeeder)は、user_master,tenpo_masterにレコードがある前提で作成しています
        echo "\n" . "BelongTenpoHistSeeder Start" . "\n";
        DB::transaction(function() {
            // 登録単位ループ
            $custList = UserMaster::take(10)->get();
            foreach ($custList as $record) {
                $this->insertRecord($record);
            }
        });
        echo "\n" . "BelongTenpoHistSeeder Finished" . "\n";
    }

    protected function insertRecord($record = []) {
            // 設定する店舗情報を取得
            $tenpoList = TenpoMaster::take(10)->get()->random(2);
        echo "\n" . "BelongTenpoHistSeeder insert Start" . "\n";
            foreach($tenpoList as $tenpo) {
                $belongTenpoHist = new BelongTenpoHist();
                $belongTenpoHist->uid__c = $record->getAuthIdentifier();
                $belongTenpoHist->tid__c = $tenpo->getAuthIdentifier();
                $belongTenpoHist->flg__c = BelongTenpoHist::VALID;
                $belongTenpoHist->start__c = Carbon::now();
                $belongTenpoHist->save();
            }
        echo "\n" . "BelongTenpoHistSeeder insert End" . "\n";

    }

}
