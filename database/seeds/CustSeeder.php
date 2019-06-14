<?php

use Illuminate\Database\Seeder;
use App\Models\BaseFormModel;
use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Cust as Cust;
use App\Models\CustMemType as CustMemType;
use App\Models\TenpoMaster as TenpoMaster;
use App\Models\CustTenpo as CustTenpo;
use App\Models\Schedule as Schedule;


class CustSeeder extends BaseSeeder
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
        //DB::statement('TRUNCATE cust_master CASCADE');

        echo "\n" . "CustSeeder Start" . "\n";
        DB::transaction(function() {
            // 登録単位ループ
            foreach ($this->getConfig("cust_master") as $records) {
                $this->insertRecord($records);
            }
        });
        echo "\n" . "CustSeeder Finished" . "\n";
    }
    /**
     * 登録単位でCUSTに関係する指定キーのみ登録
     */
    protected function insertRecord($record = []) {

            $memdao = $tenpo = null;
            $tenpoModels = $memtypeModels = collect();
            // メインModel用の関連情報を取り除く
            if (isset($record["assign"])) {
                $assign = $record["assign"];
                unset($record["assign"]);
            }
            // 関連情報に店舗名がある場合、店舗を特定し紐づけ
            if (isset($assign["tenpo_name"]) && count($assign["tenpo_name"]) ) {
                $tenpoModels = TenpoMaster::whereIn("tenpo_name",$assign["tenpo_name"])->get();
            }
            // 関連情報に会員種別がある場合、登録
            if (isset($assign["cust_memtype_name"])) {
                $memtypeModels = CustMemType::where("type_name",$assign["cust_memtype_name"])->first();
            }
            // 
            $dao = new Cust();
            $dao->mergeRequest($record);
            $dao->save();
            echo "\n   ---> " . "Cust Insert End [".$dao->name."]" . "\n";
            if ($memtypeModels) {
                // Cust と CustMemTypeを紐づけ
                $dao->memtype = $memtypeModels->mid;
                $dao->save();
                echo "\n" . "Linked CustMemId [".$dao->memtype."]" . "\n";
            }
            // 所属店舗情報の紐づけ
            if (count($tenpoModels)) {
                // まずは所属店舗情報との関連付けを登録
                foreach ($tenpoModels as $tenpoModel) {
                    $ctenpo = new CustTenpo();
                    $ctenpo->cid = $dao->cid;
                    $ctenpo->tenpo_id = $tenpoModel->tid;
                    $ctenpo->save();
                    echo "\n" . "Linked StoreId [".$dao->store_id."(".$tenpoModel->tenpo_name.")]" . "\n";
                }
                // 関連情報に変更スケジュールがある場合、登録
                if (isset($assign["schedule"])) {
                    // 所属店舗以外の店舗モデルコレクション取得
                    $notInTenpos  = TenpoMaster::all()->whereNotIn("tid",$tenpoModels->pluck("tid"));
                    $notInMemtype = CustMemType::where("mid","<>",$dao->memtype)->get();
                    // 存在したら変更スケジュールを登録

                    // 所属店舗以外の店舗が存在したら、店舗IDを変更するスケジュール登録
                    if ($notInTenpos->isNotEmpty()) {
                        $changeTenpo = $notInTenpos->first();
                        // 取得した店舗のIDが、スケジュール内に存在していなければ登録
                        $schdao = new Schedule();
                        $assign["schedule"]["sc_cid"]     = $dao->cid;           // 会員ID
                        $assign["schedule"]["sc_memtype"] = $dao->memtype;      // 会員種別変更なし
                        $assign["schedule"]["sc_tenpo"]   = $changeTenpo->tid;  // 店舗ID変更
                        $schdao->mergeRequest($assign["schedule"]);
                        $schdao->save();
                        echo "\n" . "Linked Schedule Store Change [".$dao->store_id." -> ".$changeTenpo->tid."]" . "\n";

                    }
                    // 自身の会員種別以外の種別が存在したら、会員種別IDを変更するスケジュール登録
                    if ($notInMemtype->isNotEmpty()) {
                        $thisTenpo = $tenpoModels->first();
                        $changeMemType = $notInMemtype->first();
                        $schdao = new Schedule();
                        $assign["schedule"]["sc_cid"]     = $dao->cid;           // 会員ID
                        $assign["schedule"]["sc_memtype"] = $changeMemType->mid; // 会員種別変更
                        $assign["schedule"]["sc_tenpo"]   = $thisTenpo->tid;     // 店舗ID変更
                        $schdao->mergeRequest($assign["schedule"]);
                        $schdao->save();
                        echo "\n" . "Linked Schedule MemType Change [".$dao->memtype." -> ".$changeMemType->mid."]" . "\n";
                    }
                }

            }
    }

}
