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
            $newCustMdl = new Cust();
            $newCustMemTypeMdl = new CustMemType();
            $newTenpoMdl = new TenpoMaster();
            $newScheduleMdl = new Schedule();

            // メインModel用の関連情報を取り除く
            if (isset($record["assign"])) {
                $assign = $record["assign"];
                unset($record["assign"]);
            }
            // 関連情報に店舗名がある場合、店舗を特定し紐づけ
            if (isset($assign["tenpo_name"]) && count($assign["tenpo_name"]) ) {
                $tenpoModels = TenpoMaster::whereIn($newCustMdl->convertKey("tenpo_name"),$assign["tenpo_name"])->get();
            }
            // 関連情報に会員種別がある場合、登録
            if (isset($assign["cust_memtype_name"])) {
                $memtypeModels = CustMemType::where($newCustMdl->convertKey("type_name"),$assign["cust_memtype_name"])->first();
            }

            $dao = Cust::where($newCustMdl->convertKey("name"),$record["name"])->first();
            if (is_null($dao)) {
                $dao = $newCustMdl;
            }
            $dao->mergeRequest($record);
            $dao->save();


            echo "\n   ---> " . "Cust Insert End [".$dao->name."]" . "\n";
            if ($memtypeModels) {
                // Cust と CustMemTypeを紐づけ
                $dao->memtype = $memtypeModels->getAuthIdentifier();
                $dao->save();
                echo "\n" . "Linked CustMemId [".$dao->memtype."]" . "\n";
            }
            // 所属店舗情報の紐づけ
            if (count($tenpoModels)) {
                // まずは所属店舗情報との関連付けを登録
                foreach ($tenpoModels as $tenpoModel) {
                    $ctenpo = new CustTenpo();
                    $ctenpo->cid = $dao->getAuthIdentifier();
                    $ctenpo->tenpo_id = $tenpoModel->getAuthIdentifier();
                    $ctenpo->save();
                    echo "\n" . "Linked StoreId [".$dao->store_id."(".$tenpoModel->tenpo_name.")]" . "\n";
                }
                // 関連情報に変更スケジュールがある場合、登録
                if (isset($assign["schedule"])) {
                    // 所属店舗以外の店舗モデルコレクション取得
                    $notInTenpos  = TenpoMaster::all()->whereNotIn($newTenpoMdl->convertKey("tid"),$tenpoModels->pluck("tid"));
                    $notInMemtype = CustMemType::where($newCustMemTypeMdl->convertKey("mid"),"<>",$dao->memtype)->get();
//                    print "<pre>"; print_r($notInMemtype); print "</pre>"; exit;
//                    $notInMemtype = CustMemType::where("mid__c","<>",$dao->memtype)->get();
                    // 存在したら変更スケジュールを登録
                    // 所属店舗以外の店舗が存在したら、店舗IDを変更するスケジュール登録
                    $sc_cid     = $newScheduleMdl->convertKey("sc_cid");
                    $sc_memtype = $newScheduleMdl->convertKey("sc_memtype");
                    $sc_tenpo   = $newScheduleMdl->convertKey("sc_tenpo");
                    if ($notInTenpos->isNotEmpty()) {
                        $changeTenpo = $notInTenpos->first();
                        // 取得した店舗のIDが、スケジュール内に存在していなければ登録
                        $schdao = Schedule::where($sc_cid,$dao->getAuthIdentifier())
                                        ->where($sc_memtype,$dao->memtype)
                                        ->where($sc_tenpo,$changeTenpo->getAuthIdentifier())
                                        ->first();
                        if (is_null($schdao)) {
                            $schdao = $newScheduleMdl;
                        }
                        $memtype = $dao->hasOneStoreTenpo(); // EagerLoad対応		

//                        $assign["schedule"][$sc_cid]     = $dao->getAuthIdentifier();           // 会員ID
//                        $assign["schedule"][$sc_memtype] = $memtype->getAuthIdentifier();       // 会員種別変更なし
//                        $assign["schedule"][$sc_tenpo]   = $changeTenpo->getAuthIdentifier();   // 店舗ID変更
                        $assign["schedule"][$sc_cid]     = $dao->cid;                             // 会員ID
                        $assign["schedule"][$sc_memtype] = $memtype->mid;                         // 会員種別変更なし
                        $assign["schedule"][$sc_tenpo]   = $changeTenpo->tid;                     // 店舗ID変更
                        $schdao->mergeRequest($assign["schedule"]);
                        $schdao->save();
                        echo "\n" . "Linked Schedule Store Change [".$dao->store_id." -> ".$changeTenpo->tid."]" . "\n";

                    }
                    // 自身の会員種別以外の種別が存在したら、会員種別IDを変更するスケジュール登録
                    if ($notInMemtype->isNotEmpty()) {
                        $thisTenpo = $tenpoModels->first();
                        $changeMemType = $notInMemtype->first();
                        $schdao = Schedule::where($sc_cid,$dao->getAuthIdentifier())
                                        ->where($sc_memtype,$changeMemType->getAuthIdentifier())
                                        ->where($sc_tenpo,$thisTenpo->getAuthIdentifier())
                                        ->first();
                        if (is_null($schdao)) {
                            $schdao = $newScheduleMdl;
                        }
                        $assign["schedule"][$sc_cid]     = $dao->getAuthIdentifier();           // 会員ID
                        $assign["schedule"][$sc_memtype] = $changeMemType->getAuthIdentifier(); // 会員種別変更
                        $assign["schedule"][$sc_tenpo]   = $thisTenpo->getAuthIdentifier();     // 店舗ID変更
                        $schdao->mergeRequest($assign["schedule"]);
                        $schdao->save();
                        echo "\n" . "Linked Schedule MemType Change [".$dao->memtype." -> ".$changeMemType->getAuthIdentifier()."]" . "\n";
                    }
                }

            }
    }

}
