<?php

use Illuminate\Database\Seeder;
use App\Models\BaseFormModel;
use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\UserMaster;
use App\Models\TenpoMaster as TenpoMaster;

class UserMasterSeeder extends BaseSeeder
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
//        DB::statement('TRUNCATE user_master CASCADE');
        // 本シーダーは(UserMasterSeeder)は、tenpo_masterにレコードがある前提で作成しています。
        echo "\n" . "UserMasterSeeder Start" . "\n";
        DB::transaction(function() {
            // 登録単位ループ
            foreach (config("seeder.develop.user_master") as $record) {
                $this->insertRecord($record);
            }
        });
        echo "\n" . "UserMasterSeeder Finished" . "\n";
    }
    /**
     * 登録単位でCUSTに関係する指定キーのみ登録
     */
    protected function insertRecord($record = []) {

        // 店舗情報を取得
        $tenpoIds = TenpoMaster::take(5)->get()->implode('tid', ',');
        //
        $dao = new UserMaster();
        $dao->mergeRequest($record);
        $dao->effective_store = $tenpoIds;
        $dao->password_change_datetime = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        $dao->save();
        echo "\n   ---> " . "UserMaster Insert End [".$dao->user_name."]" . "\n";

    }

}
